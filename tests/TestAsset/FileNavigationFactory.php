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
namespace MezzioTest\Navigation\TestAsset;

use Laminas\ServiceManager\ServiceLocatorInterface;
use Mezzio\Navigation\Service\AbstractNavigationFactory;

final class FileNavigationFactory extends AbstractNavigationFactory
{
    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'file';
    }

    /**
     * @param \Laminas\ServiceManager\ServiceLocatorInterface $serviceLocator
     *
     * @return void
     */
    public function createService(ServiceLocatorInterface $serviceLocator): void
    {
        // TODO: Implement createService() method.
    }
}
