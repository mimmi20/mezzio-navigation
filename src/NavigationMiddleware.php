<?php
/**
 * This file is part of the mimmi20/mezzio-navigation package.
 *
 * Copyright (c) 2020-2024, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Mezzio\Navigation;

use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Mimmi20\Mezzio\GenericAuthorization\AuthorizationInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Pipeline middleware for injecting a UrlHelper with a RouteResult.
 */
final class NavigationMiddleware implements MiddlewareInterface
{
    /** @throws void */
    public function __construct(
        private readonly Config\NavigationConfigInterface $navigationConfig,
        private readonly UrlHelper $urlHelper,
        private readonly AuthorizationInterface | null $authorization = null,
        private readonly RouterInterface | null $router = null,
    ) {
        // nothing to do
    }

    /**
     * Inject the UrlHelper instance with a RouteResult, if present as a request attribute.
     * Injects the helper, and then dispatches the next middleware.
     *
     * @throws void
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $result = $request->getAttribute(RouteResult::class);

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
