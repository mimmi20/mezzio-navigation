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
namespace Mezzio\Navigation\Page;

use Mezzio\Navigation\ContainerInterface;
use Mezzio\Navigation\Exception;
use Traversable;

/**
 * Interface for Mezzio\Navigation\Page pages
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
     * @param array $options associative array of options to set
     *
     * @throws Exception\InvalidArgumentException if invalid options are given
     *
     * @return void
     */
    public function setOptions(array $options): void;

    // Accessors:

    /**
     * Sets page label
     *
     * @param string $label new page label
     *
     * @throws Exception\InvalidArgumentException if empty/no string is given
     *
     * @return void
     */
    public function setLabel(string $label): void;

    /**
     * Returns page label
     *
     * @return string|null page label or null
     */
    public function getLabel(): ?string;

    /**
     * Sets a fragment identifier
     *
     * @param string $fragment new fragment identifier
     *
     * @throws Exception\InvalidArgumentException if empty/no string is given
     *
     * @return void
     */
    public function setFragment(string $fragment): void;

    /**
     * Returns fragment identifier
     *
     * @return string|null fragment identifier
     */
    public function getFragment(): ?string;

    /**
     * Sets page id
     *
     * @param string|null $id [optional] id to set. Default is null,
     *                        which sets no id.
     *
     * @throws Exception\InvalidArgumentException if not given string or null
     *
     * @return void
     */
    public function setId(?string $id = null): void;

    /**
     * Returns page id
     *
     * @return string|null page id or null
     */
    public function getId(): ?string;

    /**
     * Sets page CSS class
     *
     * @param string|null $class [optional] CSS class to set. Default
     *                           is null, which sets no CSS class.
     *
     * @throws Exception\InvalidArgumentException if not given string or null
     *
     * @return void
     */
    public function setClass(?string $class = null): void;

    /**
     * Returns page class (CSS)
     *
     * @return string|null page's CSS class or null
     */
    public function getClass(): ?string;

    /**
     * Sets page title
     *
     * @param string|null $title [optional] page title. Default is
     *                           null, which sets no title.
     *
     * @throws Exception\InvalidArgumentException if not given string or null
     *
     * @return void
     */
    public function setTitle(?string $title = null): void;

    /**
     * Returns page title
     *
     * @return string|null page title or null
     */
    public function getTitle(): ?string;

    /**
     * Sets page target
     *
     * @param string|null $target [optional] target to set. Default is
     *                            null, which sets no target.
     *
     * @throws Exception\InvalidArgumentException if target is not string or null
     *
     * @return void
     */
    public function setTarget(?string $target = null): void;

    /**
     * Returns page target
     *
     * @return string|null page target or null
     */
    public function getTarget(): ?string;

    /**
     * Sets the page's forward links to other pages
     *
     * This method expects an associative array of forward links to other pages,
     * where each element's key is the name of the relation (e.g. alternate,
     * prev, next, help, etc), and the value is a mixed value that could somehow
     * be considered a page.
     *
     * @param array|Traversable $relations [optional] an associative array of
     *                                     forward links to other pages
     *
     * @throws Exception\InvalidArgumentException if $relations is not an array
     *                                            or Traversable object
     *
     * @return void
     */
    public function setRel($relations = null): void;

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
     * @return array|null an array of relations. If $relation is not
     *                    specified, all relations will be returned in
     *                    an associative array.
     */
    public function getRel(?string $relation = null): ?array;

    /**
     * Sets the page's reverse links to other pages
     *
     * This method expects an associative array of reverse links to other pages,
     * where each element's key is the name of the relation (e.g. alternate,
     * prev, next, help, etc), and the value is a mixed value that could somehow
     * be considered a page.
     *
     * @param array|Traversable $relations [optional] an associative array of
     *                                     reverse links to other pages
     *
     * @throws Exception\InvalidArgumentException if $relations it not an array
     *                                            or Traversable object
     *
     * @return void
     */
    public function setRev($relations = null): void;

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
     * @return array|null an array of relations. If $relation is not
     *                    specified, all relations will be returned in
     *                    an associative array.
     */
    public function getRev(?string $relation = null): ?array;

    /**
     * Sets page order to use in parent container
     *
     * @param int|null $order [optional] page order in container.
     *                        Default is null, which sets no
     *                        specific order.
     *
     * @throws Exception\InvalidArgumentException if order is not integer or null
     *
     * @return void
     */
    public function setOrder(?int $order = null): void;

    /**
     * Returns page order used in parent container
     *
     * @return int|null page order or null
     */
    public function getOrder(): ?int;

    /**
     * Sets ACL resource associated with this page
     *
     * @param string $resource [optional] resource to associate
     *                         with page. Default is null, which
     *                         sets no resource.
     *
     * @throws Exception\InvalidArgumentException if $resource is invalid
     *
     * @return void
     */
    public function setResource(string $resource): void;

    /**
     * Returns ACL resource associated with this page
     *
     * @return string|null ACL resource or null
     */
    public function getResource(): ?string;

    /**
     * Sets privilege associated with this page
     *
     * @param string $privilege [optional] ACL privilege to associate
     *                          with this page. Default is null, which
     *                          sets no privilege.
     *
     * @return void
     */
    public function setPrivilege(string $privilege): void;

    /**
     * Returns ACL privilege associated with this page
     *
     * @return string|null ACL privilege or null
     */
    public function getPrivilege(): ?string;

    /**
     * Sets permission associated with this page
     *
     * @param string $permission [optional] permission to associate
     *                           with this page. Default is null, which
     *                           sets no permission.
     *
     * @return void
     */
    public function setPermission(string $permission): void;

    /**
     * Returns permission associated with this page
     *
     * @return string|null permission or null
     */
    public function getPermission(): ?string;

    /**
     * Sets text domain for translation
     *
     * @param string $textDomain [optional] text domain to associate
     *                           with this page. Default is null, which
     *                           sets no text domain.
     *
     * @return void
     */
    public function setTextDomain(string $textDomain): void;

    /**
     * Returns text domain for translation
     *
     * @return string|null text domain or null
     */
    public function getTextDomain(): ?string;

    /**
     * Sets whether page should be considered active or not
     *
     * @param bool $active [optional] whether page should be
     *                     considered active or not. Default is true.
     *
     * @return void
     */
    public function setActive(bool $active = true): void;

    /**
     * Returns whether page should be considered active or not
     *
     * @param bool $recursive [optional] whether page should be considered
     *                        active if any child pages are active. Default is
     *                        false.
     *
     * @return bool whether page should be considered active
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
     */
    public function getActive(bool $recursive = false): bool;

    /**
     * Sets whether the page should be visible or not
     *
     * @param bool $visible [optional] whether page should be
     *                      considered visible or not. Default is true.
     *
     * @return void
     */
    public function setVisible(bool $visible = true): void;

    /**
     * Returns a boolean value indicating whether the page is visible
     *
     * @param bool $recursive [optional] whether page should be considered
     *                        invisible if parent is invisible. Default is
     *                        false.
     *
     * @return bool whether page should be considered visible
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
     */
    public function getVisible(bool $recursive = false): bool;

    /**
     * Sets parent container
     *
     * @param ContainerInterface|null $parent [optional] new parent to set.
     *                                        Default is null which will set no parent.
     *
     * @throws Exception\InvalidArgumentException
     *
     * @return void
     */
    public function setParent(?ContainerInterface $parent = null): void;

    /**
     * Returns parent container
     *
     * @return ContainerInterface|null parent container or null
     */
    public function getParent(): ?ContainerInterface;

    /**
     * Sets the given property
     *
     * If the given property is native (id, class, title, etc), the matching
     * set method will be used. Otherwise, it will be set as a custom property.
     *
     * @param string $property property name
     * @param mixed  $value    value to set
     *
     * @throws Exception\InvalidArgumentException if property name is invalid
     *
     * @return void
     */
    public function set(string $property, $value): void;

    /**
     * Returns the value of the given property
     *
     * If the given property is native (id, class, title, etc), the matching
     * get method will be used. Otherwise, it will return the matching custom
     * property, or null if not found.
     *
     * @param string $property property name
     *
     * @throws Exception\InvalidArgumentException if property name is invalid
     *
     * @return mixed the property's value or null
     */
    public function get(string $property);

    /**
     * Adds a forward relation to the page
     *
     * @param string $relation relation name (e.g. alternate, glossary,
     *                         canonical, etc)
     * @param mixed  $value    value to set for relation
     *
     * @return void
     */
    public function addRel(string $relation, $value): void;

    /**
     * Adds a reverse relation to the page
     *
     * @param string $relation relation name (e.g. alternate, glossary,
     *                         canonical, etc)
     * @param mixed  $value    value to set for relation
     *
     * @return void
     */
    public function addRev(string $relation, $value): void;

    /**
     * Removes a forward relation from the page
     *
     * @param string $relation name of relation to remove
     *
     * @return void
     */
    public function removeRel(string $relation): void;

    /**
     * Removes a reverse relation from the page
     *
     * @param string $relation name of relation to remove
     *
     * @return void
     */
    public function removeRev(string $relation): void;

    /**
     * Returns an array containing the defined forward relations
     *
     * @return array defined forward relations
     */
    public function getDefinedRel(): array;

    /**
     * Returns an array containing the defined reverse relations
     *
     * @return array defined reverse relations
     */
    public function getDefinedRev(): array;

    /**
     * Returns custom properties as an array
     *
     * @return array an array containing custom properties
     */
    public function getCustomProperties(): array;

    /**
     * Returns a hash code value for the page
     *
     * @return string a hash code value for this page
     */
    public function hashCode(): string;

    /**
     * Returns href for this page
     *
     * Includes the fragment identifier if it is set.
     *
     * @return string
     */
    public function getHref(): string;
}
