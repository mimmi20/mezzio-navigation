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
namespace MezzioTest\Navigation\Service;

use Laminas\Mvc\Application;
use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Router as MvcRouter;
use Laminas\Router;
use Laminas\ServiceManager\ServiceManager;
use Mezzio\Navigation\Exception;
use Mezzio\Navigation\Navigation;
use Mezzio\Navigation\Service\AbstractNavigationFactory;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

/**
 * @todo Write tests covering full functionality. Tests were introduced to
 *     resolve zendframework/zend-navigation#37, and cover one specific
 *     method to ensure argument validation works correctly.
 */
final class AbstractNavigationFactoryTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->factory = new TestAsset\TestNavigationFactory();
    }

    /**
     * @return void
     */
    public function testCanInjectComponentsUsingLaminasRouterClasses(): void
    {
        $routeMatch = $this->prophesize(Router\RouteMatch::class)->reveal();
        $router     = $this->prophesize(Router\RouteStackInterface::class)->reveal();
        $args       = [[], $routeMatch, $router];

        $r = new ReflectionMethod($this->factory, 'injectComponents');
        $r->setAccessible(true);
        try {
            $pages = $r->invokeArgs($this->factory, $args);
        } catch (Exception\InvalidArgumentException $e) {
            $message = sprintf(
                'injectComponents should not raise exception for laminas-router classes; received %s',
                $e->getMessage()
            );
            self::fail($message);
        }

        self::assertSame([], $pages);
    }

    /**
     * @return void
     */
    public function testCanInjectComponentsUsingLaminasMvcRouterClasses(): void
    {
        if (!class_exists(MvcRouter\RouteMatch::class)) {
            self::markTestSkipped('Test does not run for laminas-mvc v3 releases');
        }

        $routeMatch = $this->prophesize(MvcRouter\RouteMatch::class)->reveal();
        $router     = $this->prophesize(MvcRouter\RouteStackInterface::class)->reveal();
        $args       = [[], $routeMatch, $router];

        $r = new ReflectionMethod($this->factory, 'injectComponents');
        $r->setAccessible(true);
        try {
            $pages = $r->invokeArgs($this->factory, $args);
        } catch (Exception\InvalidArgumentException $e) {
            $message = sprintf(
                'injectComponents should not raise exception for laminas-mvc router classes; received %s',
                $e->getMessage()
            );
            self::fail($message);
        }

        self::assertSame([], $pages);
    }

    /**
     * @return void
     */
    public function testCanCreateNavigationInstanceV2(): void
    {
        $routerMatchClass = $this->getRouteMatchClass();
        $routerClass      = $this->getRouterClass();
        $routeMatch       = new $routerMatchClass([]);
        $router           = new $routerClass();

        $mvcEventStub = new MvcEvent();
        $mvcEventStub->setRouteMatch($routeMatch);
        $mvcEventStub->setRouter($router);

        $applicationMock = $this->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->getMock();

        $applicationMock->expects(self::any())
            ->method('getMvcEvent')
            ->willReturn($mvcEventStub);

        $serviceManagerMock = $this->getMockBuilder(ServiceManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $serviceManagerMock->expects(self::any())
            ->method('get')
            ->willReturnMap([
                ['config', ['navigation' => ['testStubNavigation' => []]]],
                ['Application', $applicationMock],
            ]);

        $navigationFactory
            = $this->getMockForAbstractClass(AbstractNavigationFactory::class);
        $navigationFactory->expects(self::any())
            ->method('getName')
            ->willReturn('testStubNavigation');
        $navigation = $navigationFactory->createService($serviceManagerMock);

        self::assertInstanceOf(Navigation::class, $navigation);
    }

    public function getRouterClass()
    {
        return class_exists(MvcRouter\Http\TreeRouteStack::class)
            ? MvcRouter\Http\TreeRouteStack::class
            : Router\Http\TreeRouteStack::class;
    }

    public function getRouteMatchClass()
    {
        return class_exists(MvcRouter\RouteMatch::class)
            ? MvcRouter\RouteMatch::class
            : Router\RouteMatch::class;
    }
}
