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
namespace MezzioTest\Navigation\Page;

use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Router as MvcRouter;
use Laminas\Router\Http\Literal as LiteralRoute;
use Laminas\Router\Http\Regex as RegexRoute;
use Laminas\Router\Http\Segment as SegmentRoute;
use Laminas\Router\Http\TreeRouteStack;
use Laminas\Router\RouteMatch;
use LaminasTest\Navigation\TestAsset;
use Mezzio\Navigation;
use Mezzio\Navigation\Exception;
use Mezzio\Navigation\Page;
use PHPUnit\Framework\TestCase;

/**
 * Tests the class Laminas_Navigation_Page_Mvc
 *
 * @group      Laminas_Navigation
 */
final class MvcTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        $routeClass  = $this->getRouteClass();
        $this->route = new $routeClass(
            '((?<controller>[^/]+)(/(?<action>[^/]+))?)',
            '/%controller%/%action%',
            [
                'controller' => 'index',
                'action' => 'index',
            ]
        );

        $routerClass  = $this->getRouterClass();
        $this->router = new $routerClass();
        $this->router->addRoute('default', $this->route);

        $routeMatchClass  = $this->getRouteMatchClass();
        $this->routeMatch = new $routeMatchClass([]);
        $this->routeMatch->setMatchedRouteName('default');
    }

    public function getRouteClass(string $type = 'Regex')
    {
        $v2ClassName = sprintf('Laminas\Mvc\Router\Http\%s', $type);
        $v3ClassName = sprintf('Laminas\Router\Http\%s', $type);

        return class_exists($v2ClassName)
            ? $v2ClassName
            : $v3ClassName;
    }

    public function getRouterClass()
    {
        return class_exists(MvcRouter\Http\TreeRouteStack::class)
            ? MvcRouter\Http\TreeRouteStack::class
            : TreeRouteStack::class;
    }

    public function getRouteMatchClass()
    {
        return class_exists(MvcRouter\RouteMatch::class)
            ? MvcRouter\RouteMatch::class
            : RouteMatch::class;
    }

    /**
     * @return void
     */
    public function testHrefGeneratedByRouterWithDefaultRoute(): void
    {
        $page = new Page\Mvc([
            'label' => 'foo',
            'action' => 'index',
            'controller' => 'index',
        ]);
        Page\Mvc::setDefaultRoute('default');
        $page->setRouter($this->router);
        $page->setAction('view');
        $page->setController('news');

        self::assertEquals('/news/view', $page->getHref());
    }

    /**
     * @return void
     */
    public function testHrefGeneratedByRouterRequiresNoRoute(): void
    {
        $page = new Page\Mvc([
            'label' => 'foo',
            'action' => 'index',
            'controller' => 'index',
        ]);
        $page->setRouteMatch($this->routeMatch);
        $page->setRouter($this->router);
        $page->setAction('view');
        $page->setController('news');

        self::assertEquals('/news/view', $page->getHref());
    }

    /**
     * @return void
     */
    public function testHrefRouteMatchEnabledWithoutRouteMatchObject(): void
    {
        $page = new Page\Mvc([
            'label' => 'foo',
            'route' => 'test/route',
            'use_route_match' => true,
        ]);
        $router = $this->createMock(TreeRouteStack::class);
        $router->expects(self::once())->method('assemble')->willReturn('/test/route');
        $page->setRouter($router);
        self::assertEquals('/test/route', $page->getHref());
    }

    /**
     * @return void
     */
    public function testHrefGeneratedIsRouteAware(): void
    {
        $page = new Page\Mvc([
            'label' => 'foo',
            'action' => 'myaction',
            'controller' => 'mycontroller',
            'route' => 'myroute',
            'params' => ['page' => 1337],
        ]);

        $route = new RegexRoute(
            '(lolcat/(?<action>[^/]+)/(?<page>\d+))',
            '/lolcat/%action%/%page%',
            [
                'controller' => 'foobar',
                'action' => 'bazbat',
                'page' => 1,
            ]
        );
        $router = new TreeRouteStack();
        $router->addRoute('myroute', $route);

        $routeMatch = new RouteMatch([
            'controller' => 'foobar',
            'action' => 'bazbat',
            'page' => 1,
        ]);

        $page->setRouter($router);
        $page->setRouteMatch($routeMatch);

        self::assertEquals('/lolcat/myaction/1337', $page->getHref());
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsTrueWhenMatchingRoute(): void
    {
        $page = new Page\Mvc([
            'label' => 'spiffyjrwashere',
            'route' => 'lolfish',
        ]);

        $route = new LiteralRoute('/lolfish');

        $router = new TreeRouteStack();
        $router->addRoute('lolfish', $route);

        $routeMatch = new RouteMatch([]);
        $routeMatch->setMatchedRouteName('lolfish');

        $page->setRouter($router);
        $page->setRouteMatch($routeMatch);

        self::assertTrue($page->isActive());
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsTrueWhenMatchingRouteWhileUsingModuleRouteListener(): void
    {
        $page = new Page\Mvc([
            'label' => 'mpinkstonwashere',
            'route' => 'roflcopter',
            'controller' => 'index',
        ]);

        $routeClass = $this->getRouteClass('Literal');
        $route      = new $routeClass('/roflcopter');

        $routerClass = $this->getRouterClass();
        $router      = new $routerClass();
        $router->addRoute('roflcopter', $route);

        $routeMatchClass = $this->getRouteMatchClass();
        $routeMatch      = new $routeMatchClass([
            ModuleRouteListener::MODULE_NAMESPACE => 'Application\Controller',
            'controller' => 'index',
        ]);
        $routeMatch->setMatchedRouteName('roflcopter');

        $event = new MvcEvent();
        $event->setRouter($router)
            ->setRouteMatch($routeMatch);

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->onRoute($event);

        $page->setRouter($event->getRouter());
        $page->setRouteMatch($event->getRouteMatch());

        self::assertTrue($page->isActive());
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsFalseWhenMatchingRouteButNonMatchingParams(): void
    {
        $page = new Page\Mvc([
            'label' => 'foo',
            'route' => 'bar',
            'action' => 'baz',
        ]);
        $routeMatch = new RouteMatch([]);
        $routeMatch->setMatchedRouteName('bar');
        $routeMatch->setParam('action', 'qux');
        $page->setRouteMatch($routeMatch);

        self::assertFalse($page->isActive());
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsFalseWhenNoRouteAndNoMatchedRouteNameIsSet(): void
    {
        $page = new Page\Mvc();

        $routeMatch = new RouteMatch([]);
        $page->setRouteMatch($routeMatch);

        self::assertFalse($page->isActive());
    }

    /**
     * @return void
     */
    public function testGetHrefWithFragmentIdentifier(): void
    {
        $page = new Page\Mvc([
            'label' => 'foo',
            'fragment' => 'qux',
            'controller' => 'mycontroller',
            'action' => 'myaction',
            'route' => 'myroute',
            'params' => ['page' => 1337],
        ]);

        $routeClass = $this->getRouteClass();
        $route      = new $routeClass(
            '(lolcat/(?<action>[^/]+)/(?<page>\d+))',
            '/lolcat/%action%/%page%',
            [
                'controller' => 'foobar',
                'action' => 'bazbat',
                'page' => 1,
            ]
        );
        $this->router->addRoute('myroute', $route);
        $this->routeMatch->setMatchedRouteName('myroute');

        $page->setRouteMatch($this->routeMatch);
        $page->setRouter($this->router);

        self::assertEquals('/lolcat/myaction/1337#qux', $page->getHref());
    }

    /**
     * @return void
     */
    public function testGetHrefPassesQueryPartToRouter(): void
    {
        $page = new Page\Mvc([
            'label' => 'foo',
            'query' => 'foo=bar&baz=qux',
            'controller' => 'mycontroller',
            'action' => 'myaction',
            'route' => 'myroute',
            'params' => ['page' => 1337],
        ]);

        $routeClass = $this->getRouteClass();
        $route      = new $routeClass(
            '(lolcat/(?<action>[^/]+)/(?<page>\d+))',
            '/lolcat/%action%/%page%',
            [
                'controller' => 'foobar',
                'action' => 'bazbat',
                'page' => 1,
            ]
        );
        $this->router->addRoute('myroute', $route);
        $this->routeMatch->setMatchedRouteName('myroute');

        $page->setRouteMatch($this->routeMatch);
        $page->setRouter($this->router);

        self::assertEquals('/lolcat/myaction/1337?foo=bar&baz=qux', $page->getHref());

        // Test with array notation
        $page->setQuery([
            'foo' => 'bar',
            'baz' => 'qux',
        ]);
        self::assertEquals('/lolcat/myaction/1337?foo=bar&baz=qux', $page->getHref());
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsTrueOnIdenticalControllerAction(): void
    {
        $page = new Page\Mvc([
            'action' => 'index',
            'controller' => 'index',
        ]);

        $routeMatch = new RouteMatch([
            'controller' => 'index',
            'action' => 'index',
        ]);

        $page->setRouteMatch($routeMatch);

        self::assertTrue($page->isActive());
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsFalseOnDifferentControllerAction(): void
    {
        $page = new Page\Mvc([
            'action' => 'bar',
            'controller' => 'index',
        ]);

        $routeMatch = new RouteMatch([
            'controller' => 'index',
            'action' => 'index',
        ]);

        $page->setRouteMatch($routeMatch);

        self::assertFalse($page->isActive());
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsTrueOnIdenticalIncludingPageParams(): void
    {
        $page = new Page\Mvc([
            'label' => 'foo',
            'action' => 'view',
            'controller' => 'post',
            'params' => ['id' => '1337'],
        ]);

        $routeMatch = new RouteMatch([
            'controller' => 'post',
            'action' => 'view',
            'id' => '1337',
        ]);

        $page->setRouteMatch($routeMatch);

        self::assertTrue($page->isActive());
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsTrueWhenRequestHasMoreParams(): void
    {
        $page = new Page\Mvc([
            'label' => 'foo',
            'action' => 'view',
            'controller' => 'post',
        ]);

        $routeMatch = new RouteMatch([
            'controller' => 'post',
            'action' => 'view',
            'id' => '1337',
        ]);

        $page->setRouteMatch($routeMatch);

        self::assertTrue($page->isActive());
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsFalseWhenRequestHasLessParams(): void
    {
        $page = new Page\Mvc([
            'label' => 'foo',
            'action' => 'view',
            'controller' => 'post',
            'params' => ['id' => '1337'],
        ]);

        $routeMatch = new RouteMatch([
            'controller' => 'post',
            'action' => 'view',
            'id' => null,
        ]);

        $page->setRouteMatch($routeMatch);

        self::assertFalse($page->isActive());
    }

    /**
     * @return void
     */
    public function testActionAndControllerAccessors(): void
    {
        $page = new Page\Mvc([
            'label' => 'foo',
            'action' => 'index',
            'controller' => 'index',
        ]);

        $props    = ['Action', 'Controller'];
        $valids   = ['index', 'help', 'home', 'default', '1', ' ', '', null];
        $invalids = [42, (object) null];

        foreach ($props as $prop) {
            $setter = "set{$prop}";
            $getter = "get{$prop}";

            foreach ($valids as $valid) {
                $page->{$setter}($valid);
                self::assertEquals($valid, $page->{$getter}());
            }

            foreach ($invalids as $invalid) {
                try {
                    $page->{$setter}($invalid);
                    $msg = "'{$invalid}' is invalid for {$setter}(), but no ";
                    $msg .= 'Mezzio\Navigation\Exception\InvalidArgumentException was thrown';
                    self::fail($msg);
                } catch (Navigation\Exception\InvalidArgumentException $e) {
                }
            }
        }
    }

    /**
     * @return void
     */
    public function testRouteAccessor(): void
    {
        $page = new Page\Mvc([
            'label' => 'foo',
            'action' => 'index',
            'controller' => 'index',
        ]);

        $props    = ['Route'];
        $valids   = ['index', 'help', 'home', 'default', '1', ' ', null];
        $invalids = [42, (object) null];

        foreach ($props as $prop) {
            $setter = "set{$prop}";
            $getter = "get{$prop}";

            foreach ($valids as $valid) {
                $page->{$setter}($valid);
                self::assertEquals($valid, $page->{$getter}());
            }

            foreach ($invalids as $invalid) {
                try {
                    $page->{$setter}($invalid);
                    $msg = "'{$invalid}' is invalid for {$setter}(), but no ";
                    $msg .= 'Mezzio\Navigation\Exception\InvalidArgumentException was thrown';
                    self::fail($msg);
                } catch (Navigation\Exception\InvalidArgumentException $e) {
                }
            }
        }
    }

    /**
     * @return void
     */
    public function testSetAndGetParams(): void
    {
        $page = new Page\Mvc([
            'label' => 'foo',
            'action' => 'index',
            'controller' => 'index',
        ]);

        $params = ['foo' => 'bar', 'baz' => 'bat'];

        $page->setParams($params);
        self::assertEquals($params, $page->getParams());

        $page->setParams();
        self::assertEquals([], $page->getParams());

        $page->setParams($params);
        self::assertEquals($params, $page->getParams());

        $page->setParams([]);
        self::assertEquals([], $page->getParams());
    }

    /**
     * @return void
     */
    public function testToArrayMethod(): void
    {
        $options = [
            'label' => 'foo',
            'action' => 'index',
            'controller' => 'index',
            'fragment' => 'bar',
            'id' => 'my-id',
            'class' => 'my-class',
            'title' => 'my-title',
            'target' => 'my-target',
            'order' => 100,
            'active' => true,
            'visible' => false,
            'foo' => 'bar',
            'meaning' => 42,
            'router' => $this->router,
            'route_match' => $this->routeMatch,
        ];

        $page = new Page\Mvc($options);

        $toArray = $page->toArray();

        $options['route']  = null;
        $options['params'] = [];
        $options['rel']    = [];
        $options['rev']    = [];

        $options['privilege']  = null;
        $options['resource']   = null;
        $options['permission'] = null;
        $options['pages']      = [];
        $options['type']       = 'Mezzio\Navigation\Page\Mvc';

        ksort($options);
        ksort($toArray);
        self::assertEquals($options, $toArray);
    }

    /**
     * @return void
     */
    public function testSpecifyingAnotherUrlHelperToGenerateHrefs(): void
    {
        $newRouter = new TestAsset\Router();

        $page = new Page\Mvc(['route' => 'default']);
        $page->setRouter($newRouter);

        $expected = TestAsset\Router::RETURN_URL;
        $actual   = $page->getHref();

        self::assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function testDefaultRouterCanBeSetWithConstructor(): void
    {
        $page = new Page\Mvc([
            'label' => 'foo',
            'action' => 'index',
            'controller' => 'index',
            'defaultRouter' => $this->router,
        ]);

        self::assertEquals($this->router, $page->getDefaultRouter());
        $page->setDefaultRouter(null);
    }

    /**
     * @return void
     */
    public function testDefaultRouterCanBeSetWithGetter(): void
    {
        $page = new Page\Mvc([
            'label' => 'foo',
            'action' => 'index',
            'controller' => 'index',
        ]);
        $page->setDefaultRouter($this->router);

        self::assertEquals($this->router, $page->getDefaultRouter());
        $page->setDefaultRouter(null);
    }

    /**
     * @return void
     */
    public function testNoExceptionForGetHrefIfDefaultRouterIsSet(): void
    {
        $page = new Page\Mvc([
            'label' => 'foo',
            'action' => 'index',
            'controller' => 'index',
            'route' => 'default',
            'defaultRouter' => $this->router,
        ]);

        // If the default router is not used an exception will be thrown.
        // This method intentionally has no assertion.
        self::assertNotEmpty($page->getHref());
        $page->setDefaultRouter(null);
    }

    /**
     * @return void
     */
    public function testBoolSetAndGetUseRouteMatch(): void
    {
        $page = new Page\Mvc(['useRouteMatch' => 2]);
        self::assertTrue($page->useRouteMatch());

        $page->setUseRouteMatch(null);
        self::assertFalse($page->useRouteMatch());

        $page->setUseRouteMatch(false);
        self::assertFalse($page->useRouteMatch());

        $page->setUseRouteMatch(true);
        self::assertTrue($page->useRouteMatch());

        $page->setUseRouteMatch();
        self::assertTrue($page->useRouteMatch());
    }

    /**
     * @return void
     */
    public function testMvcPageParamsInheritRouteMatchParams(): void
    {
        $page = new Page\Mvc([
            'label' => 'lollerblades',
            'route' => 'lollerblades',
        ]);

        $route = new SegmentRoute('/lollerblades/view[/:serialNumber]');

        $router = new TreeRouteStack();
        $router->addRoute('lollerblades', $route);

        $routeMatch = new RouteMatch(['serialNumber' => 23]);
        $routeMatch->setMatchedRouteName('lollerblades');

        $page->setRouter($router);
        $page->setRouteMatch($routeMatch);

        self::assertEquals('/lollerblades/view', $page->getHref());

        $page->setUseRouteMatch(true);
        self::assertEquals('/lollerblades/view/23', $page->getHref());
    }

    /**
     * @return void
     */
    public function testInheritedRouteMatchParamsWorkWithModuleRouteListener(): void
    {
        $page = new Page\Mvc([
            'label' => 'mpinkstonwashere',
            'route' => 'lmaoplane',
        ]);

        $routeClass = $this->getRouteClass('Segment');
        $route      = new $routeClass('/lmaoplane[/:controller]');

        $routerClass = $this->getRouterClass();
        $router      = new $routerClass();
        $router->addRoute('lmaoplane', $route);

        $routeMatchClass = $this->getRouteMatchClass();
        $routeMatch      = new $routeMatchClass([
            ModuleRouteListener::MODULE_NAMESPACE => 'Application\Controller',
            'controller' => 'index',
        ]);
        $routeMatch->setMatchedRouteName('lmaoplane');

        $event = new MvcEvent();
        $event->setRouter($router)
            ->setRouteMatch($routeMatch);

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->onRoute($event);

        $page->setRouter($event->getRouter());
        $page->setRouteMatch($event->getRouteMatch());

        self::assertEquals('/lmaoplane', $page->getHref());

        $page->setUseRouteMatch(true);
        self::assertEquals('/lmaoplane/index', $page->getHref());
    }

    /**
     * @return void
     */
    public function testMistakeDetectIsActiveOnIndexController(): void
    {
        $page = new Page\Mvc(
            [
                'label' => 'some Label',
                'route' => 'myRoute',
            ]
        );

        $routeClass = $this->getRouteClass('Literal');
        $route      = new $routeClass('/foo');

        $routerClass = $this->getRouterClass();
        $router      = new $routerClass();
        $router->addRoute('myRoute', $route);

        $routeMatchClass = $this->getRouteMatchClass();
        $routeMatch      = new $routeMatchClass(
            [
                ModuleRouteListener::MODULE_NAMESPACE => 'Application\Controller',
                'controller' => 'index',
                'action' => 'index',
            ]
        );
        $routeMatch->setMatchedRouteName('index');

        $event = new MvcEvent();
        $event->setRouter($router)
            ->setRouteMatch($routeMatch);

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->onRoute($event);

        $page->setRouter($event->getRouter());
        $page->setRouteMatch($event->getRouteMatch());

        self::assertFalse($page->isActive());
    }

    /**
     * @return void
     */
    public function testRecursiveDetectIsActiveWhenRouteNameIsKnown(): void
    {
        $parentPage = new Page\Mvc(
            [
                'label' => 'some Label',
                'route' => 'parentPageRoute',
            ]
        );
        $childPage = new Page\Mvc(
            [
                'label' => 'child',
                'route' => 'childPageRoute',
            ]
        );
        $parentPage->addPage($childPage);

        $routerClass = $this->getRouterClass();
        $router      = new $routerClass();
        $router->addRoutes(
            [
                'parentPageRoute' => [
                    'type' => 'literal',
                    'options' => [
                        'route' => '/foo',
                        'defaults' => [
                            'controller' => 'fooController',
                            'action' => 'fooAction',
                        ],
                    ],
                ],
                'childPageRoute' => [
                    'type' => 'literal',
                    'options' => [
                        'route' => '/bar',
                        'defaults' => [
                            'controller' => 'barController',
                            'action' => 'barAction',
                        ],
                    ],
                ],
            ]
        );

        $routeMatchClass = $this->getRouteMatchClass();
        $routeMatch      = new $routeMatchClass(
            [
                ModuleRouteListener::MODULE_NAMESPACE => 'Application\Controller',
                'controller' => 'barController',
                'action' => 'barAction',
            ]
        );
        $routeMatch->setMatchedRouteName('childPageRoute');

        $event = new MvcEvent();
        $event->setRouter($router)
            ->setRouteMatch($routeMatch);

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->onRoute($event);

        $parentPage->setRouter($event->getRouter());
        $parentPage->setRouteMatch($event->getRouteMatch());

        $childPage->setRouter($event->getRouter());
        $childPage->setRouteMatch($event->getRouteMatch());

        self::assertTrue($childPage->isActive(true));
        self::assertTrue($parentPage->isActive(true));
    }

    /**
     * @return void
     */
    public function testSetRouteMatchThrowsExceptionOnInvalidParameter(): void
    {
        $this->expectException(Exception\InvalidArgumentException::class);

        $page = new Page\Mvc();
        $page->setRouter($this->getRouterClass());
        $page->setRouteMatch(null);
    }
}
