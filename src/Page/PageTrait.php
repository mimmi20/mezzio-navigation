<?php
/**
 * This file is part of the mimmi20/mezzio-navigation package.
 *
 * Copyright (c) 2020-2021, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mezzio\Navigation\Page;

use Laminas\Stdlib\Exception\InvalidArgumentException;
use Mezzio\Navigation\ContainerInterface;
use Mezzio\Navigation\ContainerTrait;
use Mezzio\Navigation\Exception;

use function array_keys;
use function array_merge;
use function is_int;
use function is_numeric;
use function is_string;
use function mb_strtolower;
use function method_exists;
use function spl_object_hash;
use function sprintf;
use function str_replace;
use function ucwords;

/**
 * Base class for Mezzio\Navigation\Page pages
 */
trait PageTrait
{
    use ContainerTrait {
        toArray as parentToArray;
    }

    /**
     * Page label
     */
    private ?string $label = null;

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
    private ?string $fragment = null;

    /**
     * Page id
     */
    private ?string $id = null;

    /**
     * Style class for this page (CSS)
     */
    private ?string $class = null;

    /**
     * Style class for the container around this page (CSS)
     */
    private ?string $liClass = null;

    /**
     * A more descriptive title for this page
     */
    private ?string $title = null;

    /**
     * This page's target
     */
    private ?string $target = null;

    /**
     * Forward links to other pages
     *
     * @see http://www.w3.org/TR/html4/struct/links.html#h-12.3.1
     *
     * @var array<string, string>
     */
    private array $rel = [];

    /**
     * Reverse links to other pages
     *
     * @see http://www.w3.org/TR/html4/struct/links.html#h-12.3.1
     *
     * @var array<string, string>
     */
    private array $rev = [];

    /**
     * Page order used by parent container
     */
    private ?int $order = null;

    /**
     * resource associated with this page
     */
    private ?string $resource = null;

    /**
     * ACL privilege associated with this page
     */
    private ?string $privilege = null;

    /**
     * Permission associated with this page
     */
    private ?string $permission = null;

    /**
     * Text domain for Translator
     */
    private ?string $textDomain = null;

    /**
     * Whether this page should be considered active
     */
    private ?bool $active = null;

    /**
     * Whether this page should be considered visible
     */
    private bool $visible = true;

    /**
     * Parent container
     */
    private ?ContainerInterface $parent = null;

    /**
     * Custom page properties, used by __set(), __get() and __isset()
     *
     * @var array<string, string>
     */
    private array $properties = [];

    /**
     * @param iterable|mixed[]|null $options [optional] page options. Default is null, which should set defaults.
     *
     * @throws Exception\InvalidArgumentException if invalid options are given
     */
    public function __construct(?iterable $options = null)
    {
        if (null === $options) {
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
     * @param string $name  property name
     * @param mixed  $value value to set
     *
     * @throws Exception\InvalidArgumentException if property name is invalid
     */
    public function __set(string $name, $value): void
    {
        $this->set($name, $value);
    }

    /**
     * Returns a property, or null if it doesn't exist
     *
     * Magic overload for enabling <code>$page->propname</code>.
     *
     * @param string $name property name
     *
     * @return mixed property value or null
     *
     * @throws Exception\InvalidArgumentException if property name is invalid
     */
    public function __get(string $name)
    {
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
     */
    public function __isset(string $name): bool
    {
        $method = 'get' . static::normalizePropertyName($name);
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
        $method = 'set' . static::normalizePropertyName($name);
        if (method_exists($this, $method)) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'Unsetting native property "%s" is not allowed',
                    $name
                )
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
     */
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
     * @param iterable|string[] $options associative array of options to set
     *
     * @throws Exception\InvalidArgumentException if invalid options are given
     */
    final public function setOptions(iterable $options): void
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
     */
    final public function setLabel(?string $label = null): void
    {
        $this->label = $label;
    }

    /**
     * Returns page label
     *
     * @return string|null page label or null
     */
    final public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Sets a fragment identifier
     *
     * @param string|null $fragment new fragment identifier
     */
    final public function setFragment(?string $fragment = null): void
    {
        $this->fragment = $fragment;
    }

    /**
     * Returns fragment identifier
     *
     * @return string|null fragment identifier
     */
    final public function getFragment(): ?string
    {
        return $this->fragment;
    }

    /**
     * Sets page id
     *
     * @param string|null $id [optional] id to set. Default is null,
     *                        which sets no id.
     */
    final public function setId(?string $id = null): void
    {
        $this->id = $id;
    }

    /**
     * Returns page id
     *
     * @return string|null page id or null
     */
    final public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Sets page CSS class
     *
     * @param string|null $class [optional] CSS class to set. Default is null, which sets no CSS class.
     */
    final public function setClass(?string $class = null): void
    {
        $this->class = $class;
    }

    /**
     * Returns page class (CSS)
     *
     * @return string|null page's CSS class or null
     */
    final public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * Sets page CSS class
     *
     * @param string|null $liClass [optional] CSS class to set. Default is null, which sets no CSS class.
     */
    final public function setLiClass(?string $liClass = null): void
    {
        $this->liClass = $liClass;
    }

    /**
     * Returns page class (CSS)
     *
     * @return string|null page's CSS class or null
     */
    final public function getLiClass(): ?string
    {
        return $this->liClass;
    }

    /**
     * Sets page title
     *
     * @param string|null $title [optional] page title. Default is
     *                           null, which sets no title.
     */
    final public function setTitle(?string $title = null): void
    {
        $this->title = $title;
    }

    /**
     * Returns page title
     *
     * @return string|null page title or null
     */
    final public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets page target
     *
     * @param string|null $target [optional] target to set. Default is
     *                            null, which sets no target.
     */
    final public function setTarget(?string $target = null): void
    {
        $this->target = $target;
    }

    /**
     * Returns page target
     *
     * @return string|null page target or null
     */
    final public function getTarget(): ?string
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
     * @param iterable|string[]|null $relations [optional] an associative array of forward links to other pages
     *
     * @throws Exception\InvalidArgumentException if $relations is not an array or Traversable object
     * @throws InvalidArgumentException
     */
    final public function setRel(?iterable $relations = null): void
    {
        $this->rel = [];

        if (null === $relations) {
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
     * @return array<string, string>|string|null an array of relations. If $relation is not
     *                       specified, all relations will be returned in
     *                       an associative array.
     */
    final public function getRel(?string $relation = null)
    {
        if (null !== $relation) {
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
     * @param iterable|string[]|null $relations [optional] an associative array of reverse links to other pages
     *
     * @throws Exception\InvalidArgumentException if $relations it not an array or Traversable object
     * @throws InvalidArgumentException
     */
    final public function setRev(?iterable $relations = null): void
    {
        $this->rev = [];

        if (null === $relations) {
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
     * @return array<string, string>|string|null an array of relations. If $relation is not
     *                       specified, all relations will be returned in
     *                       an associative array.
     */
    final public function getRev(?string $relation = null)
    {
        if (null !== $relation) {
            return $this->rev[$relation] ?? null;
        }

        return $this->rev;
    }

    /**
     * Sets parent container
     *
     * @param ContainerInterface|null $parent [optional] new parent to set.
     *                                        Default is null which will set no parent.
     *
     * @throws Exception\InvalidArgumentException
     */
    final public function setParent(?ContainerInterface $parent = null): void
    {
        if ($parent === $this) {
            throw new Exception\InvalidArgumentException(
                'A page cannot have itself as a parent'
            );
        }

        // return if the given parent already is parent
        if ($parent === $this->parent) {
            return;
        }

        // remove from old parent
        if (null !== $this->parent) {
            $this->parent->removePage($this);
        }

        // set new parent
        $this->parent = $parent;

        // add to parent if page and not already a child
        if (null === $this->parent || $this->parent->hasPage($this, false)) {
            return;
        }

        $this->parent->addPage($this);
    }

    /**
     * Returns parent container
     *
     * @return ContainerInterface|null parent container or null
     */
    final public function getParent(): ?ContainerInterface
    {
        return $this->parent;
    }

    /**
     * Sets page order to use in parent container
     *
     * @param int|string|null $order [optional] page order in container.
     *                               Default is null, which sets no
     *                               specific order.
     *
     * @throws Exception\InvalidArgumentException if order is not integer or null
     */
    final public function setOrder($order = null): void
    {
        if (is_int($order) || null === $order) {
            $this->order = $order;
        } elseif (is_string($order) || is_numeric($order)) {
            $this->order = (int) $order;
        } else {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $order must be a string, an integer or null'
            );
        }

        // notify parent, if any
        if (null === $this->parent) {
            return;
        }

        $this->parent->notifyOrderUpdated();
    }

    /**
     * Returns page order used in parent container
     *
     * @return int|null page order or null
     */
    final public function getOrder(): ?int
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
     * @throws Exception\InvalidArgumentException if $resource is invalid
     */
    final public function setResource(string $resource): void
    {
        $this->resource = $resource;
    }

    /**
     * Returns ACL resource associated with this page
     *
     * @return string|null ACL resource or null
     */
    final public function getResource(): ?string
    {
        return $this->resource;
    }

    /**
     * Sets privilege associated with this page
     *
     * @param string $privilege [optional] ACL privilege to associate
     *                          with this page. Default is null, which
     *                          sets no privilege.
     */
    final public function setPrivilege(string $privilege): void
    {
        $this->privilege = $privilege;
    }

    /**
     * Returns ACL privilege associated with this page
     *
     * @return string|null ACL privilege or null
     */
    final public function getPrivilege(): ?string
    {
        return $this->privilege;
    }

    /**
     * Sets permission associated with this page
     *
     * @param string $permission [optional] permission to associate
     *                           with this page. Default is null, which
     *                           sets no permission.
     */
    final public function setPermission(string $permission): void
    {
        $this->permission = $permission;
    }

    /**
     * Returns permission associated with this page
     *
     * @return string|null permission or null
     */
    final public function getPermission(): ?string
    {
        return $this->permission;
    }

    /**
     * Sets text domain for translation
     *
     * @param string $textDomain [optional] text domain to associate
     *                           with this page. Default is null, which
     *                           sets no text domain.
     */
    final public function setTextDomain(string $textDomain): void
    {
        $this->textDomain = $textDomain;
    }

    /**
     * Returns text domain for translation
     *
     * @return string|null text domain or null
     */
    final public function getTextDomain(): ?string
    {
        return $this->textDomain;
    }

    /**
     * Sets whether page should be considered active or not
     *
     * @param bool|string $active [optional] whether page should be
     *                            considered active or not. Default is true.
     */
    final public function setActive($active = true): void
    {
        if (is_string($active) && 'false' === mb_strtolower($active)) {
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
     */
    final public function isActive(bool $recursive = false): bool
    {
        if (null === $this->active && $recursive) {
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
     */
    final public function getActive(bool $recursive = false): bool
    {
        return $this->isActive($recursive);
    }

    /**
     * Sets whether the page should be visible or not
     *
     * @param bool|string $visible [optional] whether page should be
     *                             considered visible or not. Default is true.
     */
    final public function setVisible($visible = true): void
    {
        if (is_string($visible) && 'false' === mb_strtolower($visible)) {
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
     */
    final public function isVisible(bool $recursive = false): bool
    {
        if (
            $recursive
            && null !== $this->parent
            && $this->parent instanceof PageInterface
        ) {
            if (!$this->parent->isVisible($recursive)) {
                return false;
            }
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
     */
    final public function getVisible(bool $recursive = false): bool
    {
        return $this->isVisible($recursive);
    }

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
     */
    final public function set(string $property, $value): void
    {
        if ('' === $property) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $property must be a non-empty string'
            );
        }

        $method = 'set' . static::normalizePropertyName($property);

        if ('setOptions' !== $method && method_exists($this, $method)) {
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
     * @return mixed the property's value or null
     *
     * @throws Exception\InvalidArgumentException if property name is invalid
     */
    final public function get(string $property)
    {
        if ('' === $property) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $property must be a non-empty string'
            );
        }

        $method = 'get' . static::normalizePropertyName($property);

        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        if (isset($this->properties[$property])) {
            return $this->properties[$property];
        }

        return null;
    }

    // Public methods:

    /**
     * Adds a forward relation to the page
     *
     * @param string $relation relation name (e.g. alternate, glossary,
     *                         canonical, etc)
     * @param mixed  $value    value to set for relation
     */
    final public function addRel(string $relation, $value): void
    {
        $this->rel[$relation] = $value;
    }

    /**
     * Adds a reverse relation to the page
     *
     * @param string $relation relation name (e.g. alternate, glossary,
     *                         canonical, etc)
     * @param mixed  $value    value to set for relation
     */
    final public function addRev(string $relation, $value): void
    {
        $this->rev[$relation] = $value;
    }

    /**
     * Removes a forward relation from the page
     *
     * @param string $relation name of relation to remove
     */
    final public function removeRel(string $relation): void
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
     */
    final public function removeRev(string $relation): void
    {
        if (!isset($this->rev[$relation])) {
            return;
        }

        unset($this->rev[$relation]);
    }

    /**
     * Returns an array containing the defined forward relations
     *
     * @return array<string> defined forward relations
     */
    final public function getDefinedRel(): array
    {
        return array_keys($this->rel);
    }

    /**
     * Returns an array containing the defined reverse relations
     *
     * @return array<string> defined reverse relations
     */
    final public function getDefinedRev(): array
    {
        return array_keys($this->rev);
    }

    /**
     * Returns custom properties as an array
     *
     * @return array<string, string> an array containing custom properties
     */
    final public function getCustomProperties(): array
    {
        return $this->properties;
    }

    /**
     * Returns a hash code value for the page
     *
     * @return string a hash code value for this page
     */
    final public function hashCode(): string
    {
        return spl_object_hash($this);
    }

    /**
     * Returns an array representation of the page
     *
     * @return array<string, array<string, string>|bool|int|string|null> associative array containing all page properties
     */
    final public function toArray(): array
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
                'pages' => $this->parentToArray(),
            ]
        );
    }

    // Internal methods:

    /**
     * Normalizes a property name
     *
     * @param string $property property name to normalize
     *
     * @return string normalized property name
     */
    private static function normalizePropertyName(string $property): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
    }
}
