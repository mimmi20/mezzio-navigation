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

use Interop\Container\ContainerInterface;
use Mezzio\Navigation\Config\NavigationConfigInterface;
use Mezzio\Navigation\Exception\InvalidArgumentException;
use Mezzio\Navigation\Navigation;
use Mezzio\Navigation\Page\PageFactoryInterface;
use Mezzio\Navigation\Service\NavigationAbstractServiceFactory;
use PHPUnit\Framework\TestCase;

final class NavigationAbstractServiceFactoryTest extends TestCase
{
    /** @var NavigationAbstractServiceFactory */
    private $factory;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->factory = new NavigationAbstractServiceFactory();
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @return void
     */
    public function testCanNotCreateWithoutNamespace(): void
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::never())
            ->method('get');

        /* @var ContainerInterface $container */
        self::assertFalse($this->factory->canCreate($container, 'test'));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @return void
     */
    public function testCanNotCreateWithoutConfig(): void
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

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with(NavigationConfigInterface::class)
            ->willReturn($navigationConfig);

        /* @var ContainerInterface $container */
        self::assertFalse($this->factory->canCreate($container, 'Mezzio\\Navigation\\Test'));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @return void
     */
    public function testCanCreate(): void
    {
        $pages = [
            'Test' => [],
        ];

        $navigationConfig = $this->getMockBuilder(NavigationConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $navigationConfig->expects(self::once())
            ->method('getPages')
            ->willReturn($pages);

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with(NavigationConfigInterface::class)
            ->willReturn($navigationConfig);

        /* @var ContainerInterface $container */
        self::assertTrue($this->factory->canCreate($container, 'Mezzio\\Navigation\\Test'));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @return void
     */
    public function testCanCreateLowercaed(): void
    {
        $pages = [
            'test' => [],
        ];

        $navigationConfig = $this->getMockBuilder(NavigationConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $navigationConfig->expects(self::once())
            ->method('getPages')
            ->willReturn($pages);

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with(NavigationConfigInterface::class)
            ->willReturn($navigationConfig);

        /* @var ContainerInterface $container */
        self::assertTrue($this->factory->canCreate($container, 'Mezzio\\Navigation\\Test'));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     *
     * @return void
     */
    public function testInvoke(): void
    {
        $pages = [
            'Test' => [],
        ];

        $navigationConfig = $this->getMockBuilder(NavigationConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $navigationConfig->expects(self::exactly(2))
            ->method('getPages')
            ->willReturn($pages);

        $pageFactory = $this->createMock(PageFactoryInterface::class);

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::exactly(3))
            ->method('get')
            ->withConsecutive([NavigationConfigInterface::class], [NavigationConfigInterface::class], [])
            ->willReturnOnConsecutiveCalls($navigationConfig, $navigationConfig, $pageFactory);

        /** @var ContainerInterface $container */
        $navigation = ($this->factory)($container, 'Mezzio\\Navigation\\Test');

        self::assertInstanceOf(Navigation::class, $navigation);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     *
     * @return void
     */
    public function testInvokeLowercaed(): void
    {
        $pages = [
            'test' => [],
        ];

        $navigationConfig = $this->getMockBuilder(NavigationConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $navigationConfig->expects(self::exactly(2))
            ->method('getPages')
            ->willReturn($pages);

        $pageFactory = $this->createMock(PageFactoryInterface::class);

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::exactly(3))
            ->method('get')
            ->withConsecutive([NavigationConfigInterface::class], [NavigationConfigInterface::class], [])
            ->willReturnOnConsecutiveCalls($navigationConfig, $navigationConfig, $pageFactory);

        /** @var ContainerInterface $container */
        $navigation = ($this->factory)($container, 'Mezzio\\Navigation\\Test');

        self::assertInstanceOf(Navigation::class, $navigation);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     *
     * @return void
     */
    public function testCanNotInvoleWithoutConfig(): void
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

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with(NavigationConfigInterface::class)
            ->willReturn($navigationConfig);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Failed to find a navigation container by the name "Mezzio\Navigation\Test"');
        $this->expectExceptionCode(0);

        /* @var ContainerInterface $container */
        ($this->factory)($container, 'Mezzio\\Navigation\\Test');
    }
}
