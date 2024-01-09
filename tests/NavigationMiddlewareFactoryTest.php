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

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouterInterface;
use Mimmi20\Mezzio\GenericAuthorization\AuthorizationInterface;
use Mimmi20\Mezzio\Navigation\Config\NavigationConfigInterface;
use Mimmi20\Mezzio\Navigation\Exception\InvalidArgumentException;
use Mimmi20\Mezzio\Navigation\Exception\MissingHelperException;
use Mimmi20\Mezzio\Navigation\NavigationMiddleware;
use Mimmi20\Mezzio\Navigation\NavigationMiddlewareFactory;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

use function assert;
use function sprintf;

final class NavigationMiddlewareFactoryTest extends TestCase
{
    /**
     * @throws Exception
     * @throws MissingHelperException
     * @throws InvalidArgumentException
     */
    public function testFactoryWithoutNavigationConfig(): void
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::once())
            ->method('has')
            ->with(NavigationConfigInterface::class)
            ->willReturn(false);
        $container->expects(self::never())
            ->method('get');

        $factory = new NavigationMiddlewareFactory();

        $this->expectException(MissingHelperException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires a %s service at instantiation; none found',
                NavigationMiddleware::class,
                NavigationConfigInterface::class,
            ),
        );
        $this->expectExceptionCode(0);

        assert($container instanceof ContainerInterface);
        $factory($container);
    }

    /**
     * @throws Exception
     * @throws MissingHelperException
     * @throws InvalidArgumentException
     */
    public function testFactoryWithoutUrlHelper(): void
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher   = self::exactly(2);
        $container->expects($matcher)
            ->method('has')
            ->willReturnCallback(
                static function (string $id) use ($matcher): bool {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(NavigationConfigInterface::class, $id),
                        default => self::assertSame(UrlHelper::class, $id),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => true,
                        default => false,
                    };
                },
            );
        $container->expects(self::never())
            ->method('get');

        $factory = new NavigationMiddlewareFactory();

        $this->expectException(MissingHelperException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires a %s service at instantiation; none found',
                NavigationMiddleware::class,
                UrlHelper::class,
            ),
        );
        $this->expectExceptionCode(0);

        assert($container instanceof ContainerInterface);
        $factory($container);
    }

    /**
     * @throws Exception
     * @throws MissingHelperException
     * @throws InvalidArgumentException
     */
    public function testFactory(): void
    {
        $authorization    = $this->createMock(AuthorizationInterface::class);
        $router           = $this->createMock(RouterInterface::class);
        $navigationConfig = $this->createMock(NavigationConfigInterface::class);
        $urlHelper        = $this->createMock(UrlHelper::class);

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher   = self::exactly(4);
        $container->expects($matcher)
            ->method('has')
            ->willReturnCallback(
                static function (string $id) use ($matcher): bool {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(NavigationConfigInterface::class, $id),
                        3 => self::assertSame(AuthorizationInterface::class, $id),
                        4 => self::assertSame(RouterInterface::class, $id),
                        default => self::assertSame(UrlHelper::class, $id),
                    };

                    return true;
                },
            );
        $matcher = self::exactly(4);
        $container->expects($matcher)
            ->method('get')
            ->willReturnCallback(
                static function (string $id) use ($matcher, $authorization, $router, $navigationConfig, $urlHelper): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(AuthorizationInterface::class, $id),
                        2 => self::assertSame(RouterInterface::class, $id),
                        3 => self::assertSame(NavigationConfigInterface::class, $id),
                        default => self::assertSame(UrlHelper::class, $id),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $authorization,
                        2 => $router,
                        3 => $navigationConfig,
                        default => $urlHelper,
                    };
                },
            );

        $factory = new NavigationMiddlewareFactory();

        assert($container instanceof ContainerInterface);
        $middleware = $factory($container);
        self::assertInstanceOf(NavigationMiddleware::class, $middleware);
    }

    /**
     * @throws Exception
     * @throws MissingHelperException
     * @throws InvalidArgumentException
     */
    public function testFactoryContainerExceptionAuthorizationInterface(): void
    {
        $exception = new ServiceNotCreatedException('test');
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher   = self::exactly(3);
        $container->expects($matcher)
            ->method('has')
            ->willReturnCallback(
                static function (string $id) use ($matcher): bool {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(NavigationConfigInterface::class, $id),
                        3 => self::assertSame(AuthorizationInterface::class, $id),
                        default => self::assertSame(UrlHelper::class, $id),
                    };

                    return true;
                },
            );
        $container->expects(self::once())
            ->method('get')
            ->with(AuthorizationInterface::class)
            ->willThrowException($exception);

        $factory = new NavigationMiddlewareFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Cannot create %s service; could not initialize dependency %s',
                NavigationMiddleware::class,
                AuthorizationInterface::class,
            ),
        );
        $this->expectExceptionCode(0);

        assert($container instanceof ContainerInterface);
        $factory($container);
    }

    /**
     * @throws Exception
     * @throws MissingHelperException
     * @throws InvalidArgumentException
     */
    public function testFactoryContainerExceptionRouterInterface(): void
    {
        $authorization = $this->createMock(AuthorizationInterface::class);
        $exception     = new ServiceNotCreatedException('test');
        $container     = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher       = self::exactly(4);
        $container->expects($matcher)
            ->method('has')
            ->willReturnCallback(
                static function (string $id) use ($matcher): bool {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(NavigationConfigInterface::class, $id),
                        3 => self::assertSame(AuthorizationInterface::class, $id),
                        4 => self::assertSame(RouterInterface::class, $id),
                        default => self::assertSame(UrlHelper::class, $id),
                    };

                    return true;
                },
            );
        $matcher = self::exactly(2);
        $container->expects($matcher)
            ->method('get')
            ->willReturnCallback(
                static function (string $id) use ($matcher, $authorization, $exception): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(AuthorizationInterface::class, $id),
                        default => self::assertSame(RouterInterface::class, $id),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $authorization,
                        default => throw $exception,
                    };
                },
            );

        $factory = new NavigationMiddlewareFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Cannot create %s service; could not initialize dependency %s',
                NavigationMiddleware::class,
                RouterInterface::class,
            ),
        );
        $this->expectExceptionCode(0);

        assert($container instanceof ContainerInterface);
        $factory($container);
    }

    /**
     * @throws Exception
     * @throws MissingHelperException
     * @throws InvalidArgumentException
     */
    public function testFactoryContainerExceptionNavigationConfig(): void
    {
        $authorization = $this->createMock(AuthorizationInterface::class);
        $router        = $this->createMock(RouterInterface::class);
        $exception     = new ServiceNotCreatedException('test');
        $container     = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher       = self::exactly(4);
        $container->expects($matcher)
            ->method('has')
            ->willReturnCallback(
                static function (string $id) use ($matcher): bool {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(NavigationConfigInterface::class, $id),
                        3 => self::assertSame(AuthorizationInterface::class, $id),
                        4 => self::assertSame(RouterInterface::class, $id),
                        default => self::assertSame(UrlHelper::class, $id),
                    };

                    return true;
                },
            );
        $matcher = self::exactly(3);
        $container->expects($matcher)
            ->method('get')
            ->willReturnCallback(
                static function (string $id) use ($matcher, $authorization, $router, $exception): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(AuthorizationInterface::class, $id),
                        2 => self::assertSame(RouterInterface::class, $id),
                        default => self::assertSame(NavigationConfigInterface::class, $id),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $authorization,
                        2 => $router,
                        default => throw $exception,
                    };
                },
            );

        $factory = new NavigationMiddlewareFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Cannot create %s service; could not initialize dependency %s',
                NavigationMiddleware::class,
                NavigationConfigInterface::class,
            ),
        );
        $this->expectExceptionCode(0);

        assert($container instanceof ContainerInterface);
        $factory($container);
    }

    /**
     * @throws Exception
     * @throws MissingHelperException
     * @throws InvalidArgumentException
     */
    public function testFactoryContainerExceptionUrlHelper(): void
    {
        $authorization    = $this->createMock(AuthorizationInterface::class);
        $router           = $this->createMock(RouterInterface::class);
        $navigationConfig = $this->createMock(NavigationConfigInterface::class);
        $exception        = new ServiceNotCreatedException('test');
        $container        = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher          = self::exactly(4);
        $container->expects($matcher)
            ->method('has')
            ->willReturnCallback(
                static function (string $id) use ($matcher): bool {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(NavigationConfigInterface::class, $id),
                        3 => self::assertSame(AuthorizationInterface::class, $id),
                        4 => self::assertSame(RouterInterface::class, $id),
                        default => self::assertSame(UrlHelper::class, $id),
                    };

                    return true;
                },
            );
        $matcher = self::exactly(4);
        $container->expects($matcher)
            ->method('get')
            ->willReturnCallback(
                static function (string $id) use ($matcher, $authorization, $router, $navigationConfig, $exception): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(AuthorizationInterface::class, $id),
                        2 => self::assertSame(RouterInterface::class, $id),
                        3 => self::assertSame(NavigationConfigInterface::class, $id),
                        default => self::assertSame(UrlHelper::class, $id),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $authorization,
                        2 => $router,
                        3 => $navigationConfig,
                        default => throw $exception,
                    };
                },
            );

        $factory = new NavigationMiddlewareFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Cannot create %s service; could not initialize dependency %s',
                NavigationMiddleware::class,
                UrlHelper::class,
            ),
        );
        $this->expectExceptionCode(0);

        assert($container instanceof ContainerInterface);
        $factory($container);
    }

    /**
     * @throws Exception
     * @throws MissingHelperException
     * @throws InvalidArgumentException
     */
    public function testFactoryAllowsSerialization(): void
    {
        $navigationConfigName = 'MyNavigationConfigInterface';
        $urlHelperServiceName = 'MyUrlHelper';

        $authorization    = $this->createMock(AuthorizationInterface::class);
        $router           = $this->createMock(RouterInterface::class);
        $navigationConfig = $this->createMock(NavigationConfigInterface::class);
        $urlHelper        = $this->createMock(UrlHelper::class);

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matcher   = self::exactly(4);
        $container->expects($matcher)
            ->method('has')
            ->willReturnCallback(
                static function (string $id) use ($matcher, $navigationConfigName, $urlHelperServiceName): bool {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame($navigationConfigName, $id),
                        2 => self::assertSame($urlHelperServiceName, $id),
                        4 => self::assertSame(RouterInterface::class, $id),
                        default => self::assertSame(AuthorizationInterface::class, $id),
                    };

                    return true;
                },
            );
        $matcher = self::exactly(4);
        $container->expects($matcher)
            ->method('get')
            ->willReturnCallback(
                static function (string $id) use ($matcher, $authorization, $router, $navigationConfigName, $urlHelperServiceName, $navigationConfig, $urlHelper): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(AuthorizationInterface::class, $id),
                        2 => self::assertSame(RouterInterface::class, $id),
                        3 => self::assertSame($navigationConfigName, $id),
                        default => self::assertSame($urlHelperServiceName, $id),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $authorization,
                        2 => $router,
                        3 => $navigationConfig,
                        default => $urlHelper,
                    };
                },
            );

        $factory = NavigationMiddlewareFactory::__set_state(
            [
                'navigationConfigName' => $navigationConfigName,
                'urlHelperServiceName' => $urlHelperServiceName,
            ],
        );

        self::assertInstanceOf(NavigationMiddlewareFactory::class, $factory);

        assert($container instanceof ContainerInterface);
        $middleware = $factory($container);
        self::assertInstanceOf(NavigationMiddleware::class, $middleware);
    }
}
