<?php
/**
 * This file is part of the mimmi20/mezzio-navigation package.
 *
 * Copyright (c) 2020-2024, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\MezzioTest\Navigation;

use Mimmi20\Mezzio\Navigation\Config\NavigationConfig;
use Mimmi20\Mezzio\Navigation\Config\NavigationConfigInterface;
use Mimmi20\Mezzio\Navigation\ConfigProvider;
use Mimmi20\Mezzio\Navigation\Navigation;
use Mimmi20\Mezzio\Navigation\NavigationMiddleware;
use Mimmi20\Mezzio\Navigation\Service\NavigationAbstractServiceFactory;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    private ConfigProvider $provider;

    /** @throws void */
    protected function setUp(): void
    {
        $this->provider = new ConfigProvider();
    }

    /** @throws Exception */
    public function testProviderDefinesExpectedFactoryServices(): void
    {
        $dependencies = $this->provider->getDependencyConfig();
        self::assertIsArray($dependencies);

        self::assertArrayHasKey('factories', $dependencies);
        $factories = $dependencies['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(Navigation::class, $factories);
        self::assertArrayHasKey(NavigationMiddleware::class, $factories);
        self::assertArrayHasKey(NavigationConfig::class, $factories);

        self::assertArrayHasKey('abstract_factories', $dependencies);
        $abstractFactories = $dependencies['abstract_factories'];
        self::assertIsArray($abstractFactories);
        self::assertContains(NavigationAbstractServiceFactory::class, $abstractFactories);

        self::assertArrayHasKey('aliases', $dependencies);
        $aliases = $dependencies['aliases'];
        self::assertIsArray($aliases);
        self::assertArrayHasKey(NavigationConfigInterface::class, $aliases);
    }

    /** @throws Exception */
    public function testInvocationReturnsArrayWithDependencies(): void
    {
        $config = ($this->provider)();

        self::assertIsArray($config);
        self::assertArrayHasKey('dependencies', $config);

        $dependencies = $config['dependencies'];
        self::assertIsArray($dependencies);

        self::assertArrayHasKey('factories', $dependencies);
        $factories = $dependencies['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(Navigation::class, $factories);
        self::assertArrayHasKey(NavigationMiddleware::class, $factories);
        self::assertArrayHasKey(NavigationConfig::class, $factories);

        self::assertArrayHasKey('abstract_factories', $dependencies);
        $abstractFactories = $dependencies['abstract_factories'];
        self::assertIsArray($abstractFactories);
        self::assertContains(NavigationAbstractServiceFactory::class, $abstractFactories);

        self::assertArrayHasKey('aliases', $dependencies);
        $aliases = $dependencies['aliases'];
        self::assertIsArray($aliases);
        self::assertArrayHasKey(NavigationConfigInterface::class, $aliases);
    }
}
