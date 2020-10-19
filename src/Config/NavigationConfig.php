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
namespace Mezzio\Navigation\Config;

use Mezzio\Authorization\AuthorizationInterface;
use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * A simple container class for {@link \Mezzio\Navigation\Page} pages
 */
final class NavigationConfig
{
    /** @var UrlHelper|null */
    private $urlHelper;

    /** @var RouteResult|null */
    private $routeResult;

    /** @var \Mezzio\Router\RouterInterface|null */
    private $router;

    /** @var ServerRequestInterface|null */
    private $request;

    /** @var AuthorizationInterface|null */
    private $authorization;

    /** @var array[] */
    private $pages;

    /**
     * @return UrlHelper|null
     */
    public function getUrlHelper(): ?UrlHelper
    {
        return $this->urlHelper;
    }

    /**
     * @param UrlHelper $urlHelper
     *
     * @return void
     */
    public function setUrlHelper(UrlHelper $urlHelper): void
    {
        $this->urlHelper = $urlHelper;
    }

    /**
     * @return RouteResult|null
     */
    public function getRouteResult(): ?RouteResult
    {
        return $this->routeResult;
    }

    /**
     * @param RouteResult $routeResult
     *
     * @return void
     */
    public function setRouteResult(RouteResult $routeResult): void
    {
        $this->routeResult = $routeResult;
    }

    /**
     * @return \Mezzio\Router\RouterInterface|null
     */
    public function getRouter(): ?RouterInterface
    {
        return $this->router;
    }

    /**
     * @param \Mezzio\Router\RouterInterface|null $router
     *
     * @return void
     */
    public function setRouter(?RouterInterface $router): void
    {
        $this->router = $router;
    }

    /**
     * @return ServerRequestInterface|null
     */
    public function getRequest(): ?ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return void
     */
    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    /**
     * @return AuthorizationInterface|null
     */
    public function getAuthorization(): ?AuthorizationInterface
    {
        return $this->authorization;
    }

    /**
     * @param AuthorizationInterface|null $authorization
     *
     * @return void
     */
    public function setAuthorization(?AuthorizationInterface $authorization = null): void
    {
        $this->authorization = $authorization;
    }

    /**
     * @return array[]
     */
    public function getPages(): array
    {
        return $this->pages;
    }

    /**
     * @param array[] $pages
     *
     * @return void
     */
    public function setPages(array $pages): void
    {
        $this->pages = $pages;
    }
}
