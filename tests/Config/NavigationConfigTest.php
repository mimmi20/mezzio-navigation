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
namespace MezzioTest\Navigation\Config;

use Mezzio\GenericAuthorization\AuthorizationInterface;
use Mezzio\Helper\UrlHelper;
use Mezzio\Navigation\Config\NavigationConfig;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

final class NavigationConfigTest extends TestCase
{
    /** @var NavigationConfig */
    private $config;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->config = new NavigationConfig();
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testSetUrlHelper(): void
    {
        self::assertNull($this->config->getUrlHelper());

        $helper = $this->createMock(UrlHelper::class);

        /* @var UrlHelper $helper */
        $this->config->setUrlHelper($helper);

        self::assertSame($helper, $this->config->getUrlHelper());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testSetRouteResult(): void
    {
        self::assertNull($this->config->getRouteResult());

        $routeResult = $this->createMock(RouteResult::class);

        /* @var RouteResult $routeResult */
        $this->config->setRouteResult($routeResult);

        self::assertSame($routeResult, $this->config->getRouteResult());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testSetRouter(): void
    {
        self::assertNull($this->config->getRouter());

        $router = $this->createMock(RouterInterface::class);

        /* @var RouterInterface $router */
        $this->config->setRouter($router);

        self::assertSame($router, $this->config->getRouter());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testSetRequest(): void
    {
        self::assertNull($this->config->getRequest());

        $request = $this->createMock(ServerRequestInterface::class);

        /* @var ServerRequestInterface $request */
        $this->config->setRequest($request);

        self::assertSame($request, $this->config->getRequest());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testSetAuthorization(): void
    {
        self::assertNull($this->config->getAuthorization());

        $authorization = $this->createMock(AuthorizationInterface::class);

        /* @var AuthorizationInterface $authorization */
        $this->config->setAuthorization($authorization);

        self::assertSame($authorization, $this->config->getAuthorization());
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @return void
     */
    public function testSetPages(): void
    {
        self::assertNull($this->config->getPages());

        $pages = [['test' => 'test']];

        $this->config->setPages($pages);

        self::assertSame($pages, $this->config->getPages());
    }
}
