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
namespace MezzioTest\Navigation\Service;

use Mezzio\Navigation\Config\NavigationConfigInterface;
use Mezzio\Navigation\Exception\InvalidArgumentException;
use Mezzio\Navigation\Navigation;
use Mezzio\Navigation\Page\PageFactoryInterface;
use Mezzio\Navigation\Page\PageInterface;
use Mezzio\Navigation\Page\Route;
use Mezzio\Navigation\Page\Uri;
use Mezzio\Navigation\Service\DefaultNavigationFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

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
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
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

        /* @var ContainerInterface $container */
        ($this->factory)($container);
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
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
}
