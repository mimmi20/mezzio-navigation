<?php

/**
 * This file is part of the mimmi20/mezzio-navigation package.
 *
 * Copyright (c) 2020-2026, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Mezzio\Navigation;

use Laminas\ServiceManager\Factory\InvokableFactory;

final class ConfigProvider
{
    /**
     * Return general-purpose laminas-navigation configuration.
     *
     * @return array<string, array<string, array<int|string, string>>>
     *
     * @throws void
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
        ];
    }

    /**
     * Return application-level dependency configuration.
     *
     * @return array<string, array<int|string, string>>
     *
     * @throws void
     *
     * @api
     */
    public function getDependencyConfig(): array
    {
        return [
            'abstract_factories' => [
                Service\NavigationAbstractServiceFactory::class,
            ],
            'factories' => [
                Navigation::class => Service\DefaultNavigationFactory::class,
                Config\NavigationConfig::class => Config\NavigationConfigFactory::class,
                NavigationMiddleware::class => NavigationMiddlewareFactory::class,
                Page\PageFactory::class => InvokableFactory::class,
            ],
            'aliases' => [
                Config\NavigationConfigInterface::class => Config\NavigationConfig::class,
                Page\PageFactoryInterface::class => Page\PageFactory::class,
            ],
        ];
    }
}
