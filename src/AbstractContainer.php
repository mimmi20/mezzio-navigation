<?php

/**
 * This file is part of the mimmi20/mezzio-navigation package.
 *
 * Copyright (c) 2020-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Mezzio\Navigation;

use ErrorException;
use Laminas\Stdlib\ErrorHandler;
use Mimmi20\Mezzio\Navigation\Exception\BadMethodCallException;
use Mimmi20\Mezzio\Navigation\Exception\InvalidArgumentException;
use Mimmi20\Mezzio\Navigation\Exception\OutOfBoundsException;
use Mimmi20\Mezzio\Navigation\Page\PageInterface;
use Override;
use RecursiveIteratorIterator;

use function array_key_exists;
use function array_keys;
use function array_search;
use function asort;
use function assert;
use function count;
use function current;
use function key;
use function next;
use function preg_match;
use function reset;
use function sprintf;

use const E_WARNING;

/**
 * Trait for Mimmi20\Mezzio\Navigation\Page classes.
 *
 * @implements ContainerInterface<PageInterface>
 */
abstract class AbstractContainer implements ContainerInterface
{
    /**
     * Contains sub pages
     *
     * @var array<string, PageInterface>
     */
    protected array $pages = [];

    /**
     * An index that contains the order in which to iterate pages
     *
     * @var array<string, int|null>
     */
    protected array $index = [];

    /**
     * Whether index is dirty and needs to be re-arranged
     */
    protected bool $dirtyIndex = false;

    /**
     * Magic overload: Proxy calls to finder methods
     *
     * Examples of finder calls:
     * <code>
     * // METHOD                    // SAME AS
     * $nav->findByLabel('foo');    // $nav->findOneBy('label', 'foo');
     * $nav->findOneByLabel('foo'); // $nav->findOneBy('label', 'foo');
     * $nav->findAllByClass('foo'); // $nav->findAllBy('class', 'foo');
     * </code>
     *
     * @param string            $method    method name
     * @param array<int, mixed> $arguments method arguments
     *
     * @throws BadMethodCallException if method does not exist
     * @throws ErrorException
     */
    public function __call(string $method, array $arguments): mixed
    {
        ErrorHandler::start(E_WARNING);

        $result = preg_match('/(?P<function>find(?:One|All)By)(?P<property>.+)/', $method, $match);
        $error  = ErrorHandler::stop();

        if (!$result) {
            throw new BadMethodCallException(
                sprintf(
                    'Bad method call: Unknown method %s::%s',
                    static::class,
                    $method,
                ),
                0,
                $error,
            );
        }

        return $this->{$match['function']}($match['property'], $arguments[0]);
    }

    // Public methods:

    /**
     * Notifies container that the order of pages are updated
     *
     * @throws void
     */
    #[Override]
    final public function notifyOrderUpdated(): void
    {
        $this->dirtyIndex = true;
    }

    /**
     * Adds a page to the container
     * This method will inject the container as the given page's parent by
     * calling {@link PageInterface::setParent()}.
     *
     * @param PageInterface $page page to add
     *
     * @throws InvalidArgumentException if page is invalid
     */
    #[Override]
    final public function addPage(PageInterface $page): void
    {
        if ($page === $this) {
            throw new InvalidArgumentException('A page cannot have itself as a parent');
        }

        $hash = $page->hashCode();

        if (array_key_exists($hash, $this->index)) {
            // page is already in container
            return;
        }

        // adds page to container and sets dirty flag
        $this->pages[$hash] = $page;
        $this->index[$hash] = $page->getOrder();
        $this->dirtyIndex   = true;

        // inject self as page parent
        $page->setParent($this);
    }

    /**
     * Adds several pages at once
     *
     * @param array<int|string, PageInterface> $pages pages to add
     *
     * @throws InvalidArgumentException if $pages is not array, Traversable or PageInterface
     */
    #[Override]
    final public function addPages(iterable $pages): void
    {
        foreach ($pages as $page) {
            if (!$page instanceof PageInterface) {
                throw new InvalidArgumentException(
                    'Invalid argument: $page must be an Instance of PageInterface',
                );
            }

            $this->addPage($page);
        }
    }

    /**
     * Sets pages this container should have, removing existing pages
     *
     * @param array<int|string, PageInterface> $pages pages to set
     *
     * @throws InvalidArgumentException
     */
    #[Override]
    final public function setPages(iterable $pages): void
    {
        $this->removePages();
        $this->addPages($pages);
    }

    /**
     * Returns pages in the container
     *
     * @return array<string, PageInterface>
     *
     * @throws void
     */
    #[Override]
    final public function getPages(): array
    {
        return $this->pages;
    }

    /**
     * Removes the given page from the container
     *
     * @param int|PageInterface $page      page to remove, either a page instance or a specific page order
     * @param bool              $recursive [optional] whether to remove recursively
     *
     * @return bool whether the removal was successful
     *
     * @throws void
     */
    #[Override]
    final public function removePage(int | PageInterface $page, bool $recursive = false): bool
    {
        if ($page instanceof PageInterface) {
            $hash = $page->hashCode();
        } else {
            $this->sort();

            $hash = array_search($page, $this->index, true);

            if (!$hash) {
                return false;
            }
        }

        if (isset($this->pages[$hash])) {
            unset($this->pages[$hash], $this->index[$hash]);

            $this->dirtyIndex = true;

            return true;
        }

        if ($recursive) {
            foreach ($this->pages as $childPage) {
                assert($childPage instanceof PageInterface);

                if ($childPage->hasPage($page, true)) {
                    $childPage->removePage($page, true);

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Removes all pages in container
     *
     * @throws void
     */
    #[Override]
    final public function removePages(): void
    {
        $this->pages = [];
        $this->index = [];
    }

    /**
     * Checks if the container has the given page
     *
     * @param int|PageInterface $page      page to look for
     * @param bool              $recursive [optional] whether to search recursively. Default is false.
     *
     * @return bool whether page is in container
     *
     * @throws void
     */
    #[Override]
    final public function hasPage(int | PageInterface $page, bool $recursive = false): bool
    {
        if ($page instanceof PageInterface) {
            $hash = $page->hashCode();
        } else {
            $this->sort();

            $hash = array_search($page, $this->index, true);

            if (!$hash) {
                return false;
            }
        }

        if (array_key_exists($hash, $this->index)) {
            return true;
        }

        if ($recursive) {
            foreach ($this->pages as $childPage) {
                if ($childPage->hasPage($page, true)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Returns true if container contains any pages
     *
     * @param bool $onlyVisible whether to check only visible pages
     *
     * @return bool whether container has any pages
     *
     * @throws void
     */
    #[Override]
    final public function hasPages(bool $onlyVisible = false): bool
    {
        if ($onlyVisible) {
            foreach ($this->pages as $page) {
                assert($page instanceof PageInterface);

                if ($page->isVisible()) {
                    return true;
                }
            }

            // no visible pages found
            return false;
        }

        return $this->index !== [];
    }

    /**
     * Returns a child page matching $property == $value, or null if not found
     *
     * @param string $property name of property to match against
     * @param mixed  $value    value to match property against
     *
     * @return PageInterface|null matching page or null
     *
     * @throws InvalidArgumentException
     */
    #[Override]
    final public function findOneBy(string $property, mixed $value): PageInterface | null
    {
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $page) {
            assert($page instanceof PageInterface);

            if ($page->get($property) === $value) {
                return $page;
            }
        }

        return null;
    }

    /**
     * Returns all child pages matching $property == $value, or an empty array
     * if no pages are found
     *
     * @param string $property name of property to match against
     * @param mixed  $value    value to match property against
     *
     * @return array<int, PageInterface> array containing only Page\PageInterface instances
     *
     * @throws InvalidArgumentException
     */
    #[Override]
    final public function findAllBy(string $property, mixed $value): array
    {
        $found = [];

        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $page) {
            assert($page instanceof PageInterface);

            if ($page->get($property) !== $value) {
                continue;
            }

            $found[] = $page;
        }

        return $found;
    }

    /**
     * Returns an array representation of all pages in container
     *
     * @return array<string, mixed>
     *
     * @throws void
     */
    #[Override]
    public function toArray(): array
    {
        $this->sort();
        $pages   = [];
        $indexes = array_keys($this->index);

        foreach ($indexes as $hash) {
            $pages[$hash] = $this->pages[$hash]->toArray();
        }

        return $pages;
    }

    // RecursiveIterator interface:

    /**
     * Returns current page
     * Implements RecursiveIterator interface.
     *
     * @return PageInterface current page
     *
     * @throws OutOfBoundsException if the index is invalid
     */
    #[Override]
    final public function current(): PageInterface
    {
        if ($this->index === []) {
            throw new OutOfBoundsException(
                'container is currently empty, could not find any key in internal iterator',
            );
        }

        $this->sort();

        $hash = key($this->index);

        if ($hash === null || !isset($this->pages[$hash])) {
            throw new OutOfBoundsException(
                'Corruption detected in container; invalid key found in internal iterator',
            );
        }

        return $this->pages[$hash];
    }

    /**
     * Returns hash code of current page
     *
     * Implements RecursiveIterator interface.
     *
     * @return string hash code of current page
     *
     * @throws void
     */
    #[Override]
    final public function key(): string
    {
        $this->sort();

        return (string) key($this->index);
    }

    /**
     * Moves index pointer to next page in the container
     *
     * Implements RecursiveIterator interface.
     *
     * @throws void
     */
    #[Override]
    final public function next(): void
    {
        $this->sort();

        next($this->index);
    }

    /**
     * Sets index pointer to first page in the container
     *
     * Implements RecursiveIterator interface.
     *
     * @throws void
     */
    #[Override]
    final public function rewind(): void
    {
        $this->sort();

        reset($this->index);
    }

    /**
     * Checks if container index is valid
     *
     * Implements RecursiveIterator interface.
     *
     * @throws void
     */
    #[Override]
    final public function valid(): bool
    {
        $this->sort();

        return current($this->index) !== false && current($this->index) !== null;
    }

    /**
     * Proxy to hasPages()
     *
     * Implements RecursiveIterator interface.
     *
     * @return bool whether container has any pages
     *
     * @throws OutOfBoundsException
     */
    #[Override]
    final public function hasChildren(): bool
    {
        return $this->valid() && $this->current()->hasPages();
    }

    /**
     * Returns the child container.
     *
     * Implements RecursiveIterator interface.
     *
     * @throws void
     *
     * @codeCoverageIgnore
     */
    #[Override]
    final public function getChildren(): PageInterface | null
    {
        $hash = key($this->index);

        return $this->pages[$hash] ?? null;
    }

    // Countable interface:

    /**
     * Returns number of pages in container
     *
     * Implements Countable interface.
     *
     * @return int<0, max> number of pages in the container
     *
     * @throws void
     */
    #[Override]
    final public function count(): int
    {
        return count($this->index);
    }

    // Internal methods:

    /**
     * Sorts the page index according to page order
     *
     * @throws void
     */
    private function sort(): void
    {
        if (!$this->dirtyIndex) {
            return;
        }

        $newIndex = [];
        $index    = 0;

        foreach ($this->pages as $hash => $page) {
            $order = $page->getOrder();

            if ($order === null) {
                $newIndex[$hash] = $index;
                ++$index;
            } else {
                $newIndex[$hash] = $order;
            }
        }

        asort($newIndex);

        $this->index      = $newIndex;
        $this->dirtyIndex = false;
    }
}
