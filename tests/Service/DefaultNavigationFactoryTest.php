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
namespace MezzioTest\Navigation\Service;

use Mezzio\Navigation\Config\NavigationConfigInterface;
use Mezzio\Navigation\Exception\InvalidArgumentException;
use Mezzio\Navigation\Navigation;
use Mezzio\Navigation\Page\PageFactoryInterface;
use Mezzio\Navigation\Page\PageInterface;
use Mezzio\Navigation\Page\Route;
use Mezzio\Navigation\Page\RouteInterface;
use Mezzio\Navigation\Page\Uri;
use Mezzio\Navigation\Page\UriInterface;
use Mezzio\Navigation\Service\DefaultNavigationFactory;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

final class DefaultNavigationFactoryTest extends TestCase
{
    /** @var DefaultNavigationFactory */
    private $factory;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->factory = new DefaultNavigationFactory();
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     *
     * @return void
     */
    public function testCanNotInvokeWithoutConfig(): void
    {
        $pages = [
            'Test2' => [],
        ];

        $navigationConfig = $this->getMockBuilder(NavigationConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $navigationConfig->expects(self::once())
            ->method('getPages')
            ->willReturn($pages);

        $pageFactory = $this->getMockBuilder(PageFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive([NavigationConfigInterface::class], [PageFactoryInterface::class])
            ->willReturnOnConsecutiveCalls($navigationConfig, $pageFactory);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Failed to find a navigation container by the name "default"');
        $this->expectExceptionCode(0);

        /* @var ContainerInterface $container */
        ($this->factory)($container);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testInvoke(): void
    {
        $page1Config = [
            'type' => Route::class,
        ];
        $page2Config = [
            'type' => Uri::class,
        ];
        $pageConfig = [
            'default' => [
                $page1Config,
                $page2Config,
            ],
        ];

        $page1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $page1->expects(self::once())
            ->method('hashCode')
            ->willReturn('test1');

        $page2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $page2->expects(self::once())
            ->method('hashCode')
            ->willReturn('test2');

        $navigationConfig = $this->getMockBuilder(NavigationConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $navigationConfig->expects(self::once())
            ->method('getPages')
            ->willReturn($pageConfig);
        $navigationConfig->expects(self::once())
            ->method('getRouteResult')
            ->willReturn(null);
        $navigationConfig->expects(self::once())
            ->method('getRouter')
            ->willReturn(null);
        $navigationConfig->expects(self::once())
            ->method('getRequest')
            ->willReturn(null);

        $pageFactory = $this->getMockBuilder(PageFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $pageFactory->expects(self::exactly(2))
            ->method('factory')
            ->withConsecutive([$page1Config], [$page2Config])
            ->willReturnOnConsecutiveCalls($page1, $page2);

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive([NavigationConfigInterface::class], [PageFactoryInterface::class])
            ->willReturnOnConsecutiveCalls($navigationConfig, $pageFactory);

        /** @var ContainerInterface $container */
        $navigation = ($this->factory)($container);

        self::assertInstanceOf(Navigation::class, $navigation);

        $pages = $navigation->getPages();

        self::assertCount(2, $pages);
        self::assertContainsOnly(PageInterface::class, $pages);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testInvokeWithRouteResult(): void
    {
        $page1Config = [
            'type' => Route::class,
        ];
        $page2Config = [
            'type' => Uri::class,
        ];
        $pageConfig = [
            'default' => [
                $page1Config,
                $page2Config,
            ],
        ];

        $routeResult     = $this->createMock(RouteResult::class);
        $routerInterface = $this->createMock(RouterInterface::class);
        $prequest        = $this->createMock(ServerRequestInterface::class);

        $page1 = $this->getMockBuilder(RouteInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $page1->expects(self::once())
            ->method('hashCode')
            ->willReturn('test1');
        $page1->expects(self::once())
            ->method('setRouteMatch')
            ->with($routeResult);
        $page1->expects(self::once())
            ->method('setRouter')
            ->with($routerInterface);

        $page2 = $this->getMockBuilder(UriInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $page2->expects(self::once())
            ->method('hashCode')
            ->willReturn('test2');
        $page2->expects(self::once())
            ->method('setRequest')
            ->with($prequest);

        $navigationConfig = $this->getMockBuilder(NavigationConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
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

        $pageFactory = $this->getMockBuilder(PageFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $pageFactory->expects(self::exactly(2))
            ->method('factory')
            ->withConsecutive([$page1Config], [$page2Config])
            ->willReturnOnConsecutiveCalls($page1, $page2);

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive([NavigationConfigInterface::class], [PageFactoryInterface::class])
            ->willReturnOnConsecutiveCalls($navigationConfig, $pageFactory);

        /** @var ContainerInterface $container */
        $navigation = ($this->factory)($container);

        self::assertInstanceOf(Navigation::class, $navigation);

        $pages = $navigation->getPages();

        self::assertCount(2, $pages);
        self::assertContainsOnly(PageInterface::class, $pages);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
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
        $pageConfig = [
            'default' => [$page2Config],
        ];

        $routeResult     = $this->createMock(RouteResult::class);
        $routerInterface = $this->createMock(RouterInterface::class);
        $prequest        = $this->createMock(ServerRequestInterface::class);

        $page1 = $this->getMockBuilder(RouteInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $page1->expects(self::once())
            ->method('hashCode')
            ->willReturn('test1');
        $page1->expects(self::once())
            ->method('setRouteMatch')
            ->with($routeResult);
        $page1->expects(self::once())
            ->method('setRouter')
            ->with($routerInterface);

        $page2 = $this->getMockBuilder(UriInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $page2->expects(self::never())
            ->method('hashCode')
            ->willReturn('test2');
        $page2->expects(self::once())
            ->method('setRequest')
            ->with($prequest);

        $navigationConfig = $this->getMockBuilder(NavigationConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
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

        $pageFactory = $this->getMockBuilder(PageFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        unset($page2Config['pages']);
        $pageFactory->expects(self::exactly(2))
            ->method('factory')
            ->withConsecutive([$page1Config], [$page2Config])
            ->willReturnOnConsecutiveCalls($page2, $page1);

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive([NavigationConfigInterface::class], [PageFactoryInterface::class])
            ->willReturnOnConsecutiveCalls($navigationConfig, $pageFactory);

        /** @var ContainerInterface $container */
        $navigation = ($this->factory)($container);

        self::assertInstanceOf(Navigation::class, $navigation);

        $pages = $navigation->getPages();

        self::assertCount(1, $pages);
        self::assertContainsOnly(PageInterface::class, $pages);
    }
}
