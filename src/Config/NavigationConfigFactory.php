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

use Mezzio\Navigation\Exception\InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

use function array_key_exists;
use function is_array;

final class NavigationConfigFactory
{
    /**
     * Top-level configuration key indicating navigation configuration
     */
    public const CONFIG_KEY = 'navigation';

    /**
     * Create and return a new Navigation instance.
     *
     * @param array<mixed>|null $options
     *
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
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
