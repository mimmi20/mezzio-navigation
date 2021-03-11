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
namespace MezzioTest\Navigation;

use Mezzio\Navigation\Config\NavigationConfig;
use Mezzio\Navigation\Config\NavigationConfigInterface;
use Mezzio\Navigation\ConfigProvider;
use Mezzio\Navigation\Navigation;
use Mezzio\Navigation\NavigationMiddleware;
use Mezzio\Navigation\Service\NavigationAbstractServiceFactory;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    /** @var ConfigProvider */
    private $provider;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->provider = new ConfigProvider();
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
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

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
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
