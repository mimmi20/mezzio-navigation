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

namespace Mezzio\Navigation\Service;

/**
 * Constructed factory to set pages during construction.
 */
final class ConstructedNavigationFactory
{
    use NavigationFactoryTrait;

    public function __construct(string $configName)
    {
        $this->configName = $configName;
    }
}
