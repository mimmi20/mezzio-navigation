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

use Mezzio\Navigation\Config\NavigationConfigInterface;
use Mezzio\Navigation\Exception;
use Mezzio\Navigation\Exception\InvalidArgumentException;
use Mezzio\Navigation\Navigation;
use Mezzio\Navigation\Page\PageFactoryInterface;
use Mezzio\Navigation\Page\PageInterface;
use Mezzio\Navigation\Page\RouteInterface;
use Mezzio\Navigation\Page\UriInterface;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

use function array_key_exists;
use function array_map;
use function assert;
use function is_array;
use function sprintf;

/**
 * navigation factory trait
 */
trait NavigationFactoryTrait
{
    private string $configName;

    /**
     * Create and return a new Navigation instance (v3).
     *
     * @throws InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): Navigation
    {
        $config = $container->get(NavigationConfigInterface::class);
        assert($config instanceof NavigationConfigInterface);

        $navigation = new Navigation();

        $navigation->setPages($this->getPages($container, $config));

        return $navigation;
    }

    /**
     * @return array<PageInterface>
     *
     * @throws InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     * @throws ContainerExceptionInterface
     */
    private function getPages(ContainerInterface $container, NavigationConfigInterface $config): array
    {
        $pages   = $config->getPages();
        $factory = $container->get(PageFactoryInterface::class);

        if (
            null === $pages
            || !array_key_exists($this->configName, $pages)
            || !is_array($pages[$this->configName])
        ) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'Failed to find a navigation container by the name "%s"',
                    $this->configName
                )
            );
        }

        return $this->preparePages(
            $pages[$this->configName],
            $factory,
            $config->getRouteResult(),
            $config->getRouter(),
            $config->getRequest()
        );
    }

    /**
     * @param array<string, array<mixed>> $pages
     *
     * @return array<PageInterface>
     *
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     * @throws InvalidArgumentException
     */
    private function preparePages(
        array $pages,
        PageFactoryInterface $factory,
        ?RouteResult $routeResult = null,
        ?RouterInterface $router = null,
        ?ServerRequestInterface $request = null
    ): array {
        return array_map(
            function (array $pageConfig) use ($factory, $routeResult, $router, $request): PageInterface {
                $subPages = null;

                if (array_key_exists('pages', $pageConfig) && is_array($pageConfig['pages'])) {
                    $subPages = $this->preparePages($pageConfig['pages'], $factory, $routeResult, $router, $request);
                }

                unset($pageConfig['pages']);

                $page = $factory->factory($pageConfig);

                if ($page instanceof RouteInterface) {
                    if (null !== $routeResult) {
                        $page->setRouteMatch($routeResult);
                    }

                    $page->setRouter($router);
                } elseif ($page instanceof UriInterface) {
                    $page->setRequest($request);
                }

                if (null !== $subPages) {
                    $page->setPages($subPages);
                }

                return $page;
            },
            $pages
        );
    }
}
