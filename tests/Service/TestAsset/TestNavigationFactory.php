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
namespace MezzioTest\Navigation\Service\TestAsset;

use Mezzio\Navigation\Service\AbstractNavigationFactory;

final class TestNavigationFactory extends AbstractNavigationFactory
{
    /** @var string */
    private $factoryName;

    /**
     * @param string $factoryName
     */
    public function __construct($factoryName = 'test')
    {
        $this->factoryName = $factoryName;
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return $this->factoryName;
    }
}
