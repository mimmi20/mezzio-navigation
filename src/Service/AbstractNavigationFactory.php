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
use Mezzio\Navigation\Exception;
use Mezzio\Navigation\Navigation;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Traversable;

/**
 * Abstract navigation factory
 */
abstract class AbstractNavigationFactory implements FactoryInterface
{
    /** @var array|null */
    protected $pages;

    /**
     * Create and return a new Navigation instance (v3).
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return Navigation
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new Navigation($this->getPages($container));
    }

    /**
     * @return string
     *
     * @abstract
     */
    abstract protected function getName(): string;

    /**
     * @param ContainerInterface $container
     *
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return array|null
     */
    protected function getPages(ContainerInterface $container): ?array
    {
        if (null === $this->pages) {
            $configuration = $container->get('config');

            if (!isset($configuration['navigation'])) {
                throw new Exception\InvalidArgumentException('Could not find navigation configuration key');
            }

            if (!isset($configuration['navigation'][$this->getName()])) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Failed to find a navigation container by the name "%s"',
                    $this->getName()
                ));
            }

            $pages       = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);
            $this->pages = $this->preparePages($container, $pages);
        }

        return $this->pages;
    }

    /**
     * @param ContainerInterface           $container
     * @param array|\Laminas\Config\Config $pages
     *
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return array|null
     */
    protected function preparePages(ContainerInterface $container, $pages): ?array
    {
        $application = $container->get('Application');
        $routeMatch  = $application->getMvcEvent()->getRouteMatch();
        $router      = $application->getMvcEvent()->getRouter();
        $request     = $application->getMvcEvent()->getRequest();

        // HTTP request is the only one that may be injected
        if (!$request instanceof Request) {
            $request = null;
        }

        return $this->injectComponents($pages, $routeMatch, $router, $request);
    }

    /**
     * @param array|\Laminas\Config\Config|string|null $config
     *
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return array|\Laminas\Config\Config|null
     */
    protected function getPagesFromConfig($config = null)
    {
        if (is_string($config)) {
            if (!file_exists($config)) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Config was a string but file "%s" does not exist',
                    $config
                ));
            }

            $config = Config\Factory::fromFile($config);
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
     * @param \Mezzio\Router\RouteResult|null     $routeMatch
     * @param \Mezzio\Router\RouterInterface|null $router
     * @param Request|null                        $request
     *
     * @return array
     */
    protected function injectComponents(
        array $pages,
        ?RouteResult $routeMatch = null,
        ?RouterInterface $router = null,
        ?Request $request = null
    ) {
        foreach ($pages as &$page) {
            $hasUri = isset($page['uri']);
            $hasMvc = isset($page['action']) || isset($page['controller']) || isset($page['route']);
            if ($hasMvc) {
                if (!isset($page['routeMatch']) && $routeMatch) {
                    $page['routeMatch'] = $routeMatch;
                }

                if (!isset($page['router'])) {
                    $page['router'] = $router;
                }
            } elseif ($hasUri) {
                if (!isset($page['request'])) {
                    $page['request'] = $request;
                }
            }

            if (!isset($page['pages'])) {
                continue;
            }

            $page['pages'] = $this->injectComponents($page['pages'], $routeMatch, $router, $request);
        }

        return $pages;
    }
}
