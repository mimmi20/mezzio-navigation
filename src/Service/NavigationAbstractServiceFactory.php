<?php

/**
 * This file is part of the mimmi20/mezzio-navigation package.
 *
 * Copyright (c) 2020-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Mezzio\Navigation\Service;

use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Mimmi20\Mezzio\Navigation\Config\NavigationConfigInterface;
use Mimmi20\Mezzio\Navigation\Exception\InvalidArgumentException;
use Mimmi20\Mezzio\Navigation\Navigation;
use Override;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

use function assert;
use function get_debug_type;
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
     *
     * @api
     */
    public const string SERVICE_PREFIX = 'Mimmi20\Mezzio\Navigation\\';

    /**
     * @param array<int|string, mixed>|null $options
     * @param string                        $requestedName Name by which service was requested, must start with Mimmi20\Mezzio\Navigation\
     *
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        array | null $options = null,
    ): Navigation {
        $factory = new ConstructedNavigationFactory(
            $this->getNamedConfigName($container, $requestedName),
        );

        return $factory($container);
    }

    /**
     * Can we create a navigation by the requested name? (v3)
     *
     * @param string $requestedName Name by which service was requested, must start with Mimmi20\Mezzio\Navigation\
     *
     * @throws ContainerExceptionInterface
     */
    #[Override]
    public function canCreate(ContainerInterface $container, string $requestedName): bool
    {
        if (mb_strpos($requestedName, self::SERVICE_PREFIX) !== 0) {
            return false;
        }

        $config = $container->get(NavigationConfigInterface::class);

        assert($config instanceof NavigationConfigInterface);

        return $this->hasNamedConfig($requestedName, $config);
    }

    /**
     * Extract config name from service name
     *
     * @throws void
     */
    private function getConfigName(string $name): string
    {
        return mb_substr($name, mb_strlen(self::SERVICE_PREFIX));
    }

    /**
     * Does the configuration have a matching named section?
     *
     * @throws void
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
        $config = $container->get(NavigationConfigInterface::class);
        assert(
            $config instanceof NavigationConfigInterface,
            sprintf(
                '$config should be an Instance of %s, but was %s',
                NavigationConfigInterface::class,
                get_debug_type($config),
            ),
        );

        $withoutPrefix = $this->getConfigName($name);

        $pages = $config->getPages();

        if (isset($pages[$withoutPrefix])) {
            return $withoutPrefix;
        }

        if (isset($pages[mb_strtolower($withoutPrefix)])) {
            return mb_strtolower($withoutPrefix);
        }

        throw new InvalidArgumentException(
            sprintf('Failed to find a navigation container by the name "%s"', $name),
        );
    }
}
