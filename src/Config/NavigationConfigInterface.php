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

use Mezzio\GenericAuthorization\AuthorizationInterface;
use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * A simple container class for {@link \Mezzio\Navigation\Page} pages
 */
interface NavigationConfigInterface
{
    /**
     * @return UrlHelper|null
     */
    public function getUrlHelper(): ?UrlHelper;

    /**
     * @param UrlHelper $urlHelper
     *
     * @return void
     */
    public function setUrlHelper(UrlHelper $urlHelper): void;

    /**
     * @return RouteResult|null
     */
    public function getRouteResult(): ?RouteResult;

    /**
     * @param RouteResult $routeResult
     *
     * @return void
     */
    public function setRouteResult(RouteResult $routeResult): void;

    /**
     * @return \Mezzio\Router\RouterInterface|null
     */
    public function getRouter(): ?RouterInterface;

    /**
     * @param \Mezzio\Router\RouterInterface|null $router
     *
     * @return void
     */
    public function setRouter(?RouterInterface $router): void;

    /**
     * @return ServerRequestInterface|null
     */
    public function getRequest(): ?ServerRequestInterface;

    /**
     * @param ServerRequestInterface $request
     *
     * @return void
     */
    public function setRequest(ServerRequestInterface $request): void;

    /**
     * @return AuthorizationInterface|null
     */
    public function getAuthorization(): ?AuthorizationInterface;

    /**
     * @param AuthorizationInterface|null $authorization
     *
     * @return void
     */
    public function setAuthorization(?AuthorizationInterface $authorization = null): void;

    /**
     * @return array[]|null
     */
    public function getPages(): ?array;

    /**
     * @param array[] $pages
     *
     * @return void
     */
    public function setPages(array $pages): void;
}
