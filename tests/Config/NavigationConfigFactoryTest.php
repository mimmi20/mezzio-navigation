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

use Mimmi20\Mezzio\Navigation\Config\NavigationConfig;
use Mimmi20\Mezzio\Navigation\Config\NavigationConfigFactory;
use Mimmi20\Mezzio\Navigation\Exception\InvalidArgumentException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

use function assert;

final class NavigationConfigFactoryTest extends TestCase
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ContainerExceptionInterface
     */
    public function testFactoryWithoutNavigationConfig(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn('');

        $navigationConfigFactory = new NavigationConfigFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not find navigation configuration key');
        $this->expectExceptionCode(0);

        assert($container instanceof ContainerInterface);
        $navigationConfigFactory($container);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ContainerExceptionInterface
     */
    public function testFactoryWithoutNavigationConfig2(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn([]);

        $navigationConfigFactory = new NavigationConfigFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not find navigation configuration key');
        $this->expectExceptionCode(0);

        assert($container instanceof ContainerInterface);
        $navigationConfigFactory($container);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ContainerExceptionInterface
     */
    public function testFactoryWithoutNavigationConfig3(): void
    {
        $pages = [NavigationConfigFactory::CONFIG_KEY => ''];

        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn($pages);

        $navigationConfigFactory = new NavigationConfigFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not find navigation configuration key');
        $this->expectExceptionCode(0);

        assert($container instanceof ContainerInterface);
        $navigationConfigFactory($container);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ContainerExceptionInterface
     */
    public function testFactory(): void
    {
        $pages = [
            NavigationConfigFactory::CONFIG_KEY => [],
        ];

        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn($pages);

        $navigationConfigFactory = new NavigationConfigFactory();

        assert($container instanceof ContainerInterface);
        $navigationConfig = $navigationConfigFactory($container);

        self::assertInstanceOf(NavigationConfig::class, $navigationConfig);

        self::assertSame($pages[NavigationConfigFactory::CONFIG_KEY], $navigationConfig->getPages());
    }
}
