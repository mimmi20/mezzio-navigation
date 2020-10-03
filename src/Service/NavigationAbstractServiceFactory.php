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
     * Top-level configuration key indicating navigation configuration
     */
    public const CONFIG_KEY = 'navigation';

    /**
     * Service manager factory prefix
     */
    public const SERVICE_PREFIX = 'Laminas\\Navigation\\';

    /**
     * Navigation configuration
     *
     * @var array
     */
    private $config;

    /**
     * Can we create a navigation by the requested name? (v3)
     *
     * @param ContainerInterface $container
     * @param string             $requestedName Name by which service was requested, must
     *                                          start with Mezzio\Navigation\
     *
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        if (0 !== mb_strpos($requestedName, self::SERVICE_PREFIX)) {
            return false;
        }

        $config = $this->getConfig($container);

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
        $config  = $this->getConfig($container);
        $factory = new ConstructedNavigationFactory($this->getNamedConfig($requestedName, $config));

        return $factory($container, $requestedName);
    }

    /**
     * Get navigation configuration, if any
     *
     * @param ContainerInterface $container
     *
     * @return array
     */
    private function getConfig(ContainerInterface $container): array
    {
        if (null !== $this->config) {
            return $this->config;
        }

        if (!$container->has('config')) {
            $this->config = [];

            return $this->config;
        }

        $config = $container->get('config');
        if (
            !isset($config[self::CONFIG_KEY])
            || !is_array($config[self::CONFIG_KEY])
        ) {
            $this->config = [];

            return $this->config;
        }

        $this->config = $config[self::CONFIG_KEY];

        return $this->config;
    }

    /**
     * Extract config name from service name
     *
     * @param string $name
     *
     * @return false|string
     */
    private function getConfigName(string $name)
    {
        return mb_substr($name, mb_strlen(self::SERVICE_PREFIX));
    }

    /**
     * Does the configuration have a matching named section?
     *
     * @param string             $name
     * @param array|\ArrayAccess $config
     *
     * @return bool
     */
    private function hasNamedConfig(string $name, $config): bool
    {
        $withoutPrefix = $this->getConfigName($name);

        if (isset($config[$withoutPrefix])) {
            return true;
        }

        return isset($config[mb_strtolower($withoutPrefix)]);
    }

    /**
     * Get the matching named configuration section.
     *
     * @param string             $name
     * @param array|\ArrayAccess $config
     *
     * @return array
     */
    private function getNamedConfig(string $name, $config): array
    {
        $withoutPrefix = $this->getConfigName($name);

        if (isset($config[$withoutPrefix])) {
            return $config[$withoutPrefix];
        }

        if (isset($config[mb_strtolower($withoutPrefix)])) {
            return $config[mb_strtolower($withoutPrefix)];
        }

        return [];
    }
}
