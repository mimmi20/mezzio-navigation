<?php
/**
 * This file is part of the mimmi20/mezzio-navigation package.
 *
 * Copyright (c) 2020-2024, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\MezzioTest\Navigation;

use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Mimmi20\Mezzio\GenericAuthorization\AuthorizationInterface;
use Mimmi20\Mezzio\Navigation\Config\NavigationConfigInterface;
use Mimmi20\Mezzio\Navigation\NavigationMiddleware;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function assert;

final class NavigationMiddlewareTest extends TestCase
{
    /** @throws Exception */
    public function testConstructor(): void
    {
        $navigationConfig = $this->createMock(NavigationConfigInterface::class);
        $urlHelper        = $this->createMock(UrlHelper::class);
        $authorization    = $this->createMock(AuthorizationInterface::class);
        $router           = $this->createMock(RouterInterface::class);

        assert($navigationConfig instanceof NavigationConfigInterface);
        assert($urlHelper instanceof UrlHelper);
        assert($authorization instanceof AuthorizationInterface);
        assert($router instanceof RouterInterface);
        $middleware = new NavigationMiddleware($navigationConfig, $urlHelper, $authorization, $router);
        self::assertInstanceOf(NavigationMiddleware::class, $middleware);
    }

    /** @throws Exception */
    public function testConstructorWithoutOptionalParameters(): void
    {
        $navigationConfig = $this->createMock(NavigationConfigInterface::class);
        $urlHelper        = $this->createMock(UrlHelper::class);

        assert($navigationConfig instanceof NavigationConfigInterface);
        assert($urlHelper instanceof UrlHelper);
        $middleware = new NavigationMiddleware($navigationConfig, $urlHelper);
        self::assertInstanceOf(NavigationMiddleware::class, $middleware);
    }

    /** @throws Exception */
    public function testProcessWithoutRouteResult(): void
    {
        $urlHelper     = $this->createMock(UrlHelper::class);
        $authorization = $this->createMock(AuthorizationInterface::class);
        $router        = $this->createMock(RouterInterface::class);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects(self::once())
            ->method('getAttribute')
            ->with(RouteResult::class)
            ->willReturn(null);

        $navigationConfig = $this->getMockBuilder(NavigationConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $navigationConfig->expects(self::once())
            ->method('setUrlHelper')
            ->with($urlHelper);
        $navigationConfig->expects(self::once())
            ->method('setRequest')
            ->with($request);
        $navigationConfig->expects(self::once())
            ->method('setAuthorization')
            ->with($authorization);
        $navigationConfig->expects(self::never())
            ->method('setRouteResult');
        $navigationConfig->expects(self::once())
            ->method('setRouter')
            ->with($router);

        assert($navigationConfig instanceof NavigationConfigInterface);
        assert($urlHelper instanceof UrlHelper);
        assert($authorization instanceof AuthorizationInterface);
        assert($router instanceof RouterInterface);
        $middleware = new NavigationMiddleware($navigationConfig, $urlHelper, $authorization, $router);
        self::assertInstanceOf(NavigationMiddleware::class, $middleware);

        $expectedResponse = $this->createMock(ResponseInterface::class);

        $handler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $handler->expects(self::once())
            ->method('handle')
            ->with($request)
            ->willReturn($expectedResponse);

        assert($request instanceof ServerRequestInterface);
        assert($handler instanceof RequestHandlerInterface);
        $response = $middleware->process($request, $handler);

        self::assertSame($expectedResponse, $response);
    }

    /** @throws Exception */
    public function testProcess(): void
    {
        $urlHelper     = $this->createMock(UrlHelper::class);
        $authorization = $this->createMock(AuthorizationInterface::class);
        $router        = $this->createMock(RouterInterface::class);
        $routeResult   = $this->createMock(RouteResult::class);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects(self::once())
            ->method('getAttribute')
            ->with(RouteResult::class)
            ->willReturn($routeResult);

        $navigationConfig = $this->getMockBuilder(NavigationConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $navigationConfig->expects(self::once())
            ->method('setUrlHelper')
            ->with($urlHelper);
        $navigationConfig->expects(self::once())
            ->method('setRequest')
            ->with($request);
        $navigationConfig->expects(self::once())
            ->method('setAuthorization')
            ->with($authorization);
        $navigationConfig->expects(self::once())
            ->method('setRouteResult')
            ->with($routeResult);
        $navigationConfig->expects(self::once())
            ->method('setRouter')
            ->with($router);

        assert($navigationConfig instanceof NavigationConfigInterface);
        assert($urlHelper instanceof UrlHelper);
        assert($authorization instanceof AuthorizationInterface);
        assert($router instanceof RouterInterface);
        $middleware = new NavigationMiddleware($navigationConfig, $urlHelper, $authorization, $router);
        self::assertInstanceOf(NavigationMiddleware::class, $middleware);

        $expectedResponse = $this->createMock(ResponseInterface::class);

        $handler = $this->getMockBuilder(RequestHandlerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $handler->expects(self::once())
            ->method('handle')
            ->with($request)
            ->willReturn($expectedResponse);

        assert($request instanceof ServerRequestInterface);
        assert($handler instanceof RequestHandlerInterface);
        $response = $middleware->process($request, $handler);

        self::assertSame($expectedResponse, $response);
    }
}
