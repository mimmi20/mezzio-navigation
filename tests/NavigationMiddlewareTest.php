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
namespace MezzioTest\Navigation;

use Mezzio\GenericAuthorization\AuthorizationInterface;
use Mezzio\Helper\UrlHelper;
use Mezzio\Navigation\Config\NavigationConfigInterface;
use Mezzio\Navigation\NavigationMiddleware;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class NavigationMiddlewareTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $navigationConfig = $this->createMock(NavigationConfigInterface::class);
        $urlHelper        = $this->createMock(UrlHelper::class);
        $authorization    = $this->createMock(AuthorizationInterface::class);
        $router           = $this->createMock(RouterInterface::class);

        /** @var NavigationConfigInterface $navigationConfig */
        /** @var UrlHelper $urlHelper */
        /** @var AuthorizationInterface $authorization */
        /** @var RouterInterface $router */
        $middleware = new NavigationMiddleware($navigationConfig, $urlHelper, $authorization, $router);
        self::assertInstanceOf(NavigationMiddleware::class, $middleware);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testConstructorWithoutOptionalParameters(): void
    {
        $navigationConfig = $this->createMock(NavigationConfigInterface::class);
        $urlHelper        = $this->createMock(UrlHelper::class);

        /** @var NavigationConfigInterface $navigationConfig */
        /** @var UrlHelper $urlHelper */
        $middleware = new NavigationMiddleware($navigationConfig, $urlHelper);
        self::assertInstanceOf(NavigationMiddleware::class, $middleware);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
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

        /** @var NavigationConfigInterface $navigationConfig */
        /** @var UrlHelper $urlHelper */
        /** @var AuthorizationInterface $authorization */
        /** @var RouterInterface $router */
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

        /** @var ServerRequestInterface $request */
        /** @var RequestHandlerInterface $handler */
        $response = $middleware->process(
            $request,
            $handler
        );

        self::assertSame($expectedResponse, $response);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
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

        /** @var NavigationConfigInterface $navigationConfig */
        /** @var UrlHelper $urlHelper */
        /** @var AuthorizationInterface $authorization */
        /** @var RouterInterface $router */
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

        /** @var ServerRequestInterface $request */
        /** @var RequestHandlerInterface $handler */
        $response = $middleware->process(
            $request,
            $handler
        );

        self::assertSame($expectedResponse, $response);
    }
}
