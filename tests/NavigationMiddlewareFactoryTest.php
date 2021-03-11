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

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Mezzio\GenericAuthorization\AuthorizationInterface;
use Mezzio\Helper\UrlHelper;
use Mezzio\Navigation\Config\NavigationConfigInterface;
use Mezzio\Navigation\Exception\InvalidArgumentException;
use Mezzio\Navigation\Exception\MissingHelperException;
use Mezzio\Navigation\NavigationMiddleware;
use Mezzio\Navigation\NavigationMiddlewareFactory;
use Mezzio\Router\RouterInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class NavigationMiddlewareFactoryTest extends TestCase
{
    /**
     * @throws \PHPUnit\Framework\Exception
     *
     * @return void
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
                NavigationConfigInterface::class
            )
        );
        $this->expectExceptionCode(0);

        /* @var ContainerInterface $container */
        $factory($container);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     *
     * @return void
     */
    public function testFactoryWithoutUrlHelper(): void
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::exactly(2))
            ->method('has')
            ->withConsecutive([NavigationConfigInterface::class], [UrlHelper::class])
            ->willReturnOnConsecutiveCalls(true, false);
        $container->expects(self::never())
            ->method('get');

        $factory = new NavigationMiddlewareFactory();

        $this->expectException(MissingHelperException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%s requires a %s service at instantiation; none found',
                NavigationMiddleware::class,
                UrlHelper::class
            )
        );
        $this->expectExceptionCode(0);

        /* @var ContainerInterface $container */
        $factory($container);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
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
        $container->expects(self::exactly(4))
            ->method('has')
            ->withConsecutive([NavigationConfigInterface::class], [UrlHelper::class], [AuthorizationInterface::class], [RouterInterface::class])
            ->willReturnOnConsecutiveCalls(true, true, true, true);
        $container->expects(self::exactly(4))
            ->method('get')
            ->withConsecutive([AuthorizationInterface::class], [RouterInterface::class], [NavigationConfigInterface::class], [UrlHelper::class])
            ->willReturnOnConsecutiveCalls($authorization, $router, $navigationConfig, $urlHelper);

        $factory = new NavigationMiddlewareFactory();

        /** @var ContainerInterface $container */
        $middleware = $factory($container);
        self::assertInstanceOf(NavigationMiddleware::class, $middleware);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     *
     * @return void
     */
    public function testFactoryContainerExceptionAuthorizationInterface(): void
    {
        $exception = new ServiceNotCreatedException('test');
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::exactly(3))
            ->method('has')
            ->withConsecutive([NavigationConfigInterface::class], [UrlHelper::class], [AuthorizationInterface::class])
            ->willReturnOnConsecutiveCalls(true, true, true);
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
                AuthorizationInterface::class
            )
        );
        $this->expectExceptionCode(0);

        /* @var ContainerInterface $container */
        $factory($container);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Laminas\ServiceManager\Exception\ServiceNotCreatedException
     *
     * @return void
     */
    public function testFactoryContainerExceptionRouterInterface(): void
    {
        $authorization = $this->createMock(AuthorizationInterface::class);
        $exception     = new ServiceNotCreatedException('test');
        $container     = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::exactly(4))
            ->method('has')
            ->withConsecutive([NavigationConfigInterface::class], [UrlHelper::class], [AuthorizationInterface::class], [RouterInterface::class])
            ->willReturnOnConsecutiveCalls(true, true, true, true);
        $container->expects(self::exactly(2))
            ->method('get')
            ->withConsecutive([AuthorizationInterface::class], [RouterInterface::class])
            ->willReturnCallback(
                static function ($argument) use ($authorization, $exception) {
                    if (AuthorizationInterface::class === $argument) {
                        return $authorization;
                    }

                    throw $exception;
                }
            );

        $factory = new NavigationMiddlewareFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Cannot create %s service; could not initialize dependency %s',
                NavigationMiddleware::class,
                RouterInterface::class
            )
        );
        $this->expectExceptionCode(0);

        /* @var ContainerInterface $container */
        $factory($container);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Laminas\ServiceManager\Exception\ServiceNotCreatedException
     *
     * @return void
     */
    public function testFactoryContainerExceptionNavigationConfig(): void
    {
        $authorization = $this->createMock(AuthorizationInterface::class);
        $router        = $this->createMock(RouterInterface::class);
        $exception     = new ServiceNotCreatedException('test');
        $container     = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::exactly(4))
            ->method('has')
            ->withConsecutive([NavigationConfigInterface::class], [UrlHelper::class], [AuthorizationInterface::class], [RouterInterface::class])
            ->willReturnOnConsecutiveCalls(true, true, true, true);
        $container->expects(self::exactly(3))
            ->method('get')
            ->withConsecutive([AuthorizationInterface::class], [RouterInterface::class], [NavigationConfigInterface::class])
            ->willReturnCallback(
                static function ($argument) use ($authorization, $router, $exception) {
                    if (AuthorizationInterface::class === $argument) {
                        return $authorization;
                    }

                    if (RouterInterface::class === $argument) {
                        return $router;
                    }

                    throw $exception;
                }
            );

        $factory = new NavigationMiddlewareFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Cannot create %s service; could not initialize dependency %s',
                NavigationMiddleware::class,
                NavigationConfigInterface::class
            )
        );
        $this->expectExceptionCode(0);

        /* @var ContainerInterface $container */
        $factory($container);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Laminas\ServiceManager\Exception\ServiceNotCreatedException
     *
     * @return void
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
        $container->expects(self::exactly(4))
            ->method('has')
            ->withConsecutive([NavigationConfigInterface::class], [UrlHelper::class], [AuthorizationInterface::class], [RouterInterface::class])
            ->willReturnOnConsecutiveCalls(true, true, true, true);
        $container->expects(self::exactly(4))
            ->method('get')
            ->withConsecutive([AuthorizationInterface::class], [RouterInterface::class], [NavigationConfigInterface::class], [UrlHelper::class])
            ->willReturnCallback(
                static function ($argument) use ($authorization, $router, $navigationConfig, $exception) {
                    if (AuthorizationInterface::class === $argument) {
                        return $authorization;
                    }

                    if (RouterInterface::class === $argument) {
                        return $router;
                    }

                    if (NavigationConfigInterface::class === $argument) {
                        return $navigationConfig;
                    }

                    throw $exception;
                }
            );

        $factory = new NavigationMiddlewareFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Cannot create %s service; could not initialize dependency %s',
                NavigationMiddleware::class,
                UrlHelper::class
            )
        );
        $this->expectExceptionCode(0);

        /* @var ContainerInterface $container */
        $factory($container);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
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
        $container->expects(self::exactly(4))
            ->method('has')
            ->withConsecutive([$navigationConfigName], [$urlHelperServiceName], [AuthorizationInterface::class], [RouterInterface::class])
            ->willReturnOnConsecutiveCalls(true, true, true, true);
        $container->expects(self::exactly(4))
            ->method('get')
            ->withConsecutive([AuthorizationInterface::class], [RouterInterface::class], [$navigationConfigName], [$urlHelperServiceName])
            ->willReturnOnConsecutiveCalls($authorization, $router, $navigationConfig, $urlHelper);

        $factory = NavigationMiddlewareFactory::__set_state(
            [
                'navigationConfigName' => $navigationConfigName,
                'urlHelperServiceName' => $urlHelperServiceName,
            ]
        );

        self::assertInstanceOf(NavigationMiddlewareFactory::class, $factory);

        /** @var ContainerInterface $container */
        $middleware = $factory($container);
        self::assertInstanceOf(NavigationMiddleware::class, $middleware);
    }
}
