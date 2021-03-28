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
interface NavigationConfigInterface
{
    public function getUrlHelper(): ?UrlHelper;

    public function setUrlHelper(UrlHelper $urlHelper): void;

    public function getRouteResult(): ?RouteResult;

    public function setRouteResult(RouteResult $routeResult): void;

    public function getRouter(): ?RouterInterface;

    public function setRouter(?RouterInterface $router): void;

    public function getRequest(): ?ServerRequestInterface;

    public function setRequest(ServerRequestInterface $request): void;

    public function getAuthorization(): ?AuthorizationInterface;

    public function setAuthorization(?AuthorizationInterface $authorization = null): void;

    /**
     * @return array<int|string, array<mixed>>|null
     */
    public function getPages(): ?array;

    /**
     * @param array<int|string, array<mixed>> $pages
     */
    public function setPages(array $pages): void;
}
