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

namespace Mezzio\Navigation;

use Mezzio\GenericAuthorization\AuthorizationInterface;
use Mezzio\Helper\UrlHelper;
use Mezzio\Navigation\Exception\InvalidArgumentException;
use Mezzio\Router\RouterInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

use function assert;
use function sprintf;

final class NavigationMiddlewareFactory
{
    private string $navigationConfigName;

    private string $urlHelperServiceName;

    /**
     * Allow varying behavior based on URL helper service name.
     */
    public function __construct(string $navigationConfigName = Config\NavigationConfigInterface::class, string $urlHelperServiceName = UrlHelper::class)
    {
        $this->navigationConfigName = $navigationConfigName;
        $this->urlHelperServiceName = $urlHelperServiceName;
    }

    /**
     * Allow serialization
     *
     * @param array<string, string> $data
     *
     * @return NavigationMiddlewareFactory
     */
    public static function __set_state(array $data): self
    {
        return new self(
            $data['navigationConfigName'] ?? Config\NavigationConfigInterface::class,
            $data['urlHelperServiceName'] ?? UrlHelper::class
        );
    }

    /**
     * Create and return a NavigationMiddleware instance.
     *
     * @throws Exception\MissingHelperException   if the UrlHelper service is missing
     * @throws Exception\InvalidArgumentException
     */
    public function __invoke(ContainerInterface $container): NavigationMiddleware
    {
        if (!$container->has($this->navigationConfigName)) {
            throw new Exception\MissingHelperException(
                sprintf(
                    '%s requires a %s service at instantiation; none found',
                    NavigationMiddleware::class,
                    $this->navigationConfigName
                )
            );
        }

        if (!$container->has($this->urlHelperServiceName)) {
            throw new Exception\MissingHelperException(
                sprintf(
                    '%s requires a %s service at instantiation; none found',
                    NavigationMiddleware::class,
                    $this->urlHelperServiceName
                )
            );
        }

        $authorization = null;
        $router        = null;

        if ($container->has(AuthorizationInterface::class)) {
            try {
                $authorization = $container->get(AuthorizationInterface::class);
                assert($authorization instanceof AuthorizationInterface);
            } catch (ContainerExceptionInterface $e) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Cannot create %s service; could not initialize dependency %s',
                        NavigationMiddleware::class,
                        AuthorizationInterface::class
                    )
                );
            }
        }

        if ($container->has(RouterInterface::class)) {
            try {
                $router = $container->get(RouterInterface::class);
                assert($router instanceof RouterInterface);
            } catch (ContainerExceptionInterface $e) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Cannot create %s service; could not initialize dependency %s',
                        NavigationMiddleware::class,
                        RouterInterface::class
                    )
                );
            }
        }

        try {
            $navigationConfig = $container->get($this->navigationConfigName);
        } catch (ContainerExceptionInterface $e) {
            throw new InvalidArgumentException(
                sprintf(
                    'Cannot create %s service; could not initialize dependency %s',
                    NavigationMiddleware::class,
                    $this->navigationConfigName
                )
            );
        }

        try {
            $urlHelper = $container->get($this->urlHelperServiceName);
        } catch (ContainerExceptionInterface $e) {
            throw new InvalidArgumentException(
                sprintf(
                    'Cannot create %s service; could not initialize dependency %s',
                    NavigationMiddleware::class,
                    $this->urlHelperServiceName
                )
            );
        }

        assert($navigationConfig instanceof Config\NavigationConfigInterface);
        assert($urlHelper instanceof UrlHelper);

        return new NavigationMiddleware(
            $navigationConfig,
            $urlHelper,
            $authorization,
            $router
        );
    }
}
