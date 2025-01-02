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

namespace Mimmi20\Mezzio\Navigation\Config;

use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Mimmi20\Mezzio\GenericAuthorization\AuthorizationInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * A simple container class for {@link \Mimmi20\Mezzio\Navigation\Page} pages
 */
interface NavigationConfigInterface
{
    /** @throws void */
    public function getUrlHelper(): UrlHelper | null;

    /** @throws void */
    public function setUrlHelper(UrlHelper $urlHelper): void;

    /** @throws void */
    public function getRouteResult(): RouteResult | null;

    /** @throws void */
    public function setRouteResult(RouteResult $routeResult): void;

    /** @throws void */
    public function getRouter(): RouterInterface | null;

    /** @throws void */
    public function setRouter(RouterInterface | null $router): void;

    /** @throws void */
    public function getRequest(): ServerRequestInterface | null;

    /** @throws void */
    public function setRequest(ServerRequestInterface $request): void;

    /** @throws void */
    public function getAuthorization(): AuthorizationInterface | null;

    /** @throws void */
    public function setAuthorization(AuthorizationInterface | null $authorization = null): void;

    /**
     * @return array<int, array<string, string>>|null
     *
     * @throws void
     */
    public function getPages(): array | null;

    /**
     * @param array<int, array<string, string>> $pages
     *
     * @throws void
     */
    public function setPages(array $pages): void;
}
