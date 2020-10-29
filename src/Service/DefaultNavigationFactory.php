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

/**
 * Default navigation factory.
 */
final class DefaultNavigationFactory
{
    use NavigationFactoryTrait;

    /**
     * creates the DefaultNavigationFactory.
     */
    public function __construct()
    {
        $this->configName = 'default';
    }
}
