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

use Mezzio\Navigation\ContainerInterface;
use Mezzio\Navigation\Exception\DomainException;
use Mezzio\Navigation\Exception\InvalidArgumentException;
use Mezzio\Navigation\Page\PageInterface;
use Mezzio\Navigation\Page\Route;
use Mezzio\Router\RouteResult;
use Mezzio\Router\RouterInterface;
use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase
{
    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testConstructorWithoutParameters(): void
    {
        $page = new Route();

        self::assertSame([], $page->getPages());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testConstructorWithRoute(): void
    {
        $route = 'test';

        $page = new Route(['route' => $route]);

        self::assertSame($route, $page->getRoute());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetOptionsWithRoute(): void
    {
        $route = 'test';

        $page = new Route();
        $page->setOptions(['route' => $route]);

        self::assertSame($route, $page->getRoute());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetRoute(): void
    {
        $route = 'test';

        $page = new Route();
        $page->setRoute($route);

        self::assertSame($route, $page->getRoute());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testConstructorWithLabel(): void
    {
        $label = 'test';

        $page = new Route(['label' => $label]);

        self::assertSame($label, $page->getLabel());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetOptionsWithLabel(): void
    {
        $label = 'test';

        $page = new Route();
        $page->setOptions(['label' => $label]);

        self::assertSame($label, $page->getLabel());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetLabel(): void
    {
        $label = 'test';

        $page = new Route();
        $page->setLabel($label);

        self::assertSame($label, $page->getLabel());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testConstructorWithFragment(): void
    {
        $fragment = 'test';

        $page = new Route(['fragment' => $fragment]);

        self::assertSame($fragment, $page->getFragment());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetOptionsWithFragment(): void
    {
        $fragment = 'test';

        $page = new Route();
        $page->setOptions(['fragment' => $fragment]);

        self::assertSame($fragment, $page->getFragment());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetFragment(): void
    {
        $fragment = 'test';

        $page = new Route();
        $page->setFragment($fragment);

        self::assertSame($fragment, $page->getFragment());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testConstructorWithId(): void
    {
        $id = 'test';

        $page = new Route(['id' => $id]);

        self::assertSame($id, $page->getId());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetOptionsWithId(): void
    {
        $id = 'test';

        $page = new Route();
        $page->setOptions(['id' => $id]);

        self::assertSame($id, $page->getId());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetId(): void
    {
        $id = 'test';

        $page = new Route();
        $page->setId($id);

        self::assertSame($id, $page->getId());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testConstructorClass(): void
    {
        $class = 'test';

        $page = new Route(['class' => $class]);

        self::assertSame($class, $page->getClass());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetOptionsWithClass(): void
    {
        $class = 'test';

        $page = new Route();
        $page->setOptions(['class' => $class]);

        self::assertSame($class, $page->getClass());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetClass(): void
    {
        $class = 'test';

        $page = new Route();
        $page->setClass($class);

        self::assertSame($class, $page->getClass());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testConstructorTitle(): void
    {
        $title = 'test';

        $page = new Route(['title' => $title]);

        self::assertSame($title, $page->getTitle());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetOptionsWithTitle(): void
    {
        $title = 'test';

        $page = new Route();
        $page->setOptions(['title' => $title]);

        self::assertSame($title, $page->getTitle());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetTitle(): void
    {
        $title = 'test';

        $page = new Route();
        $page->setTitle($title);

        self::assertSame($title, $page->getTitle());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testConstructorTarget(): void
    {
        $target = 'test';

        $page = new Route(['target' => $target]);

        self::assertSame($target, $page->getTarget());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetOptionsWithTarget(): void
    {
        $target = 'test';

        $page = new Route();
        $page->setOptions(['target' => $target]);

        self::assertSame($target, $page->getTarget());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetTarget(): void
    {
        $target = 'test';

        $page = new Route();
        $page->setTarget($target);

        self::assertSame($target, $page->getTarget());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetRel(): void
    {
        $relValue = 'test1';
        $relKey   = 'test';

        $page = new Route();
        $page->setRel();

        self::assertSame([], $page->getRel());

        $page->setRel([$relKey => $relValue, 42 => 'tests']);

        self::assertSame([$relKey => $relValue], $page->getRel());
        self::assertSame($relValue, $page->getRel($relKey));

        self::assertCount(1, (array) $page->getRel());

        $page->addRel('test2', 'test2');

        self::assertCount(2, (array) $page->getRel());

        $page->removeRel('test');

        self::assertCount(1, (array) $page->getRel());

        $page->removeRel('test4');

        self::assertCount(1, (array) $page->getRel());

        self::assertSame(['test2'], $page->getDefinedRel());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetRev(): void
    {
        $revValue = 'test1';
        $revKey   = 'test';

        $page = new Route();
        $page->setRev();

        self::assertSame([], $page->getRev());

        $page->setRev([$revKey => $revValue, 42 => 'tests']);

        self::assertSame([$revKey => $revValue], $page->getRev());
        self::assertSame($revValue, $page->getRev($revKey));

        self::assertCount(1, (array) $page->getRev());

        $page->addRev('test2', 'test2');

        self::assertCount(2, (array) $page->getRev());

        $page->removeRev('test');

        self::assertCount(1, (array) $page->getRev());

        $page->removeRev('test4');

        self::assertCount(1, (array) $page->getRev());

        self::assertSame(['test2'], $page->getDefinedRev());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetParentException(): void
    {
        $page = new Route();
        self::assertNull($page->getParent());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');

        $page->setParent($page);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var ContainerInterface $parent */
        $page->setParent($parent);
        $page->setParent($parent);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var ContainerInterface $parent1 */
        /* @var ContainerInterface $parent2 */
        $page->setParent($parent1);
        self::assertSame($parent1, $page->getParent());

        $page->setParent($parent2);
        self::assertSame($parent2, $page->getParent());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetOrder(): void
    {
        $order = 42;

        $page = new Route();
        self::assertNull($page->getOrder());

        $page->setOrder($order);

        self::assertSame($order, $page->getOrder());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetOrderWithParent(): void
    {
        $order = 42;

        $page = new Route();

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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetResource(): void
    {
        $resource = 'test';

        $page = new Route();
        self::assertNull($page->getResource());

        $page->setResource($resource);

        self::assertSame($resource, $page->getResource());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetPrivilege(): void
    {
        $privilege = 'test';

        $page = new Route();
        self::assertNull($page->getPrivilege());

        $page->setPrivilege($privilege);

        self::assertSame($privilege, $page->getPrivilege());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetPermission(): void
    {
        $permission = 'test';

        $page = new Route();
        self::assertNull($page->getPermission());

        $page->setPermission($permission);

        self::assertSame($permission, $page->getPermission());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetTextDomain(): void
    {
        $textDomain = 'test';

        $page = new Route();
        self::assertNull($page->getTextDomain());

        $page->setTextDomain($textDomain);

        self::assertSame($textDomain, $page->getTextDomain());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetVisible(): void
    {
        $visible = false;

        $page = new Route();

        self::assertTrue($page->isVisible());
        self::assertTrue($page->getVisible());

        $page->setVisible($visible);

        self::assertFalse($page->isVisible());
        self::assertFalse($page->getVisible());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetVisibleWithParent(): void
    {
        $page = new Route();

        $parent1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parent1->expects(self::exactly(2))
            ->method('isVisible')
            ->willReturn(true);

        /* @var PageInterface $parent1 */
        $page->setParent($parent1);

        self::assertTrue($page->isVisible(true));
        self::assertTrue($page->getVisible(true));

        $parent2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parent2->expects(self::exactly(2))
            ->method('isVisible')
            ->willReturn(false);

        /* @var PageInterface $parent2 */
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetActive(): void
    {
        $active = true;

        $page = new Route();
        self::assertFalse($page->isActive());
        self::assertFalse($page->getActive());

        $page->setActive($active);

        self::assertTrue($page->isActive());
        self::assertTrue($page->getActive());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        $page = new Route();
        self::assertFalse($page->isActive(true));
        self::assertFalse($page->getActive(true));

        $page->addPage($childPage1);

        self::assertTrue($page->isActive(true));
        self::assertTrue($page->getActive(true));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        $page = new Route();
        self::assertFalse($page->isActive());
        self::assertFalse($page->getActive());

        /* @var RouteResult $routeResult */
        $page->setRouteMatch($routeResult);
        $page->setParams($params);

        self::assertTrue($page->isActive());
        self::assertTrue($page->getActive());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        $page = new Route();

        /* @var RouteResult $routeResult */
        $page->setRouteMatch($routeResult);
        $page->setParams($params);
        $page->setRoute($route);

        self::assertFalse($page->isActive());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        $page = new Route();

        /* @var RouteResult $routeResult */
        $page->setRouteMatch($routeResult);
        $page->setParams($params);
        $page->setRoute($route);

        self::assertTrue($page->isActive());
    }

    /**
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetWithException(): void
    {
        $page = new Route();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $property must be a non-empty string');

        $page->set('', null);
    }

    /**
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testGetWithException(): void
    {
        $page = new Route();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $property must be a non-empty string');

        $page->get('');
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testGetSet(): void
    {
        $target = 'test2';
        $test   = 'test 42';
        $abc    = '4711';

        $page = new Route();

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

        self::assertFalse(isset($page->test));
        self::assertSame(['abc' => '4711'], $page->getCustomProperties());
    }

    /**
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testUnset(): void
    {
        $page = new Route();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsetting native property "target" is not allowed');

        unset($page->target);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testHashCode(): void
    {
        $page = new Route();

        $label = 'test';

        $page->setLabel($label);

        $expected = spl_object_hash($page);

        self::assertSame($expected, $page->hashCode());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetQuery(): void
    {
        $query = 'test';

        $page = new Route();

        self::assertNull($page->getQuery());

        $page->setQuery($query);

        self::assertSame($query, $page->getQuery());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetParams(): void
    {
        $params = ['test'];

        $page = new Route();

        self::assertSame([], $page->getParams());

        $page->setParams($params);

        self::assertSame($params, $page->getParams());
    }

    /**
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetRouteException(): void
    {
        $page = new Route();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $route must be a non-empty string');

        $page->setRoute('');
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetRouteMatch(): void
    {
        $routeResult = $this->createMock(RouteResult::class);

        $page = new Route();

        self::assertNull($page->getRouteMatch());

        /* @var RouteResult $routeResult */
        $page->setRouteMatch($routeResult);

        self::assertSame($routeResult, $page->getRouteMatch());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetUseRouteMatch(): void
    {
        $page = new Route();

        self::assertFalse($page->useRouteMatch());

        $page->setUseRouteMatch(true);

        self::assertTrue($page->useRouteMatch());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetRouter(): void
    {
        $router = $this->createMock(RouterInterface::class);

        $page = new Route();

        self::assertNull($page->getRouter());

        /* @var RouterInterface $router */
        $page->setRouter($router);

        self::assertSame($router, $page->getRouter());
    }

    /**
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Mezzio\Navigation\Exception\DomainException
     * @throws \Mezzio\Router\Exception\RuntimeException
     *
     * @return void
     */
    public function testGetHrefMissingRouter(): void
    {
        $page = new Route();

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Mezzio\Navigation\Page\Route::getHref cannot execute as no Mezzio\Router\RouterInterface instance is composed');
        $page->getHref();
    }

    /**
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Mezzio\Navigation\Exception\DomainException
     * @throws \Mezzio\Router\Exception\RuntimeException
     *
     * @return void
     */
    public function testGetHrefMissingRoute(): void
    {
        $router = $this->createMock(RouterInterface::class);

        $page = new Route();

        /* @var RouterInterface $router */
        $page->setRouter($router);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('No route name could be found');
        $page->getHref();
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Mezzio\Navigation\Exception\DomainException
     * @throws \Mezzio\Router\Exception\RuntimeException
     *
     * @return void
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

        $page = new Route();

        /* @var RouterInterface $router */
        $page->setRouter($router);
        $page->setRoute($route);

        $uri = $page->getHref();

        self::assertSame($expectedUri, $uri);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Mezzio\Navigation\Exception\DomainException
     * @throws \Mezzio\Router\Exception\RuntimeException
     *
     * @return void
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

        $page = new Route();

        /* @var RouterInterface $router */
        $page->setRouter($router);

        /* @var RouteResult $routeResult */
        $page->setRouteMatch($routeResult);

        $page->setUseRouteMatch(true);

        $uri = $page->getHref();

        self::assertSame($expectedUri, $uri);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Mezzio\Navigation\Exception\DomainException
     * @throws \Mezzio\Router\Exception\RuntimeException
     *
     * @return void
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

        $page = new Route();

        /* @var RouterInterface $router */
        $page->setRouter($router);

        /* @var RouteResult $routeResult */
        $page->setRouteMatch($routeResult);

        $page->setUseRouteMatch(true);
        $page->setFragment($fragment);

        $uri = $page->getHref();

        self::assertSame($expectedUri, $uri);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Mezzio\Navigation\Exception\DomainException
     * @throws \Mezzio\Router\Exception\RuntimeException
     *
     * @return void
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

        $page = new Route();

        /* @var RouterInterface $router */
        $page->setRouter($router);

        /* @var RouteResult $routeResult */
        $page->setRouteMatch($routeResult);

        $page->setUseRouteMatch(true);
        $page->setQuery($query);

        $page->getHref();

        $uri = $page->getHref();

        self::assertSame($expectedUri, $uri);
    }
}
