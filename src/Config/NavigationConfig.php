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

namespace Mezzio\Navigation\Config;

use Mezzio\GenericAuthorization\AuthorizationInterface;
use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * A simple container class for {@link \Mezzio\Navigation\Page} pages
 */
final class NavigationConfig implements NavigationConfigInterface
{
    private ?UrlHelper $urlHelper = null;

    private ?RouteResult $routeResult = null;

    private ?RouterInterface $router = null;

    private ?ServerRequestInterface $request = null;

    private ?AuthorizationInterface $authorization = null;

    /** @var array<int|string, array<mixed>>|null */
    private ?array $pages = null;

    public function getUrlHelper(): ?UrlHelper
    {
        return $this->urlHelper;
    }

    public function setUrlHelper(UrlHelper $urlHelper): void
    {
        $this->urlHelper = $urlHelper;
    }

    public function getRouteResult(): ?RouteResult
    {
        return $this->routeResult;
    }

    public function setRouteResult(RouteResult $routeResult): void
    {
        $this->routeResult = $routeResult;
    }

    public function getRouter(): ?RouterInterface
    {
        return $this->router;
    }

    public function setRouter(?RouterInterface $router): void
    {
        $this->router = $router;
    }

    public function getRequest(): ?ServerRequestInterface
    {
        return $this->request;
    }

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function getAuthorization(): ?AuthorizationInterface
    {
        return $this->authorization;
    }

    public function setAuthorization(?AuthorizationInterface $authorization = null): void
    {
        $this->authorization = $authorization;
    }

    /**
     * @return array<int|string, array<mixed>>|null
     */
    public function getPages(): ?array
    {
        return $this->pages;
    }

    /**
     * @param array<int|string, array<mixed>> $pages
     */
    public function setPages(array $pages): void
    {
        $this->pages = $pages;
    }
}
