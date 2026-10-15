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
     * @throws MissingHelperException
     * @throws InvalidArgumentException
     */
    public function testFactoryWithoutNavigationConfig(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::once())
            ->method('has')
            ->with(NavigationConfigInterface::class)
            ->willReturn(value: false);
        $container->expects(self::never())
            ->method('get');

        $navigationMiddlewareFactory = new NavigationMiddlewareFactory();

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
        $navigationMiddlewareFactory($container);
    }

    /**
     * @throws MissingHelperException
     * @throws InvalidArgumentException
     */
    public function testFactoryWithoutUrlHelper(): void
    {
        $container    = $this->createMock(ContainerInterface::class);
        $invokedCount = self::exactly(2);
        $container->expects($invokedCount)
            ->method('has')
            ->willReturnCallback(
                static function (string $id) use ($invokedCount): bool {
                    match ($invokedCount->numberOfInvocations()) {
                        1 => self::assertSame(NavigationConfigInterface::class, $id),
                        default => self::assertSame(UrlHelper::class, $id),
                    };

                    return match ($invokedCount->numberOfInvocations()) {
                        1 => true,
                        default => false,
                    };
                },
            );
        $container->expects(self::never())
            ->method('get');

        $navigationMiddlewareFactory = new NavigationMiddlewareFactory();

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
        $navigationMiddlewareFactory($container);
    }

    /**
     * @throws Exception
     * @throws MissingHelperException
     * @throws InvalidArgumentException
     */
    public function testFactory(): void
    {
        $authorization    = self::createStub(AuthorizationInterface::class);
        $router           = self::createStub(RouterInterface::class);
        $navigationConfig = self::createStub(NavigationConfigInterface::class);
        $urlHelper        = self::createStub(UrlHelper::class);

        $container = $this->createMock(ContainerInterface::class);
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

        $navigationMiddlewareFactory = new NavigationMiddlewareFactory();

        assert($container instanceof ContainerInterface);
        $navigationMiddleware = $navigationMiddlewareFactory($container);
        self::assertInstanceOf(NavigationMiddleware::class, $navigationMiddleware);
    }

    /**
     * @throws MissingHelperException
     * @throws InvalidArgumentException
     */
    public function testFactoryContainerExceptionAuthorizationInterface(): void
    {
        $serviceNotCreatedException = new ServiceNotCreatedException('test');
        $container                  = $this->createMock(ContainerInterface::class);
        $invokedCount               = self::exactly(3);
        $container->expects($invokedCount)
            ->method('has')
            ->willReturnCallback(
                static function (string $id) use ($invokedCount): bool {
                    match ($invokedCount->numberOfInvocations()) {
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
            ->willThrowException($serviceNotCreatedException);

        $navigationMiddlewareFactory = new NavigationMiddlewareFactory();

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
        $navigationMiddlewareFactory($container);
    }

    /**
     * @throws MissingHelperException
     * @throws InvalidArgumentException
     */
    public function testFactoryContainerExceptionRouterInterface(): void
    {
        $authorization              = self::createStub(AuthorizationInterface::class);
        $serviceNotCreatedException = new ServiceNotCreatedException('test');
        $container                  = $this->createMock(ContainerInterface::class);
        $matcher                    = self::exactly(4);
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
                static function (string $id) use ($matcher, $authorization, $serviceNotCreatedException): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(AuthorizationInterface::class, $id),
                        default => self::assertSame(RouterInterface::class, $id),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $authorization,
                        default => throw $serviceNotCreatedException,
                    };
                },
            );

        $navigationMiddlewareFactory = new NavigationMiddlewareFactory();

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
        $navigationMiddlewareFactory($container);
    }

    /**
     * @throws MissingHelperException
     * @throws InvalidArgumentException
     */
    public function testFactoryContainerExceptionNavigationConfig(): void
    {
        $authorization              = self::createStub(AuthorizationInterface::class);
        $router                     = self::createStub(RouterInterface::class);
        $serviceNotCreatedException = new ServiceNotCreatedException('test');
        $container                  = $this->createMock(ContainerInterface::class);
        $matcher                    = self::exactly(4);
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
                static function (string $id) use ($matcher, $authorization, $router, $serviceNotCreatedException): mixed {
                    match ($matcher->numberOfInvocations()) {
                        1 => self::assertSame(AuthorizationInterface::class, $id),
                        2 => self::assertSame(RouterInterface::class, $id),
                        default => self::assertSame(NavigationConfigInterface::class, $id),
                    };

                    return match ($matcher->numberOfInvocations()) {
                        1 => $authorization,
                        2 => $router,
                        default => throw $serviceNotCreatedException,
                    };
                },
            );

        $navigationMiddlewareFactory = new NavigationMiddlewareFactory();

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
        $navigationMiddlewareFactory($container);
    }

    /**
     * @throws MissingHelperException
     * @throws InvalidArgumentException
     */
    public function testFactoryContainerExceptionUrlHelper(): void
    {
        $authorization              = self::createStub(AuthorizationInterface::class);
        $router                     = self::createStub(RouterInterface::class);
        $navigationConfig           = self::createStub(NavigationConfigInterface::class);
        $serviceNotCreatedException = new ServiceNotCreatedException('test');
        $container                  = $this->createMock(ContainerInterface::class);
        $matcher                    = self::exactly(4);
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
                static function (string $id) use ($matcher, $authorization, $router, $navigationConfig, $serviceNotCreatedException): mixed {
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
                        default => throw $serviceNotCreatedException,
                    };
                },
            );

        $navigationMiddlewareFactory = new NavigationMiddlewareFactory();

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
        $navigationMiddlewareFactory($container);
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

        $authorization    = self::createStub(AuthorizationInterface::class);
        $router           = self::createStub(RouterInterface::class);
        $navigationConfig = self::createStub(NavigationConfigInterface::class);
        $urlHelper        = self::createStub(UrlHelper::class);

        $container = $this->createMock(ContainerInterface::class);
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

        $navigationMiddlewareFactory = NavigationMiddlewareFactory::__set_state(
            [
                'navigationConfigName' => $navigationConfigName,
                'urlHelperServiceName' => $urlHelperServiceName,
            ],
        );

        self::assertInstanceOf(NavigationMiddlewareFactory::class, $navigationMiddlewareFactory);

        assert($container instanceof ContainerInterface);
        $navigationMiddleware = $navigationMiddlewareFactory($container);
        self::assertInstanceOf(NavigationMiddleware::class, $navigationMiddleware);
    }
}
