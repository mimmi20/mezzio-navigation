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

use Interop\Container\ContainerInterface;

/**
 * Constructed factory to set pages during construction.
 */
final class ConstructedNavigationFactory extends AbstractNavigationFactory
{
    /** @var array|\Laminas\Config\Config|string */
    protected $config;

    /**
     * @param array|\Laminas\Config\Config|string $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param ContainerInterface $container
     *
     * @return array|null
     */
    public function getPages(ContainerInterface $container): ?array
    {
        if (null === $this->pages) {
            $this->pages = $this->preparePages($container, $this->getPagesFromConfig($this->config));
        }

        return $this->pages;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'constructed';
    }
}
