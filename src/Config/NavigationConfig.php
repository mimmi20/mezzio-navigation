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

namespace Mimmi20\Mezzio\Navigation\Config;

use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Mimmi20\Mezzio\GenericAuthorization\AuthorizationInterface;
use Override;
use Psr\Http\Message\ServerRequestInterface;

/**
 * A simple container class for {@link \Mimmi20\Mezzio\Navigation\Page} pages
 */
final class NavigationConfig implements NavigationConfigInterface
{
    private UrlHelper | null $urlHelper                  = null;
    private RouteResult | null $routeResult              = null;
    private RouterInterface | null $router               = null;
    private ServerRequestInterface | null $request       = null;
    private AuthorizationInterface | null $authorization = null;

    /** @var array<int, array<string, string>>|null */
    private array | null $pages = null;

    /** @throws void */
    #[Override]
    public function getUrlHelper(): UrlHelper | null
    {
        return $this->urlHelper;
    }

    /** @throws void */
    #[Override]
    public function setUrlHelper(UrlHelper $urlHelper): void
    {
        $this->urlHelper = $urlHelper;
    }

    /** @throws void */
    #[Override]
    public function getRouteResult(): RouteResult | null
    {
        return $this->routeResult;
    }

    /** @throws void */
    #[Override]
    public function setRouteResult(RouteResult $routeResult): void
    {
        $this->routeResult = $routeResult;
    }

    /** @throws void */
    #[Override]
    public function getRouter(): RouterInterface | null
    {
        return $this->router;
    }

    /** @throws void */
    #[Override]
    public function setRouter(RouterInterface | null $router): void
    {
        $this->router = $router;
    }

    /** @throws void */
    #[Override]
    public function getRequest(): ServerRequestInterface | null
    {
        return $this->request;
    }

    /** @throws void */
    #[Override]
    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    /** @throws void */
    #[Override]
    public function getAuthorization(): AuthorizationInterface | null
    {
        return $this->authorization;
    }

    /** @throws void */
    #[Override]
    public function setAuthorization(AuthorizationInterface | null $authorization = null): void
    {
        $this->authorization = $authorization;
    }

    /**
     * @return array<int, array<string, string>>|null
     *
     * @throws void
     */
    #[Override]
    public function getPages(): array | null
    {
        return $this->pages;
    }

    /**
     * @param array<int, array<string, string>> $pages
     *
     * @throws void
     */
    #[Override]
    public function setPages(array $pages): void
    {
        $this->pages = $pages;
    }
}
