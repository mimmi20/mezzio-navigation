<?php
/**
 * This file is part of the mimmi20/mezzio-navigation package.
 *
 * Copyright (c) 2020, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);
namespace Mezzio\Navigation;

use Laminas\Stdlib\ErrorHandler;
use Mezzio\Navigation\Page\PageInterface;
use RecursiveIteratorIterator;

/**
 * Trait for Mezzio\Navigation\Page classes.
 */
trait ContainerTrait
{
    /**
     * Contains sub pages
     *
     * @var PageInterface[]
     */
    private $pages = [];

    /**
     * An index that contains the order in which to iterate pages
     *
     * @var array
     */
    private $index = [];

    /**
     * Whether index is dirty and needs to be re-arranged
     *
     * @var bool
     */
    private $dirtyIndex = false;

    // Internal methods:

    /**
     * Sorts the page index according to page order
     *
     * @return void
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

            if (null === $order) {
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

    // Public methods:

    /**
     * Notifies container that the order of pages are updated
     *
     * @return void
     */
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
     * @throws Exception\InvalidArgumentException if page is invalid
     *
     * @return void
     */
    final public function addPage(PageInterface $page): void
    {
        if ($page === $this) {
            throw new Exception\InvalidArgumentException(
                'A page cannot have itself as a parent'
            );
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
     * @param PageInterface[] $pages pages to add
     *
     * @throws Exception\InvalidArgumentException                 if $pages is not array, Traversable or PageInterface
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
     * @return void
     */
    final public function addPages(iterable $pages): void
    {
        foreach ($pages as $page) {
            if (!$page instanceof PageInterface) {
                throw new Exception\InvalidArgumentException(
                    'Invalid argument: $page must be an Instance of PageInterface'
                );
            }

            $this->addPage($page);
        }
    }

    /**
     * Sets pages this container should have, removing existing pages
     *
     * @param PageInterface[] $pages pages to set
     *
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
     * @return void
     */
    final public function setPages(iterable $pages): void
    {
        $this->removePages();
        $this->addPages($pages);
    }

    /**
     * Returns pages in the container
     *
     * @return PageInterface[]
     */
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
     */
    final public function removePage($page, bool $recursive = false): bool
    {
        if ($page instanceof PageInterface) {
            $hash = $page->hashCode();
        } elseif (is_int($page)) {
            $this->sort();

            $hash = array_search($page, $this->index, true);

            if (!$hash) {
                return false;
            }
        } else {
            return false;
        }

        if (isset($this->pages[$hash])) {
            unset($this->pages[$hash], $this->index[$hash]);

            $this->dirtyIndex = true;

            return true;
        }

        if ($recursive) {
            foreach ($this->pages as $childPage) {
                \assert($childPage instanceof PageInterface);

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
     * @return void
     */
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
     */
    final public function hasPage($page, bool $recursive = false): bool
    {
        if ($page instanceof PageInterface) {
            $hash = $page->hashCode();
        } elseif (is_int($page)) {
            $this->sort();

            $hash = array_search($page, $this->index, true);

            if (!$hash) {
                return false;
            }
        } else {
            return false;
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
     */
    final public function hasPages(bool $onlyVisible = false): bool
    {
        if ($onlyVisible) {
            foreach ($this->pages as $page) {
                \assert($page instanceof PageInterface);

                if ($page->isVisible()) {
                    return true;
                }
            }

            // no visible pages found
            return false;
        }

        return !empty($this->index);
    }

    /**
     * Returns a child page matching $property == $value, or null if not found
     *
     * @param string $property name of property to match against
     * @param mixed  $value    value to match property against
     *
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return PageInterface|null matching page or null
     */
    final public function findOneBy(string $property, $value): ?PageInterface
    {
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $page) {
            \assert($page instanceof PageInterface);

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
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return PageInterface[] array containing only Page\PageInterface instances
     */
    final public function findAllBy(string $property, $value): array
    {
        $found = [];

        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $page) {
            \assert($page instanceof PageInterface);

            if ($page->get($property) !== $value) {
                continue;
            }

            $found[] = $page;
        }

        return $found;
    }

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
     * @param string $method    method name
     * @param array  $arguments method arguments
     *
     * @throws Exception\BadMethodCallException if method does not exist
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        ErrorHandler::start(E_WARNING);

        $result = preg_match('/(find(?:One|All)By)(.+)/', $method, $match);
        $error  = ErrorHandler::stop();

        if (!$result) {
            throw new Exception\BadMethodCallException(
                sprintf(
                    'Bad method call: Unknown method %s::%s',
                    static::class,
                    $method
                ),
                0,
                $error
            );
        }

        return $this->{$match[1]}($match[2], $arguments[0]);
    }

    /**
     * Returns an array representation of all pages in container
     *
     * @return array
     */
    final public function toArray(): array
    {
        $this->sort();
        $pages   = [];
        $indexes = array_keys($this->index);
        foreach ($indexes as $hash) {
            $pages[] = $this->pages[$hash]->toArray();
        }

        return $pages;
    }

    // RecursiveIterator interface:

    /**
     * Returns current page
     * Implements RecursiveIterator interface.
     *
     * @throws Exception\OutOfBoundsException if the index is invalid
     *
     * @return PageInterface current page
     */
    final public function current(): PageInterface
    {
        if (empty($this->index)) {
            throw new Exception\OutOfBoundsException(
                'container is currently empty, could not find any key in internal iterator'
            );
        }

        $this->sort();

        $hash = key($this->index);
        if (null === $hash || !isset($this->pages[$hash])) {
            throw new Exception\OutOfBoundsException(
                'Corruption detected in container; invalid key found in internal iterator'
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
     */
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
     * @return void
     */
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
     * @return void
     */
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
     * @return bool
     */
    final public function valid(): bool
    {
        $this->sort();

        return false !== current($this->index) && null !== current($this->index);
    }

    /**
     * Proxy to hasPages()
     *
     * Implements RecursiveIterator interface.
     *
     * @throws \Mezzio\Navigation\Exception\OutOfBoundsException
     *
     * @return bool whether container has any pages
     */
    final public function hasChildren(): bool
    {
        return $this->valid() && $this->current()->hasPages();
    }

    /**
     * Returns the child container.
     *
     * Implements RecursiveIterator interface.
     *
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Mezzio\Navigation\Exception\OutOfBoundsException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
     * @return ContainerInterface
     *
     * @codeCoverageIgnore
     */
    final public function getChildren(): ContainerInterface
    {
        $iterator = new self();

        if ($this->valid()) {
            $iterator->setPages($this->current()->getPages());
        }

        return $iterator;
    }

    // Countable interface:

    /**
     * Returns number of pages in container
     *
     * Implements Countable interface.
     *
     * @return int number of pages in the container
     */
    final public function count(): int
    {
        return count($this->index);
    }
}
