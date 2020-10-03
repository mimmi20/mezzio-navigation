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
namespace MezzioTest\Navigation;

use Mezzio\Navigation\ConfigProvider;
use Mezzio\Navigation\Navigation;
use Mezzio\Navigation\Service;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    private $config = [
        'abstract_factories' => [
            Service\NavigationAbstractServiceFactory::class,
        ],
        'aliases' => [
            'navigation' => Navigation::class,
            'Zend\Navigation\Navigation' => Navigation::class,
        ],
        'factories' => [
            Navigation::class => Service\DefaultNavigationFactory::class,
        ],
    ];

    /**
     * @return ConfigProvider
     */
    public function testProvidesExpectedConfiguration(): ConfigProvider
    {
        $provider = new ConfigProvider();
        self::assertSame($this->config, $provider->getDependencyConfig());

        return $provider;
    }

    /**
     * @param ConfigProvider $provider
     *
     * @return void
     *
     * @depends testProvidesExpectedConfiguration
     */
    public function testInvocationProvidesDependencyConfiguration(ConfigProvider $provider): void
    {
        self::assertSame(['dependencies' => $provider->getDependencyConfig()], $provider());
    }
}
