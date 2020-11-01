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
namespace Mezzio\Navigation\Service;

use Mezzio\Navigation\Config\NavigationConfigInterface;
use Mezzio\Navigation\Exception\InvalidArgumentException;
use Mezzio\Navigation\Navigation;
use Psr\Container\ContainerInterface;

/**
 * Navigation abstract service factory
 *
 * Allows configuring several navigation instances. If you have a navigation config key named "special" then you can
 * use $container->get('Mezzio\Navigation\Special') to retrieve a navigation instance with this configuration.
 */
final class NavigationAbstractServiceFactory
{
    /**
     * Service manager factory prefix
     */
    public const SERVICE_PREFIX = 'Mezzio\\Navigation\\';

    /**
     * Can we create a navigation by the requested name? (v3)
     *
     * @param ContainerInterface $container
     * @param string             $requestedName Name by which service was requested, must
     *                                          start with Mezzio\Navigation\
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @return bool
     */
    public function canCreate(ContainerInterface $container, string $requestedName): bool
    {
        if (0 !== mb_strpos($requestedName, self::SERVICE_PREFIX)) {
            return false;
        }

        $config = $container->get(NavigationConfigInterface::class);

        return $this->hasNamedConfig($requestedName, $config);
    }

    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param string                            $requestedName
     * @param array|null                        $options
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws InvalidArgumentException
     *
     * @return Navigation
     */
    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null)
    {
        $factory = new ConstructedNavigationFactory(
            $this->getNamedConfigName($container, $requestedName)
        );

        return $factory($container);
    }

    /**
     * Extract config name from service name
     *
     * @param string $name
     *
     * @return string
     */
    private function getConfigName(string $name): string
    {
        return mb_substr($name, mb_strlen(self::SERVICE_PREFIX));
    }

    /**
     * Does the configuration have a matching named section?
     *
     * @param string                    $name
     * @param NavigationConfigInterface $config
     *
     * @return bool
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
     * @param ContainerInterface $container
     * @param string             $name
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws InvalidArgumentException
     *
     * @return string
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
