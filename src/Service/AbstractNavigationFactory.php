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
use Laminas\Config;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Stdlib\ArrayUtils;
use Mezzio\Navigation\Config\NavigationConfigInterface;
use Mezzio\Navigation\Exception;
use Mezzio\Navigation\Navigation;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ServerRequestInterface;
use Traversable;

/**
 * Abstract navigation factory
 */
abstract class AbstractNavigationFactory implements FactoryInterface
{
    /** @var array|null */
    protected $pages;

    /** @var string */
    protected $configName;

    /**
     * Create and return a new Navigation instance (v3).
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Config\Exception\RuntimeException
     * @throws \Laminas\Config\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @return Navigation
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): Navigation
    {
        $config = $container->get(NavigationConfigInterface::class);
        \assert($config instanceof NavigationConfigInterface);

        return new Navigation($this->getPages($config));
    }

    /**
     * @param NavigationConfigInterface $config
     *
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     * @throws \Laminas\Config\Exception\RuntimeException
     * @throws \Laminas\Config\Exception\InvalidArgumentException
     *
     * @return array|null
     */
    protected function getPages(NavigationConfigInterface $config): ?array
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

    /**
     * @param NavigationConfigInterface $config
     * @param array                     $pages
     *
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return array|null
     */
    protected function preparePages(NavigationConfigInterface $config, array $pages): ?array
    {
        if (null === $config->getRouteResult()) {
            $routeResult = null;
        } else {
            $routeResult = $config->getRouteResult();
        }

        return $this->injectComponents(
            $pages,
            $routeResult,
            $config->getRouter(),
            $config->getRequest()
        );
    }

    /**
     * @param array|\Laminas\Config\Config|string|null $config
     *
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     * @throws \Laminas\Config\Exception\RuntimeException
     * @throws \Laminas\Config\Exception\InvalidArgumentException
     *
     * @return array
     */
    protected function getPagesFromConfig($config = null): array
    {
        if (is_string($config)) {
            if (!file_exists($config)) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Config was a string but file "%s" does not exist',
                    $config
                ));
            }

            $config = Config\Factory::fromFile($config);

            if ($config instanceof Config\Config) {
                $config = $config->toArray();
            }
        } elseif ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        } elseif (!is_array($config)) {
            throw new Exception\InvalidArgumentException(
                'Invalid input, expected array, filename, or Traversable object'
            );
        }

        return $config;
    }

    /**
     * @param array                               $pages
     * @param \Mezzio\Router\RouteResult|null     $routeResult
     * @param \Mezzio\Router\RouterInterface|null $router
     * @param ServerRequestInterface|null         $request
     *
     * @return array
     */
    protected function injectComponents(
        array $pages,
        ?RouteResult $routeResult = null,
        ?RouterInterface $router = null,
        ?ServerRequestInterface $request = null
    ) {
        foreach (array_keys($pages) as $pageIndex) {
            $hasUri   = isset($pages[$pageIndex]['uri']);
            $hasRoute = isset($pages[$pageIndex]['route']);

            if ($hasRoute) {
                if (!isset($pages[$pageIndex]['route_match']) && $routeResult) {
                    $pages[$pageIndex]['route_match'] = $routeResult;
                }

                if (!isset($pages[$pageIndex]['router'])) {
                    $pages[$pageIndex]['router'] = $router;
                }
            } elseif ($hasUri) {
                if (!isset($pages[$pageIndex]['request'])) {
                    $pages[$pageIndex]['request'] = $request;
                }
            }

            if (!isset($pages[$pageIndex]['pages'])) {
                continue;
            }

            $pages[$pageIndex]['pages'] = $this->injectComponents($pages[$pageIndex]['pages'], $routeResult, $router, $request);
        }

        return $pages;
    }
}
