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
namespace Mezzio\Navigation;

use Mezzio\Authorization\AuthorizationInterface;
use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouterInterface;
use Psr\Container\ContainerInterface;
use function sprintf;

final class NavigationMiddlewareFactory
{
    /** @var string */
    private $navigationConfigName;

    /** @var string */
    private $urlHelperServiceName;

    /**
     * Allow serialization
     *
     * @param array $data
     *
     * @return \Mezzio\Navigation\NavigationMiddlewareFactory
     */
    public static function __set_state(array $data): self
    {
        return new self(
            $data['navigationServiceName'] ?? Navigation::class,
            $data['urlHelperServiceName'] ?? UrlHelper::class
        );
    }

    /**
     * Allow varying behavior based on URL helper service name.
     *
     * @param string $navigationConfigName
     * @param string $urlHelperServiceName
     */
    public function __construct(string $navigationConfigName = Config\NavigationConfig::class, string $urlHelperServiceName = UrlHelper::class)
    {
        $this->navigationConfigName = $navigationConfigName;
        $this->urlHelperServiceName = $urlHelperServiceName;
    }

    /**
     * Create and return a UrlHelperMiddleware instance.
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @throws Exception\MissingHelperException           if the UrlHelper service is missing
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @return \Mezzio\Navigation\NavigationMiddleware
     */
    public function __invoke(ContainerInterface $container): NavigationMiddleware
    {
        if (!$container->has($this->navigationConfigName)) {
            throw new Exception\MissingHelperException(sprintf(
                '%s requires a %s service at instantiation; none found',
                NavigationMiddleware::class,
                $this->navigationConfigName
            ));
        }

        if (!$container->has($this->urlHelperServiceName)) {
            throw new Exception\MissingHelperException(sprintf(
                '%s requires a %s service at instantiation; none found',
                NavigationMiddleware::class,
                $this->urlHelperServiceName
            ));
        }

        $authorization = null;
        $router        = null;

        if ($container->has(AuthorizationInterface::class)) {
            $authorization = $container->get(AuthorizationInterface::class);
        }

        if ($container->has(RouterInterface::class)) {
            $router = $container->get(RouterInterface::class);
        }

        return new NavigationMiddleware(
            $container->get($this->navigationConfigName),
            $container->get($this->urlHelperServiceName),
            $authorization,
            $router
        );
    }
}
