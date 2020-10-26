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

use Mezzio\Navigation\Exception;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;

/**
 * Represents a page that is defined using controller, action, route
 * name and route params to assemble the href
 *
 * The two constants defined were originally provided via the laminas-mvc class
 * ModuleRouteListener; to remove the requirement on that component, they are
 * reproduced here.
 */
final class Route implements PageInterface
{
    use PageTrait {
        isActive as isActiveParent;
        toArray as toParentArray;
    }

    /**
     * URL query part to use when assembling URL
     *
     * @var array|string|null
     */
    private $query;

    /**
     * Params to use when assembling URL
     *
     * @see getHref()
     *
     * @var array
     */
    private $params = [];

    /**
     * RouteInterface name to use when assembling URL
     *
     * @see getHref()
     *
     * @var string|null
     */
    private $route;

    /**
     * Cached href
     *
     * The use of this variable minimizes execution time when getHref() is
     * called more than once during the lifetime of a request. If a property
     * is updated, the cache is invalidated.
     *
     * @var string|null
     */
    private $hrefCache;

    /**
     * RouteInterface matches; used for routing parameters and testing validity
     *
     * @var \Mezzio\Router\RouteResult|null
     */
    private $routeMatch;

    /**
     * If true and set routeMatch than getHref will use routeMatch params
     * to assemble uri
     *
     * @var bool
     */
    private $useRouteMatch = false;

    /**
     * Router for assembling URLs
     *
     * @see getHref()
     *
     * @var \Mezzio\Router\RouterInterface|null
     */
    private $router;

    /**
     * Default router to be used if router is not given.
     *
     * @see getHref()
     *
     * @var \Mezzio\Router\RouterInterface|null
     */
    private static $defaultRouter = null;

    /**
     * Default route name
     *
     * @var string|null
     */
    private static $defaultRoute = null;

    // Accessors:

    /**
     * Returns whether page should be considered active or not
     *
     * This method will compare the page properties against the route matches
     * composed in the object.
     *
     * @param bool $recursive [optional] whether page should be considered
     *                        active if any child pages are active. Default is
     *                        false.
     *
     * @return bool whether page should be considered active or not
     */
    public function isActive(bool $recursive = false): bool
    {
        if (!$this->active) {
            $reqParams = [];

            if ($this->routeMatch instanceof RouteResult) {
                $reqParams  = $this->routeMatch->getMatchedParams();
                $pageParams = $this->params;

                if (null !== $this->getRoute()) {
                    if (
                        $this->routeMatch->getMatchedRouteName() === $this->getRoute()
                        && (count(array_intersect_assoc($reqParams, $pageParams)) === count($pageParams))
                    ) {
                        $this->active = true;

                        return $this->active;
                    }

                    return $this->isActiveParent($recursive);
                }
            }

            $pageParams = $this->params;

            if (count(array_intersect_assoc($reqParams, $pageParams)) === count($pageParams)) {
                $this->active = true;

                return true;
            }
        }

        return $this->isActiveParent($recursive);
    }

    /**
     * Returns href for this page
     *
     * This method uses {@link RouteStackInterface} to assemble
     * the href based on the page's properties.
     *
     * @see RouteStackInterface
     *
     * @throws Exception\DomainException                 if no router is set
     * @throws \Mezzio\Router\Exception\RuntimeException
     *
     * @return string page href
     */
    public function getHref(): string
    {
        if ($this->hrefCache) {
            return $this->hrefCache;
        }

        $router = $this->router;
        if (null === $router) {
            $router = self::$defaultRouter;
        }

        if (!$router instanceof RouterInterface) {
            throw new Exception\DomainException(
                __METHOD__
                . ' cannot execute as no Mezzio\Router\RouterInterface instance is composed'
            );
        }

        if ($this->useRouteMatch() && null !== $this->getRouteMatch()) {
            $rmParams = $this->getRouteMatch()->getMatchedParams();
            $params   = array_merge($rmParams, $this->getParams());
        } else {
            $params = $this->getParams();
        }

        $name = null;

        switch (true) {
            case null !== $this->getRoute() || null !== self::getDefaultRoute():
                $name = null !== $this->getRoute() ? $this->getRoute() : self::getDefaultRoute();
                break;
            case null !== $this->getRouteMatch() && !$this->getRouteMatch()->isFailure():
                $name = $this->getRouteMatch()->getMatchedRouteName();
                break;
            default:
                // do nothing
        }

        if (!is_string($name)) {
            throw new Exception\DomainException('No route name could be found');
        }

        $options = ['name' => $name];

        // Add the fragment identifier if it is set
        $fragment = $this->getFragment();
        if (null !== $fragment) {
            $options['fragment'] = $fragment;
        }

        $query = $this->getQuery();
        if (null !== $query) {
            $options['query'] = $query;
        }

        $url = $router->generateUri($name, $params, $options);

        return $this->hrefCache = $url;
    }

    /**
     * Sets URL query part to use when assembling URL
     *
     * @see getHref()
     *
     * @param array|string|null $query URL query part
     *
     * @return void
     */
    public function setQuery($query): void
    {
        $this->query     = $query;
        $this->hrefCache = null;
    }

    /**
     * Returns URL query part to use when assembling URL
     *
     * @see getHref()
     *
     * @return array|string|null URL query part (as an array or string) or null
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Sets params to use when assembling URL
     *
     * @see getHref()
     *
     * @param array|null $params [optional] page params. Default is null
     *                           which sets no params.
     *
     * @return void
     */
    public function setParams(?array $params = null): void
    {
        $this->params    = empty($params) ? [] : $params;
        $this->hrefCache = null;
    }

    /**
     * Returns params to use when assembling URL
     *
     * @see getHref()
     *
     * @return array page params
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Sets route name to use when assembling URL
     *
     * @see getHref()
     *
     * @param string $route route name to use when assembling URL
     *
     * @throws Exception\InvalidArgumentException if invalid $route is given
     *
     * @return void
     */
    public function setRoute(string $route): void
    {
        if (null !== $route && (!is_string($route) || 1 > mb_strlen($route))) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $route must be a non-empty string or null'
            );
        }

        $this->route     = $route;
        $this->hrefCache = null;
    }

    /**
     * Returns route name to use when assembling URL
     *
     * @see getHref()
     *
     * @return string|null route name
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * Get the route match.
     *
     * @return RouteResult|null
     */
    public function getRouteMatch(): ?RouteResult
    {
        return $this->routeMatch;
    }

    /**
     * Set route match object from which parameters will be retrieved
     *
     * @param RouteResult $matches
     *
     * @return void
     */
    public function setRouteMatch(RouteResult $matches): void
    {
        $this->routeMatch = $matches;
    }

    /**
     * Get the useRouteMatch flag
     *
     * @return bool
     */
    public function useRouteMatch(): bool
    {
        return $this->useRouteMatch;
    }

    /**
     * Set whether the page should use route match params for assembling link uri
     *
     * @see getHref()
     *
     * @param bool $useRouteMatch [optional]
     *
     * @return void
     */
    public function setUseRouteMatch(bool $useRouteMatch = true): void
    {
        $this->useRouteMatch = (bool) $useRouteMatch;
        $this->hrefCache     = null;
    }

    /**
     * Get the router.
     *
     * @return RouterInterface|null
     */
    public function getRouter(): ?RouterInterface
    {
        return $this->router;
    }

    /**
     * Sets router for assembling URLs
     *
     * @see getHref()
     *
     * @param RouterInterface|null $router Router
     *
     * @return void
     */
    public function setRouter(?RouterInterface $router): void
    {
        $this->router = $router;
    }

    /**
     * Sets the default router for assembling URLs.
     *
     * @see getHref()
     *
     * @param RouterInterface $router Router
     *
     * @return void
     */
    public static function setDefaultRouter(RouterInterface $router): void
    {
        self::$defaultRouter = $router;
    }

    /**
     * Gets the default router for assembling URLs.
     *
     * @return RouterInterface|null
     */
    public static function getDefaultRouter(): ?RouterInterface
    {
        return self::$defaultRouter;
    }

    /**
     * Set default route name
     *
     * @param string $route
     *
     * @return void
     */
    public static function setDefaultRoute(string $route): void
    {
        self::$defaultRoute = $route;
    }

    /**
     * Get default route name
     *
     * @return string|null
     */
    public static function getDefaultRoute(): ?string
    {
        return self::$defaultRoute;
    }

    // Public methods:

    /**
     * Returns an array representation of the page
     *
     * @return array associative array containing all page properties
     */
    public function toArray(): array
    {
        return array_merge(
            $this->toParentArray(),
            [
                'params' => $this->getParams(),
                'route' => $this->getRoute(),
                'router' => $this->getRouter(),
                'route_match' => $this->getRouteMatch(),
            ]
        );
    }
}
