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

namespace Mimmi20\MezzioTest\Navigation\Config;

use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Mimmi20\Mezzio\GenericAuthorization\AuthorizationInterface;
use Mimmi20\Mezzio\Navigation\Config\NavigationConfig;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

use function assert;

final class NavigationConfigTest extends TestCase
{
    /** @throws Exception */
    public function testSetUrlHelper(): void
    {
        $navigationConfig = new NavigationConfig();

        self::assertNull($navigationConfig->getUrlHelper());

        $helper = self::createStub(UrlHelper::class);

        assert($helper instanceof UrlHelper);
        $navigationConfig->setUrlHelper($helper);

        self::assertSame($helper, $navigationConfig->getUrlHelper());
    }

    /** @throws Exception */
    public function testSetRouteResult(): void
    {
        $navigationConfig = new NavigationConfig();

        self::assertNull($navigationConfig->getRouteResult());

        $routeResult = self::createStub(RouteResult::class);

        assert($routeResult instanceof RouteResult);
        $navigationConfig->setRouteResult($routeResult);

        self::assertSame($routeResult, $navigationConfig->getRouteResult());
    }

    /** @throws Exception */
    public function testSetRouter(): void
    {
        $navigationConfig = new NavigationConfig();

        self::assertNull($navigationConfig->getRouter());

        $router = self::createStub(RouterInterface::class);

        assert($router instanceof RouterInterface);
        $navigationConfig->setRouter($router);

        self::assertSame($router, $navigationConfig->getRouter());
    }

    /** @throws Exception */
    public function testSetRequest(): void
    {
        $navigationConfig = new NavigationConfig();

        self::assertNull($navigationConfig->getRequest());

        $request = self::createStub(ServerRequestInterface::class);

        assert($request instanceof ServerRequestInterface);
        $navigationConfig->setRequest($request);

        self::assertSame($request, $navigationConfig->getRequest());
    }

    /** @throws Exception */
    public function testSetAuthorization(): void
    {
        $navigationConfig = new NavigationConfig();

        self::assertNull($navigationConfig->getAuthorization());

        $authorization = self::createStub(AuthorizationInterface::class);

        assert($authorization instanceof AuthorizationInterface);
        $navigationConfig->setAuthorization($authorization);

        self::assertSame($authorization, $navigationConfig->getAuthorization());
    }

    /** @throws Exception */
    public function testSetPages(): void
    {
        $navigationConfig = new NavigationConfig();

        self::assertNull($navigationConfig->getPages());

        $pages = [['test' => 'test']];

        $navigationConfig->setPages($pages);

        self::assertSame($pages, $navigationConfig->getPages());
    }
}
