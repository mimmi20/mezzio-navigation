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
namespace MezzioTest\Navigation\Service;

use PHPUnit\Framework\TestCase;

/**
 * @todo Write tests covering full functionality. Tests were introduced to
 *     resolve zendframework/zend-navigation#37, and cover one specific
 *     method to ensure argument validation works correctly.
 */
final class AbstractNavigationFactoryTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->factory = new TestAsset\TestNavigationFactory();
    }

    /**
     * @return void
     */
    public function testCanInjectComponentsUsingLaminasRouterClasses(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testCanInjectComponentsUsingLaminasMvcRouterClasses(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testCanCreateNavigationInstanceV2(): void
    {
        self::markTestSkipped();
    }
}
