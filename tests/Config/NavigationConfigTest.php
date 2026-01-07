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
use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

use function assert;

final class NavigationConfigTest extends TestCase
{
    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testSetUrlHelper(): void
    {
        $config = new NavigationConfig();

        self::assertNull($config->getUrlHelper());

        $helper = $this->createMock(UrlHelper::class);

        assert($helper instanceof UrlHelper);
        $config->setUrlHelper($helper);

        self::assertSame($helper, $config->getUrlHelper());
    }

    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testSetRouteResult(): void
    {
        $config = new NavigationConfig();

        self::assertNull($config->getRouteResult());

        $routeResult = $this->createMock(RouteResult::class);

        assert($routeResult instanceof RouteResult);
        $config->setRouteResult($routeResult);

        self::assertSame($routeResult, $config->getRouteResult());
    }

    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testSetRouter(): void
    {
        $config = new NavigationConfig();

        self::assertNull($config->getRouter());

        $router = $this->createMock(RouterInterface::class);

        assert($router instanceof RouterInterface);
        $config->setRouter($router);

        self::assertSame($router, $config->getRouter());
    }

    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testSetRequest(): void
    {
        $config = new NavigationConfig();

        self::assertNull($config->getRequest());

        $request = $this->createMock(ServerRequestInterface::class);

        assert($request instanceof ServerRequestInterface);
        $config->setRequest($request);

        self::assertSame($request, $config->getRequest());
    }

    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testSetAuthorization(): void
    {
        $config = new NavigationConfig();

        self::assertNull($config->getAuthorization());

        $authorization = $this->createMock(AuthorizationInterface::class);

        assert($authorization instanceof AuthorizationInterface);
        $config->setAuthorization($authorization);

        self::assertSame($authorization, $config->getAuthorization());
    }

    /** @throws Exception */
    public function testSetPages(): void
    {
        $config = new NavigationConfig();

        self::assertNull($config->getPages());

        $pages = [['test' => 'test']];

        $config->setPages($pages);

        self::assertSame($pages, $config->getPages());
    }
}
