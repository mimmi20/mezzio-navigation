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
use PHPUnit\Event\NoPreviousThrowableException;
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
        $page = new Route();

        self::assertSame([], $page->getPages());
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
        $page  = new Route();
        $label = 'test';

        $page->setOptions(['label' => $label]);

        self::assertSame($label, $page->getLabel());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetLabel(): void
    {
        $page  = new Route();
        $label = 'test';

        $page->setLabel($label);

        self::assertSame($label, $page->getLabel());
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
        $page     = new Route();
        $fragment = 'test';

        $page->setOptions(['fragment' => $fragment]);

        self::assertSame($fragment, $page->getFragment());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetFragment(): void
    {
        $page     = new Route();
        $fragment = 'test';

        $page->setFragment($fragment);

        self::assertSame($fragment, $page->getFragment());
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
        $page = new Route();
        $id   = 'test';

        $page->setOptions(['id' => $id]);

        self::assertSame($id, $page->getId());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetId(): void
    {
        $page = new Route();
        $id   = 'test';

        $page->setId($id);

        self::assertSame($id, $page->getId());
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
        $page  = new Route();
        $class = 'test';

        $page->setOptions(['class' => $class]);

        self::assertSame($class, $page->getClass());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetClass(): void
    {
        $page  = new Route();
        $class = 'test';

        $page->setClass($class);

        self::assertSame($class, $page->getClass());
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
        $page  = new Route();
        $class = 'test';

        $page->setOptions(['liClass' => $class]);

        self::assertSame($class, $page->getLiClass());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetLiClass(): void
    {
        $page  = new Route();
        $class = 'test';

        $page->setLiClass($class);

        self::assertSame($class, $page->getLiClass());
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
        $page  = new Route();
        $title = 'test';

        $page->setOptions(['title' => $title]);

        self::assertSame($title, $page->getTitle());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetTitle(): void
    {
        $page  = new Route();
        $title = 'test';

        $page->setTitle($title);

        self::assertSame($title, $page->getTitle());
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
        $page   = new Route();
        $target = 'test';

        $page->setOptions(['target' => $target]);

        self::assertSame($target, $page->getTarget());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetTarget(): void
    {
        $page   = new Route();
        $target = 'test';

        $page->setTarget($target);

        self::assertSame($target, $page->getTarget());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetRel(): void
    {
        $page     = new Route();
        $relValue = 'test1';
        $relKey   = 'test';

        $page->setRel();

        self::assertSame([], $page->getRel());

        $page->setRel([$relKey => $relValue, 42 => 'tests']);

        self::assertSame([$relKey => $relValue], $page->getRel());
        self::assertSame($relValue, $page->getRel($relKey));

        self::assertCount(1, $page->getRel());

        $page->addRel('test2', 'test2');

        self::assertCount(2, (array) $page->getRel());

        $page->removeRel('test');

        self::assertCount(1, (array) $page->getRel());

        $page->removeRel('test4');

        self::assertCount(1, (array) $page->getRel());

        self::assertSame(['test2'], $page->getDefinedRel());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetRev(): void
    {
        $page     = new Route();
        $revValue = 'test1';
        $revKey   = 'test';

        $page->setRev();

        self::assertSame([], $page->getRev());

        $page->setRev([$revKey => $revValue, 42 => 'tests']);

        self::assertSame([$revKey => $revValue], $page->getRev());
        self::assertSame($revValue, $page->getRev($revKey));

        self::assertCount(1, $page->getRev());

        $page->addRev('test2', 'test2');

        self::assertCount(2, (array) $page->getRev());

        $page->removeRev('test');

        self::assertCount(1, (array) $page->getRev());

        $page->removeRev('test4');

        self::assertCount(1, (array) $page->getRev());

        self::assertSame(['test2'], $page->getDefinedRev());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetParentException(): void
    {
        $page = new Route();

        self::assertNull($page->getParent());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');
        $this->expectExceptionCode(0);

        $page->setParent($page);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testDuplicateSetParent(): void
    {
        $page = new Route();

        $parent = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parent->expects(self::never())
            ->method('removePage');
        $parent->expects(self::once())
            ->method('hasPage')
            ->with($page, false)
            ->willReturn(false);
        $parent->expects(self::once())
            ->method('addPage')
            ->with($page);

        assert($parent instanceof ContainerInterface);
        $page->setParent($parent);
        $page->setParent($parent);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetTwoParents(): void
    {
        $page = new Route();

        $parent1 = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parent1->expects(self::once())
            ->method('removePage')
            ->with($page);
        $parent1->expects(self::once())
            ->method('hasPage')
            ->with($page, false)
            ->willReturn(false);
        $parent1->expects(self::once())
            ->method('addPage')
            ->with($page);

        $parent2 = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parent2->expects(self::never())
            ->method('removePage');
        $parent2->expects(self::once())
            ->method('hasPage')
            ->with($page, false)
            ->willReturn(true);
        $parent2->expects(self::never())
            ->method('addPage');

        assert($parent1 instanceof ContainerInterface);
        assert($parent2 instanceof ContainerInterface);
        $page->setParent($parent1);
        self::assertSame($parent1, $page->getParent());

        $page->setParent($parent2);
        self::assertSame($parent2, $page->getParent());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOrder(): void
    {
        $page  = new Route();
        $order = 42;

        self::assertNull($page->getOrder());

        $page->setOrder($order);

        self::assertSame($order, $page->getOrder());

        $page->setOrder('42');

        self::assertSame($order, $page->getOrder());

        $page->setOrder(42.0);

        self::assertSame($order, $page->getOrder());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOrderWithParent(): void
    {
        $page  = new Route();
        $order = 42;

        $parent = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parent->expects(self::once())
            ->method('notifyOrderUpdated');

        $page->setParent($parent);
        $page->setOrder($order);

        self::assertSame($order, $page->getOrder());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetResource(): void
    {
        $page     = new Route();
        $resource = 'test';

        self::assertNull($page->getResource());

        $page->setRoute($resource);

        self::assertSame($resource, $page->getResource());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetPrivilege(): void
    {
        $page      = new Route();
        $privilege = 'test';

        self::assertNull($page->getPrivilege());

        $page->setPrivilege($privilege);

        self::assertSame($privilege, $page->getPrivilege());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetTextDomain(): void
    {
        $page       = new Route();
        $textDomain = 'test';

        self::assertNull($page->getTextDomain());

        $page->setTextDomain($textDomain);

        self::assertSame($textDomain, $page->getTextDomain());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetVisible(): void
    {
        $page    = new Route();
        $visible = false;

        self::assertTrue($page->isVisible());
        self::assertTrue($page->getVisible());

        $page->setVisible($visible);

        self::assertFalse($page->isVisible());
        self::assertFalse($page->getVisible());

        $page->setVisible('1');

        self::assertTrue($page->isVisible());
        self::assertTrue($page->getVisible());

        $page->setVisible('false');

        self::assertFalse($page->isVisible());
        self::assertFalse($page->getVisible());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetVisibleWithParent(): void
    {
        $page    = new Route();
        $parent1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parent1->expects(self::exactly(2))
            ->method('isVisible')
            ->willReturn(true);

        assert($parent1 instanceof PageInterface);
        $page->setParent($parent1);

        self::assertTrue($page->isVisible(true));
        self::assertTrue($page->getVisible(true));

        $parent2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parent2->expects(self::exactly(2))
            ->method('isVisible')
            ->willReturn(false);

        assert($parent2 instanceof PageInterface);
        $page->setParent($parent2);

        self::assertFalse($page->isVisible(true));
        self::assertFalse($page->getVisible(true));

        $page->setVisible(false);

        self::assertFalse($page->isVisible());
        self::assertFalse($page->getVisible());

        $page->setVisible(true);

        self::assertTrue($page->isVisible());
        self::assertTrue($page->getVisible());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetActive(): void
    {
        $page   = new Route();
        $active = true;

        self::assertFalse($page->isActive());
        self::assertFalse($page->getActive());

        $page->setActive($active);

        self::assertTrue($page->isActive());
        self::assertTrue($page->getActive());

        $page->setActive('1');

        self::assertTrue($page->isActive());
        self::assertTrue($page->getActive());

        $page->setActive('false');

        self::assertFalse($page->isActive());
        self::assertFalse($page->getActive());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetActiveWithPages(): void
    {
        $page       = new Route();
        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::exactly(2))
            ->method('isActive')
            ->with(true)
            ->willReturn(true);

        self::assertFalse($page->isActive(true));
        self::assertFalse($page->getActive(true));

        $page->addPage($childPage1);

        self::assertTrue($page->isActive(true));
        self::assertTrue($page->getActive(true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetActiveWithRouteMatchWithoutRoute(): void
    {
        $page        = new Route();
        $routeResult = $this->getMockBuilder(RouteResult::class)
            ->disableOriginalConstructor()
            ->getMock();
        $routeResult->expects(self::once())
            ->method('getMatchedParams')
            ->willReturn(['test', 'abc']);
        $routeResult->expects(self::never())
            ->method('getMatchedRouteName');

        $params = ['test'];

        self::assertFalse($page->isActive());
        self::assertFalse($page->getActive());

        assert($routeResult instanceof RouteResult);
        $page->setRouteMatch($routeResult);
        $page->setParams($params);

        self::assertTrue($page->isActive());
        self::assertTrue($page->getActive());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetActiveWithRouteMatchWithRouteNotMatch(): void
    {
        $page        = new Route();
        $routeResult = $this->getMockBuilder(RouteResult::class)
            ->disableOriginalConstructor()
            ->getMock();
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

        $routeResult = $this->getMockBuilder(RouteResult::class)
            ->disableOriginalConstructor()
            ->getMock();
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
        $page = new Route();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $property must be a non-empty string');
        $this->expectExceptionCode(0);

        $page->set('', null);
    }

    /** @throws InvalidArgumentException */
    public function testGetWithException(): void
    {
        $page = new Route();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $property must be a non-empty string');
        $this->expectExceptionCode(0);

        $page->get('');
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testGetSet(): void
    {
        $page   = new Route();
        $target = 'test2';
        $test   = 'test 42';
        $abc    = '4711';

        self::assertNull($page->get('test'));

        $page->set('target', $target);
        $page->set('test', $test);
        $page->abc = $abc;

        self::assertSame($target, $page->get('target'));
        self::assertSame($test, $page->get('test'));
        self::assertSame($abc, $page->abc);

        self::assertTrue(isset($page->target));
        self::assertTrue(isset($page->test));

        self::assertSame(['test' => 'test 42', 'abc' => '4711'], $page->getCustomProperties());

        unset($page->test, $page->test);

        self::assertObjectNotHasProperty('test', $page);
        self::assertSame(['abc' => '4711'], $page->getCustomProperties());
    }

    /** @throws InvalidArgumentException */
    public function testUnset(): void
    {
        $page = new Route();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsetting native property "target" is not allowed');
        $this->expectExceptionCode(0);

        unset($page->target);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testToString(): void
    {
        $page = new Route();

        self::assertSame('', (string) $page);

        $label = 'test';

        $page->setLabel($label);

        self::assertSame($label, (string) $page);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHashCode(): void
    {
        $page  = new Route();
        $label = 'test';

        $page->setLabel($label);

        $expected = spl_object_hash($page);

        self::assertSame($expected, $page->hashCode());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetQuery(): void
    {
        $page  = new Route();
        $query = 'test';

        self::assertNull($page->getQuery());

        $page->setQuery($query);

        self::assertSame($query, $page->getQuery());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetParams(): void
    {
        $page   = new Route();
        $params = ['test'];

        self::assertSame([], $page->getParams());

        $page->setParams($params);

        self::assertSame($params, $page->getParams());
    }

    /** @throws InvalidArgumentException */
    public function testSetRouteException(): void
    {
        $page = new Route();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $route must be a non-empty string');
        $this->expectExceptionCode(0);

        $page->setRoute('');
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testSetRouteMatch(): void
    {
        $page = new Route();

        $routeResult = $this->createMock(RouteResult::class);

        self::assertNull($page->getRouteMatch());

        assert($routeResult instanceof RouteResult);
        $page->setRouteMatch($routeResult);

        self::assertSame($routeResult, $page->getRouteMatch());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetUseRouteMatch(): void
    {
        $page = new Route();

        self::assertFalse($page->useRouteMatch());

        $page->setUseRouteMatch(true);

        self::assertTrue($page->useRouteMatch());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testSetRouter(): void
    {
        $page   = new Route();
        $router = $this->createMock(RouterInterface::class);

        self::assertNull($page->getRouter());

        assert($router instanceof RouterInterface);
        $page->setRouter($router);

        self::assertSame($router, $page->getRouter());
    }

    /**
     * @throws DomainException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function testGetHrefMissingRouter(): void
    {
        $page = new Route();

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            'Mezzio\Navigation\Page\Route::getHref cannot execute as no Mezzio\Router\RouterInterface instance is composed',
        );
        $this->expectExceptionCode(0);

        $page->getHref();
    }

    /**
     * @throws DomainException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \PHPUnit\Framework\InvalidArgumentException
     */
    public function testGetHrefMissingRoute(): void
    {
        $page = new Route();

        $router = $this->createMock(RouterInterface::class);

        assert($router instanceof RouterInterface);
        $page->setRouter($router);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('No route name could be found');
        $this->expectExceptionCode(0);

        $page->getHref();
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws RuntimeException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetHrefWithRoute(): void
    {
        $page        = new Route();
        $route       = 'testRoute';
        $expectedUri = '/test';
        $router      = $this->getMockBuilder(RouterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
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
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetHrefWithRouteMatch(): void
    {
        $page        = new Route();
        $route       = 'testRoute';
        $expectedUri = '/test';
        $params      = ['test', 'abc'];

        $router = $this->getMockBuilder(RouterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $router->expects(self::once())
            ->method('generateUri')
            ->with($route, $params, ['name' => $route])
            ->willReturn($expectedUri);

        $routeResult = $this->getMockBuilder(RouteResult::class)
            ->disableOriginalConstructor()
            ->getMock();
        $routeResult->expects(self::once())
            ->method('getMatchedParams')
            ->willReturn($params);
        $routeResult->expects(self::once())
            ->method('getMatchedRouteName')
            ->willReturn($route);
        $routeResult->expects(self::once())
            ->method('isFailure')
            ->willReturn(false);

        assert($router instanceof RouterInterface);
        $page->setRouter($router);

        assert($routeResult instanceof RouteResult);
        $page->setRouteMatch($routeResult);

        $page->setUseRouteMatch(true);

        $uri = $page->getHref();

        self::assertSame($expectedUri, $uri);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws RuntimeException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetHrefWithFragmentIdentifier(): void
    {
        $page        = new Route();
        $route       = 'testRoute';
        $expectedUri = '/test';
        $params      = ['test', 'abc'];
        $fragment    = 'bar';

        $router = $this->getMockBuilder(RouterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $router->expects(self::once())
            ->method('generateUri')
            ->with($route, $params, ['name' => $route, 'fragment' => $fragment])
            ->willReturn($expectedUri);

        $routeResult = $this->getMockBuilder(RouteResult::class)
            ->disableOriginalConstructor()
            ->getMock();
        $routeResult->expects(self::once())
            ->method('getMatchedParams')
            ->willReturn($params);
        $routeResult->expects(self::once())
            ->method('getMatchedRouteName')
            ->willReturn($route);
        $routeResult->expects(self::once())
            ->method('isFailure')
            ->willReturn(false);

        assert($router instanceof RouterInterface);
        $page->setRouter($router);

        assert($routeResult instanceof RouteResult);
        $page->setRouteMatch($routeResult);

        $page->setUseRouteMatch(true);
        $page->setFragment($fragment);

        $uri = $page->getHref();

        self::assertSame($expectedUri, $uri);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws RuntimeException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetHrefWithQuery(): void
    {
        $page        = new Route();
        $route       = 'testRoute';
        $expectedUri = '/test';
        $params      = ['test', 'abc'];
        $query       = 'bar';

        $router = $this->getMockBuilder(RouterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $router->expects(self::once())
            ->method('generateUri')
            ->with($route, $params, ['name' => $route, 'query' => $query])
            ->willReturn($expectedUri);

        $routeResult = $this->getMockBuilder(RouteResult::class)
            ->disableOriginalConstructor()
            ->getMock();
        $routeResult->expects(self::once())
            ->method('getMatchedParams')
            ->willReturn($params);
        $routeResult->expects(self::once())
            ->method('getMatchedRouteName')
            ->willReturn($route);
        $routeResult->expects(self::once())
            ->method('isFailure')
            ->willReturn(false);

        assert($router instanceof RouterInterface);
        $page->setRouter($router);

        assert($routeResult instanceof RouteResult);
        $page->setRouteMatch($routeResult);

        $page->setUseRouteMatch(true);
        $page->setQuery($query);

        $page->getHref();

        $uri = $page->getHref();

        self::assertSame($expectedUri, $uri);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testToArray(): void
    {
        $page        = new Route();
        $params      = ['testParams'];
        $route       = 'testRoute';
        $router      = $this->createMock(RouterInterface::class);
        $routeResult = $this->createMock(RouteResult::class);

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
        $page = new Route();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');
        $this->expectExceptionCode(0);

        $page->addPage($page);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testAddChildPageTwice(): void
    {
        $page     = new Route();
        $hashCode = 'abc';

        $childPage = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage->expects(self::exactly(2))
            ->method('hashCode')
            ->willReturn($hashCode);
        $childPage->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage->expects(self::once())
            ->method('setParent')
            ->with($page);

        assert($childPage instanceof PageInterface);
        $page->addPage($childPage);
        $page->addPage($childPage);
    }

    /** @throws InvalidArgumentException */
    public function testAddChildPageSelf(): void
    {
        $page = new Route();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');
        $this->expectExceptionCode(0);

        $page->addPage($page);
    }

    /** @throws InvalidArgumentException */
    public function testAddPages(): void
    {
        $page = new Route();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $page must be an Instance of PageInterface');
        $this->expectExceptionCode(0);

        $page->addPages(['test']);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRemovePageByIndex(): void
    {
        $page  = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($page);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);

        self::assertTrue($page->removePage(1));
        self::assertSame([$code2 => $childPage2], $page->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRemovePageByObject(): void
    {
        $page  = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::exactly(2))
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::once())
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($page);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);

        self::assertTrue($page->removePage($childPage2));
        self::assertSame([$code1 => $childPage1], $page->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRemovePageNotExistingPage(): void
    {
        $page  = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($page);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);

        self::assertFalse($page->removePage(3));
        self::assertSame([$code1 => $childPage1, $code2 => $childPage2], $page->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRemovePageRecursive(): void
    {
        $page  = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::never())
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::never())
            ->method('setParent')
            ->with($page);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(true);
        $childPage1->expects(self::once())
            ->method('removePage')
            ->with($childPage2, true);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertTrue($page->removePage($childPage2, true));
        self::assertSame([$code1 => $childPage1], $page->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRemovePageRecursiveNotFound(): void
    {
        $page  = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::never())
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::never())
            ->method('setParent')
            ->with($page);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(false);
        $childPage1->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertFalse($page->removePage($childPage2, true));
        self::assertSame([$code1 => $childPage1], $page->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testHasPageByIndex(): void
    {
        $page  = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($page);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);

        self::assertTrue($page->hasPage(1));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testHasPageByObject(): void
    {
        $page  = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::exactly(2))
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::once())
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($page);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);

        self::assertTrue($page->hasPage($childPage2));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testHasNotExistingPage(): void
    {
        $page  = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($page);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);

        self::assertFalse($page->hasPage(3));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testHasPageRecursive(): void
    {
        $page  = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::never())
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::never())
            ->method('setParent')
            ->with($page);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(true);
        $childPage1->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertTrue($page->hasPage($childPage2, true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testHasPageRecursiveNotFound(): void
    {
        $page  = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::never())
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::never())
            ->method('setParent')
            ->with($page);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(false);
        $childPage1->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertFalse($page->hasPage($childPage2, true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testHasNoVisiblePages(): void
    {
        $page = new Route();

        self::assertFalse($page->hasPages());

        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);
        $childPage1->expects(self::once())
            ->method('isVisible')
            ->willReturn(false);

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::once())
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($page);
        $childPage2->expects(self::once())
            ->method('isVisible')
            ->willReturn(false);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);

        self::assertTrue($page->hasPages());
        self::assertFalse($page->hasPages(true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testHasVisiblePages(): void
    {
        $page = new Route();

        self::assertFalse($page->hasPages());

        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::once())
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);
        $childPage1->expects(self::once())
            ->method('isVisible')
            ->willReturn(false);

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::once())
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($page);
        $childPage2->expects(self::once())
            ->method('isVisible')
            ->willReturn(true);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);

        self::assertTrue($page->hasPages());
        self::assertTrue($page->hasPages(true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindOneBy(): void
    {
        $page = new Route();

        $property = 'route';
        $value    = 'test';

        self::assertNull($page->findOneBy($property, $value));

        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($page);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);

        self::assertSame($childPage2, $page->findOneBy($property, $value));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindAllBy(): void
    {
        $page     = new Route();
        $property = 'route';
        $value    = 'test';

        self::assertSame([], $page->findAllBy($property, $value));

        $code1 = 'code 1';
        $code2 = 'code 2';
        $code3 = 'code 3';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($page);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        $childPage3 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage3->expects(self::once())
            ->method('hashCode')
            ->willReturn($code3);
        $childPage3->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(null);
        $childPage3->expects(self::once())
            ->method('setParent')
            ->with($page);
        $childPage3->expects(self::never())
            ->method('isVisible');
        $childPage3->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn(null);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        assert($childPage3 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);
        $page->addPage($childPage3);

        self::assertSame([$childPage2, $childPage1], $page->findAllBy($property, $value));
    }

    /** @throws InvalidArgumentException */
    public function testCallFindAllByException(): void
    {
        $page  = new Route();
        $value = 'test';

        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage(
            'Bad method call: Unknown method Mimmi20\Mezzio\Navigation\Page\Route::findAlllByTest',
        );
        $this->expectExceptionCode(0);

        $page->findAlllByTest($value);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testCallFindAllBy(): void
    {
        $page     = new Route();
        $property = 'Route';
        $value    = 'test';

        self::assertSame([], $page->findAllByRoute($value));

        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($page);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);

        self::assertSame([$childPage2, $childPage1], $page->findAllByRoute($value));
    }

    /**
     * @throws OutOfBoundsException
     * @throws InvalidArgumentException
     */
    public function testCurrentException(): void
    {
        $page = new Route();

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage(
            'container is currently empty, could not find any key in internal iterator',
        );
        $this->expectExceptionCode(0);

        $page->current();
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testCurrent(): void
    {
        $page  = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($page);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::never())
            ->method('get');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);

        self::assertSame($childPage2, $page->current());
        self::assertSame($code2, $page->key());
        self::assertTrue($page->valid());

        $page->next();

        self::assertSame($childPage1, $page->current());
        self::assertSame($code1, $page->key());
        self::assertTrue($page->valid());

        $page->next();

        self::assertSame('', $page->key());
        self::assertFalse($page->valid());

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage(
            'Corruption detected in container; invalid key found in internal iterator',
        );
        $this->expectExceptionCode(0);

        $page->current();
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRewind(): void
    {
        $page  = new Route();
        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::once())
            ->method('hashCode')
            ->willReturn($code1);
        $childPage1->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(1);
        $childPage1->expects(self::once())
            ->method('setParent')
            ->with($page);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');

        $childPage2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($page);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::never())
            ->method('get');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);

        self::assertSame($childPage2, $page->current());
        self::assertSame($code2, $page->key());
        self::assertTrue($page->valid());

        $page->next();

        self::assertSame($childPage1, $page->current());
        self::assertSame($code1, $page->key());
        self::assertTrue($page->valid());

        $page->rewind();

        self::assertSame($childPage2, $page->current());
        self::assertSame($code2, $page->key());
        self::assertTrue($page->valid());
    }
}
