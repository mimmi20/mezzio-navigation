<?php

/**
 * This file is part of the mimmi20/mezzio-navigation package.
 *
 * Copyright (c) 2020-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\MezzioTest\Navigation\Config;

use Mezzio\Helper\UrlHelper;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Mimmi20\Mezzio\GenericAuthorization\AuthorizationInterface;
use Mimmi20\Mezzio\Navigation\Config\NavigationConfig;
use Override;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

use function assert;

final class NavigationConfigTest extends TestCase
{
    private NavigationConfig $config;

    /** @throws void */
    #[Override]
    protected function setUp(): void
    {
        $this->config = new NavigationConfig();
    }

    /** @throws Exception */
    public function testSetUrlHelper(): void
    {
        self::assertNull($this->config->getUrlHelper());

        $helper = $this->createMock(UrlHelper::class);

        assert($helper instanceof UrlHelper);
        $this->config->setUrlHelper($helper);

        self::assertSame($helper, $this->config->getUrlHelper());
    }

    /** @throws Exception */
    public function testSetRouteResult(): void
    {
        self::assertNull($this->config->getRouteResult());

        $routeResult = $this->createMock(RouteResult::class);

        assert($routeResult instanceof RouteResult);
        $this->config->setRouteResult($routeResult);

        self::assertSame($routeResult, $this->config->getRouteResult());
    }

    /** @throws Exception */
    public function testSetRouter(): void
    {
        self::assertNull($this->config->getRouter());

        $router = $this->createMock(RouterInterface::class);

        assert($router instanceof RouterInterface);
        $this->config->setRouter($router);

        self::assertSame($router, $this->config->getRouter());
    }

    /** @throws Exception */
    public function testSetRequest(): void
    {
        self::assertNull($this->config->getRequest());

        $request = $this->createMock(ServerRequestInterface::class);

        assert($request instanceof ServerRequestInterface);
        $this->config->setRequest($request);

        self::assertSame($request, $this->config->getRequest());
    }

    /** @throws Exception */
    public function testSetAuthorization(): void
    {
        self::assertNull($this->config->getAuthorization());

        $authorization = $this->createMock(AuthorizationInterface::class);

        assert($authorization instanceof AuthorizationInterface);
        $this->config->setAuthorization($authorization);

        self::assertSame($authorization, $this->config->getAuthorization());
    }

    /** @throws Exception */
    public function testSetPages(): void
    {
        self::assertNull($this->config->getPages());

        $pages = [['test' => 'test']];

        $this->config->setPages($pages);

        self::assertSame($pages, $this->config->getPages());
    }
}
