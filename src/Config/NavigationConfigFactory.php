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
namespace Mezzio\Navigation\Config;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Mezzio\Navigation\Exception\InvalidArgumentException;
use Psr\Container\ContainerInterface;

final class NavigationConfigFactory
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
     *
     * @return NavigationConfig
     */
    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): NavigationConfig
    {
        $configuration = $container->get('config');

        if (
            !is_array($configuration)
            || !array_key_exists(self::CONFIG_KEY, $configuration)
            || !is_array($configuration[self::CONFIG_KEY])
        ) {
            throw new InvalidArgumentException('Could not find navigation configuration key');
        }

        $config = new NavigationConfig();
        $config->setPages($configuration[self::CONFIG_KEY]);

        return $config;
    }
}
