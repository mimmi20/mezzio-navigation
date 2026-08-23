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

namespace Mimmi20\MezzioTest\Navigation;

use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Mimmi20\Mezzio\GenericAuthorization\AuthorizationInterface;
use Mimmi20\Mezzio\Navigation\Config\NavigationConfigInterface;
use Mimmi20\Mezzio\Navigation\NavigationMiddleware;
use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function assert;

final class NavigationMiddlewareTest extends TestCase
{
    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testConstructor(): void
    {
        $navigationConfig = self::createStub(NavigationConfigInterface::class);
        $urlHelper        = self::createStub(UrlHelper::class);
        $authorization    = self::createStub(AuthorizationInterface::class);
        $router           = self::createStub(RouterInterface::class);

        assert($navigationConfig instanceof NavigationConfigInterface);
        assert($urlHelper instanceof UrlHelper);
        assert($authorization instanceof AuthorizationInterface);
        assert($router instanceof RouterInterface);
        $navigationMiddleware = new NavigationMiddleware(
            $navigationConfig,
            $urlHelper,
            $authorization,
            $router,
        );
        self::assertInstanceOf(NavigationMiddleware::class, $navigationMiddleware);
    }

    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testConstructorWithoutOptionalParameters(): void
    {
        $navigationConfig = self::createStub(NavigationConfigInterface::class);
        $urlHelper        = self::createStub(UrlHelper::class);

        assert($navigationConfig instanceof NavigationConfigInterface);
        assert($urlHelper instanceof UrlHelper);
        $navigationMiddleware = new NavigationMiddleware($navigationConfig, $urlHelper);
        self::assertInstanceOf(NavigationMiddleware::class, $navigationMiddleware);
    }

    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testProcessWithoutRouteResult(): void
    {
        $urlHelper     = self::createStub(UrlHelper::class);
        $authorization = self::createStub(AuthorizationInterface::class);
        $router        = self::createStub(RouterInterface::class);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects(self::once())
            ->method('getAttribute')
            ->with(RouteResult::class)
            ->willReturn(value: null);

        $navigationConfig = $this->createMock(NavigationConfigInterface::class);
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
        $navigationMiddleware = new NavigationMiddleware(
            $navigationConfig,
            $urlHelper,
            $authorization,
            $router,
        );
        self::assertInstanceOf(NavigationMiddleware::class, $navigationMiddleware);

        $expectedResponse = self::createStub(ResponseInterface::class);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects(self::once())
            ->method('handle')
            ->with($request)
            ->willReturn($expectedResponse);

        assert($request instanceof ServerRequestInterface);
        assert($handler instanceof RequestHandlerInterface);
        $response = $navigationMiddleware->process($request, $handler);

        self::assertSame($expectedResponse, $response);
    }

    /**
     * @throws Exception
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testProcess(): void
    {
        $urlHelper     = self::createStub(UrlHelper::class);
        $authorization = self::createStub(AuthorizationInterface::class);
        $router        = self::createStub(RouterInterface::class);
        $routeResult   = self::createStub(RouteResult::class);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects(self::once())
            ->method('getAttribute')
            ->with(RouteResult::class)
            ->willReturn($routeResult);

        $navigationConfig = $this->createMock(NavigationConfigInterface::class);
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
        $navigationMiddleware = new NavigationMiddleware(
            $navigationConfig,
            $urlHelper,
            $authorization,
            $router,
        );
        self::assertInstanceOf(NavigationMiddleware::class, $navigationMiddleware);

        $expectedResponse = self::createStub(ResponseInterface::class);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects(self::once())
            ->method('handle')
            ->with($request)
            ->willReturn($expectedResponse);

        assert($request instanceof ServerRequestInterface);
        assert($handler instanceof RequestHandlerInterface);
        $response = $navigationMiddleware->process($request, $handler);

        self::assertSame($expectedResponse, $response);
    }
}
