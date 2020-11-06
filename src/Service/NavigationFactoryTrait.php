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

use Mezzio\Navigation\Config\NavigationConfigInterface;
use Mezzio\Navigation\Exception;
use Mezzio\Navigation\Navigation;
use Mezzio\Navigation\Page\PageFactoryInterface;
use Mezzio\Navigation\Page\PageInterface;
use Mezzio\Navigation\Page\RouteInterface;
use Mezzio\Navigation\Page\UriInterface;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * navigation factory trait
 */
trait NavigationFactoryTrait
{
    /** @var array|null */
    private $pages;

    /** @var string */
    private $configName;

    /**
     * Create and return a new Navigation instance (v3).
     *
     * @param ContainerInterface $container
     *
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @return Navigation
     */
    public function __invoke(ContainerInterface $container): Navigation
    {
        $config = $container->get(NavigationConfigInterface::class);
        \assert($config instanceof NavigationConfigInterface);

        $navigation = new Navigation();

        $navigation->setPages($this->getPages($container, $config));

        return $navigation;
    }

    /**
     * @param ContainerInterface        $container
     * @param NavigationConfigInterface $config
     *
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     * @throws \Psr\Container\ContainerExceptionInterface
     *
     * @return PageInterface[]
     */
    private function getPages(ContainerInterface $container, NavigationConfigInterface $config): array
    {
        if (null === $this->pages) {
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

            $this->pages = $this->preparePages(
                $pages[$this->configName],
                $factory,
                $config->getRouteResult(),
                $config->getRouter(),
                $config->getRequest()
            );
        }

        return $this->pages;
    }

    /**
     * @param array[]                             $pages
     * @param PageFactoryInterface                $factory
     * @param \Mezzio\Router\RouteResult|null     $routeResult
     * @param \Mezzio\Router\RouterInterface|null $router
     * @param ServerRequestInterface|null         $request
     *
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return PageInterface[]
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
                $page = $factory->factory($pageConfig);

                if ($page instanceof RouteInterface) {
                    if (null !== $routeResult) {
                        $page->setRouteMatch($routeResult);
                    }

                    $page->setRouter($router);
                } elseif ($page instanceof UriInterface) {
                    $page->setRequest($request);
                }

                if (isset($pageConfig['pages'])) {
                    $page->setPages($this->preparePages($pageConfig['pages'], $factory, $routeResult, $router, $request));
                }

                return $page;
            },
            $pages
        );
    }
}
