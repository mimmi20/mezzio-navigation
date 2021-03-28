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

namespace Mezzio\Navigation\Service;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\AbstractFactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Mezzio\Navigation\Config\NavigationConfigInterface;
use Mezzio\Navigation\Exception\InvalidArgumentException;
use Mezzio\Navigation\Navigation;
use Psr\Container\ContainerExceptionInterface;

use function mb_strlen;
use function mb_strpos;
use function mb_strtolower;
use function mb_substr;
use function sprintf;

/**
 * Navigation abstract service factory
 *
 * Allows configuring several navigation instances. If you have a navigation config key named "special" then you can
 * use $container->get('Mezzio\Navigation\Special') to retrieve a navigation instance with this configuration.
 */
final class NavigationAbstractServiceFactory implements AbstractFactoryInterface
{
    /**
     * Service manager factory prefix
     */
    public const SERVICE_PREFIX = 'Mezzio\\Navigation\\';

    /**
     * @param string            $requestedName
     * @param array<mixed>|null $options
     *
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): Navigation
    {
        $factory = new ConstructedNavigationFactory(
            $this->getNamedConfigName($container, $requestedName)
        );

        return $factory($container);
    }

    /**
     * Can we create a navigation by the requested name? (v3)
     *
     * @param string $requestedName Name by which service was requested, must
     *                              start with Mezzio\Navigation\
     *
     * @throws ContainerExceptionInterface
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     */
    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        if (0 !== mb_strpos($requestedName, self::SERVICE_PREFIX)) {
            return false;
        }

        $config = $container->get(NavigationConfigInterface::class);

        return $this->hasNamedConfig($requestedName, $config);
    }

    /**
     * Determine if we can create a service with name
     *
     * @param string $name
     * @param string $requestedName
     *
     * @throws ContainerExceptionInterface
     *
     * @codeCoverageIgnore
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName): bool
    {
        return $this->canCreate($serviceLocator, $requestedName);
    }

    /**
     * Create service with name
     *
     * @param string $name
     * @param string $requestedName
     *
     * @codeCoverageIgnore
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName): Navigation
    {
        return $this($serviceLocator, $requestedName);
    }

    /**
     * Extract config name from service name
     */
    private function getConfigName(string $name): string
    {
        return mb_substr($name, mb_strlen(self::SERVICE_PREFIX));
    }

    /**
     * Does the configuration have a matching named section?
     */
    private function hasNamedConfig(string $name, NavigationConfigInterface $config): bool
    {
        $withoutPrefix = $this->getConfigName($name);

        $pages = $config->getPages();

        if (isset($pages[$withoutPrefix])) {
            return true;
        }

        return isset($pages[mb_strtolower($withoutPrefix)]);
    }

    /**
     * Get the matching named configuration section.
     *
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     */
    private function getNamedConfigName(ContainerInterface $container, string $name): string
    {
        $config        = $container->get(NavigationConfigInterface::class);
        $withoutPrefix = $this->getConfigName($name);

        $pages = $config->getPages();

        if (isset($pages[$withoutPrefix])) {
            return $withoutPrefix;
        }

        if (isset($pages[mb_strtolower($withoutPrefix)])) {
            return mb_strtolower($withoutPrefix);
        }

        throw new InvalidArgumentException(
            sprintf('Failed to find a navigation container by the name "%s"', $name)
        );
    }
}
