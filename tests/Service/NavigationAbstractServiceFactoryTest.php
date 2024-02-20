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

namespace Mimmi20\MezzioTest\Navigation\Service;

use Mimmi20\Mezzio\Navigation\Config\NavigationConfigInterface;
use Mimmi20\Mezzio\Navigation\Exception\InvalidArgumentException;
use Mimmi20\Mezzio\Navigation\Navigation;
use Mimmi20\Mezzio\Navigation\Page\PageFactoryInterface;
use Mimmi20\Mezzio\Navigation\Service\NavigationAbstractServiceFactory;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

use function assert;

final class NavigationAbstractServiceFactoryTest extends TestCase
{
    private NavigationAbstractServiceFactory $factory;

    /** @throws void */
    protected function setUp(): void
    {
        $this->factory = new NavigationAbstractServiceFactory();
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function testCanNotCreateWithoutNamespace(): void
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::never())
            ->method('get');

        assert($container instanceof ContainerInterface);
        self::assertFalse($this->factory->canCreate($container, 'test'));
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
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

        assert($container instanceof ContainerInterface);
        self::assertFalse($this->factory->canCreate($container, 'Mimmi20\Mezzio\Navigation\Test'));
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
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

        assert($container instanceof ContainerInterface);
        self::assertTrue($this->factory->canCreate($container, 'Mimmi20\Mezzio\Navigation\Test'));
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function testCanCreateLowercased(): void
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

        assert($container instanceof ContainerInterface);
        self::assertTrue($this->factory->canCreate($container, 'Mimmi20\Mezzio\Navigation\Test'));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ContainerExceptionInterface
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
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
        $matcher   = self::exactly(3);
        $container->expects($matcher)
            ->method('get')
            ->willReturnCallback(
                static function (string $id) use ($matcher, $navigationConfig, $pageFactory): mixed {
                    $invokation = $matcher->numberOfInvocations();

                    match ($invokation) {
                        1, 2 => self::assertSame(
                            NavigationConfigInterface::class,
                            $id,
                            (string) $invokation,
                        ),
                        default => self::assertSame(
                            PageFactoryInterface::class,
                            $id,
                            (string) $invokation,
                        ),
                    };

                    return match ($invokation) {
                        1, 2 => $navigationConfig,
                        default => $pageFactory,
                    };
                },
            );

        assert($container instanceof ContainerInterface);
        $navigation = ($this->factory)($container, 'Mimmi20\Mezzio\Navigation\Test');

        self::assertInstanceOf(Navigation::class, $navigation);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ContainerExceptionInterface
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     */
    public function testInvokeLowercased(): void
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
        $matcher   = self::exactly(3);
        $container->expects($matcher)
            ->method('get')
            ->willReturnCallback(
                static function (string $id) use ($matcher, $navigationConfig, $pageFactory): mixed {
                    $invokation = $matcher->numberOfInvocations();

                    match ($invokation) {
                        1, 2 => self::assertSame(
                            NavigationConfigInterface::class,
                            $id,
                            (string) $invokation,
                        ),
                        default => self::assertSame(
                            PageFactoryInterface::class,
                            $id,
                            (string) $invokation,
                        ),
                    };

                    return match ($invokation) {
                        1, 2 => $navigationConfig,
                        default => $pageFactory,
                    };
                },
            );

        assert($container instanceof ContainerInterface);
        $navigation = ($this->factory)($container, 'Mimmi20\Mezzio\Navigation\Test');

        self::assertInstanceOf(Navigation::class, $navigation);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ContainerExceptionInterface
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
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
        $this->expectExceptionMessage(
            'Failed to find a navigation container by the name "Mimmi20\Mezzio\Navigation\Test"',
        );
        $this->expectExceptionCode(0);

        assert($container instanceof ContainerInterface);
        ($this->factory)($container, 'Mimmi20\Mezzio\Navigation\Test');
    }
}
