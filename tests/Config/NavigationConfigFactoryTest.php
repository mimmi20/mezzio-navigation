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
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn('');

        $factory = new NavigationConfigFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not find navigation configuration key');
        $this->expectExceptionCode(0);

        assert($container instanceof ContainerInterface);
        $factory($container);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ContainerExceptionInterface
     */
    public function testFactoryWithoutNavigationConfig2(): void
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn([]);

        $factory = new NavigationConfigFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not find navigation configuration key');
        $this->expectExceptionCode(0);

        assert($container instanceof ContainerInterface);
        $factory($container);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ContainerExceptionInterface
     */
    public function testFactoryWithoutNavigationConfig3(): void
    {
        $pages = [NavigationConfigFactory::CONFIG_KEY => ''];

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn($pages);

        $factory = new NavigationConfigFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not find navigation configuration key');
        $this->expectExceptionCode(0);

        assert($container instanceof ContainerInterface);
        $factory($container);
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

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('get')
            ->with('config')
            ->willReturn($pages);

        $factory = new NavigationConfigFactory();

        assert($container instanceof ContainerInterface);
        $config = $factory($container);

        self::assertInstanceOf(NavigationConfig::class, $config);

        self::assertSame($pages[NavigationConfigFactory::CONFIG_KEY], $config->getPages());
    }
}
