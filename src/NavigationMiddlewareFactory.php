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

namespace Mimmi20\Mezzio\Navigation;

use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouterInterface;
use Mimmi20\Mezzio\GenericAuthorization\AuthorizationInterface;
use Mimmi20\Mezzio\Navigation\Exception\InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

use function assert;
use function sprintf;

final readonly class NavigationMiddlewareFactory
{
    /**
     * Allow varying behavior based on URL helper service name.
     *
     * @throws void
     */
    public function __construct(
        private string $navigationConfigName = Config\NavigationConfigInterface::class,
        private string $urlHelperServiceName = UrlHelper::class,
    ) {
        // nothing to do
    }

    /**
     * Allow serialization
     *
     * @param array<string, string> $data
     *
     * @throws void
     */
    public static function __set_state(array $data): self
    {
        return new self(
            $data['navigationConfigName'] ?? Config\NavigationConfigInterface::class,
            $data['urlHelperServiceName'] ?? UrlHelper::class,
        );
    }

    /**
     * Create and return a NavigationMiddleware instance.
     *
     * @throws Exception\MissingHelperException   if the UrlHelper service is missing
     * @throws InvalidArgumentException
     */
    public function __invoke(ContainerInterface $container): NavigationMiddleware
    {
        if (!$container->has($this->navigationConfigName)) {
            throw new Exception\MissingHelperException(
                sprintf(
                    '%s requires a %s service at instantiation; none found',
                    NavigationMiddleware::class,
                    $this->navigationConfigName,
                ),
            );
        }

        if (!$container->has($this->urlHelperServiceName)) {
            throw new Exception\MissingHelperException(
                sprintf(
                    '%s requires a %s service at instantiation; none found',
                    NavigationMiddleware::class,
                    $this->urlHelperServiceName,
                ),
            );
        }

        $authorization = null;
        $router        = null;

        if ($container->has(AuthorizationInterface::class)) {
            try {
                $authorization = $container->get(AuthorizationInterface::class);
                assert($authorization instanceof AuthorizationInterface);
            } catch (ContainerExceptionInterface) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Cannot create %s service; could not initialize dependency %s',
                        NavigationMiddleware::class,
                        AuthorizationInterface::class,
                    ),
                );
            }
        }

        if ($container->has(RouterInterface::class)) {
            try {
                $router = $container->get(RouterInterface::class);
                assert($router instanceof RouterInterface);
            } catch (ContainerExceptionInterface) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Cannot create %s service; could not initialize dependency %s',
                        NavigationMiddleware::class,
                        RouterInterface::class,
                    ),
                );
            }
        }

        try {
            $navigationConfig = $container->get($this->navigationConfigName);
        } catch (ContainerExceptionInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'Cannot create %s service; could not initialize dependency %s',
                    NavigationMiddleware::class,
                    $this->navigationConfigName,
                ),
            );
        }

        try {
            $urlHelper = $container->get($this->urlHelperServiceName);
        } catch (ContainerExceptionInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'Cannot create %s service; could not initialize dependency %s',
                    NavigationMiddleware::class,
                    $this->urlHelperServiceName,
                ),
            );
        }

        assert($navigationConfig instanceof Config\NavigationConfigInterface);
        assert($urlHelper instanceof UrlHelper);

        return new NavigationMiddleware($navigationConfig, $urlHelper, $authorization, $router);
    }
}
