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

use Countable;
use Laminas\Stdlib\ErrorHandler;
use Mezzio\Navigation\Page\AbstractPage;
use RecursiveIterator;
use RecursiveIteratorIterator;
use Traversable;

/**
 * AbstractContainer class for Mezzio\Navigation\Page classes.
 */
abstract class AbstractContainer implements Countable, RecursiveIterator
{
    /**
     * Contains sub pages
     *
     * @var array
     */
    protected $pages = [];

    /**
     * An index that contains the order in which to iterate pages
     *
     * @var array
     */
    protected $index = [];

    /**
     * Whether index is dirty and needs to be re-arranged
     *
     * @var bool
     */
    protected $dirtyIndex = false;

    // Internal methods:

    /**
     * Sorts the page index according to page order
     *
     * @return void
     */
    protected function sort(): void
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
     *
     * This method will inject the container as the given page's parent by
     * calling {@link Page\AbstractPage::setParent()}.
     *
     * @param array|Page\AbstractPage|Traversable $page page to add
     *
     * @throws Exception\InvalidArgumentException if page is invalid
     *
     * @return void
     */
    public function addPage($page): void
    {
        if ($page === $this) {
            throw new Exception\InvalidArgumentException(
                'A page cannot have itself as a parent'
            );
        }

        if (!$page instanceof Page\AbstractPage) {
            if (!is_array($page) && !$page instanceof Traversable) {
                throw new Exception\InvalidArgumentException(
                    'Invalid argument: $page must be an instance of '
                    . 'Mezzio\Navigation\Page\AbstractPage or Traversable, or an array'
                );
            }

            $page = Page\AbstractPage::factory($page);
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
     * @param AbstractContainer|array|Traversable $pages pages to add
     *
     * @throws Exception\InvalidArgumentException if $pages is not array,
     *                                            Traversable or AbstractContainer
     *
     * @return void
     */
    final public function addPages($pages): void
    {
        if (!is_array($pages) && !$pages instanceof Traversable) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $pages must be an array, an '
                . 'instance of Traversable or an instance of '
                . 'Mezzio\Navigation\AbstractContainer'
            );
        }

        // Because adding a page to a container removes it from the original
        // (see {@link Page\AbstractPage::setParent()}), iteration of the
        // original container will break. As such, we need to iterate the
        // container into an array first.
        if ($pages instanceof self) {
            $pages = iterator_to_array($pages);
        }

        foreach ($pages as $page) {
            if (null === $page) {
                continue;
            }

            $this->addPage($page);
        }
    }

    /**
     * Sets pages this container should have, removing existing pages
     *
     * @param array $pages pages to set
     *
     * @return void
     */
    final public function setPages(array $pages): void
    {
        $this->removePages();
        $this->addPages($pages);
    }

    /**
     * Returns pages in the container
     *
     * @return array array of Page\AbstractPage instances
     */
    final public function getPages(): array
    {
        return $this->pages;
    }

    /**
     * Removes the given page from the container
     *
     * @param int|Page\AbstractPage $page      page to remove, either a page
     *                                         instance or a specific page order
     * @param bool                  $recursive [optional] whether to remove recursively
     *
     * @return bool whether the removal was successful
     */
    final public function removePage($page, bool $recursive = false): bool
    {
        if ($page instanceof Page\AbstractPage) {
            $hash = $page->hashCode();
        } elseif (is_int($page)) {
            $this->sort();
            if (!$hash = array_search($page, $this->index, true)) {
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
                \assert($childPage instanceof \Mezzio\Navigation\Page\AbstractPage);
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
     * @param Page\AbstractPage $page      page to look for
     * @param bool              $recursive [optional] whether to search recursively.
     *                                     Default is false.
     *
     * @return bool whether page is in container
     */
    final public function hasPage(Page\AbstractPage $page, bool $recursive = false): bool
    {
        if (array_key_exists($page->hashCode(), $this->index)) {
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
                if ($page->isVisible()) {
                    return true;
                }
            }

            // no visible pages found
            return false;
        }

        return $this->index ? true : false;
    }

    /**
     * Returns a child page matching $property == $value, or null if not found
     *
     * @param string $property name of property to match against
     * @param mixed  $value    value to match property against
     *
     * @return AbstractPage|null matching page or null
     */
    final public function findOneBy(string $property, $value): ?AbstractPage
    {
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $page) {
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
     * @return array array containing only Page\AbstractPage instances
     */
    final public function findAllBy(string $property, $value): array
    {
        $found = [];

        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $page) {
            if ($page->get($property) !== $value) {
                continue;
            }

            $found[] = $page;
        }

        return $found;
    }

    /**
     * Returns page(s) matching $property == $value
     *
     * @param string $property name of property to match against
     * @param mixed  $value    value to match property against
     * @param bool   $all      [optional] whether an array of all matching
     *                         pages should be returned, or only the first.
     *                         If true, an array will be returned, even if not
     *                         matching pages are found. If false, null will
     *                         be returned if no matching page is found.
     *                         Default is false.
     *
     * @return array|Page\AbstractPage|null matching page or null
     */
    final public function findBy(string $property, $value, bool $all = false)
    {
        if ($all) {
            return $this->findAllBy($property, $value);
        }

        return $this->findOneBy($property, $value);
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
     */
    public function __call(string $method, $arguments)
    {
        ErrorHandler::start(E_WARNING);
        $result = preg_match('/(find(?:One|All)?By)(.+)/', $method, $match);
        $error  = ErrorHandler::stop();
        if (!$result) {
            throw new Exception\BadMethodCallException(sprintf(
                'Bad method call: Unknown method %s::%s',
                static::class,
                $method
            ), 0, $error);
        }

        return $this->{$match[1]}($match[2], $arguments[0]);
    }

    /**
     * Returns an array representation of all pages in container
     *
     * @return array
     */
    public function toArray(): array
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
     *
     * Implements RecursiveIterator interface.
     *
     * @throws Exception\OutOfBoundsException if the index is invalid
     *
     * @return AbstractPage current page or null
     */
    final public function current(): AbstractPage
    {
        $this->sort();

        current($this->index);
        $hash = key($this->index);
        if (!isset($this->pages[$hash])) {
            throw new Exception\OutOfBoundsException(
                'Corruption detected in container; '
                . 'invalid key found in internal iterator'
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

        return key($this->index);
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

        return false !== current($this->index);
    }

    /**
     * Proxy to hasPages()
     *
     * Implements RecursiveIterator interface.
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
     * @return AbstractPage|null
     */
    final public function getChildren(): ?AbstractPage
    {
        $hash = key($this->index);

        if (isset($this->pages[$hash])) {
            return $this->pages[$hash];
        }

        return null;
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
