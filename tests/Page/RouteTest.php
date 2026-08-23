<?php

/**
 * This file is part of the mimmi20/mezzio-navigation package.
 *
 * Copyright (c) 2020-2026, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\MezzioTest\Navigation\Page;

use Mezzio\Router\Exception\RuntimeException;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use Mimmi20\Mezzio\Navigation\ContainerInterface;
use Mimmi20\Mezzio\Navigation\Exception\BadMethodCallException;
use Mimmi20\Mezzio\Navigation\Exception\DomainException;
use Mimmi20\Mezzio\Navigation\Exception\InvalidArgumentException;
use Mimmi20\Mezzio\Navigation\Exception\OutOfBoundsException;
use Mimmi20\Mezzio\Navigation\Page\PageInterface;
use Mimmi20\Mezzio\Navigation\Page\Route;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function assert;
use function spl_object_hash;

final class RouteTest extends TestCase
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorWithoutParameters(): void
    {
        $route = new Route();

        self::assertSame([], $route->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorWithRoute(): void
    {
        $route = 'test';

        $page = new Route(['route' => $route]);

        self::assertSame($route, $page->getRoute());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithRoute(): void
    {
        $page  = new Route();
        $route = 'test';

        $page->setOptions(['route' => $route]);

        self::assertSame($route, $page->getRoute());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetRoute(): void
    {
        $page  = new Route();
        $route = 'test';

        $page->setRoute($route);

        self::assertSame($route, $page->getRoute());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorWithLabel(): void
    {
        $page  = new Route();
        $label = 'test';

        $page = new Route(['label' => $label]);

        self::assertSame($label, $page->getLabel());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithLabel(): void
    {
        $route = new Route();
        $label = 'test';

        $route->setOptions(['label' => $label]);

        self::assertSame($label, $route->getLabel());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetLabel(): void
    {
        $route = new Route();
        $label = 'test';

        $route->setLabel($label);

        self::assertSame($label, $route->getLabel());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorWithFragment(): void
    {
        $page     = new Route();
        $fragment = 'test';

        $page = new Route(['fragment' => $fragment]);

        self::assertSame($fragment, $page->getFragment());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithFragment(): void
    {
        $route    = new Route();
        $fragment = 'test';

        $route->setOptions(['fragment' => $fragment]);

        self::assertSame($fragment, $route->getFragment());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetFragment(): void
    {
        $route    = new Route();
        $fragment = 'test';

        $route->setFragment($fragment);

        self::assertSame($fragment, $route->getFragment());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorWithId(): void
    {
        $page = new Route();
        $id   = 'test';

        $page = new Route(['id' => $id]);

        self::assertSame($id, $page->getId());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithId(): void
    {
        $route = new Route();
        $id    = 'test';

        $route->setOptions(['id' => $id]);

        self::assertSame($id, $route->getId());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetId(): void
    {
        $route = new Route();
        $id    = 'test';

        $route->setId($id);

        self::assertSame($id, $route->getId());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorClass(): void
    {
        $page  = new Route();
        $class = 'test';

        $page = new Route(['class' => $class]);

        self::assertSame($class, $page->getClass());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithClass(): void
    {
        $route = new Route();
        $class = 'test';

        $route->setOptions(['class' => $class]);

        self::assertSame($class, $route->getClass());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetClass(): void
    {
        $route = new Route();
        $class = 'test';

        $route->setClass($class);

        self::assertSame($class, $route->getClass());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorLiClass(): void
    {
        $page  = new Route();
        $class = 'test';

        $page = new Route(['liClass' => $class]);

        self::assertSame($class, $page->getLiClass());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithLiClass(): void
    {
        $route = new Route();
        $class = 'test';

        $route->setOptions(['liClass' => $class]);

        self::assertSame($class, $route->getLiClass());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetLiClass(): void
    {
        $route = new Route();
        $class = 'test';

        $route->setLiClass($class);

        self::assertSame($class, $route->getLiClass());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorTitle(): void
    {
        $page  = new Route();
        $title = 'test';

        $page = new Route(['title' => $title]);

        self::assertSame($title, $page->getTitle());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithTitle(): void
    {
        $route = new Route();
        $title = 'test';

        $route->setOptions(['title' => $title]);

        self::assertSame($title, $route->getTitle());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetTitle(): void
    {
        $route = new Route();
        $title = 'test';

        $route->setTitle($title);

        self::assertSame($title, $route->getTitle());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorTarget(): void
    {
        $page   = new Route();
        $target = 'test';

        $page = new Route(['target' => $target]);

        self::assertSame($target, $page->getTarget());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithTarget(): void
    {
        $route  = new Route();
        $target = 'test';

        $route->setOptions(['target' => $target]);

        self::assertSame($target, $route->getTarget());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetTarget(): void
    {
        $route  = new Route();
        $target = 'test';

        $route->setTarget($target);

        self::assertSame($target, $route->getTarget());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetRel(): void
    {
        $route    = new Route();
        $relValue = 'test1';
        $relKey   = 'test';

        $route->setRel();

        self::assertSame([], $route->getRel());

        $route->setRel([$relKey => $relValue, 42 => 'tests']);

        self::assertSame([$relKey => $relValue], $route->getRel());
        self::assertSame($relValue, $route->getRel($relKey));

        self::assertCount(1, $route->getRel());

        $route->addRel('test2', 'test2');

        self::assertCount(2, (array) $route->getRel());

        $route->removeRel('test');

        self::assertCount(1, (array) $route->getRel());

        $route->removeRel('test4');

        self::assertCount(1, (array) $route->getRel());

        self::assertSame(['test2'], $route->getDefinedRel());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetRev(): void
    {
        $route    = new Route();
        $revValue = 'test1';
        $revKey   = 'test';

        $route->setRev();

        self::assertSame([], $route->getRev());

        $route->setRev([$revKey => $revValue, 42 => 'tests']);

        self::assertSame([$revKey => $revValue], $route->getRev());
        self::assertSame($revValue, $route->getRev($revKey));

        self::assertCount(1, $route->getRev());

        $route->addRev('test2', 'test2');

        self::assertCount(2, (array) $route->getRev());

        $route->removeRev('test');

        self::assertCount(1, (array) $route->getRev());

        $route->removeRev('test4');

        self::assertCount(1, (array) $route->getRev());

        self::assertSame(['test2'], $route->getDefinedRev());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetParentException(): void
    {
        $route = new Route();

        self::assertNull($route->getParent());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');
        $this->expectExceptionCode(0);

        $route->setParent($route);
    }

    /** @throws InvalidArgumentException */
    public function testDuplicateSetParent(): void
    {
        $route = new Route();

        $parent = $this->createMock(ContainerInterface::class);
        $parent->expects(self::never())
            ->method('removePage');
        $parent->expects(self::once())
            ->method('hasPage')
            ->with($route, false)
            ->willReturn(value: false);
        $parent->expects(self::once())
            ->method('addPage')
            ->with($route);

        assert($parent instanceof ContainerInterface);
        $route->setParent($parent);
        $route->setParent($parent);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetTwoParents(): void
    {
        $route = new Route();

        $parent1 = $this->createMock(ContainerInterface::class);
        $parent1->expects(self::once())
            ->method('removePage')
            ->with($route);
        $parent1->expects(self::once())
            ->method('hasPage')
            ->with($route, false)
            ->willReturn(value: false);
        $parent1->expects(self::once())
            ->method('addPage')
            ->with($route);

        $parent2 = $this->createMock(ContainerInterface::class);
        $parent2->expects(self::never())
            ->method('removePage');
        $parent2->expects(self::once())
            ->method('hasPage')
            ->with($route, false)
            ->willReturn(value: true);
        $parent2->expects(self::never())
            ->method('addPage');

        assert($parent1 instanceof ContainerInterface);
        assert($parent2 instanceof ContainerInterface);
        $route->setParent($parent1);
        self::assertSame($parent1, $route->getParent());

        $route->setParent($parent2);
        self::assertSame($parent2, $route->getParent());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOrder(): void
    {
        $route = new Route();
        $order = 42;

        self::assertNull($route->getOrder());

        $route->setOrder($order);

        self::assertSame($order, $route->getOrder());

        $route->setOrder('42');

        self::assertSame($order, $route->getOrder());

        $route->setOrder(42.0);

        self::assertSame($order, $route->getOrder());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOrderWithParent(): void
    {
        $route = new Route();
        $order = 42;

        $parent = $this->createMock(ContainerInterface::class);
        $parent->expects(self::once())
            ->method('notifyOrderUpdated');

        $route->setParent($parent);
        $route->setOrder($order);

        self::assertSame($order, $route->getOrder());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetResource(): void
    {
        $route    = new Route();
        $resource = 'test';

        self::assertNull($route->getResource());

        $route->setRoute($resource);

        self::assertSame($resource, $route->getResource());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetPrivilege(): void
    {
        $route     = new Route();
        $privilege = 'test';

        self::assertNull($route->getPrivilege());

        $route->setPrivilege($privilege);

        self::assertSame($privilege, $route->getPrivilege());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetTextDomain(): void
    {
        $route      = new Route();
        $textDomain = 'test';

        self::assertNull($route->getTextDomain());

        $route->setTextDomain($textDomain);

        self::assertSame($textDomain, $route->getTextDomain());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetVisible(): void
    {
        $route   = new Route();
        $visible = false;

        self::assertTrue($route->isVisible());
        self::assertTrue($route->getVisible());

        $route->setVisible($visible);

        self::assertFalse($route->isVisible());
        self::assertFalse($route->getVisible());

        $route->setVisible('1');

        self::assertTrue($route->isVisible());
        self::assertTrue($route->getVisible());

        $route->setVisible('false');

        self::assertFalse($route->isVisible());
        self::assertFalse($route->getVisible());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetVisibleWithParent(): void
    {
        $route   = new Route();
        $parent1 = $this->createMock(PageInterface::class);
        $parent1->expects(self::exactly(2))
            ->method('isVisible')
            ->willReturn(value: true);

        assert($parent1 instanceof PageInterface);
        $route->setParent($parent1);

        self::assertTrue($route->isVisible(recursive: true));
        self::assertTrue($route->getVisible(recursive: true));

        $parent2 = $this->createMock(PageInterface::class);
        $parent2->expects(self::exactly(2))
            ->method('isVisible')
            ->willReturn(value: false);

        assert($parent2 instanceof PageInterface);
        $route->setParent($parent2);

        self::assertFalse($route->isVisible(recursive: true));
        self::assertFalse($route->getVisible(recursive: true));

        $route->setVisible(visible: false);

        self::assertFalse($route->isVisible());
        self::assertFalse($route->getVisible());

        $route->setVisible(visible: true);

        self::assertTrue($route->isVisible());
        self::assertTrue($route->getVisible());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetActive(): void
    {
        $route  = new Route();
        $active = true;

        self::assertFalse($route->isActive());
        self::assertFalse($route->getActive());

        $route->setActive($active);

        self::assertTrue($route->isActive());
        self::assertTrue($route->getActive());

        $route->setActive('1');

        self::assertTrue($route->isActive());
        self::assertTrue($route->getActive());

        $route->setActive('false');

        self::assertFalse($route->isActive());
        self::assertFalse($route->getActive());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetActiveWithPages(): void
    {
        $route      = new Route();
        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::exactly(2))
            ->method('isActive')
            ->with(true)
            ->willReturn(value: true);

        self::assertFalse($route->isActive(recursive: true));
        self::assertFalse($route->getActive(recursive: true));

        $route->addPage($childPage1);

        self::assertTrue($route->isActive(recursive: true));
        self::assertTrue($route->getActive(recursive: true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetActiveWithRouteMatchWithoutRoute(): void
    {
        $route       = new Route();
        $routeResult = $this->createMock(RouteResult::class);
        $routeResult->expects(self::once())
            ->method('getMatchedParams')
            ->willReturn(['test', 'abc']);
        $routeResult->expects(self::never())
            ->method('getMatchedRouteName');

        $params = ['test'];

        self::assertFalse($route->isActive());
        self::assertFalse($route->getActive());

        assert($routeResult instanceof RouteResult);
        $route->setRouteMatch($routeResult);
        $route->setParams($params);

        self::assertTrue($route->isActive());
        self::assertTrue($route->getActive());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetActiveWithRouteMatchWithRouteNotMatch(): void
    {
        $page        = new Route();
        $routeResult = $this->createMock(RouteResult::class);
        $routeResult->expects(self::once())
            ->method('getMatchedParams')
            ->willReturn(['test', 'abc']);
        $routeResult->expects(self::once())
            ->method('getMatchedRouteName')
            ->willReturn('testRoute2');

        $params = ['test'];
        $route  = 'testRoute';

        assert($routeResult instanceof RouteResult);
        $page->setRouteMatch($routeResult);
        $page->setParams($params);
        $page->setRoute($route);

        self::assertFalse($page->isActive());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetActiveWithRouteMatchWithRouteMatch(): void
    {
        $page  = new Route();
        $route = 'testRoute';

        $routeResult = $this->createMock(RouteResult::class);
        $routeResult->expects(self::once())
            ->method('getMatchedParams')
            ->willReturn(['test', 'abc']);
        $routeResult->expects(self::once())
            ->method('getMatchedRouteName')
            ->willReturn($route);

        $params = ['test'];

        assert($routeResult instanceof RouteResult);
        $page->setRouteMatch($routeResult);
        $page->setParams($params);
        $page->setRoute($route);

        self::assertTrue($page->isActive());
    }

    /** @throws InvalidArgumentException */
    public function testSetWithException(): void
    {
        $route = new Route();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $property must be a non-empty string');
        $this->expectExceptionCode(0);

        $route->set('', value: null);
    }

    /** @throws InvalidArgumentException */
    public function testGetWithException(): void
    {
        $route = new Route();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $property must be a non-empty string');
        $this->expectExceptionCode(0);

        $route->get('');
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testGetSet(): void
    {
        $route  = new Route();
        $target = 'test2';
        $test   = 'test 42';
        $abc    = '4711';

        self::assertNull($route->get('test'));

        $route->set('target', $target);
        $route->set('test', $test);
        $route->abc = $abc;

        self::assertSame($target, $route->get('target'));
        self::assertSame($test, $route->get('test'));
        self::assertSame($abc, $route->abc);

        self::assertTrue(isset($route->target));
        self::assertTrue(isset($route->test));

        self::assertSame(['test' => 'test 42', 'abc' => '4711'], $route->getCustomProperties());

        unset($route->test, $route->test);

        self::assertObjectNotHasProperty('test', $route);
        self::assertSame(['abc' => '4711'], $route->getCustomProperties());
    }

    /** @throws InvalidArgumentException */
    public function testUnset(): void
    {
        $route = new Route();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsetting native property "target" is not allowed');
        $this->expectExceptionCode(0);

        unset($route->target);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testToString(): void
    {
        $route = new Route();

        self::assertSame('', (string) $route);

        $label = 'test';

        $route->setLabel($label);

        self::assertSame($label, (string) $route);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHashCode(): void
    {
        $route = new Route();
        $label = 'test';

        $route->setLabel($label);

        $expected = spl_object_hash($route);

        self::assertSame($expected, $route->hashCode());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetQuery(): void
    {
        $route = new Route();
        $query = 'test';

        self::assertNull($route->getQuery());

        $route->setQuery($query);

        self::assertSame($query, $route->getQuery());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetParams(): void
    {
        $route  = new Route();
        $params = ['test'];

        self::assertSame([], $route->getParams());

        $route->setParams($params);

        self::assertSame($params, $route->getParams());
    }

    /** @throws InvalidArgumentException */
    public function testSetRouteException(): void
    {
        $route = new Route();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $route must be a non-empty string');
        $this->expectExceptionCode(0);

        $route->setRoute('');
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetRouteMatch(): void
    {
        $route = new Route();

        $routeResult = self::createStub(RouteResult::class);

        self::assertNull($route->getRouteMatch());

        assert($routeResult instanceof RouteResult);
        $route->setRouteMatch($routeResult);

        self::assertSame($routeResult, $route->getRouteMatch());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetUseRouteMatch(): void
    {
        $route = new Route();

        self::assertFalse($route->useRouteMatch());

        $route->setUseRouteMatch(useRouteMatch: true);

        self::assertTrue($route->useRouteMatch());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetRouter(): void
    {
        $route  = new Route();
        $router = self::createStub(RouterInterface::class);

        self::assertNull($route->getRouter());

        assert($router instanceof RouterInterface);
        $route->setRouter($router);

        self::assertSame($router, $route->getRouter());
    }

    /**
     * @throws DomainException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function testGetHrefMissingRouter(): void
    {
        $route = new Route();

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'Mezzio\Navigation\Page\Route::getHref cannot execute as no Mezzio\Router\RouterInterface instance is composed',
        );
        $this->expectExceptionCode(0);

        $route->getHref();
    }

    /**
     * @throws DomainException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function testGetHrefMissingRoute(): void
    {
        $route = new Route();

        $router = self::createStub(RouterInterface::class);

        assert($router instanceof RouterInterface);
        $route->setRouter($router);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('No route name could be found');
        $this->expectExceptionCode(0);

        $route->getHref();
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws RuntimeException
     */
    public function testGetHrefWithRoute(): void
    {
        $page        = new Route();
        $route       = 'testRoute';
        $expectedUri = '/test';
        $router      = $this->createMock(RouterInterface::class);
        $router->expects(self::once())
            ->method('generateUri')
            ->with($route, [], ['name' => $route])
            ->willReturn($expectedUri);

        assert($router instanceof RouterInterface);
        $page->setRouter($router);
        $page->setRoute($route);

        $uri = $page->getHref();

        self::assertSame($expectedUri, $uri);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws RuntimeException
     */
    public function testGetHrefWithRouteMatch(): void
    {
        $page        = new Route();
        $route       = 'testRoute';
        $expectedUri = '/test';
        $params      = ['test', 'abc'];

        $router = $this->createMock(RouterInterface::class);
        $router->expects(self::once())
            ->method('generateUri')
            ->with($route, $params, ['name' => $route])
            ->willReturn($expectedUri);

        $routeResult = $this->createMock(RouteResult::class);
        $routeResult->expects(self::once())
            ->method('getMatchedParams')
            ->willReturn($params);
        $routeResult->expects(self::once())
            ->method('getMatchedRouteName')
            ->willReturn($route);
        $routeResult->expects(self::once())
            ->method('isFailure')
            ->willReturn(value: false);

        assert($router instanceof RouterInterface);
        $page->setRouter($router);

        assert($routeResult instanceof RouteResult);
        $page->setRouteMatch($routeResult);

        $page->setUseRouteMatch(useRouteMatch: true);

        $uri = $page->getHref();

        self::assertSame($expectedUri, $uri);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws RuntimeException
     */
    public function testGetHrefWithFragmentIdentifier(): void
    {
        $page        = new Route();
        $route       = 'testRoute';
        $expectedUri = '/test';
        $params      = ['test', 'abc'];
        $fragment    = 'bar';

        $router = $this->createMock(RouterInterface::class);
        $router->expects(self::once())
            ->method('generateUri')
            ->with($route, $params, ['name' => $route, 'fragment' => $fragment])
            ->willReturn($expectedUri);

        $routeResult = $this->createMock(RouteResult::class);
        $routeResult->expects(self::once())
            ->method('getMatchedParams')
            ->willReturn($params);
        $routeResult->expects(self::once())
            ->method('getMatchedRouteName')
            ->willReturn($route);
        $routeResult->expects(self::once())
            ->method('isFailure')
            ->willReturn(value: false);

        assert($router instanceof RouterInterface);
        $page->setRouter($router);

        assert($routeResult instanceof RouteResult);
        $page->setRouteMatch($routeResult);

        $page->setUseRouteMatch(useRouteMatch: true);
        $page->setFragment($fragment);

        $uri = $page->getHref();

        self::assertSame($expectedUri, $uri);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws RuntimeException
     */
    public function testGetHrefWithQuery(): void
    {
        $page        = new Route();
        $route       = 'testRoute';
        $expectedUri = '/test';
        $params      = ['test', 'abc'];
        $query       = 'bar';

        $router = $this->createMock(RouterInterface::class);
        $router->expects(self::once())
            ->method('generateUri')
            ->with($route, $params, ['name' => $route, 'query' => $query])
            ->willReturn($expectedUri);

        $routeResult = $this->createMock(RouteResult::class);
        $routeResult->expects(self::once())
            ->method('getMatchedParams')
            ->willReturn($params);
        $routeResult->expects(self::once())
            ->method('getMatchedRouteName')
            ->willReturn($route);
        $routeResult->expects(self::once())
            ->method('isFailure')
            ->willReturn(value: false);

        assert($router instanceof RouterInterface);
        $page->setRouter($router);

        assert($routeResult instanceof RouteResult);
        $page->setRouteMatch($routeResult);

        $page->setUseRouteMatch(useRouteMatch: true);
        $page->setQuery($query);

        $page->getHref();

        $uri = $page->getHref();

        self::assertSame($expectedUri, $uri);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testToArray(): void
    {
        $page        = new Route();
        $params      = ['testParams'];
        $route       = 'testRoute';
        $router      = self::createStub(RouterInterface::class);
        $routeResult = self::createStub(RouteResult::class);

        $page->setParams($params);
        $page->setRoute($route);

        assert($router instanceof RouterInterface);
        $page->setRouter($router);

        assert($routeResult instanceof RouteResult);
        $page->setRouteMatch($routeResult);

        $expected = [
            'label' => null,
            'fragment' => null,
            'id' => null,
            'class' => null,
            'title' => null,
            'target' => null,
            'rel' => [],
            'rev' => [],
            'order' => null,
            'resource' => 'testRoute',
            'privilege' => null,
            'active' => false,
            'visible' => true,
            'type' => Route::class,
            'pages' => [],
            'params' => $params,
            'route' => $route,
            'router' => $router,
            'route_match' => $routeResult,
        ];

        $result = $page->toArray();

        self::assertIsArray($result);
        self::assertSame($expected, $result);
    }

    /** @throws InvalidArgumentException */
    public function testAddSelfAsChild(): void
    {
        $route = new Route();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');
        $this->expectExceptionCode(0);

        $route->addPage($route);
    }

    /** @throws InvalidArgumentException */
    public function testAddChildPageTwice(): void
    {
        $route    = new Route();
        $hashCode = 'abc';

        $childPage = $this->createMock(PageInterface::class);
        $childPage->expects(self::exactly(2))
            ->method('hashCode')
            ->willReturn($hashCode);
        $childPage->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage->expects(self::once())
            ->method('setParent')
            ->with($route);

        assert($childPage instanceof PageInterface);
        $route->addPage($childPage);
        $route->addPage($childPage);
    }

    /** @throws InvalidArgumentException */
    public function testAddChildPageSelf(): void
    {
        $route = new Route();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');
        $this->expectExceptionCode(0);

        $route->addPage($route);
    }

    /** @throws InvalidArgumentException */
    public function testAddPages(): void
    {
        $route = new Route();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $page must be an Instance of PageInterface');
        $this->expectExceptionCode(0);

        $route->addPages(['test']);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageByIndex(): void
    {
        $route = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($route);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $route->addPage($childPage1);
        $route->addPage($childPage2);

        self::assertTrue($route->removePage(1));
        self::assertSame([$code2 => $childPage2], $route->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageByObject(): void
    {
        $route = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::exactly(2))
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::once())
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($route);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $route->addPage($childPage1);
        $route->addPage($childPage2);

        self::assertTrue($route->removePage($childPage2));
        self::assertSame([$code1 => $childPage1], $route->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageNotExistingPage(): void
    {
        $route = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($route);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $route->addPage($childPage1);
        $route->addPage($childPage2);

        self::assertFalse($route->removePage(3));
        self::assertSame([$code1 => $childPage1, $code2 => $childPage2], $route->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageRecursive(): void
    {
        $route = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::never())
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::never())
            ->method('setParent')
            ->with($route);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(value: true);
        $childPage1->expects(self::once())
            ->method('removePage')
            ->with($childPage2, true);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $route->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertTrue($route->removePage($childPage2, recursive: true));
        self::assertSame([$code1 => $childPage1], $route->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageRecursiveNotFound(): void
    {
        $route = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::never())
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::never())
            ->method('setParent')
            ->with($route);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(value: false);
        $childPage1->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $route->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertFalse($route->removePage($childPage2, recursive: true));
        self::assertSame([$code1 => $childPage1], $route->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasPageByIndex(): void
    {
        $route = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($route);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $route->addPage($childPage1);
        $route->addPage($childPage2);

        self::assertTrue($route->hasPage(1));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasPageByObject(): void
    {
        $route = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::exactly(2))
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::once())
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($route);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $route->addPage($childPage1);
        $route->addPage($childPage2);

        self::assertTrue($route->hasPage($childPage2));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasNotExistingPage(): void
    {
        $route = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($route);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $route->addPage($childPage1);
        $route->addPage($childPage2);

        self::assertFalse($route->hasPage(3));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasPageRecursive(): void
    {
        $route = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::never())
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::never())
            ->method('setParent')
            ->with($route);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(value: true);
        $childPage1->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $route->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertTrue($route->hasPage($childPage2, recursive: true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasPageRecursiveNotFound(): void
    {
        $route = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::never())
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::never())
            ->method('setParent')
            ->with($route);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(value: false);
        $childPage1->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $route->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertFalse($route->hasPage($childPage2, recursive: true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasNoVisiblePages(): void
    {
        $route = new Route();

        self::assertFalse($route->hasPages());

        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);
        $childPage1->expects(self::once())
            ->method('isVisible')
            ->willReturn(value: false);

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::once())
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($route);
        $childPage2->expects(self::once())
            ->method('isVisible')
            ->willReturn(value: false);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $route->addPage($childPage1);
        $route->addPage($childPage2);

        self::assertTrue($route->hasPages());
        self::assertFalse($route->hasPages(onlyVisible: true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasVisiblePages(): void
    {
        $route = new Route();

        self::assertFalse($route->hasPages());

        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);
        $childPage1->expects(self::once())
            ->method('isVisible')
            ->willReturn(value: false);

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::once())
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($route);
        $childPage2->expects(self::once())
            ->method('isVisible')
            ->willReturn(value: true);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $route->addPage($childPage1);
        $route->addPage($childPage2);

        self::assertTrue($route->hasPages());
        self::assertTrue($route->hasPages(onlyVisible: true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testFindOneBy(): void
    {
        $route = new Route();

        $property = 'route';
        $value    = 'test';

        self::assertNull($route->findOneBy($property, $value));

        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($route);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $route->addPage($childPage1);
        $route->addPage($childPage2);

        self::assertSame($childPage2, $route->findOneBy($property, $value));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testFindAllBy(): void
    {
        $route    = new Route();
        $property = 'route';
        $value    = 'test';

        self::assertSame([], $route->findAllBy($property, $value));

        $code1 = 'code 1';
        $code2 = 'code 2';
        $code3 = 'code 3';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($route);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        $childPage3 = $this->createMock(PageInterface::class);
        $childPage3->expects(self::once())
            ->method('hashCode')
            ->willReturn($code3);
        $childPage3->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage3->expects(self::once())
            ->method('setParent')
            ->with($route);
        $childPage3->expects(self::never())
            ->method('isVisible');
        $childPage3->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn(value: null);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        assert($childPage3 instanceof PageInterface);
        $route->addPage($childPage1);
        $route->addPage($childPage2);
        $route->addPage($childPage3);

        self::assertSame([$childPage2, $childPage1], $route->findAllBy($property, $value));
    }

    /** @throws InvalidArgumentException */
    public function testCallFindAllByException(): void
    {
        $route = new Route();
        $value = 'test';

        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage(
            'Bad method call: Unknown method Mimmi20\Mezzio\Navigation\Page\Route::findAlllByTest',
        );
        $this->expectExceptionCode(0);

        $route->findAlllByTest($value);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testCallFindAllBy(): void
    {
        $route    = new Route();
        $property = 'Route';
        $value    = 'test';

        self::assertSame([], $route->findAllByRoute($value));

        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($route);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $route->addPage($childPage1);
        $route->addPage($childPage2);

        self::assertSame([$childPage2, $childPage1], $route->findAllByRoute($value));
    }

    /**
     * @throws OutOfBoundsException
     * @throws InvalidArgumentException
     */
    public function testCurrentException(): void
    {
        $route = new Route();

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage(
            'container is currently empty, could not find any key in internal iterator',
        );
        $this->expectExceptionCode(0);

        $route->current();
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    public function testCurrent(): void
    {
        $route = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($route);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::never())
            ->method('get');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $route->addPage($childPage1);
        $route->addPage($childPage2);

        self::assertSame($childPage2, $route->current());
        self::assertSame($code2, $route->key());
        self::assertTrue($route->valid());

        $route->next();

        self::assertSame($childPage1, $route->current());
        self::assertSame($code1, $route->key());
        self::assertTrue($route->valid());

        $route->next();

        self::assertSame('', $route->key());
        self::assertFalse($route->valid());

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage(
            'Corruption detected in container; invalid key found in internal iterator',
        );
        $this->expectExceptionCode(0);

        $route->current();
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    public function testRewind(): void
    {
        $route = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($route);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($route);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::never())
            ->method('get');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $route->addPage($childPage1);
        $route->addPage($childPage2);

        self::assertSame($childPage2, $route->current());
        self::assertSame($code2, $route->key());
        self::assertTrue($route->valid());

        $route->next();

        self::assertSame($childPage1, $route->current());
        self::assertSame($code1, $route->key());
        self::assertTrue($route->valid());

        $route->rewind();

        self::assertSame($childPage2, $route->current());
        self::assertSame($code2, $route->key());
        self::assertTrue($route->valid());
    }
}
