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
use Mezzio\Navigation\Page\PageInterface;
use RecursiveIterator;
use Traversable;

/**
 * ContainerInterface class for Mezzio\Navigation\Navigation classes.
 */
interface ContainerInterface extends Countable, RecursiveIterator
{
    /**
     * Notifies container that the order of pages are updated
     *
     * @return void
     */
    public function notifyOrderUpdated(): void;

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
    public function addPage(PageInterface $page): void;

    /**
     * Adds several pages at once
     *
     * @param array|PageInterface[]|Traversable $pages pages to add
     *
     * @throws Exception\InvalidArgumentException
     *
     * @return void
     */
    public function addPages($pages): void;

    /**
     * Sets pages this container should have, removing existing pages
     *
     * @param PageInterface[] $pages pages to set
     *
     * @return void
     */
    public function setPages($pages): void;

    /**
     * Returns pages in the container
     *
     * @return PageInterface[]
     */
    public function getPages(): array;

    /**
     * Removes the given page from the container
     *
     * @param int|PageInterface $page      page to remove, either a page instance or a specific page order
     * @param bool              $recursive [optional] whether to remove recursively
     *
     * @return bool whether the removal was successful
     */
    public function removePage($page, bool $recursive = false): bool;

    /**
     * Removes all pages in container
     *
     * @return void
     */
    public function removePages(): void;

    /**
     * Checks if the container has the given page
     *
     * @param int|PageInterface $page      page to look for
     * @param bool              $recursive [optional] whether to search recursively. Default is false.
     *
     * @return bool whether page is in container
     */
    public function hasPage($page, bool $recursive = false): bool;

    /**
     * Returns true if container contains any pages
     *
     * @param bool $onlyVisible whether to check only visible pages
     *
     * @return bool whether container has any pages
     */
    public function hasPages(bool $onlyVisible = false): bool;

    /**
     * Returns a child page matching $property == $value, or null if not found
     *
     * @param string $property name of property to match against
     * @param mixed  $value    value to match property against
     *
     * @return PageInterface|null matching page or null
     */
    public function findOneBy(string $property, $value): ?PageInterface;

    /**
     * Returns all child pages matching $property == $value, or an empty array
     * if no pages are found
     *
     * @param string $property name of property to match against
     * @param mixed  $value    value to match property against
     *
     * @return PageInterface[]
     */
    public function findAllBy(string $property, $value): array;

    /**
     * Returns an array representation of all pages in container
     *
     * @return array
     */
    public function toArray(): array;
}