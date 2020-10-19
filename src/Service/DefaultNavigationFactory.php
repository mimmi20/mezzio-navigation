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

use Mezzio\Navigation\Config\NavigationConfig;
use Mezzio\Navigation\Exception;

/**
 * Default navigation factory.
 */
final class DefaultNavigationFactory extends AbstractNavigationFactory
{
    /**
     * @return string
     */
    private function getName(): string
    {
        return 'default';
    }

    /**
     * @param NavigationConfig $config
     *
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     * @throws \Laminas\Config\Exception\RuntimeException
     * @throws \Laminas\Config\Exception\InvalidArgumentException
     *
     * @return array|null
     */
    protected function getPages(NavigationConfig $config): ?array
    {
        if (null === $this->pages) {
            $pages = $config->getPages();

            if (!array_key_exists($this->getName(), $pages) || !is_array($pages[$this->getName()])) {
                throw new Exception\InvalidArgumentException(
                    sprintf(
                        'Failed to find a navigation container by the name "%s"',
                        $this->getName()
                    )
                );
            }

            $this->pages = $this->preparePages(
                $config,
                $this->getPagesFromConfig($pages[$this->getName()])
            );
        }

        return $this->pages;
    }
}
