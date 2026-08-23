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

namespace Mimmi20\MezzioTest\Navigation\Service;

use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Mimmi20\Mezzio\Navigation\Config\NavigationConfigInterface;
use Mimmi20\Mezzio\Navigation\Exception\InvalidArgumentException;
use Mimmi20\Mezzio\Navigation\Navigation;
use Mimmi20\Mezzio\Navigation\Page\PageFactoryInterface;
use Mimmi20\Mezzio\Navigation\Page\PageInterface;
use Mimmi20\Mezzio\Navigation\Page\Route;
use Mimmi20\Mezzio\Navigation\Page\RouteInterface;
use Mimmi20\Mezzio\Navigation\Page\Uri;
use Mimmi20\Mezzio\Navigation\Page\UriInterface;
use Mimmi20\Mezzio\Navigation\Service\ConstructedNavigationFactory;
use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;

final class ConstructedNavigationFactoryTest extends TestCase
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ContainerExceptionInterface
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testCanNotInvokeWithoutConfig(): void
    {
        $pages = [
            'Test2' => [],
        ];

        $navigationConfig = $this->createMock(NavigationConfigInterface::class);
        $navigationConfig->expects(self::once())
            ->method('getPages')
            ->willReturn($pages);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::once())
            ->method('get')
            ->with(NavigationConfigInterface::class)
            ->willReturn($navigationConfig);

        $constructedNavigationFactory = new ConstructedNavigationFactory('test');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Failed to find a navigation container by the name "test"');
        $this->expectExceptionCode(0);

        assert($container instanceof ContainerInterface);
        $constructedNavigationFactory($container);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ContainerExceptionInterface
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testInvoke(): void
    {
        $page1Config = [
            'type' => Route::class,
        ];
        $page2Config = [
            'type' => Uri::class,
        ];
        $pageConfig  = [
            'test' => [
                $page1Config,
                $page2Config,
            ],
        ];

        $page1 = $this->createMock(PageInterface::class);
        $page1->expects(self::once())
            ->method('hashCode')
            ->willReturn('test1');

        $page2 = $this->createMock(PageInterface::class);
        $page2->expects(self::once())
            ->method('hashCode')
            ->willReturn('test2');

        $navigationConfig = $this->createMock(NavigationConfigInterface::class);
        $navigationConfig->expects(self::once())
            ->method('getPages')
            ->willReturn($pageConfig);
        $navigationConfig->expects(self::once())
            ->method('getRouteResult')
            ->willReturn(value: null);
        $navigationConfig->expects(self::once())
            ->method('getRouter')
            ->willReturn(value: null);
        $navigationConfig->expects(self::once())
            ->method('getRequest')
            ->willReturn(value: null);

        $pageFactory = $this->createMock(PageFactoryInterface::class);
        $matcher     = self::exactly(2);
        $pageFactory->expects($matcher)
            ->method('factory')
            ->willReturnCallback(
                static function (array $options) use ($matcher, $page1Config, $page2Config, $page1, $page2): PageInterface {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame($page1Config, $options),
                        default => self::assertSame($page2Config, $options),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $page1,
                        default => $page2,
                    };
                },
            );

        $container = $this->createMock(ContainerInterface::class);
        $matcher   = self::exactly(2);
        $container->expects($matcher)
            ->method('get')
            ->willReturnCallback(
                static function (string $id) use ($matcher, $navigationConfig, $pageFactory): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(NavigationConfigInterface::class, $id),
                        default => self::assertSame(PageFactoryInterface::class, $id),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $navigationConfig,
                        default => $pageFactory,
                    };
                },
            );

        $constructedNavigationFactory = new ConstructedNavigationFactory('test');

        assert($container instanceof ContainerInterface);
        $navigation = $constructedNavigationFactory($container);

        self::assertInstanceOf(Navigation::class, $navigation);

        $pages = $navigation->getPages();

        self::assertCount(2, $pages);
        self::assertContainsOnlyInstancesOf(PageInterface::class, $pages);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ContainerExceptionInterface
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testInvokeWithRouteResult(): void
    {
        $page1Config = [
            'type' => Route::class,
        ];
        $page2Config = [
            'type' => Uri::class,
        ];
        $pageConfig  = [
            'test' => [
                $page1Config,
                $page2Config,
            ],
        ];

        $routeResult     = self::createStub(RouteResult::class);
        $routerInterface = self::createStub(RouterInterface::class);
        $prequest        = self::createStub(ServerRequestInterface::class);

        $page1 = $this->createMock(RouteInterface::class);
        $page1->expects(self::once())
            ->method('hashCode')
            ->willReturn('test1');
        $page1->expects(self::once())
            ->method('setRouteMatch')
            ->with($routeResult);
        $page1->expects(self::once())
            ->method('setRouter')
            ->with($routerInterface);

        $page2 = $this->createMock(UriInterface::class);
        $page2->expects(self::once())
            ->method('hashCode')
            ->willReturn('test2');
        $page2->expects(self::once())
            ->method('setRequest')
            ->with($prequest);

        $navigationConfig = $this->createMock(NavigationConfigInterface::class);
        $navigationConfig->expects(self::once())
            ->method('getPages')
            ->willReturn($pageConfig);
        $navigationConfig->expects(self::once())
            ->method('getRouteResult')
            ->willReturn($routeResult);
        $navigationConfig->expects(self::once())
            ->method('getRouter')
            ->willReturn($routerInterface);
        $navigationConfig->expects(self::once())
            ->method('getRequest')
            ->willReturn($prequest);

        $pageFactory = $this->createMock(PageFactoryInterface::class);
        $matcher     = self::exactly(2);
        $pageFactory->expects($matcher)
            ->method('factory')
            ->willReturnCallback(
                static function (array $options) use ($matcher, $page1Config, $page2Config, $page1, $page2): PageInterface {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame($page1Config, $options),
                        default => self::assertSame($page2Config, $options),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $page1,
                        default => $page2,
                    };
                },
            );

        $container = $this->createMock(ContainerInterface::class);
        $matcher   = self::exactly(2);
        $container->expects($matcher)
            ->method('get')
            ->willReturnCallback(
                static function (string $id) use ($matcher, $navigationConfig, $pageFactory): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(NavigationConfigInterface::class, $id),
                        default => self::assertSame(PageFactoryInterface::class, $id),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $navigationConfig,
                        default => $pageFactory,
                    };
                },
            );

        $constructedNavigationFactory = new ConstructedNavigationFactory('test');

        assert($container instanceof ContainerInterface);
        $navigation = $constructedNavigationFactory($container);

        self::assertInstanceOf(Navigation::class, $navigation);

        $pages = $navigation->getPages();

        self::assertCount(2, $pages);
        self::assertContainsOnlyInstancesOf(PageInterface::class, $pages);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ContainerExceptionInterface
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testInvokeWithSubPages(): void
    {
        $page1Config = [
            'type' => Route::class,
        ];
        $page2Config = [
            'type' => Uri::class,
            'pages' => [$page1Config],
        ];
        $pageConfig  = [
            'test' => [$page2Config],
        ];

        $routeResult     = self::createStub(RouteResult::class);
        $routerInterface = self::createStub(RouterInterface::class);
        $prequest        = self::createStub(ServerRequestInterface::class);

        $page1 = $this->createMock(RouteInterface::class);
        $page1->expects(self::once())
            ->method('hashCode')
            ->willReturn('test1');
        $page1->expects(self::once())
            ->method('setRouteMatch')
            ->with($routeResult);
        $page1->expects(self::once())
            ->method('setRouter')
            ->with($routerInterface);

        $page2 = $this->createMock(UriInterface::class);
        $page2->expects(self::never())
            ->method('hashCode')
            ->willReturn('test2');
        $page2->expects(self::once())
            ->method('setRequest')
            ->with($prequest);

        $navigationConfig = $this->createMock(NavigationConfigInterface::class);
        $navigationConfig->expects(self::once())
            ->method('getPages')
            ->willReturn($pageConfig);
        $navigationConfig->expects(self::once())
            ->method('getRouteResult')
            ->willReturn($routeResult);
        $navigationConfig->expects(self::once())
            ->method('getRouter')
            ->willReturn($routerInterface);
        $navigationConfig->expects(self::once())
            ->method('getRequest')
            ->willReturn($prequest);

        $pageFactory = $this->createMock(PageFactoryInterface::class);
        unset($page2Config['pages']);
        $matcher = self::exactly(2);
        $pageFactory->expects($matcher)
            ->method('factory')
            ->willReturnCallback(
                static function (array $options) use ($matcher, $page1Config, $page2Config, $page1, $page2): PageInterface {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame($page1Config, $options),
                        default => self::assertSame($page2Config, $options),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $page2,
                        default => $page1,
                    };
                },
            );

        $container = $this->createMock(ContainerInterface::class);
        $matcher   = self::exactly(2);
        $container->expects($matcher)
            ->method('get')
            ->willReturnCallback(
                static function (string $id) use ($matcher, $navigationConfig, $pageFactory): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(NavigationConfigInterface::class, $id),
                        default => self::assertSame(PageFactoryInterface::class, $id),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $navigationConfig,
                        default => $pageFactory,
                    };
                },
            );

        $constructedNavigationFactory = new ConstructedNavigationFactory('test');

        assert($container instanceof ContainerInterface);
        $navigation = $constructedNavigationFactory($container);

        self::assertInstanceOf(Navigation::class, $navigation);

        $pages = $navigation->getPages();

        self::assertCount(1, $pages);
        self::assertContainsOnlyInstancesOf(PageInterface::class, $pages);
    }
}
