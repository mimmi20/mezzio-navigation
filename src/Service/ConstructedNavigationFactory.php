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
 * Constructed factory to set pages during construction.
 */
final class ConstructedNavigationFactory extends AbstractNavigationFactory
{
    /** @var string */
    protected $configName;

    /**
     * @param string $configName
     */
    public function __construct(string $configName)
    {
        $this->configName = $configName;
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
    public function getPages(NavigationConfig $config): ?array
    {
        if (null === $this->pages) {
            $pages = $config->getPages();

            if (!array_key_exists($this->configName, $pages) || !is_array($pages[$this->configName])) {
                throw new Exception\InvalidArgumentException(
                    sprintf(
                        'Failed to find a navigation container by the name "%s"',
                        $this->configName
                    )
                );
            }

            $this->pages = $this->preparePages(
                $config,
                $this->getPagesFromConfig($pages[$this->configName])
            );
        }

        return $this->pages;
    }
}
