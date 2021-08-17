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

namespace MezzioTest\Navigation\Page;

use Mezzio\Navigation\ContainerInterface;
use Mezzio\Navigation\Exception\BadMethodCallException;
use Mezzio\Navigation\Exception\DomainException;
use Mezzio\Navigation\Exception\InvalidArgumentException;
use Mezzio\Navigation\Exception\OutOfBoundsException;
use Mezzio\Navigation\Page\PageInterface;
use Mezzio\Navigation\Page\Route;
use Mezzio\Router\Exception\RuntimeException;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\SkippedTestError;
use PHPUnit\Framework\SyntheticSkippedError;
use PHPUnit\Framework\TestCase;

use function assert;
use function spl_object_hash;

final class RouteTest extends TestCase
{
    private Route $page;

    /**
     * @throws InvalidArgumentException
     */
    protected function setUp(): void
    {
        $this->page = new Route();
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testConstructorWithoutParameters(): void
    {
        self::assertSame([], $this->page->getPages());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorWithRoute(): void
    {
        $route = 'test';

        $this->page = new Route(['route' => $route]);

        self::assertSame($route, $this->page->getRoute());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithRoute(): void
    {
        $route = 'test';

        $this->page->setOptions(['route' => $route]);

        self::assertSame($route, $this->page->getRoute());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetRoute(): void
    {
        $route = 'test';

        $this->page->setRoute($route);

        self::assertSame($route, $this->page->getRoute());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorWithLabel(): void
    {
        $label = 'test';

        $this->page = new Route(['label' => $label]);

        self::assertSame($label, $this->page->getLabel());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithLabel(): void
    {
        $label = 'test';

        $this->page->setOptions(['label' => $label]);

        self::assertSame($label, $this->page->getLabel());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetLabel(): void
    {
        $label = 'test';

        $this->page->setLabel($label);

        self::assertSame($label, $this->page->getLabel());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorWithFragment(): void
    {
        $fragment = 'test';

        $this->page = new Route(['fragment' => $fragment]);

        self::assertSame($fragment, $this->page->getFragment());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithFragment(): void
    {
        $fragment = 'test';

        $this->page->setOptions(['fragment' => $fragment]);

        self::assertSame($fragment, $this->page->getFragment());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetFragment(): void
    {
        $fragment = 'test';

        $this->page->setFragment($fragment);

        self::assertSame($fragment, $this->page->getFragment());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorWithId(): void
    {
        $id = 'test';

        $this->page = new Route(['id' => $id]);

        self::assertSame($id, $this->page->getId());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithId(): void
    {
        $id = 'test';

        $this->page->setOptions(['id' => $id]);

        self::assertSame($id, $this->page->getId());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetId(): void
    {
        $id = 'test';

        $this->page->setId($id);

        self::assertSame($id, $this->page->getId());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorClass(): void
    {
        $class = 'test';

        $this->page = new Route(['class' => $class]);

        self::assertSame($class, $this->page->getClass());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithClass(): void
    {
        $class = 'test';

        $this->page->setOptions(['class' => $class]);

        self::assertSame($class, $this->page->getClass());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetClass(): void
    {
        $class = 'test';

        $this->page->setClass($class);

        self::assertSame($class, $this->page->getClass());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorLiClass(): void
    {
        $class = 'test';

        $this->page = new Route(['liClass' => $class]);

        self::assertSame($class, $this->page->getLiClass());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithLiClass(): void
    {
        $class = 'test';

        $this->page->setOptions(['liClass' => $class]);

        self::assertSame($class, $this->page->getLiClass());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetLiClass(): void
    {
        $class = 'test';

        $this->page->setLiClass($class);

        self::assertSame($class, $this->page->getLiClass());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorTitle(): void
    {
        $title = 'test';

        $this->page = new Route(['title' => $title]);

        self::assertSame($title, $this->page->getTitle());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithTitle(): void
    {
        $title = 'test';

        $this->page->setOptions(['title' => $title]);

        self::assertSame($title, $this->page->getTitle());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetTitle(): void
    {
        $title = 'test';

        $this->page->setTitle($title);

        self::assertSame($title, $this->page->getTitle());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorTarget(): void
    {
        $target = 'test';

        $this->page = new Route(['target' => $target]);

        self::assertSame($target, $this->page->getTarget());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithTarget(): void
    {
        $target = 'test';

        $this->page->setOptions(['target' => $target]);

        self::assertSame($target, $this->page->getTarget());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetTarget(): void
    {
        $target = 'test';

        $this->page->setTarget($target);

        self::assertSame($target, $this->page->getTarget());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetRel(): void
    {
        $relValue = 'test1';
        $relKey   = 'test';

        $this->page->setRel();

        self::assertSame([], $this->page->getRel());

        $this->page->setRel([$relKey => $relValue, 42 => 'tests']);

        self::assertSame([$relKey => $relValue], $this->page->getRel());
        self::assertSame($relValue, $this->page->getRel($relKey));

        self::assertCount(1, (array) $this->page->getRel());

        $this->page->addRel('test2', 'test2');

        self::assertCount(2, (array) $this->page->getRel());

        $this->page->removeRel('test');

        self::assertCount(1, (array) $this->page->getRel());

        $this->page->removeRel('test4');

        self::assertCount(1, (array) $this->page->getRel());

        self::assertSame(['test2'], $this->page->getDefinedRel());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetRev(): void
    {
        $revValue = 'test1';
        $revKey   = 'test';

        $this->page->setRev();

        self::assertSame([], $this->page->getRev());

        $this->page->setRev([$revKey => $revValue, 42 => 'tests']);

        self::assertSame([$revKey => $revValue], $this->page->getRev());
        self::assertSame($revValue, $this->page->getRev($revKey));

        self::assertCount(1, (array) $this->page->getRev());

        $this->page->addRev('test2', 'test2');

        self::assertCount(2, (array) $this->page->getRev());

        $this->page->removeRev('test');

        self::assertCount(1, (array) $this->page->getRev());

        $this->page->removeRev('test4');

        self::assertCount(1, (array) $this->page->getRev());

        self::assertSame(['test2'], $this->page->getDefinedRev());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetParentException(): void
    {
        self::assertNull($this->page->getParent());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');
        $this->expectExceptionCode(0);

        $this->page->setParent($this->page);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testDuplicateSetParent(): void
    {
        $parent = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parent->expects(self::never())
            ->method('removePage');
        $parent->expects(self::once())
            ->method('hasPage')
            ->with($this->page, false)
            ->willReturn(false);
        $parent->expects(self::once())
            ->method('addPage')
            ->with($this->page);

        assert($parent instanceof ContainerInterface);
        $this->page->setParent($parent);
        $this->page->setParent($parent);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetTwoParents(): void
    {
        $parent1 = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parent1->expects(self::once())
            ->method('removePage')
            ->with($this->page);
        $parent1->expects(self::once())
            ->method('hasPage')
            ->with($this->page, false)
            ->willReturn(false);
        $parent1->expects(self::once())
            ->method('addPage')
            ->with($this->page);

        $parent2 = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parent2->expects(self::never())
            ->method('removePage');
        $parent2->expects(self::once())
            ->method('hasPage')
            ->with($this->page, false)
            ->willReturn(true);
        $parent2->expects(self::never())
            ->method('addPage');

        assert($parent1 instanceof ContainerInterface);
        assert($parent2 instanceof ContainerInterface);
        $this->page->setParent($parent1);
        self::assertSame($parent1, $this->page->getParent());

        $this->page->setParent($parent2);
        self::assertSame($parent2, $this->page->getParent());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOrder(): void
    {
        $order = 42;

        self::assertNull($this->page->getOrder());

        $this->page->setOrder($order);

        self::assertSame($order, $this->page->getOrder());

        $this->page->setOrder('42');

        self::assertSame($order, $this->page->getOrder());

        $this->page->setOrder(42.0);

        self::assertSame($order, $this->page->getOrder());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSetOrderWithException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $order must be a string, an integer or null');
        $this->expectExceptionCode(0);

        $this->page->setOrder([]);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOrderWithParent(): void
    {
        $order = 42;

        $parent = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parent->expects(self::once())
            ->method('notifyOrderUpdated');

        $this->page->setParent($parent);
        $this->page->setOrder($order);

        self::assertSame($order, $this->page->getOrder());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetResource(): void
    {
        $resource = 'test';

        self::assertNull($this->page->getResource());

        $this->page->setResource($resource);

        self::assertSame($resource, $this->page->getResource());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetPrivilege(): void
    {
        $privilege = 'test';

        self::assertNull($this->page->getPrivilege());

        $this->page->setPrivilege($privilege);

        self::assertSame($privilege, $this->page->getPrivilege());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetPermission(): void
    {
        $permission = 'test';

        self::assertNull($this->page->getPermission());

        $this->page->setPermission($permission);

        self::assertSame($permission, $this->page->getPermission());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetTextDomain(): void
    {
        $textDomain = 'test';

        self::assertNull($this->page->getTextDomain());

        $this->page->setTextDomain($textDomain);

        self::assertSame($textDomain, $this->page->getTextDomain());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetVisible(): void
    {
        $visible = false;

        self::assertTrue($this->page->isVisible());
        self::assertTrue($this->page->getVisible());

        $this->page->setVisible($visible);

        self::assertFalse($this->page->isVisible());
        self::assertFalse($this->page->getVisible());

        $this->page->setVisible('1');

        self::assertTrue($this->page->isVisible());
        self::assertTrue($this->page->getVisible());

        $this->page->setVisible('false');

        self::assertFalse($this->page->isVisible());
        self::assertFalse($this->page->getVisible());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetVisibleWithParent(): void
    {
        $parent1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parent1->expects(self::exactly(2))
            ->method('isVisible')
            ->willReturn(true);

        assert($parent1 instanceof PageInterface);
        $this->page->setParent($parent1);

        self::assertTrue($this->page->isVisible(true));
        self::assertTrue($this->page->getVisible(true));

        $parent2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parent2->expects(self::exactly(2))
            ->method('isVisible')
            ->willReturn(false);

        assert($parent2 instanceof PageInterface);
        $this->page->setParent($parent2);

        self::assertFalse($this->page->isVisible(true));
        self::assertFalse($this->page->getVisible(true));

        $this->page->setVisible(false);

        self::assertFalse($this->page->isVisible());
        self::assertFalse($this->page->getVisible());

        $this->page->setVisible(true);

        self::assertTrue($this->page->isVisible());
        self::assertTrue($this->page->getVisible());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetActive(): void
    {
        $active = true;

        self::assertFalse($this->page->isActive());
        self::assertFalse($this->page->getActive());

        $this->page->setActive($active);

        self::assertTrue($this->page->isActive());
        self::assertTrue($this->page->getActive());

        $this->page->setActive('1');

        self::assertTrue($this->page->isActive());
        self::assertTrue($this->page->getActive());

        $this->page->setActive('false');

        self::assertFalse($this->page->isActive());
        self::assertFalse($this->page->getActive());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetActiveWithPages(): void
    {
        $childPage1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $childPage1->expects(self::exactly(2))
            ->method('isActive')
            ->with(true)
            ->willReturn(true);

        self::assertFalse($this->page->isActive(true));
        self::assertFalse($this->page->getActive(true));

        $this->page->addPage($childPage1);

        self::assertTrue($this->page->isActive(true));
        self::assertTrue($this->page->getActive(true));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetActiveWithRouteMatchWithoutRoute(): void
    {
        $routeResult = $this->getMockBuilder(RouteResult::class)
            ->disableOriginalConstructor()
            ->getMock();
        $routeResult->expects(self::once())
            ->method('getMatchedParams')
            ->willReturn(['test', 'abc']);
        $routeResult->expects(self::never())
            ->method('getMatchedRouteName');

        $params = ['test'];

        self::assertFalse($this->page->isActive());
        self::assertFalse($this->page->getActive());

        assert($routeResult instanceof RouteResult);
        $this->page->setRouteMatch($routeResult);
        $this->page->setParams($params);

        self::assertTrue($this->page->isActive());
        self::assertTrue($this->page->getActive());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetActiveWithRouteMatchWithRouteNotMatch(): void
    {
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
        $this->page->setRouteMatch($routeResult);
        $this->page->setParams($params);
        $this->page->setRoute($route);

        self::assertFalse($this->page->isActive());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetActiveWithRouteMatchWithRouteMatch(): void
    {
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
        $this->page->setRouteMatch($routeResult);
        $this->page->setParams($params);
        $this->page->setRoute($route);

        self::assertTrue($this->page->isActive());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSetWithException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $property must be a non-empty string');
        $this->expectExceptionCode(0);

        $this->page->set('', null);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testGetWithException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $property must be a non-empty string');
        $this->expectExceptionCode(0);

        $this->page->get('');
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testGetSet(): void
    {
        $target = 'test2';
        $test   = 'test 42';
        $abc    = '4711';

        self::assertNull($this->page->get('test'));

        $this->page->set('target', $target);
        $this->page->set('test', $test);
        $this->page->abc = $abc;

        self::assertSame($target, $this->page->get('target'));
        self::assertSame($test, $this->page->get('test'));
        self::assertSame($abc, $this->page->abc);

        self::assertTrue(isset($this->page->target));
        self::assertTrue(isset($this->page->test));

        self::assertSame(['test' => 'test 42', 'abc' => '4711'], $this->page->getCustomProperties());

        unset($this->page->test, $this->page->test);

        self::assertFalse(isset($this->page->test));
        self::assertSame(['abc' => '4711'], $this->page->getCustomProperties());
    }

    public function testUnset(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsetting native property "target" is not allowed');
        $this->expectExceptionCode(0);

        unset($this->page->target);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testToString(): void
    {
        self::assertSame('', (string) $this->page);

        $label = 'test';

        $this->page->setLabel($label);

        self::assertSame($label, (string) $this->page);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testHashCode(): void
    {
        $label = 'test';

        $this->page->setLabel($label);

        $expected = spl_object_hash($this->page);

        self::assertSame($expected, $this->page->hashCode());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetQuery(): void
    {
        $query = 'test';

        self::assertNull($this->page->getQuery());

        $this->page->setQuery($query);

        self::assertSame($query, $this->page->getQuery());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetParams(): void
    {
        $params = ['test'];

        self::assertSame([], $this->page->getParams());

        $this->page->setParams($params);

        self::assertSame($params, $this->page->getParams());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSetRouteException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $route must be a non-empty string');
        $this->expectExceptionCode(0);

        $this->page->setRoute('');
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetRouteMatch(): void
    {
        $routeResult = $this->createMock(RouteResult::class);

        self::assertNull($this->page->getRouteMatch());

        assert($routeResult instanceof RouteResult);
        $this->page->setRouteMatch($routeResult);

        self::assertSame($routeResult, $this->page->getRouteMatch());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetUseRouteMatch(): void
    {
        self::assertFalse($this->page->useRouteMatch());

        $this->page->setUseRouteMatch(true);

        self::assertTrue($this->page->useRouteMatch());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     */
    public function testSetRouter(): void
    {
        $router = $this->createMock(RouterInterface::class);

        self::assertNull($this->page->getRouter());

        assert($router instanceof RouterInterface);
        $this->page->setRouter($router);

        self::assertSame($router, $this->page->getRouter());
    }

    /**
     * @throws DomainException
     * @throws RuntimeException
     */
    public function testGetHrefMissingRouter(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Mezzio\Navigation\Page\Route::getHref cannot execute as no Mezzio\Router\RouterInterface instance is composed');
        $this->expectExceptionCode(0);

        $this->page->getHref();
    }

    /**
     * @throws DomainException
     * @throws RuntimeException
     */
    public function testGetHrefMissingRoute(): void
    {
        $router = $this->createMock(RouterInterface::class);

        assert($router instanceof RouterInterface);
        $this->page->setRouter($router);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('No route name could be found');
        $this->expectExceptionCode(0);

        $this->page->getHref();
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws DomainException
     * @throws RuntimeException
     */
    public function testGetHrefWithRoute(): void
    {
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
        $this->page->setRouter($router);
        $this->page->setRoute($route);

        $uri = $this->page->getHref();

        self::assertSame($expectedUri, $uri);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws DomainException
     * @throws RuntimeException
     */
    public function testGetHrefWithRouteMatch(): void
    {
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
        $this->page->setRouter($router);

        assert($routeResult instanceof RouteResult);
        $this->page->setRouteMatch($routeResult);

        $this->page->setUseRouteMatch(true);

        $uri = $this->page->getHref();

        self::assertSame($expectedUri, $uri);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws DomainException
     * @throws RuntimeException
     */
    public function testGetHrefWithFragmentIdentifier(): void
    {
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
        $this->page->setRouter($router);

        assert($routeResult instanceof RouteResult);
        $this->page->setRouteMatch($routeResult);

        $this->page->setUseRouteMatch(true);
        $this->page->setFragment($fragment);

        $uri = $this->page->getHref();

        self::assertSame($expectedUri, $uri);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws DomainException
     * @throws RuntimeException
     */
    public function testGetHrefWithQuery(): void
    {
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
        $this->page->setRouter($router);

        assert($routeResult instanceof RouteResult);
        $this->page->setRouteMatch($routeResult);

        $this->page->setUseRouteMatch(true);
        $this->page->setQuery($query);

        $this->page->getHref();

        $uri = $this->page->getHref();

        self::assertSame($expectedUri, $uri);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testToArray(): void
    {
        $params      = ['testParams'];
        $route       = 'testRoute';
        $router      = $this->createMock(RouterInterface::class);
        $routeResult = $this->createMock(RouteResult::class);

        $this->page->setParams($params);
        $this->page->setRoute($route);

        assert($router instanceof RouterInterface);
        $this->page->setRouter($router);

        assert($routeResult instanceof RouteResult);
        $this->page->setRouteMatch($routeResult);

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
            'resource' => null,
            'privilege' => null,
            'permission' => null,
            'active' => false,
            'visible' => true,
            'type' => Route::class,
            'pages' => [],
            'params' => $params,
            'route' => $route,
            'router' => $router,
            'route_match' => $routeResult,
        ];

        $result = $this->page->toArray();

        self::assertIsArray($result);
        self::assertSame($expected, $result);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testAddSelfAsChild(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');
        $this->expectExceptionCode(0);

        $this->page->addPage($this->page);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testAddChildPageTwice(): void
    {
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
            ->with($this->page);

        assert($childPage instanceof PageInterface);
        $this->page->addPage($childPage);
        $this->page->addPage($childPage);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testAddChildPageSelf(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');
        $this->expectExceptionCode(0);

        $this->page->addPage($this->page);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testAddPages(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $page must be an Instance of PageInterface');
        $this->expectExceptionCode(0);

        $this->page->addPages(['test']);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageByIndex(): void
    {
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
            ->with($this->page);

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
            ->with($this->page);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->removePage(1));
        self::assertSame([$code2 => $childPage2], $this->page->getPages());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageByObject(): void
    {
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
            ->with($this->page);

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
            ->with($this->page);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->removePage($childPage2));
        self::assertSame([$code1 => $childPage1], $this->page->getPages());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageNotByHash(): void
    {
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
            ->with($this->page);

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
            ->with($this->page);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertFalse($this->page->removePage($code1));
        self::assertSame([$code1 => $childPage1, $code2 => $childPage2], $this->page->getPages());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageNotExistingPage(): void
    {
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
            ->with($this->page);

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
            ->with($this->page);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertFalse($this->page->removePage(3));
        self::assertSame([$code1 => $childPage1, $code2 => $childPage2], $this->page->getPages());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageRecursive(): void
    {
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
            ->with($this->page);

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
            ->with($this->page);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(true);
        $childPage1->expects(self::once())
            ->method('removePage')
            ->with($childPage2, true);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertTrue($this->page->removePage($childPage2, true));
        self::assertSame([$code1 => $childPage1], $this->page->getPages());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageRecursiveNotFound(): void
    {
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
            ->with($this->page);

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
            ->with($this->page);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(false);
        $childPage1->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertFalse($this->page->removePage($childPage2, true));
        self::assertSame([$code1 => $childPage1], $this->page->getPages());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasPageByIndex(): void
    {
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
            ->with($this->page);

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
            ->with($this->page);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->hasPage(1));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasPageByObject(): void
    {
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
            ->with($this->page);

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
            ->with($this->page);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->hasPage($childPage2));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasPageNotByHash(): void
    {
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
            ->with($this->page);

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
            ->with($this->page);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertFalse($this->page->hasPage($code1));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasNotExistingPage(): void
    {
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
            ->with($this->page);

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
            ->with($this->page);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertFalse($this->page->hasPage(3));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasPageRecursive(): void
    {
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
            ->with($this->page);

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
            ->with($this->page);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(true);
        $childPage1->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertTrue($this->page->hasPage($childPage2, true));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasPageRecursiveNotFound(): void
    {
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
            ->with($this->page);

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
            ->with($this->page);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(false);
        $childPage1->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertFalse($this->page->hasPage($childPage2, true));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasNoVisiblePages(): void
    {
        self::assertFalse($this->page->hasPages());

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
            ->with($this->page);
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
            ->with($this->page);
        $childPage2->expects(self::once())
            ->method('isVisible')
            ->willReturn(false);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->hasPages());
        self::assertFalse($this->page->hasPages(true));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasVisiblePages(): void
    {
        self::assertFalse($this->page->hasPages());

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
            ->with($this->page);
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
            ->with($this->page);
        $childPage2->expects(self::once())
            ->method('isVisible')
            ->willReturn(true);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->hasPages());
        self::assertTrue($this->page->hasPages(true));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testFindOneBy(): void
    {
        $property = 'route';
        $value    = 'test';

        self::assertNull($this->page->findOneBy($property, $value));

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
            ->with($this->page);
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
            ->with($this->page);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertSame($childPage2, $this->page->findOneBy($property, $value));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testFindAllBy(): void
    {
        $property = 'route';
        $value    = 'test';

        self::assertSame([], $this->page->findAllBy($property, $value));

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
            ->with($this->page);
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
            ->with($this->page);
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
            ->with($this->page);
        $childPage3->expects(self::never())
            ->method('isVisible');
        $childPage3->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn(null);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        assert($childPage3 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);
        $this->page->addPage($childPage3);

        self::assertSame([$childPage2, $childPage1], $this->page->findAllBy($property, $value));
    }

    /**
     * @throws void
     */
    public function testCallFindAllByException(): void
    {
        $value = 'test';

        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Bad method call: Unknown method Mezzio\Navigation\Page\Route::findAlllByTest');
        $this->expectExceptionCode(0);

        $this->page->findAlllByTest($value);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testCallFindAllBy(): void
    {
        $property = 'Route';
        $value    = 'test';

        self::assertSame([], $this->page->findAllByRoute($value));

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
            ->with($this->page);
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
            ->with($this->page);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertSame([$childPage2, $childPage1], $this->page->findAllByRoute($value));
    }

    /**
     * @throws OutOfBoundsException
     */
    public function testCurrentException(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('container is currently empty, could not find any key in internal iterator');
        $this->expectExceptionCode(0);

        $this->page->current();
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    public function testCurrent(): void
    {
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
            ->with($this->page);
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
            ->with($this->page);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::never())
            ->method('get');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertSame($childPage2, $this->page->current());
        self::assertSame($code2, $this->page->key());
        self::assertTrue($this->page->valid());

        $this->page->next();

        self::assertSame($childPage1, $this->page->current());
        self::assertSame($code1, $this->page->key());
        self::assertTrue($this->page->valid());

        $this->page->next();

        self::assertSame('', $this->page->key());
        self::assertFalse($this->page->valid());

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Corruption detected in container; invalid key found in internal iterator');
        $this->expectExceptionCode(0);

        $this->page->current();
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    public function testRewind(): void
    {
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
            ->with($this->page);
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
            ->with($this->page);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::never())
            ->method('get');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertSame($childPage2, $this->page->current());
        self::assertSame($code2, $this->page->key());
        self::assertTrue($this->page->valid());

        $this->page->next();

        self::assertSame($childPage1, $this->page->current());
        self::assertSame($code1, $this->page->key());
        self::assertTrue($this->page->valid());

        $this->page->rewind();

        self::assertSame($childPage2, $this->page->current());
        self::assertSame($code2, $this->page->key());
        self::assertTrue($this->page->valid());
    }

    /**
     * @ throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @ throws \PHPUnit\Framework\Exception
     * @ throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @throws SyntheticSkippedError
     * @throws SkippedTestError
     */
    public function testHasChildren(): void
    {
        self::markTestSkipped();
//        self::assertFalse($this->page->hasChildren());
//
//        $code1 = 'code 1';
//        $code2 = 'code 2';
//
//        $childPage1 = $this->getMockBuilder(PageInterface::class)
//            ->disableOriginalConstructor()
//            ->getMock();
//        $childPage1->expects(self::once())
//            ->method('hashCode')
//            ->willReturn($code1);
//        $childPage1->expects(self::exactly(2))
//            ->method('getOrder')
//            ->willReturn(1);
//        $childPage1->expects(self::once())
//            ->method('setParent')
//            ->with($this->page);
//        $childPage1->expects(self::never())
//            ->method('isVisible');
//        $childPage1->expects(self::never())
//            ->method('get');
//
//        $childPage2 = $this->getMockBuilder(PageInterface::class)
//            ->disableOriginalConstructor()
//            ->getMock();
//        $childPage2->expects(self::once())
//            ->method('hashCode')
//            ->willReturn($code2);
//        $childPage2->expects(self::exactly(2))
//            ->method('getOrder')
//            ->willReturn(null);
//        $childPage2->expects(self::once())
//            ->method('setParent')
//            ->with($this->page);
//        $childPage2->expects(self::never())
//            ->method('isVisible');
//        $childPage2->expects(self::never())
//            ->method('get');
//
//        /* @var PageInterface $childPage1 */
//        /* @var PageInterface $childPage2 */
//        $this->page->addPage($childPage1);
//        $this->page->addPage($childPage2);
//
//        self::assertTrue($this->page->hasPages());
//        self::assertTrue($this->page->hasChildren());
    }
}
