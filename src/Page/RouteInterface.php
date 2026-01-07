<?php

/**
 * This file is part of the mimmi20/mezzio-navigation package.
 *
 * Copyright (c) 2020-2026, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Mezzio\Navigation\Page;

use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Mimmi20\Mezzio\Navigation\Exception;

/**
 * Represents a page that is defined using controller, action, route
 * name and route params to assemble the href
 *
 * The two constants defined were originally provided via the laminas-mvc class
 * ModuleRouteListener; to remove the requirement on that component, they are
 * reproduced here.
 */
interface RouteInterface extends PageInterface
{
    /**
     * Sets URL query part to use when assembling URL
     *
     * @see getHref()
     *
     * @param array<string, string>|string|null $query URL query part
     *
     * @throws void
     */
    public function setQuery(array | string | null $query): void;

    /**
     * Returns URL query part to use when assembling URL
     *
     * @see getHref()
     *
     * @return array<string, string>|string|null URL query part (as an array or string) or null
     *
     * @throws void
     */
    public function getQuery(): array | string | null;

    /**
     * Sets params to use when assembling URL
     *
     * @see getHref()
     *
     * @param array<int|string, string> $params [optional] page params
     *
     * @throws void
     */
    public function setParams(array $params = []): void;

    /**
     * Returns params to use when assembling URL
     *
     * @see getHref()
     *
     * @return array<int|string, string> page params
     *
     * @throws void
     */
    public function getParams(): array;

    /**
     * Sets route name to use when assembling URL
     *
     * @see getHref()
     *
     * @param string $route route name to use when assembling URL
     *
     * @throws Exception\InvalidArgumentException if invalid $route is given
     */
    public function setRoute(string $route): void;

    /**
     * Returns route name to use when assembling URL
     *
     * @see getHref()
     *
     * @return string|null route name
     *
     * @throws void
     */
    public function getRoute(): string | null;

    /**
     * Get the route match.
     *
     * @throws void
     */
    public function getRouteMatch(): RouteResult | null;

    /**
     * Set route match object from which parameters will be retrieved
     *
     * @throws void
     */
    public function setRouteMatch(RouteResult $matches): void;

    /**
     * Get the useRouteMatch flag
     *
     * @throws void
     */
    public function useRouteMatch(): bool;

    /**
     * Set whether the page should use route match params for assembling link uri
     *
     * @see getHref()
     *
     * @param bool $useRouteMatch [optional]
     *
     * @throws void
     */
    public function setUseRouteMatch(bool $useRouteMatch = true): void;

    /**
     * Get the router.
     *
     * @throws void
     */
    public function getRouter(): RouterInterface | null;

    /**
     * Sets router for assembling URLs
     *
     * @see getHref()
     *
     * @param RouterInterface|null $router Router
     *
     * @throws void
     */
    public function setRouter(RouterInterface | null $router): void;
}
