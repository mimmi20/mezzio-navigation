<?php
/**
 * This file is part of the mimmi20/mezzio-navigation package.
 *
 * Copyright (c) 2020-2023, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Mezzio\Navigation\Page;

use Mezzio\Router\Exception\RuntimeException;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Mimmi20\Mezzio\Navigation\Exception;

use function array_intersect_assoc;
use function array_merge;
use function count;
use function is_string;

/**
 * Represents a page that is defined using controller, action, route
 * name and route params to assemble the href
 *
 * The two constants defined were originally provided via the laminas-mvc class
 * ModuleRouteListener; to remove the requirement on that component, they are
 * reproduced here.
 */
final class Route implements RouteInterface
{
    use PageTrait {
        isActive as isActiveParent;
        toArray as toParentArray;
    }

    /**
     * URL query part to use when assembling URL
     *
     * @var array<string, string>|string|null
     */
    private array | string | null $query = null;

    /**
     * Params to use when assembling URL
     *
     * @see getHref()
     *
     * @var array<int|string, string>
     */
    private array $params = [];

    /**
     * RouteInterface name to use when assembling URL
     *
     * @see getHref()
     */
    private string | null $route = null;

    /**
     * Cached href
     *
     * The use of this variable minimizes execution time when getHref() is
     * called more than once during the lifetime of a request. If a property
     * is updated, the cache is invalidated.
     */
    private string | null $hrefCache = null;

    /**
     * RouteInterface matches; used for routing parameters and testing validity
     */
    private RouteResult | null $routeMatch = null;

    /**
     * If true and set routeMatch than getHref will use routeMatch params
     * to assemble uri
     */
    private bool $useRouteMatch = false;

    /**
     * Router for assembling URLs
     *
     * @see getHref()
     */
    private RouterInterface | null $router = null;

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
     *
     * @throws void
     */
    public function isActive(bool $recursive = false): bool
    {
        if ($this->active === null) {
            $reqParams  = [];
            $pageParams = $this->getParams();

            if ($this->getRouteMatch() !== null) {
                $reqParams = $this->getRouteMatch()->getMatchedParams();

                if ($this->getRoute() !== null) {
                    if (
                        $this->getRouteMatch()->getMatchedRouteName() === $this->getRoute()
                        && (count(array_intersect_assoc($reqParams, $pageParams)) === count(
                            $pageParams,
                        ))
                    ) {
                        $this->active = true;

                        return true;
                    }

                    return $this->isActiveParent($recursive);
                }
            }

            if (
                $pageParams !== []
                && count(array_intersect_assoc($reqParams, $pageParams)) === count($pageParams)
            ) {
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
     * @return string page href
     *
     * @throws Exception\DomainException if no router is set
     * @throws RuntimeException
     */
    public function getHref(): string
    {
        if ($this->hrefCache) {
            return $this->hrefCache;
        }

        if (!$this->router instanceof RouterInterface) {
            throw new Exception\DomainException(
                __METHOD__
                . ' cannot execute as no Mezzio\Router\RouterInterface instance is composed',
            );
        }

        $name = null;

        if ($this->getRoute() !== null) {
            $name = $this->getRoute();
        } elseif ($this->getRouteMatch() !== null && !$this->getRouteMatch()->isFailure()) {
            $name = $this->getRouteMatch()->getMatchedRouteName();
        }

        if (!is_string($name)) {
            throw new Exception\DomainException('No route name could be found');
        }

        $options = ['name' => $name];

        // Add the fragment identifier if it is set
        $fragment = $this->getFragment();

        if ($fragment !== null) {
            $options['fragment'] = $fragment;
        }

        $query = $this->getQuery();

        if ($query !== null) {
            $options['query'] = $query;
        }

        $params = $this->getParams();

        if ($this->useRouteMatch() && $this->getRouteMatch() !== null) {
            $params = array_merge(
                $this->getRouteMatch()->getMatchedParams(),
                $params,
            );
        }

        $url = $this->router->generateUri($name, $params, $options);

        return $this->hrefCache = $url;
    }

    /**
     * Sets URL query part to use when assembling URL
     *
     * @see getHref()
     *
     * @param array<string, string>|string|null $query URL query part
     *
     * @throws void
     */
    public function setQuery(array | string | null $query): void
    {
        $this->query     = $query;
        $this->hrefCache = null;
    }

    /**
     * Returns URL query part to use when assembling URL
     *
     * @see getHref()
     *
     * @return array<string, string>|string|null URL query part (as an array or string) or null
     *
     * @throws void
     */
    public function getQuery(): array | string | null
    {
        return $this->query;
    }

    /**
     * Sets params to use when assembling URL
     *
     * @see getHref()
     *
     * @param array<int|string, string> $params [optional] page params
     *
     * @throws void
     */
    public function setParams(array $params = []): void
    {
        $this->params    = $params;
        $this->hrefCache = null;
    }

    /**
     * Returns params to use when assembling URL
     *
     * @see getHref()
     *
     * @return array<int|string, string> page params
     *
     * @throws void
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
     */
    public function setRoute(string $route): void
    {
        if ($route === '') {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $route must be a non-empty string',
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
     *
     * @throws void
     */
    public function getRoute(): string | null
    {
        return $this->route;
    }

    /**
     * Get the route match.
     *
     * @throws void
     */
    public function getRouteMatch(): RouteResult | null
    {
        return $this->routeMatch;
    }

    /**
     * Set route match object from which parameters will be retrieved
     *
     * @throws void
     */
    public function setRouteMatch(RouteResult $matches): void
    {
        $this->routeMatch = $matches;
    }

    /**
     * Get the useRouteMatch flag
     *
     * @throws void
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
     * @throws void
     */
    public function setUseRouteMatch(bool $useRouteMatch = true): void
    {
        $this->useRouteMatch = $useRouteMatch;
        $this->hrefCache     = null;
    }

    /**
     * Get the router.
     *
     * @throws void
     */
    public function getRouter(): RouterInterface | null
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
     * @throws void
     */
    public function setRouter(RouterInterface | null $router): void
    {
        $this->router = $router;
    }

    // Public methods:

    /**
     * Returns an array representation of the page
     *
     * @return array<mixed> associative array containing all page properties
     *
     * @throws void
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
            ],
        );
    }
}
