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

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Mezzio\Navigation\Config\NavigationConfig;
use Mezzio\Navigation\Navigation;

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
    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        if (0 !== mb_strpos($requestedName, self::SERVICE_PREFIX)) {
            return false;
        }

        $config = $container->get(NavigationConfig::class);

        return $this->hasNamedConfig($requestedName, $config);
    }

    /**
     * @param \Interop\Container\ContainerInterface $container
     * @param string                                $requestedName
     * @param array|null                            $options
     *
     * @return Navigation
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $factory = new ConstructedNavigationFactory($this->getNamedConfigName($container, $requestedName));

        return $factory($container, $requestedName);
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
     * @param string           $name
     * @param NavigationConfig $config
     *
     * @return bool
     */
    private function hasNamedConfig(string $name, NavigationConfig $config): bool
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
     * @param string $name
     * @return string
     */
    private function getNamedConfigName(ContainerInterface $container, string $name): string
    {
        $config        = $container->get(NavigationConfig::class);
        $withoutPrefix = $this->getConfigName($name);

        $pages = $config->getPages();

        if (isset($pages[$withoutPrefix])) {
            return $withoutPrefix;
        }

        if (isset($pages[strtolower($withoutPrefix)])) {
            return strtolower($withoutPrefix);
        }

        return '';
    }
}
