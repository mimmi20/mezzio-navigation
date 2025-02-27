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

use Countable;
use Mimmi20\Mezzio\Navigation\Exception\InvalidArgumentException;
use Mimmi20\Mezzio\Navigation\Page\PageInterface;
use RecursiveIterator;

/**
 * ContainerInterface class for Mimmi20\Mezzio\Navigation\Navigation classes.
 *
 * @template TPage of PageInterface
 * @extends RecursiveIterator<string, TPage>
 */
interface ContainerInterface extends Countable, RecursiveIterator
{
    /**
     * Notifies container that the order of pages are updated
     *
     * @throws void
     */
    public function notifyOrderUpdated(): void;

    /**
     * Adds a page to the container
     * This method will inject the container as the given page's parent by
     * calling {@link PageInterface::setParent()}.
     *
     * @param PageInterface $page page to add
     *
     * @throws InvalidArgumentException if page is invalid
     */
    public function addPage(PageInterface $page): void;

    /**
     * Adds several pages at once
     *
     * @param iterable<int|string, PageInterface> $pages pages to add
     *
     * @throws InvalidArgumentException if $pages is not array, Traversable or PageInterface
     */
    public function addPages(iterable $pages): void;

    /**
     * Sets pages this container should have, removing existing pages
     *
     * @param iterable<int|string, PageInterface> $pages pages to set
     *
     * @throws InvalidArgumentException
     */
    public function setPages(iterable $pages): void;

    /**
     * Returns pages in the container
     *
     * @return array<string, PageInterface>
     *
     * @throws void
     */
    public function getPages(): array;

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
    public function removePage(int | PageInterface $page, bool $recursive = false): bool;

    /**
     * Removes all pages in container
     *
     * @throws void
     */
    public function removePages(): void;

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
    public function hasPage(int | PageInterface $page, bool $recursive = false): bool;

    /**
     * Returns true if container contains any pages
     *
     * @param bool $onlyVisible whether to check only visible pages
     *
     * @return bool whether container has any pages
     *
     * @throws void
     */
    public function hasPages(bool $onlyVisible = false): bool;

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
    public function findOneBy(string $property, mixed $value): PageInterface | null;

    /**
     * Returns all child pages matching $property == $value, or an empty array
     * if no pages are found
     *
     * @param string $property name of property to match against
     * @param mixed  $value    value to match property against
     *
     * @return array<int, PageInterface>
     *
     * @throws InvalidArgumentException
     */
    public function findAllBy(string $property, mixed $value): array;

    /**
     * Returns an array representation of all pages in container
     *
     * @return array<int|string, mixed>
     *
     * @throws void
     */
    public function toArray(): array;
}
