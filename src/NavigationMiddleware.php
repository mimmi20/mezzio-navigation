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
namespace Mezzio\Navigation;

use Mezzio\Authorization\AuthorizationInterface;
use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Pipeline middleware for injecting a UrlHelper with a RouteResult.
 */
final class NavigationMiddleware implements MiddlewareInterface
{
    /** @var Config\NavigationConfig */
    private $navigationConfig;

    /** @var UrlHelper */
    private $urlHelper;

    /** @var AuthorizationInterface|null */
    private $authorization;

    /** @var \Mezzio\Router\RouterInterface|null */
    private $router;

    /**
     * @param \Mezzio\Navigation\Config\NavigationConfig        $navigationConfig
     * @param \Mezzio\Helper\UrlHelper                          $urlHelper
     * @param \Mezzio\Authorization\AuthorizationInterface|null $authorization
     * @param ?RouterInterface                                  $router
     */
    public function __construct(
        Config\NavigationConfig $navigationConfig,
        UrlHelper $urlHelper,
        ?AuthorizationInterface $authorization,
        ?RouterInterface $router
    ) {
        $this->navigationConfig = $navigationConfig;
        $this->urlHelper        = $urlHelper;
        $this->authorization    = $authorization;
        $this->router           = $router;
    }

    /**
     * Inject the UrlHelper instance with a RouteResult, if present as a request attribute.
     * Injects the helper, and then dispatches the next middleware.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $result = $request->getAttribute(RouteResult::class, false);

        if ($result instanceof RouteResult) {
            $this->navigationConfig->setRouteResult($result);
        }

        $this->navigationConfig->setUrlHelper($this->urlHelper);
        $this->navigationConfig->setRequest($request);
        $this->navigationConfig->setAuthorization($this->authorization);
        $this->navigationConfig->setRouter($this->router);

        return $handler->handle($request);
    }
}
