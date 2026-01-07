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

use Mimmi20\Mezzio\Navigation\ContainerInterface;
use Mimmi20\Mezzio\Navigation\Exception\BadMethodCallException;
use Mimmi20\Mezzio\Navigation\Exception\InvalidArgumentException;
use Mimmi20\Mezzio\Navigation\Exception\OutOfBoundsException;
use Mimmi20\Mezzio\Navigation\Page\PageInterface;
use Mimmi20\Mezzio\Navigation\Page\Uri;
use PHPUnit\Event\NoPreviousThrowableException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

use function assert;
use function spl_object_hash;

/**
 * Tests the class Laminas_Navigation_Page_Uri
 */
#[Group('Laminas_Navigation')]
final class UriTest extends TestCase
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorWithoutParameters(): void
    {
        $page = new Uri();

        self::assertSame([], $page->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorWithLabel(): void
    {
        $label = 'test';

        $page = new Uri(['label' => $label]);

        self::assertSame($label, $page->getLabel());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithLabel(): void
    {
        $page  = new Uri();
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
        $page  = new Uri();
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
        $fragment = 'test';

        $page = new Uri(['fragment' => $fragment]);

        self::assertSame($fragment, $page->getFragment());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithFragment(): void
    {
        $page     = new Uri();
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
        $page     = new Uri();
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
        $id = 'test';

        $page = new Uri(['id' => $id]);

        self::assertSame($id, $page->getId());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithId(): void
    {
        $page = new Uri();
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
        $page = new Uri();
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
        $page  = new Uri();
        $class = 'test';

        $page = new Uri(['class' => $class]);

        self::assertSame($class, $page->getClass());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithClass(): void
    {
        $page  = new Uri();
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
        $page  = new Uri();
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
        $page  = new Uri();
        $class = 'test';

        $page = new Uri(['liClass' => $class]);

        self::assertSame($class, $page->getLiClass());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithLiClass(): void
    {
        $page  = new Uri();
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
        $page  = new Uri();
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
        $page  = new Uri();
        $title = 'test';

        $page = new Uri(['title' => $title]);

        self::assertSame($title, $page->getTitle());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithTitle(): void
    {
        $page  = new Uri();
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
        $page  = new Uri();
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
        $page   = new Uri();
        $target = 'test';

        $page = new Uri(['target' => $target]);

        self::assertSame($target, $page->getTarget());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithTarget(): void
    {
        $page   = new Uri();
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
        $page   = new Uri();
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
        $page     = new Uri();
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
        $page     = new Uri();
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
        $page = new Uri();

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
        $page = new Uri();

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
        $page = new Uri();

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
        $page  = new Uri();
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
        $page  = new Uri();
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
        $page     = new Uri();
        $resource = 'test';

        self::assertNull($page->getResource());

        $page->setResource($resource);

        self::assertSame($resource, $page->getResource());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetPrivilege(): void
    {
        $page      = new Uri();
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
        $page       = new Uri();
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
        $page    = new Uri();
        $visible = false;

        self::assertTrue($page->isVisible());
        self::assertTrue($page->getVisible());

        $page->setVisible($visible);

        self::assertFalse($page->isVisible());
        self::assertFalse($page->getVisible());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetVisibleWithParent(): void
    {
        $page    = new Uri();
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
    public function testSetActive(): void
    {
        $page   = new Uri();
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
        $page = new Uri();

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

    /** @throws InvalidArgumentException */
    public function testSetWithException(): void
    {
        $page = new Uri();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $property must be a non-empty string');
        $this->expectExceptionCode(0);

        $page->set('', null);
    }

    /** @throws InvalidArgumentException */
    public function testGetWithException(): void
    {
        $page = new Uri();

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
        $page   = new Uri();
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
        $page = new Uri();

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
        $page = new Uri();

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
        $page  = new Uri();
        $label = 'test';

        $page->setLabel($label);

        $expected = spl_object_hash($page);

        self::assertSame($expected, $page->hashCode());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testUriOptionAsString(): void
    {
        $page = new Uri(
            [
                'label' => 'foo',
                'uri' => '#',
            ],
        );

        self::assertSame('#', $page->getUri());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testUriOptionAsNull(): void
    {
        $page = new Uri(
            [
                'label' => 'foo',
                'uri' => null,
            ],
        );

        self::assertNull($page->getUri(), 'getUri() should return null');
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetAndGetUri(): void
    {
        $page = new Uri(
            [
                'label' => 'foo',
                'uri' => '#',
            ],
        );

        $page->setUri('http://www.example.com/');
        $page->setUri('about:blank');

        self::assertSame('about:blank', $page->getUri());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testGetHref(): void
    {
        $page = new Uri();
        $uri  = 'spotify:album:4YzcWwBUSzibRsqD9Sgu4A';

        $page->setUri($uri);

        self::assertSame($uri, $page->getHref());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testIsActiveReturnsTrueWhenHasMatchingRequestUri(): void
    {
        $url  = '/bar';
        $page = new Uri(
            [
                'label' => 'foo',
                'uri' => $url,
            ],
        );

        $uri = $this->createMock(UriInterface::class);
        $uri->expects(self::once())
            ->method('getPath')
            ->willReturn($url);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects(self::once())
            ->method('getUri')
            ->willReturn($uri);

        assert($request instanceof ServerRequestInterface);
        $page->setRequest($request);

        self::assertSame($request, $page->getRequest());
        self::assertTrue($page->isActive());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testIsActiveReturnsFalseOnNonMatchingRequestUri(): void
    {
        $url1 = '/bar';
        $url2 = '/baz';
        $page = new Uri(
            [
                'label' => 'foo',
                'uri' => $url1,
            ],
        );

        $uri = $this->createMock(UriInterface::class);
        $uri->expects(self::once())
            ->method('getPath')
            ->willReturn($url2);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects(self::once())
            ->method('getUri')
            ->willReturn($uri);

        assert($request instanceof ServerRequestInterface);
        $page->setRequest($request);

        self::assertSame($request, $page->getRequest());
        self::assertFalse($page->isActive());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testGetHrefWithFragmentIdentifier(): void
    {
        $page = new Uri();
        $uri  = 'http://www.example.com/foo.html';

        $page->setUri($uri);
        $page->setFragment('bar');

        self::assertSame($uri . '#bar', $page->getHref());

        $page->setUri('#');

        self::assertSame('#bar', $page->getHref());
    }

    /** @throws InvalidArgumentException */
    public function testAddSelfAsChild(): void
    {
        $page = new Uri();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');
        $this->expectExceptionCode(0);

        $page->addPage($page);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testAddChildPageTwice(): void
    {
        $page     = new Uri();
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
        $page = new Uri();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');
        $this->expectExceptionCode(0);

        $page->addPage($page);
    }

    /** @throws InvalidArgumentException */
    public function testAddPages(): void
    {
        $page = new Uri();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $page must be an Instance of PageInterface');
        $this->expectExceptionCode(0);

        $page->addPages(['test']);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageByIndex(): void
    {
        $page  = new Uri();
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
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

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
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

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
     */
    public function testRemovePageByObject(): void
    {
        $page  = new Uri();
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
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

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
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::never())
            ->method('get');
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

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
     */
    public function testRemovePageNotExistingPage(): void
    {
        $page  = new Uri();
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
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

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
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

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
     */
    public function testRemovePageRecursive(): void
    {
        $page  = new Uri();
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
        $childPage2->expects(self::never())
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::never())
            ->method('setParent')
            ->with($page);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::never())
            ->method('get');
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

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
     */
    public function testRemovePageRecursiveNotFound(): void
    {
        $page  = new Uri();
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
        $childPage2->expects(self::never())
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::never())
            ->method('setParent')
            ->with($page);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::never())
            ->method('get');
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

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
     */
    public function testHasPageByIndex(): void
    {
        $page  = new Uri();
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
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

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
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);

        self::assertTrue($page->hasPage(1));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasPageByObject(): void
    {
        $page  = new Uri();
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
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

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
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::never())
            ->method('get');
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);

        self::assertTrue($page->hasPage($childPage2));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasNotExistingPage(): void
    {
        $page  = new Uri();
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
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

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
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);

        self::assertFalse($page->hasPage(3));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasPageRecursive(): void
    {
        $page  = new Uri();
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
        $childPage2->expects(self::never())
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::never())
            ->method('setParent')
            ->with($page);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::never())
            ->method('get');
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

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
     */
    public function testHasPageRecursiveNotFound(): void
    {
        $page  = new Uri();
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
        $childPage2->expects(self::never())
            ->method('getOrder')
            ->willReturn(null);
        $childPage2->expects(self::never())
            ->method('setParent')
            ->with($page);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::never())
            ->method('get');
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

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
     */
    public function testHasNoVisiblePages(): void
    {
        $page = new Uri();

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
        $childPage1->expects(self::never())
            ->method('get');
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

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
        $childPage2->expects(self::never())
            ->method('get');
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

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
     */
    public function testHasVisiblePages(): void
    {
        $page = new Uri();

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
        $childPage1->expects(self::never())
            ->method('get');
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

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
        $childPage2->expects(self::never())
            ->method('get');
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

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
     */
    public function testFindOneBy(): void
    {
        $page     = new Uri();
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
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

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
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $page->addPage($childPage1);
        $page->addPage($childPage2);

        self::assertSame($childPage2, $page->findOneBy($property, $value));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testFindAllBy(): void
    {
        $page     = new Uri();
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
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

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
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

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
        $childPage3->expects(self::never())
            ->method('hasPage');
        $childPage3->expects(self::never())
            ->method('removePage');

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
        $page  = new Uri();
        $value = 'test';

        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage(
            'Bad method call: Unknown method Mimmi20\Mezzio\Navigation\Page\Uri::findAlllByTest',
        );
        $this->expectExceptionCode(0);

        $page->findAlllByTest($value);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testCallFindAllBy(): void
    {
        $page     = new Uri();
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
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

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
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

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
        $page = new Uri();

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
     */
    public function testCurrent(): void
    {
        $page  = new Uri();
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
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

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
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

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

        self::assertSame($childPage1, $page->current());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    public function testRewind(): void
    {
        $page  = new Uri();
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
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

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
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

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
