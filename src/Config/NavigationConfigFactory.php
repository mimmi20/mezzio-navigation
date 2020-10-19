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
namespace Mezzio\Navigation\Config;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Factory\FactoryInterface;

final class NavigationConfigFactory implements FactoryInterface
{
    /**
     * Top-level configuration key indicating navigation configuration
     */
    public const CONFIG_KEY = 'navigation';

    /**
     * Create and return a new Navigation instance.
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @throws ServiceNotCreatedException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
     * @return NavigationConfig
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): NavigationConfig
    {
        $configuration = $container->get('config');

        if (
            !is_array($configuration)
            || !array_key_exists(self::CONFIG_KEY, $configuration)
            || !is_array($configuration[self::CONFIG_KEY])
        ) {
            throw new ServiceNotCreatedException('Could not find navigation configuration key');
        }

        $config = new NavigationConfig();
        $config->setPages($configuration[self::CONFIG_KEY]);

        return $config;
    }
}
