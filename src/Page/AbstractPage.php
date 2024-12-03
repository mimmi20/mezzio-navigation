<?php

/**
 * This file is part of the mimmi20/mezzio-navigation package.
 *
 * Copyright (c) 2020-2024, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Mezzio\Navigation\Page;

use Mimmi20\Mezzio\Navigation\AbstractContainer;
use Mimmi20\Mezzio\Navigation\ContainerInterface;
use Mimmi20\Mezzio\Navigation\Exception;
use Override;
use Stringable;

use function array_keys;
use function array_merge;
use function is_int;
use function is_string;
use function mb_strtolower;
use function method_exists;
use function spl_object_hash;
use function sprintf;
use function str_replace;
use function ucwords;

/**
 * Base class for Mimmi20\Mezzio\Navigation\Page pages
 */
abstract class AbstractPage extends AbstractContainer implements PageInterface, Stringable
{
    /**
     * Page label
     */
    protected string | null $label = null;

    /**
     * Fragment identifier (anchor identifier)
     *
     * The fragment identifier (anchor identifier) pointing to an anchor within
     * a resource that is subordinate to another, primary resource.
     * The fragment identifier introduced by a hash mark "#".
     * Example: http://www.example.org/foo.html#bar ("bar" is the fragment identifier)
     *
     * @see http://www.w3.org/TR/html401/intro/intro.html#fragment-uri
     */
    protected string | null $fragment = null;

    /**
     * Page id
     */
    protected string | null $id = null;

    /**
     * Style class for this page (CSS)
     */
    protected string | null $class = null;

    /**
     * Style class for the container around this page (CSS)
     */
    protected string | null $liClass = null;

    /**
     * A more descriptive title for this page
     */
    protected string | null $title = null;

    /**
     * This page's target
     */
    protected string | null $target = null;

    /**
     * Forward links to other pages
     *
     * @see http://www.w3.org/TR/html4/struct/links.html#h-12.3.1
     *
     * @var array<string, ContainerInterface<PageInterface>|iterable<string, string>|PageInterface|string>
     */
    protected array $rel = [];

    /**
     * Reverse links to other pages
     *
     * @see http://www.w3.org/TR/html4/struct/links.html#h-12.3.1
     *
     * @var array<string, ContainerInterface<PageInterface>|iterable<string, string>|PageInterface|string>
     */
    protected array $rev = [];

    /**
     * Page order used by parent container
     */
    protected int | null $order = null;

    /**
     * resource associated with this page
     */
    protected string | null $resource = null;

    /**
     * ACL privilege associated with this page
     */
    protected string | null $privilege = null;

    /**
     * Text domain for Translator
     */
    protected string | null $textDomain = null;

    /**
     * Whether this page should be considered active
     */
    protected bool | null $active = null;

    /**
     * Whether this page should be considered visible
     */
    protected bool $visible = true;

    /**
     * Parent container
     *
     * @var ContainerInterface<PageInterface>|null
     */
    protected ContainerInterface | null $parent = null;

    /**
     * Custom page properties, used by __set(), __get() and __isset()
     *
     * @var array<string, bool|ContainerInterface<PageInterface>|float|int|iterable<string, (array<string>|string)>|PageInterface|string|null>
     */
    protected array $properties = [];

    /**
     * Permission associated with this page
     */
    private string | null $permission = null;

    /**
     * @param iterable<string, array<string, string>|bool|string|null>|null $options [optional] page options. Default is null, which should set defaults.
     *
     * @throws Exception\InvalidArgumentException if invalid options are given
     */
    public function __construct(iterable | null $options = null)
    {
        if ($options === null) {
            return;
        }

        $this->setOptions($options);
    }

    // Magic overloads:

    /**
     * Sets a custom property
     *
     * Magic overload for enabling <code>$page->propname = $value</code>.
     *
     * @param string                                                                                                                      $name  property name
     * @param bool|ContainerInterface<PageInterface>|float|int|iterable<string, (array<string, string>|string)>|PageInterface|string|null $value value to set
     *
     * @throws Exception\InvalidArgumentException if property name is invalid
     */
    public function __set(
        string $name,
        bool | float | int | iterable | ContainerInterface | PageInterface | string | null $value,
    ): void {
        $this->set($name, $value);
    }

    /**
     * Returns a property, or null if it doesn't exist
     *
     * Magic overload for enabling <code>$page->propname</code>.
     *
     * @param string $name property name
     *
     * @return bool|ContainerInterface<PageInterface>|float|int|iterable<string, (array<string, string>|string)>|PageInterface|string|null property value or null
     *
     * @throws Exception\InvalidArgumentException if property name is invalid
     */
    public function __get(
        string $name,
    ): bool | float | int | iterable | ContainerInterface | PageInterface | string | null {
        return $this->get($name);
    }

    /**
     * Checks if a property is set
     *
     * Magic overload for enabling <code>isset($page->propname)</code>.
     *
     * Returns true if the property is native (id, class, title, etc), and
     * true or false if it's a custom property (depending on whether the
     * property actually is set).
     *
     * @param string $name property name
     *
     * @return bool whether the given property exists
     *
     * @throws void
     */
    public function __isset(string $name): bool
    {
        $method = 'get' . self::normalizePropertyName($name);

        if (method_exists($this, $method)) {
            return true;
        }

        return isset($this->properties[$name]);
    }

    /**
     * Unsets the given custom property
     *
     * Magic overload for enabling <code>unset($page->propname)</code>.
     *
     * @param string $name property name
     *
     * @throws Exception\InvalidArgumentException if the property is native
     */
    public function __unset(string $name): void
    {
        $method = 'set' . self::normalizePropertyName($name);

        if (method_exists($this, $method)) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'Unsetting native property "%s" is not allowed',
                    $name,
                ),
            );
        }

        if (!isset($this->properties[$name])) {
            return;
        }

        unset($this->properties[$name]);
    }

    /**
     * Returns page label
     *
     * Magic overload for enabling <code>echo $page</code>.
     *
     * @return string page label
     *
     * @throws void
     */
    #[Override]
    public function __toString(): string
    {
        return (string) $this->label;
    }

    /**
     * Sets page properties using options from an associative array
     *
     * Each key in the array corresponds to the according set*() method, and
     * each word is separated by underscores, e.g. the option 'target'
     * corresponds to setTarget(), and the option 'reset_params' corresponds to
     * the method setResetParams().
     *
     * @param iterable<string, array<string, string>|bool|string|null> $options associative array of options to set
     *
     * @throws Exception\InvalidArgumentException if invalid options are given
     */
    #[Override]
    public function setOptions(iterable $options): void
    {
        foreach ($options as $key => $value) {
            $this->set($key, $value);
        }
    }

    // Accessors:

    /**
     * Sets page label
     *
     * @param string|null $label new page label
     *
     * @throws void
     */
    #[Override]
    public function setLabel(string | null $label = null): void
    {
        $this->label = $label;
    }

    /**
     * Returns page label
     *
     * @return string|null page label or null
     *
     * @throws void
     */
    #[Override]
    public function getLabel(): string | null
    {
        return $this->label;
    }

    /**
     * Sets a fragment identifier
     *
     * @param string|null $fragment new fragment identifier
     *
     * @throws void
     */
    #[Override]
    public function setFragment(string | null $fragment = null): void
    {
        $this->fragment = $fragment;
    }

    /**
     * Returns fragment identifier
     *
     * @return string|null fragment identifier
     *
     * @throws void
     */
    #[Override]
    public function getFragment(): string | null
    {
        return $this->fragment;
    }

    /**
     * Sets page id
     *
     * @param string|null $id [optional] id to set. Default is null, which sets no id.
     *
     * @throws void
     */
    #[Override]
    public function setId(string | null $id = null): void
    {
        $this->id = $id;
    }

    /**
     * Returns page id
     *
     * @return string|null page id or null
     *
     * @throws void
     */
    #[Override]
    public function getId(): string | null
    {
        return $this->id;
    }

    /**
     * Sets page CSS class
     *
     * @param string|null $class [optional] CSS class to set. Default is null, which sets no CSS class.
     *
     * @throws void
     */
    #[Override]
    public function setClass(string | null $class = null): void
    {
        $this->class = $class;
    }

    /**
     * Returns page class (CSS)
     *
     * @return string|null page's CSS class or null
     *
     * @throws void
     */
    #[Override]
    public function getClass(): string | null
    {
        return $this->class;
    }

    /**
     * Sets page CSS class
     *
     * @param string|null $liClass [optional] CSS class to set. Default is null, which sets no CSS class.
     *
     * @throws void
     */
    #[Override]
    public function setLiClass(string | null $liClass = null): void
    {
        $this->liClass = $liClass;
    }

    /**
     * Returns page class (CSS)
     *
     * @return string|null page's CSS class or null
     *
     * @throws void
     */
    #[Override]
    public function getLiClass(): string | null
    {
        return $this->liClass;
    }

    /**
     * Sets page title
     *
     * @param string|null $title [optional] page title. Default is
     *                           null, which sets no title.
     *
     * @throws void
     */
    #[Override]
    public function setTitle(string | null $title = null): void
    {
        $this->title = $title;
    }

    /**
     * Returns page title
     *
     * @return string|null page title or null
     *
     * @throws void
     */
    #[Override]
    public function getTitle(): string | null
    {
        return $this->title;
    }

    /**
     * Sets page target
     *
     * @param string|null $target [optional] target to set. Default is
     *                            null, which sets no target.
     *
     * @throws void
     */
    #[Override]
    public function setTarget(string | null $target = null): void
    {
        $this->target = $target;
    }

    /**
     * Returns page target
     *
     * @return string|null page target or null
     *
     * @throws void
     */
    #[Override]
    public function getTarget(): string | null
    {
        return $this->target;
    }

    /**
     * Sets the page's forward links to other pages
     *
     * This method expects an associative array of forward links to other pages,
     * where each element's key is the name of the relation (e.g. alternate,
     * prev, next, help, etc), and the value is a mixed value that could somehow
     * be considered a page.
     *
     * @param iterable<int|string, ContainerInterface<PageInterface>|iterable<string, string>|PageInterface|string>|null $relations [optional] an associative array of forward links to other pages
     *
     * @throws void
     */
    #[Override]
    public function setRel(iterable | null $relations = null): void
    {
        $this->rel = [];

        if ($relations === null) {
            return;
        }

        foreach ($relations as $name => $relation) {
            if (!is_string($name)) {
                continue;
            }

            $this->rel[$name] = $relation;
        }
    }

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
     * @return ContainerInterface<PageInterface>|iterable<string, ContainerInterface<PageInterface>|iterable<string, string>|PageInterface|string>|PageInterface|string|null an array of relations. If $relation is not
     *                           specified, all relations will be returned in
     *                           an associative array.
     *
     * @throws void
     */
    #[Override]
    public function getRel(
        string | null $relation = null,
    ): iterable | ContainerInterface | PageInterface | string | null {
        if ($relation !== null) {
            return $this->rel[$relation] ?? null;
        }

        return $this->rel;
    }

    /**
     * Sets the page's reverse links to other pages
     *
     * This method expects an associative array of reverse links to other pages,
     * where each element's key is the name of the relation (e.g. alternate,
     * prev, next, help, etc), and the value is a mixed value that could somehow
     * be considered a page.
     *
     * @param iterable<int|string, ContainerInterface<PageInterface>|iterable<string, string>|PageInterface|string>|null $relations [optional] an associative array of reverse links to other pages
     *
     * @throws void
     */
    #[Override]
    public function setRev(iterable | null $relations = null): void
    {
        $this->rev = [];

        if ($relations === null) {
            return;
        }

        foreach ($relations as $name => $relation) {
            if (!is_string($name)) {
                continue;
            }

            $this->rev[$name] = $relation;
        }
    }

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
     * @return ContainerInterface<PageInterface>|iterable<string, ContainerInterface<PageInterface>|iterable<string, string>|PageInterface|string>|PageInterface|string|null an array of relations. If $relation is not
     *                           specified, all relations will be returned in
     *                           an associative array.
     *
     * @throws void
     */
    #[Override]
    public function getRev(
        string | null $relation = null,
    ): iterable | ContainerInterface | PageInterface | string | null {
        if ($relation !== null) {
            return $this->rev[$relation] ?? null;
        }

        return $this->rev;
    }

    /**
     * Sets parent container
     *
     * @param ContainerInterface<PageInterface>|null $parent [optional] new parent to set.
     *                                        Default is null which will set no parent.
     *
     * @throws Exception\InvalidArgumentException
     */
    #[Override]
    public function setParent(ContainerInterface | null $parent = null): void
    {
        if ($parent === $this) {
            throw new Exception\InvalidArgumentException('A page cannot have itself as a parent');
        }

        // return if the given parent already is parent
        if ($parent === $this->parent) {
            return;
        }

        // remove from old parent
        if ($this->parent !== null) {
            $this->parent->removePage($this);
        }

        // set new parent
        $this->parent = $parent;

        // add to parent if page and not already a child
        if (!$this->parent instanceof ContainerInterface || $this->parent->hasPage($this, false)) {
            return;
        }

        $this->parent->addPage($this);
    }

    /**
     * Returns parent container
     *
     * @return ContainerInterface<PageInterface>|null parent container or null
     *
     * @throws void
     */
    #[Override]
    public function getParent(): ContainerInterface | null
    {
        return $this->parent;
    }

    /**
     * Sets page order to use in parent container
     *
     * @param float|int|string|null $order [optional] page order in container.
     *                               Default is null, which sets no
     *                               specific order.
     *
     * @throws void
     */
    #[Override]
    public function setOrder(int | float | string | null $order = null): void
    {
        $this->order = is_int($order) || $order === null ? $order : (int) $order;

        // notify parent, if any
        if (!$this->parent instanceof ContainerInterface) {
            return;
        }

        $this->parent->notifyOrderUpdated();
    }

    /**
     * Returns page order used in parent container
     *
     * @return int|null page order or null
     *
     * @throws void
     */
    #[Override]
    public function getOrder(): int | null
    {
        return $this->order;
    }

    /**
     * Sets ACL resource associated with this page
     *
     * @param string $resource [optional] resource to associate
     *                         with page. Default is null, which
     *                         sets no resource.
     *
     * @throws void
     */
    #[Override]
    public function setResource(string $resource): void
    {
        $this->resource = $resource;
    }

    /**
     * Returns ACL resource associated with this page
     *
     * @return string|null ACL resource or null
     *
     * @throws void
     */
    #[Override]
    public function getResource(): string | null
    {
        return $this->resource;
    }

    /**
     * Sets privilege associated with this page
     *
     * @param string $privilege [optional] ACL privilege to associate
     *                          with this page. Default is null, which
     *                          sets no privilege.
     *
     * @throws void
     */
    #[Override]
    public function setPrivilege(string $privilege): void
    {
        $this->privilege = $privilege;
    }

    /**
     * Returns ACL privilege associated with this page
     *
     * @return string|null ACL privilege or null
     *
     * @throws void
     */
    #[Override]
    public function getPrivilege(): string | null
    {
        return $this->privilege;
    }

    /**
     * Sets permission associated with this page
     *
     * @param string $permission [optional] permission to associate
     *                           with this page. Default is null, which
     *                           sets no permission.
     *
     * @throws void
     */
    #[Override]
    public function setPermission(string $permission): void
    {
        $this->permission = $permission;
    }

    /**
     * Returns permission associated with this page
     *
     * @return string|null permission or null
     *
     * @throws void
     */
    #[Override]
    public function getPermission(): string | null
    {
        return $this->permission;
    }

    /**
     * Sets text domain for translation
     *
     * @param string $textDomain [optional] text domain to associate
     *                           with this page. Default is null, which
     *                           sets no text domain.
     *
     * @throws void
     */
    #[Override]
    public function setTextDomain(string $textDomain): void
    {
        $this->textDomain = $textDomain;
    }

    /**
     * Returns text domain for translation
     *
     * @return string|null text domain or null
     *
     * @throws void
     */
    #[Override]
    public function getTextDomain(): string | null
    {
        return $this->textDomain;
    }

    /**
     * Sets whether page should be considered active or not
     *
     * @param bool|string $active [optional] whether page should be
     *                            considered active or not. Default is true.
     *
     * @throws void
     */
    #[Override]
    public function setActive(bool | string $active = true): void
    {
        if (is_string($active) && mb_strtolower($active) === 'false') {
            $active = false;
        }

        $this->active = (bool) $active;
    }

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
    #[Override]
    public function isActive(bool $recursive = false): bool
    {
        if ($this->active === null && $recursive) {
            foreach ($this->pages as $page) {
                if ($page->isActive(true)) {
                    return true;
                }
            }

            return false;
        }

        return (bool) $this->active;
    }

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
    #[Override]
    public function getActive(bool $recursive = false): bool
    {
        return $this->isActive($recursive);
    }

    /**
     * Sets whether the page should be visible or not
     *
     * @param bool|string $visible [optional] whether page should be
     *                             considered visible or not. Default is true.
     *
     * @throws void
     */
    #[Override]
    public function setVisible(bool | string $visible = true): void
    {
        if (is_string($visible) && mb_strtolower($visible) === 'false') {
            $visible = false;
        }

        $this->visible = (bool) $visible;
    }

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
    #[Override]
    public function isVisible(bool $recursive = false): bool
    {
        if (
            $recursive
            && $this->parent instanceof PageInterface
            && !$this->parent->isVisible($recursive)
        ) {
            return false;
        }

        return $this->visible;
    }

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
    #[Override]
    public function getVisible(bool $recursive = false): bool
    {
        return $this->isVisible($recursive);
    }

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
    #[Override]
    public function set(
        string $property,
        bool | float | int | iterable | ContainerInterface | PageInterface | string | null $value,
    ): void {
        if ($property === '') {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $property must be a non-empty string',
            );
        }

        $method = 'set' . self::normalizePropertyName($property);

        if ($method !== 'setOptions' && method_exists($this, $method)) {
            $this->{$method}($value);
        } else {
            $this->properties[$property] = $value;
        }
    }

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
    #[Override]
    public function get(
        string $property,
    ): bool | float | int | iterable | ContainerInterface | PageInterface | string | null {
        if ($property === '') {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $property must be a non-empty string',
            );
        }

        $method = 'get' . self::normalizePropertyName($property);

        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        return $this->properties[$property] ?? null;
    }

    // Public methods:

    /**
     * Adds a forward relation to the page
     *
     * @param string                                                                          $relation relation name (e.g. alternate, glossary, canonical, etc)
     * @param ContainerInterface<PageInterface>|iterable<string, string>|PageInterface|string $value    value to set for relation
     *
     * @throws void
     */
    #[Override]
    public function addRel(string $relation, iterable | ContainerInterface | PageInterface | string $value): void
    {
        $this->rel[$relation] = $value;
    }

    /**
     * Adds a reverse relation to the page
     *
     * @param string                                                                          $relation relation name (e.g. alternate, glossary, canonical, etc)
     * @param ContainerInterface<PageInterface>|iterable<string, string>|PageInterface|string $value    value to set for relation
     *
     * @throws void
     */
    #[Override]
    public function addRev(string $relation, iterable | ContainerInterface | PageInterface | string $value): void
    {
        $this->rev[$relation] = $value;
    }

    /**
     * Removes a forward relation from the page
     *
     * @param string $relation name of relation to remove
     *
     * @throws void
     */
    #[Override]
    public function removeRel(string $relation): void
    {
        if (!isset($this->rel[$relation])) {
            return;
        }

        unset($this->rel[$relation]);
    }

    /**
     * Removes a reverse relation from the page
     *
     * @param string $relation name of relation to remove
     *
     * @throws void
     */
    #[Override]
    public function removeRev(string $relation): void
    {
        if (!isset($this->rev[$relation])) {
            return;
        }

        unset($this->rev[$relation]);
    }

    /**
     * Returns an array containing the defined forward relations
     *
     * @return array<int, string> defined forward relations
     *
     * @throws void
     */
    #[Override]
    public function getDefinedRel(): array
    {
        return array_keys($this->rel);
    }

    /**
     * Returns an array containing the defined reverse relations
     *
     * @return array<int, string> defined reverse relations
     *
     * @throws void
     */
    #[Override]
    public function getDefinedRev(): array
    {
        return array_keys($this->rev);
    }

    /**
     * Returns custom properties as an array
     *
     * @return array<string, bool|ContainerInterface<PageInterface>|float|int|iterable<string, (array<string, string>|string)>|PageInterface|string|null> an array containing custom properties
     *
     * @throws void
     */
    #[Override]
    public function getCustomProperties(): array
    {
        return $this->properties;
    }

    /**
     * Returns a hash code value for the page
     *
     * @return string a hash code value for this page
     *
     * @throws void
     */
    #[Override]
    public function hashCode(): string
    {
        return spl_object_hash($this);
    }

    /**
     * Returns an array representation of the page
     *
     * @return array<string, mixed> associative array containing all page properties
     *
     * @throws void
     */
    #[Override]
    public function toArray(): array
    {
        return array_merge(
            $this->getCustomProperties(),
            [
                'label' => $this->getLabel(),
                'fragment' => $this->getFragment(),
                'id' => $this->getId(),
                'class' => $this->getClass(),
                'title' => $this->getTitle(),
                'target' => $this->getTarget(),
                'rel' => $this->getRel(),
                'rev' => $this->getRev(),
                'order' => $this->getOrder(),
                'resource' => $this->getResource(),
                'privilege' => $this->getPrivilege(),
                'permission' => $this->getPermission(),
                'active' => $this->isActive(),
                'visible' => $this->isVisible(),
                'type' => static::class,
                'pages' => parent::toArray(),
            ],
        );
    }

    // Internal methods:

    /**
     * Normalizes a property name
     *
     * @param string $property property name to normalize
     *
     * @return string normalized property name
     *
     * @throws void
     */
    private static function normalizePropertyName(string $property): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
    }
}
