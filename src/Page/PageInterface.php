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

namespace Mimmi20\Mezzio\Navigation\Page;

use Mimmi20\Mezzio\Navigation\ContainerInterface;
use Mimmi20\Mezzio\Navigation\Exception;

/**
 * Interface for Mimmi20\Mezzio\Navigation\Page pages
 *
 * @extends ContainerInterface<PageInterface>
 */
interface PageInterface extends ContainerInterface
{
    /**
     * Sets page properties using options from an associative array
     *
     * Each key in the array corresponds to the according set*() method, and
     * each word is separated by underscores, e.g. the option 'target'
     * corresponds to setTarget(), and the option 'reset_params' corresponds to
     * the method setResetParams().
     *
     * @param iterable<string, array<string, string>|bool|string> $options associative array of options to set
     *
     * @throws Exception\InvalidArgumentException if invalid options are given
     */
    public function setOptions(iterable $options): void;

    // Accessors:

    /**
     * Sets page label
     *
     * @param string|null $label new page label
     *
     * @throws void
     */
    public function setLabel(string | null $label = null): void;

    /**
     * Returns page label
     *
     * @return string|null page label or null
     *
     * @throws void
     */
    public function getLabel(): string | null;

    /**
     * Sets a fragment identifier
     *
     * @param string|null $fragment new fragment identifier
     *
     * @throws void
     */
    public function setFragment(string | null $fragment = null): void;

    /**
     * Returns fragment identifier
     *
     * @return string|null fragment identifier
     *
     * @throws void
     */
    public function getFragment(): string | null;

    /**
     * Sets page id
     *
     * @param string|null $id [optional] id to set. Default is null, which sets no id.
     *
     * @throws void
     */
    public function setId(string | null $id = null): void;

    /**
     * Returns page id
     *
     * @return string|null page id or null
     *
     * @throws void
     */
    public function getId(): string | null;

    /**
     * Sets page CSS class
     *
     * @param string|null $class [optional] CSS class to set. Default
     *                           is null, which sets no CSS class.
     *
     * @throws void
     */
    public function setClass(string | null $class = null): void;

    /**
     * Returns page class (CSS)
     *
     * @return string|null page's CSS class or null
     *
     * @throws void
     */
    public function getClass(): string | null;

    /**
     * Sets page CSS class
     *
     * @param string|null $liClass [optional] CSS class to set. Default is null, which sets no CSS class.
     *
     * @throws void
     */
    public function setLiClass(string | null $liClass = null): void;

    /**
     * Returns page class (CSS)
     *
     * @return string|null page's CSS class or null
     *
     * @throws void
     */
    public function getLiClass(): string | null;

    /**
     * Sets page title
     *
     * @param string|null $title [optional] page title. Default is
     *                           null, which sets no title.
     *
     * @throws void
     */
    public function setTitle(string | null $title = null): void;

    /**
     * Returns page title
     *
     * @return string|null page title or null
     *
     * @throws void
     */
    public function getTitle(): string | null;

    /**
     * Sets page target
     *
     * @param string|null $target [optional] target to set. Default is
     *                            null, which sets no target.
     *
     * @throws void
     */
    public function setTarget(string | null $target = null): void;

    /**
     * Returns page target
     *
     * @return string|null page target or null
     *
     * @throws void
     */
    public function getTarget(): string | null;

    /**
     * Sets the page's forward links to other pages
     *
     * This method expects an associative array of forward links to other pages,
     * where each element's key is the name of the relation (e.g. alternate,
     * prev, next, help, etc), and the value is a mixed value that could somehow
     * be considered a page.
     *
     * @param iterable<int|string, ContainerInterface<PageInterface>|iterable<string, string>|PageInterface|string>|null $relations [optional] an associative array of
     *                                                                                                                            forward links to other pages
     *
     * @throws void
     */
    public function setRel(iterable | null $relations = null): void;

    /**
     * Returns the page's forward links to other pages
     *
     * This method returns an associative array of forward links to other pages,
     * where each element's key is the name of the relation (e.g. alternate,
     * prev, next, help, etc), and the value is a mixed value that could somehow
     * be considered a page.
     *
     * @param string|null $relation [optional] name of relation to return. If not
     *                              given, all relations will be returned.
     *
     * @return ContainerInterface<PageInterface>|iterable<ContainerInterface<PageInterface>|iterable<string, string>|PageInterface|string>|PageInterface|string|null an array of relations. If $relation is not
     *                           specified, all relations will be returned in
     *                           an associative array.
     *
     * @throws void
     */
    public function getRel(string | null $relation = null): iterable | ContainerInterface | self | string | null;

    /**
     * Sets the page's reverse links to other pages
     *
     * This method expects an associative array of reverse links to other pages,
     * where each element's key is the name of the relation (e.g. alternate,
     * prev, next, help, etc), and the value is a mixed value that could somehow
     * be considered a page.
     *
     * @param iterable<int|string, ContainerInterface<PageInterface>|iterable<string, string>|PageInterface|string>|null $relations [optional] an associative array of
     *                                                                                                                            reverse links to other pages
     *
     * @throws void
     */
    public function setRev(iterable | null $relations = null): void;

    /**
     * Returns the page's reverse links to other pages
     *
     * This method returns an associative array of forward links to other pages,
     * where each element's key is the name of the relation (e.g. alternate,
     * prev, next, help, etc), and the value is a mixed value that could somehow
     * be considered a page.
     *
     * @param string|null $relation [optional] name of relation to return. If not
     *                              given, all relations will be returned.
     *
     * @return ContainerInterface<PageInterface>|iterable<ContainerInterface<PageInterface>|iterable<string, string>|PageInterface|string>|PageInterface|string|null an array of relations. If $relation is not
     *                           specified, all relations will be returned in
     *                           an associative array.
     *
     * @throws void
     */
    public function getRev(string | null $relation = null): iterable | ContainerInterface | self | string | null;

    /**
     * Sets page order to use in parent container
     *
     * @param float|int|string|null $order [optional] page order in container.
     *                               Default is null, which sets no
     *                               specific order.
     *
     * @throws void
     */
    public function setOrder(int | float | string | null $order = null): void;

    /**
     * Returns page order used in parent container
     *
     * @return int|null page order or null
     *
     * @throws void
     */
    public function getOrder(): int | null;

    /**
     * Returns resource associated with this page
     *
     * @return string|null resource or null
     *
     * @throws void
     */
    public function getResource(): string | null;

    /**
     * Sets privilege associated with this page
     *
     * @param string $privilege [optional] privilege to associate
     *                          with this page. Default is null, which
     *                          sets no privilege.
     *
     * @throws void
     */
    public function setPrivilege(string $privilege): void;

    /**
     * Returns privilege associated with this page
     *
     * @return string|null privilege or null
     *
     * @throws void
     */
    public function getPrivilege(): string | null;

    /**
     * Sets text domain for translation
     *
     * @param string $textDomain [optional] text domain to associate
     *                           with this page. Default is null, which
     *                           sets no text domain.
     *
     * @throws void
     */
    public function setTextDomain(string $textDomain): void;

    /**
     * Returns text domain for translation
     *
     * @return string|null text domain or null
     *
     * @throws void
     */
    public function getTextDomain(): string | null;

    /**
     * Sets whether page should be considered active or not
     *
     * @param bool|string $active [optional] whether page should be
     *                            considered active or not. Default is true.
     *
     * @throws void
     */
    public function setActive(bool | string $active = true): void;

    /**
     * Returns whether page should be considered active or not
     *
     * @param bool $recursive [optional] whether page should be considered
     *                        active if any child pages are active. Default is
     *                        false.
     *
     * @return bool whether page should be considered active
     *
     * @throws void
     */
    public function isActive(bool $recursive = false): bool;

    /**
     * Proxy to isActive()
     *
     * @param bool $recursive [optional] whether page should be considered
     *                        active if any child pages are active. Default
     *                        is false.
     *
     * @return bool whether page should be considered active
     *
     * @throws void
     */
    public function getActive(bool $recursive = false): bool;

    /**
     * Sets whether the page should be visible or not
     *
     * @param bool|string $visible [optional] whether page should be considered visible or not. Default is true.
     *
     * @throws void
     */
    public function setVisible(bool | string $visible = true): void;

    /**
     * Returns a boolean value indicating whether the page is visible
     *
     * @param bool $recursive [optional] whether page should be considered
     *                        invisible if parent is invisible. Default is
     *                        false.
     *
     * @return bool whether page should be considered visible
     *
     * @throws void
     */
    public function isVisible(bool $recursive = false): bool;

    /**
     * Proxy to isVisible()
     *
     * Returns a boolean value indicating whether the page is visible
     *
     * @param bool $recursive [optional] whether page should be considered
     *                        invisible if parent is invisible. Default is
     *                        false.
     *
     * @return bool whether page should be considered visible
     *
     * @throws void
     */
    public function getVisible(bool $recursive = false): bool;

    /**
     * Sets parent container
     *
     * @param ContainerInterface<PageInterface>|null $parent [optional] new parent to set.
     *                                        Default is null which will set no parent.
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setParent(ContainerInterface | null $parent = null): void;

    /**
     * Returns parent container
     *
     * @return ContainerInterface<PageInterface>|null parent container or null
     *
     * @throws void
     */
    public function getParent(): ContainerInterface | null;

    /**
     * Sets the given property
     *
     * If the given property is native (id, class, title, etc), the matching
     * set method will be used. Otherwise, it will be set as a custom property.
     *
     * @param string                                                                                                                      $property property name
     * @param bool|ContainerInterface<PageInterface>|float|int|iterable<string, (array<string, string>|string)>|PageInterface|string|null $value    value to set
     *
     * @throws Exception\InvalidArgumentException if property name is invalid
     */
    public function set(string $property, bool | float | int | iterable | string | null $value): void;

    /**
     * Returns the value of the given property
     *
     * If the given property is native (id, class, title, etc), the matching
     * get method will be used. Otherwise, it will return the matching custom
     * property, or null if not found.
     *
     * @param string $property property name
     *
     * @return bool|ContainerInterface<PageInterface>|float|int|iterable<string, (array<string, string>|string)>|PageInterface|string|null the property's value or null
     *
     * @throws Exception\InvalidArgumentException if property name is invalid
     */
    public function get(string $property): bool | float | int | iterable | string | null;

    /**
     * Adds a forward relation to the page
     *
     * @param string                                                                          $relation relation name (e.g. alternate, glossary, canonical, etc)
     * @param ContainerInterface<PageInterface>|iterable<string, string>|PageInterface|string $value    value to set for relation
     *
     * @throws void
     */
    public function addRel(string $relation, iterable | ContainerInterface | self | string $value): void;

    /**
     * Adds a reverse relation to the page
     *
     * @param string                                                                          $relation relation name (e.g. alternate, glossary, canonical, etc)
     * @param ContainerInterface<PageInterface>|iterable<string, string>|PageInterface|string $value    value to set for relation
     *
     * @throws void
     */
    public function addRev(string $relation, iterable | ContainerInterface | self | string $value): void;

    /**
     * Removes a forward relation from the page
     *
     * @param string $relation name of relation to remove
     *
     * @throws void
     */
    public function removeRel(string $relation): void;

    /**
     * Removes a reverse relation from the page
     *
     * @param string $relation name of relation to remove
     *
     * @throws void
     */
    public function removeRev(string $relation): void;

    /**
     * Returns an array containing the defined forward relations
     *
     * @return array<int, string> defined forward relations
     *
     * @throws void
     */
    public function getDefinedRel(): array;

    /**
     * Returns an array containing the defined reverse relations
     *
     * @return array<int, string> defined reverse relations
     *
     * @throws void
     */
    public function getDefinedRev(): array;

    /**
     * Returns custom properties as an array
     *
     * @return array<string, bool|ContainerInterface<PageInterface>|float|int|iterable<string, (array<string, string>|string)>|PageInterface|string|null> an array containing custom properties
     *
     * @throws void
     */
    public function getCustomProperties(): array;

    /**
     * Returns a hash code value for the page
     *
     * @return string a hash code value for this page
     *
     * @throws void
     */
    public function hashCode(): string;

    /**
     * Returns href for this page
     *
     * Includes the fragment identifier if it is set.
     *
     * @throws void
     */
    public function getHref(): string;
}
