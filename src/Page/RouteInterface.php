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
interface RouteInterface extends PageInterface
{
    /**
     * Sets URL query part to use when assembling URL
     *
     * @see getHref()
     *
     * @param array|string|null $query URL query part
     *
     * @return void
     */
    public function setQuery($query): void;

    /**
     * Returns URL query part to use when assembling URL
     *
     * @see getHref()
     *
     * @return array|string|null URL query part (as an array or string) or null
     */
    public function getQuery();

    /**
     * Sets params to use when assembling URL
     *
     * @see getHref()
     *
     * @param array $params [optional] page params
     *
     * @return void
     */
    public function setParams(array $params = []): void;

    /**
     * Returns params to use when assembling URL
     *
     * @see getHref()
     *
     * @return array page params
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
     *
     * @return void
     */
    public function setRoute(string $route): void;

    /**
     * Returns route name to use when assembling URL
     *
     * @see getHref()
     *
     * @return string|null route name
     */
    public function getRoute(): ?string;

    /**
     * Get the route match.
     *
     * @return RouteResult|null
     */
    public function getRouteMatch(): ?RouteResult;

    /**
     * Set route match object from which parameters will be retrieved
     *
     * @param RouteResult $matches
     *
     * @return void
     */
    public function setRouteMatch(RouteResult $matches): void;

    /**
     * Get the useRouteMatch flag
     *
     * @return bool
     */
    public function useRouteMatch(): bool;

    /**
     * Set whether the page should use route match params for assembling link uri
     *
     * @see getHref()
     *
     * @param bool $useRouteMatch [optional]
     *
     * @return void
     */
    public function setUseRouteMatch(bool $useRouteMatch = true): void;

    /**
     * Get the router.
     *
     * @return RouterInterface|null
     */
    public function getRouter(): ?RouterInterface;

    /**
     * Sets router for assembling URLs
     *
     * @see getHref()
     *
     * @param RouterInterface|null $router Router
     *
     * @return void
     */
    public function setRouter(?RouterInterface $router): void;
}
