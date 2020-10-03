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

use Laminas\Stdlib\ArrayUtils;
use Mezzio\Navigation\AbstractContainer;
use Mezzio\Navigation\Exception;
use Traversable;

/**
 * Base class for Mezzio\Navigation\Page pages
 */
abstract class AbstractPage extends AbstractContainer
{
    /**
     * Page label
     *
     * @var string|null
     */
    protected $label;

    /**
     * Fragment identifier (anchor identifier)
     *
     * The fragment identifier (anchor identifier) pointing to an anchor within
     * a resource that is subordinate to another, primary resource.
     * The fragment identifier introduced by a hash mark "#".
     * Example: http://www.example.org/foo.html#bar ("bar" is the fragment identifier)
     *
     * @see http://www.w3.org/TR/html401/intro/intro.html#fragment-uri
     *
     * @var string|null
     */
    protected $fragment;

    /**
     * Page id
     *
     * @var string|null
     */
    protected $id;

    /**
     * Style class for this page (CSS)
     *
     * @var string|null
     */
    protected $class;

    /**
     * A more descriptive title for this page
     *
     * @var string|null
     */
    protected $title;

    /**
     * This page's target
     *
     * @var string|null
     */
    protected $target;

    /**
     * Forward links to other pages
     *
     * @see http://www.w3.org/TR/html4/struct/links.html#h-12.3.1
     *
     * @var array
     */
    protected $rel = [];

    /**
     * Reverse links to other pages
     *
     * @see http://www.w3.org/TR/html4/struct/links.html#h-12.3.1
     *
     * @var array
     */
    protected $rev = [];

    /**
     * Page order used by parent container
     *
     * @var int|null
     */
    protected $order;

    /**
     * resource associated with this page
     *
     * @var string|null
     */
    protected $resource;

    /**
     * ACL privilege associated with this page
     *
     * @var string|null
     */
    protected $privilege;

    /**
     * Permission associated with this page
     *
     * @var string|null
     */
    protected $permission;

    /**
     * Text domain for Translator
     *
     * @var string|null
     */
    protected $textDomain;

    /**
     * Whether this page should be considered active
     *
     * @var bool
     */
    protected $active = false;

    /**
     * Whether this page should be considered visible
     *
     * @var bool
     */
    protected $visible = true;

    /**
     * Parent container
     *
     * @var \Mezzio\Navigation\AbstractContainer|null
     */
    protected $parent;

    /**
     * Custom page properties, used by __set(), __get() and __isset()
     *
     * @var array
     */
    protected $properties = [];

    /**
     * Static factories list for factory pages
     *
     * @var array
     */
    protected static $factories = [];

    // Initialization:

    /**
     * Factory for Mezzio\Navigation\Page classes
     *
     * A specific type to construct can be specified by specifying the key
     * 'type' in $options. If type is 'uri' or 'mvc', the type will be resolved
     * to Mezzio\Navigation\Page\Uri or Mezzio\Navigation\Page\Mvc. Any other value
     * for 'type' will be considered the full name of the class to construct.
     * A valid custom page class must extend Mezzio\Navigation\Page\AbstractPage.
     *
     * If 'type' is not given, the type of page to construct will be determined
     * by the following rules:
     * - If $options contains either of the keys 'action', 'controller',
     *   or 'route', a Mezzio\Navigation\Page\Mvc page will be created.
     * - If $options contains the key 'uri', a Mezzio\Navigation\Page\Uri page
     *   will be created.
     *
     * @param array|Traversable $options options used for creating page
     *
     * @throws Exception\InvalidArgumentException if $options is not
     *                                            array/Traversable
     * @throws Exception\InvalidArgumentException if 'type' is specified
     *                                            but class not found
     * @throws Exception\InvalidArgumentException if something goes wrong
     *                                            during instantiation of
     *                                            the page
     * @throws Exception\InvalidArgumentException if 'type' is given, and
     *                                            the specified type does
     *                                            not extend this class
     * @throws Exception\InvalidArgumentException if unable to determine
     *                                            which class to instantiate
     *
     * @return AbstractPage a page instance
     */
    final public static function factory($options)
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (!is_array($options)) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $options must be an array or Traversable'
            );
        }

        if (isset($options['type'])) {
            $type = $options['type'];
            if (is_string($type) && !empty($type)) {
                switch (mb_strtolower($type)) {
                    case 'mvc':
                        $type = 'Mezzio\Navigation\Page\Mvc';
                        break;
                    case 'uri':
                        $type = 'Mezzio\Navigation\Page\Uri';
                        break;
                }

                if (!class_exists($type, true)) {
                    throw new Exception\InvalidArgumentException(
                        'Cannot find class ' . $type
                    );
                }

                $page = new $type($options);
                if (!$page instanceof self) {
                    throw new Exception\InvalidArgumentException(
                        sprintf(
                            'Invalid argument: Detected type "%s", which ' .
                            'is not an instance of Mezzio\Navigation\Page',
                            $type
                        )
                    );
                }

                return $page;
            }
        }

        if (static::$factories) {
            foreach (static::$factories as $factoryCallBack) {
                $page = call_user_func($factoryCallBack, $options);

                if ($page) {
                    return $page;
                }
            }
        }

        $hasUri = isset($options['uri']);
        $hasMvc = isset($options['action']) || isset($options['controller'])
                || isset($options['route']);

        if ($hasMvc) {
            return new Mvc($options);
        }

        if ($hasUri) {
            return new Uri($options);
        }

        throw new Exception\InvalidArgumentException(
            'Invalid argument: Unable to determine class to instantiate'
        );
    }

    /**
     * Add static factory for self::factory function
     *
     * @param callable $callback Any callable variable
     *
     * @return void
     */
    final public static function addFactory(callable $callback): void
    {
        static::$factories[] = $callback;
    }

    /**
     * Page constructor
     *
     * @param array|Traversable|null $options [optional] page options. Default is
     *                                        null, which should set defaults.
     *
     * @throws Exception\InvalidArgumentException if invalid options are given
     */
    public function __construct($options = null)
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (is_array($options)) {
            $this->setOptions($options);
        }

        // do custom initialization
        $this->init();
    }

    /**
     * Initializes page (used by subclasses)
     *
     * @return void
     */
    protected function init(): void
    {
    }

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
    final public function setOptions(array $options): void
    {
        foreach ($options as $key => $value) {
            $this->set($key, $value);
        }
    }

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
    final public function setLabel(string $label): void
    {
        if (null !== $label && !is_string($label)) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $label must be a string or null'
            );
        }

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
     * @param string $fragment new fragment identifier
     *
     * @throws Exception\InvalidArgumentException if empty/no string is given
     *
     * @return void
     */
    final public function setFragment(string $fragment): void
    {
        if (null !== $fragment && !is_string($fragment)) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $fragment must be a string or null'
            );
        }

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
     *
     * @throws Exception\InvalidArgumentException if not given string or null
     *
     * @return void
     */
    final public function setId(?string $id = null): void
    {
        if (null !== $id && !is_string($id) && !is_numeric($id)) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $id must be a string, number or null'
            );
        }

        $this->id = null === $id ? $id : (string) $id;
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
     * @param string|null $class [optional] CSS class to set. Default
     *                           is null, which sets no CSS class.
     *
     * @throws Exception\InvalidArgumentException if not given string or null
     *
     * @return void
     */
    final public function setClass(?string $class = null): void
    {
        if (null !== $class && !is_string($class)) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $class must be a string or null'
            );
        }

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
     * Sets page title
     *
     * @param string|null $title [optional] page title. Default is
     *                           null, which sets no title.
     *
     * @throws Exception\InvalidArgumentException if not given string or null
     *
     * @return void
     */
    final public function setTitle(?string $title = null): void
    {
        if (null !== $title && !is_string($title)) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $title must be a non-empty string'
            );
        }

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
     *
     * @throws Exception\InvalidArgumentException if target is not string or null
     *
     * @return void
     */
    final public function setTarget(?string $target = null): void
    {
        if (null !== $target && !is_string($target)) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $target must be a string or null'
            );
        }

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
     * @param array|Traversable $relations [optional] an associative array of
     *                                     forward links to other pages
     *
     * @throws Exception\InvalidArgumentException if $relations is not an array
     *                                            or Traversable object
     *
     * @return void
     */
    final public function setRel($relations = null): void
    {
        $this->rel = [];

        if (null === $relations) {
            return;
        }

        if ($relations instanceof Traversable) {
            $relations = ArrayUtils::iteratorToArray($relations);
        }

        if (!is_array($relations)) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $relations must be an ' .
                'array or an instance of Traversable'
            );
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
     * @return array|null an array of relations. If $relation is not
     *                    specified, all relations will be returned in
     *                    an associative array.
     */
    final public function getRel(?string $relation = null): ?array
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
     * @param array|Traversable $relations [optional] an associative array of
     *                                     reverse links to other pages
     *
     * @throws Exception\InvalidArgumentException if $relations it not an array
     *                                            or Traversable object
     *
     * @return void
     */
    final public function setRev($relations = null): void
    {
        $this->rev = [];

        if (null === $relations) {
            return;
        }

        if ($relations instanceof Traversable) {
            $relations = ArrayUtils::iteratorToArray($relations);
        }

        if (!is_array($relations)) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $relations must be an ' .
                'array or an instance of Traversable'
            );
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
     * @return array|null an array of relations. If $relation is not
     *                    specified, all relations will be returned in
     *                    an associative array.
     */
    final public function getRev(?string $relation = null): ?array
    {
        if (null !== $relation) {
            return $this->rev[$relation] ??
                null;
        }

        return $this->rev;
    }

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
    final public function setOrder(?int $order = null): void
    {
        if (is_string($order)) {
            $temp = (int) $order;
            if (0 > $temp || 0 < $temp || '0' === $order) {
                $order = $temp;
            }
        }

        if (null !== $order && !is_int($order)) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $order must be an integer or null, ' .
                'or a string that casts to an integer'
            );
        }

        $this->order = $order;

        // notify parent, if any
        if (!isset($this->parent)) {
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
     *
     * @return void
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
     *
     * @return void
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
     *
     * @return void
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
     *
     * @return void
     */
    final public function setTextDomain(string $textDomain): void
    {
        $this->textDomain = $textDomain;
    }

    /**
     * Returns text domain for translation
     *
     * @return mixed|null text domain or null
     */
    final public function getTextDomain(): ?string
    {
        return $this->textDomain;
    }

    /**
     * Sets whether page should be considered active or not
     *
     * @param bool $active [optional] whether page should be
     *                     considered active or not. Default is true.
     *
     * @return void
     */
    final public function setActive(bool $active = true): void
    {
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
    public function isActive(bool $recursive = false): bool
    {
        if (!$this->active && $recursive) {
            foreach ($this->pages as $page) {
                if ($page->isActive(true)) {
                    return true;
                }
            }

            return false;
        }

        return $this->active;
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
     * @param bool $visible [optional] whether page should be
     *                      considered visible or not. Default is true.
     *
     * @return void
     */
    final public function setVisible(bool $visible = true): void
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
            && isset($this->parent)
            && $this->parent instanceof self
        ) {
            if (!$this->parent->isVisible(true)) {
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
     * Sets parent container
     *
     * @param AbstractContainer|null $parent [optional] new parent to set.
     *                                       Default is null which will set no parent.
     *
     * @throws Exception\InvalidArgumentException
     *
     * @return void
     */
    final public function setParent(?AbstractContainer $parent = null): void
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
     * @return AbstractContainer|null parent container or null
     */
    final public function getParent(): ?AbstractContainer
    {
        return $this->parent;
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
     *
     * @return void
     */
    final public function set(string $property, $value): void
    {
        if (!is_string($property) || empty($property)) {
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
     * @throws Exception\InvalidArgumentException if property name is invalid
     *
     * @return mixed the property's value or null
     */
    final public function get(string $property)
    {
        if (!is_string($property) || empty($property)) {
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
     *
     * @return void
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
     * @throws Exception\InvalidArgumentException if property name is invalid
     *
     * @return mixed property value or null
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
     *
     * @return void
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

    // Public methods:

    /**
     * Adds a forward relation to the page
     *
     * @param string $relation relation name (e.g. alternate, glossary,
     *                         canonical, etc)
     * @param mixed  $value    value to set for relation
     *
     * @return void
     */
    final public function addRel(string $relation, $value): void
    {
        if (!is_string($relation)) {
            return;
        }

        $this->rel[$relation] = $value;
    }

    /**
     * Adds a reverse relation to the page
     *
     * @param string $relation relation name (e.g. alternate, glossary,
     *                         canonical, etc)
     * @param mixed  $value    value to set for relation
     *
     * @return void
     */
    final public function addRev(string $relation, $value): void
    {
        if (!is_string($relation)) {
            return;
        }

        $this->rev[$relation] = $value;
    }

    /**
     * Removes a forward relation from the page
     *
     * @param string $relation name of relation to remove
     *
     * @return void
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
     *
     * @return void
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
     * @return array defined forward relations
     */
    final public function getDefinedRel(): array
    {
        return array_keys($this->rel);
    }

    /**
     * Returns an array containing the defined reverse relations
     *
     * @return array defined reverse relations
     */
    final public function getDefinedRev(): array
    {
        return array_keys($this->rev);
    }

    /**
     * Returns custom properties as an array
     *
     * @return array an array containing custom properties
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
     * @return array associative array containing all page properties
     */
    public function toArray(): array
    {
        return array_merge($this->getCustomProperties(), [
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
        ]);
    }

    // Internal methods:

    /**
     * Normalizes a property name
     *
     * @param string $property property name to normalize
     *
     * @return string normalized property name
     */
    protected static function normalizePropertyName(string $property): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
    }

    // Abstract methods:

    /**
     * Returns href for this page
     *
     * @return string the page's href
     */
    abstract public function getHref(): string;
}
