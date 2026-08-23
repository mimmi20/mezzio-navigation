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
#[Group(name: 'Laminas_Navigation')]
final class UriTest extends TestCase
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorWithoutParameters(): void
    {
        $uri = new Uri();

        self::assertSame([], $uri->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorWithLabel(): void
    {
        $label = 'test';

        $uri = new Uri(['label' => $label]);

        self::assertSame($label, $uri->getLabel());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithLabel(): void
    {
        $uri   = new Uri();
        $label = 'test';

        $uri->setOptions(['label' => $label]);

        self::assertSame($label, $uri->getLabel());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetLabel(): void
    {
        $uri   = new Uri();
        $label = 'test';

        $uri->setLabel($label);

        self::assertSame($label, $uri->getLabel());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorWithFragment(): void
    {
        $fragment = 'test';

        $uri = new Uri(['fragment' => $fragment]);

        self::assertSame($fragment, $uri->getFragment());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithFragment(): void
    {
        $uri      = new Uri();
        $fragment = 'test';

        $uri->setOptions(['fragment' => $fragment]);

        self::assertSame($fragment, $uri->getFragment());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetFragment(): void
    {
        $uri      = new Uri();
        $fragment = 'test';

        $uri->setFragment($fragment);

        self::assertSame($fragment, $uri->getFragment());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorWithId(): void
    {
        $id = 'test';

        $uri = new Uri(['id' => $id]);

        self::assertSame($id, $uri->getId());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithId(): void
    {
        $uri = new Uri();
        $id  = 'test';

        $uri->setOptions(['id' => $id]);

        self::assertSame($id, $uri->getId());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetId(): void
    {
        $uri = new Uri();
        $id  = 'test';

        $uri->setId($id);

        self::assertSame($id, $uri->getId());
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
        $uri   = new Uri();
        $class = 'test';

        $uri->setOptions(['class' => $class]);

        self::assertSame($class, $uri->getClass());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetClass(): void
    {
        $uri   = new Uri();
        $class = 'test';

        $uri->setClass($class);

        self::assertSame($class, $uri->getClass());
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
        $uri   = new Uri();
        $class = 'test';

        $uri->setOptions(['liClass' => $class]);

        self::assertSame($class, $uri->getLiClass());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetLiClass(): void
    {
        $uri   = new Uri();
        $class = 'test';

        $uri->setLiClass($class);

        self::assertSame($class, $uri->getLiClass());
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
        $uri   = new Uri();
        $title = 'test';

        $uri->setOptions(['title' => $title]);

        self::assertSame($title, $uri->getTitle());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetTitle(): void
    {
        $uri   = new Uri();
        $title = 'test';

        $uri->setTitle($title);

        self::assertSame($title, $uri->getTitle());
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
        $uri    = new Uri();
        $target = 'test';

        $uri->setOptions(['target' => $target]);

        self::assertSame($target, $uri->getTarget());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetTarget(): void
    {
        $uri    = new Uri();
        $target = 'test';

        $uri->setTarget($target);

        self::assertSame($target, $uri->getTarget());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetRel(): void
    {
        $uri      = new Uri();
        $relValue = 'test1';
        $relKey   = 'test';

        $uri->setRel();

        self::assertSame([], $uri->getRel());

        $uri->setRel([$relKey => $relValue, 42 => 'tests']);

        self::assertSame([$relKey => $relValue], $uri->getRel());
        self::assertSame($relValue, $uri->getRel($relKey));

        self::assertCount(1, $uri->getRel());

        $uri->addRel('test2', 'test2');

        self::assertCount(2, (array) $uri->getRel());

        $uri->removeRel('test');

        self::assertCount(1, (array) $uri->getRel());

        $uri->removeRel('test4');

        self::assertCount(1, (array) $uri->getRel());

        self::assertSame(['test2'], $uri->getDefinedRel());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetRev(): void
    {
        $uri      = new Uri();
        $revValue = 'test1';
        $revKey   = 'test';

        $uri->setRev();

        self::assertSame([], $uri->getRev());

        $uri->setRev([$revKey => $revValue, 42 => 'tests']);

        self::assertSame([$revKey => $revValue], $uri->getRev());
        self::assertSame($revValue, $uri->getRev($revKey));

        self::assertCount(1, $uri->getRev());

        $uri->addRev('test2', 'test2');

        self::assertCount(2, (array) $uri->getRev());

        $uri->removeRev('test');

        self::assertCount(1, (array) $uri->getRev());

        $uri->removeRev('test4');

        self::assertCount(1, (array) $uri->getRev());

        self::assertSame(['test2'], $uri->getDefinedRev());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetParentException(): void
    {
        $uri = new Uri();

        self::assertNull($uri->getParent());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');
        $this->expectExceptionCode(0);

        $uri->setParent($uri);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testDuplicateSetParent(): void
    {
        $uri = new Uri();

        $parent = $this->createMock(ContainerInterface::class);
        $parent->expects(self::never())
            ->method('removePage');
        $parent->expects(self::once())
            ->method('hasPage')
            ->with($uri, false)
            ->willReturn(value: false);
        $parent->expects(self::once())
            ->method('addPage')
            ->with($uri);

        assert($parent instanceof ContainerInterface);
        $uri->setParent($parent);
        $uri->setParent($parent);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testSetTwoParents(): void
    {
        $uri = new Uri();

        $parent1 = $this->createMock(ContainerInterface::class);
        $parent1->expects(self::once())
            ->method('removePage')
            ->with($uri);
        $parent1->expects(self::once())
            ->method('hasPage')
            ->with($uri, false)
            ->willReturn(value: false);
        $parent1->expects(self::once())
            ->method('addPage')
            ->with($uri);

        $parent2 = $this->createMock(ContainerInterface::class);
        $parent2->expects(self::never())
            ->method('removePage');
        $parent2->expects(self::once())
            ->method('hasPage')
            ->with($uri, false)
            ->willReturn(value: true);
        $parent2->expects(self::never())
            ->method('addPage');

        assert($parent1 instanceof ContainerInterface);
        assert($parent2 instanceof ContainerInterface);
        $uri->setParent($parent1);
        self::assertSame($parent1, $uri->getParent());

        $uri->setParent($parent2);
        self::assertSame($parent2, $uri->getParent());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOrder(): void
    {
        $uri   = new Uri();
        $order = 42;

        self::assertNull($uri->getOrder());

        $uri->setOrder($order);

        self::assertSame($order, $uri->getOrder());

        $uri->setOrder('42');

        self::assertSame($order, $uri->getOrder());

        $uri->setOrder(42.0);

        self::assertSame($order, $uri->getOrder());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testSetOrderWithParent(): void
    {
        $uri   = new Uri();
        $order = 42;

        $parent = $this->createMock(ContainerInterface::class);
        $parent->expects(self::once())
            ->method('notifyOrderUpdated');

        $uri->setParent($parent);
        $uri->setOrder($order);

        self::assertSame($order, $uri->getOrder());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetResource(): void
    {
        $uri      = new Uri();
        $resource = 'test';

        self::assertNull($uri->getResource());

        $uri->setResource($resource);

        self::assertSame($resource, $uri->getResource());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetPrivilege(): void
    {
        $uri       = new Uri();
        $privilege = 'test';

        self::assertNull($uri->getPrivilege());

        $uri->setPrivilege($privilege);

        self::assertSame($privilege, $uri->getPrivilege());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetTextDomain(): void
    {
        $uri        = new Uri();
        $textDomain = 'test';

        self::assertNull($uri->getTextDomain());

        $uri->setTextDomain($textDomain);

        self::assertSame($textDomain, $uri->getTextDomain());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetVisible(): void
    {
        $uri     = new Uri();
        $visible = false;

        self::assertTrue($uri->isVisible());
        self::assertTrue($uri->getVisible());

        $uri->setVisible($visible);

        self::assertFalse($uri->isVisible());
        self::assertFalse($uri->getVisible());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testSetVisibleWithParent(): void
    {
        $uri     = new Uri();
        $parent1 = $this->createMock(PageInterface::class);
        $parent1->expects(self::exactly(2))
            ->method('isVisible')
            ->willReturn(value: true);

        assert($parent1 instanceof PageInterface);
        $uri->setParent($parent1);

        self::assertTrue($uri->isVisible(recursive: true));
        self::assertTrue($uri->getVisible(recursive: true));

        $parent2 = $this->createMock(PageInterface::class);
        $parent2->expects(self::exactly(2))
            ->method('isVisible')
            ->willReturn(value: false);

        assert($parent2 instanceof PageInterface);
        $uri->setParent($parent2);

        self::assertFalse($uri->isVisible(recursive: true));
        self::assertFalse($uri->getVisible(recursive: true));

        $uri->setVisible(visible: false);

        self::assertFalse($uri->isVisible());
        self::assertFalse($uri->getVisible());

        $uri->setVisible(visible: true);

        self::assertTrue($uri->isVisible());
        self::assertTrue($uri->getVisible());

        $uri->setVisible('1');

        self::assertTrue($uri->isVisible());
        self::assertTrue($uri->getVisible());

        $uri->setVisible('false');

        self::assertFalse($uri->isVisible());
        self::assertFalse($uri->getVisible());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetActive(): void
    {
        $uri    = new Uri();
        $active = true;

        self::assertFalse($uri->isActive());
        self::assertFalse($uri->getActive());

        $uri->setActive($active);

        self::assertTrue($uri->isActive());
        self::assertTrue($uri->getActive());

        $uri->setActive('1');

        self::assertTrue($uri->isActive());
        self::assertTrue($uri->getActive());

        $uri->setActive('false');

        self::assertFalse($uri->isActive());
        self::assertFalse($uri->getActive());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testSetActiveWithPages(): void
    {
        $uri = new Uri();

        $childPage1 = $this->createMock(PageInterface::class);
        $childPage1->expects(self::exactly(2))
            ->method('isActive')
            ->with(true)
            ->willReturn(value: true);

        self::assertFalse($uri->isActive(recursive: true));
        self::assertFalse($uri->getActive(recursive: true));

        $uri->addPage($childPage1);

        self::assertTrue($uri->isActive(recursive: true));
        self::assertTrue($uri->getActive(recursive: true));
    }

    /** @throws InvalidArgumentException */
    public function testSetWithException(): void
    {
        $uri = new Uri();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $property must be a non-empty string');
        $this->expectExceptionCode(0);

        $uri->set('', value: null);
    }

    /** @throws InvalidArgumentException */
    public function testGetWithException(): void
    {
        $uri = new Uri();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $property must be a non-empty string');
        $this->expectExceptionCode(0);

        $uri->get('');
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testGetSet(): void
    {
        $uri    = new Uri();
        $target = 'test2';
        $test   = 'test 42';
        $abc    = '4711';

        self::assertNull($uri->get('test'));

        $uri->set('target', $target);
        $uri->set('test', $test);
        $uri->abc = $abc;

        self::assertSame($target, $uri->get('target'));
        self::assertSame($test, $uri->get('test'));
        self::assertSame($abc, $uri->abc);

        self::assertTrue(isset($uri->target));
        self::assertTrue(isset($uri->test));

        self::assertSame(['test' => 'test 42', 'abc' => '4711'], $uri->getCustomProperties());

        unset($uri->test, $uri->test);

        self::assertObjectNotHasProperty('test', $uri);
        self::assertSame(['abc' => '4711'], $uri->getCustomProperties());
    }

    /** @throws InvalidArgumentException */
    public function testUnset(): void
    {
        $uri = new Uri();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsetting native property "target" is not allowed');
        $this->expectExceptionCode(0);

        unset($uri->target);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testToString(): void
    {
        $uri = new Uri();

        self::assertSame('', (string) $uri);

        $label = 'test';

        $uri->setLabel($label);

        self::assertSame($label, (string) $uri);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHashCode(): void
    {
        $uri   = new Uri();
        $label = 'test';

        $uri->setLabel($label);

        $expected = spl_object_hash($uri);

        self::assertSame($expected, $uri->hashCode());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testUriOptionAsString(): void
    {
        $uri = new Uri(
            [
                'label' => 'foo',
                'uri' => '#',
            ],
        );

        self::assertSame('#', $uri->getUri());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testUriOptionAsNull(): void
    {
        $uri = new Uri(
            [
                'label' => 'foo',
                'uri' => null,
            ],
        );

        self::assertNull($uri->getUri(), 'getUri() should return null');
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetAndGetUri(): void
    {
        $uri = new Uri(
            [
                'label' => 'foo',
                'uri' => '#',
            ],
        );

        $uri->setUri('http://www.example.com/');
        $uri->setUri('about:blank');

        self::assertSame('about:blank', $uri->getUri());
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
        $uri = new Uri();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');
        $this->expectExceptionCode(0);

        $uri->addPage($uri);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testAddChildPageTwice(): void
    {
        $uri      = new Uri();
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
            ->with($uri);

        assert($childPage instanceof PageInterface);
        $uri->addPage($childPage);
        $uri->addPage($childPage);
    }

    /** @throws InvalidArgumentException */
    public function testAddChildPageSelf(): void
    {
        $uri = new Uri();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');
        $this->expectExceptionCode(0);

        $uri->addPage($uri);
    }

    /** @throws InvalidArgumentException */
    public function testAddPages(): void
    {
        $uri = new Uri();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $page must be an Instance of PageInterface');
        $this->expectExceptionCode(0);

        $uri->addPages(['test']);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRemovePageByIndex(): void
    {
        $uri   = new Uri();
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
            ->with($uri);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($uri);
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
        $uri->addPage($childPage1);
        $uri->addPage($childPage2);

        self::assertTrue($uri->removePage(1));
        self::assertSame([$code2 => $childPage2], $uri->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRemovePageByObject(): void
    {
        $uri   = new Uri();
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
            ->with($uri);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::exactly(2))
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::once())
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($uri);
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
        $uri->addPage($childPage1);
        $uri->addPage($childPage2);

        self::assertTrue($uri->removePage($childPage2));
        self::assertSame([$code1 => $childPage1], $uri->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRemovePageNotExistingPage(): void
    {
        $uri   = new Uri();
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
            ->with($uri);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($uri);
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
        $uri->addPage($childPage1);
        $uri->addPage($childPage2);

        self::assertFalse($uri->removePage(3));
        self::assertSame([$code1 => $childPage1, $code2 => $childPage2], $uri->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRemovePageRecursive(): void
    {
        $uri   = new Uri();
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
            ->with($uri);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::never())
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::never())
            ->method('setParent')
            ->with($uri);
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
            ->willReturn(value: true);
        $childPage1->expects(self::once())
            ->method('removePage')
            ->with($childPage2, true);

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $uri->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertTrue($uri->removePage($childPage2, recursive: true));
        self::assertSame([$code1 => $childPage1], $uri->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRemovePageRecursiveNotFound(): void
    {
        $uri   = new Uri();
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
            ->with($uri);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::never())
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::never())
            ->method('setParent')
            ->with($uri);
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
            ->willReturn(value: false);
        $childPage1->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $uri->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertFalse($uri->removePage($childPage2, recursive: true));
        self::assertSame([$code1 => $childPage1], $uri->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testHasPageByIndex(): void
    {
        $uri   = new Uri();
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
            ->with($uri);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($uri);
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
        $uri->addPage($childPage1);
        $uri->addPage($childPage2);

        self::assertTrue($uri->hasPage(1));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testHasPageByObject(): void
    {
        $uri   = new Uri();
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
            ->with($uri);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::exactly(2))
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::once())
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($uri);
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
        $uri->addPage($childPage1);
        $uri->addPage($childPage2);

        self::assertTrue($uri->hasPage($childPage2));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testHasNotExistingPage(): void
    {
        $uri   = new Uri();
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
            ->with($uri);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($uri);
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
        $uri->addPage($childPage1);
        $uri->addPage($childPage2);

        self::assertFalse($uri->hasPage(3));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testHasPageRecursive(): void
    {
        $uri   = new Uri();
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
            ->with($uri);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::never())
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::never())
            ->method('setParent')
            ->with($uri);
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
            ->willReturn(value: true);
        $childPage1->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $uri->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertTrue($uri->hasPage($childPage2, recursive: true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testHasPageRecursiveNotFound(): void
    {
        $uri   = new Uri();
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
            ->with($uri);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::never())
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::never())
            ->method('setParent')
            ->with($uri);
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
            ->willReturn(value: false);
        $childPage1->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $uri->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertFalse($uri->hasPage($childPage2, recursive: true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testHasNoVisiblePages(): void
    {
        $uri = new Uri();

        self::assertFalse($uri->hasPages());

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
            ->with($uri);
        $childPage1->expects(self::once())
            ->method('isVisible')
            ->willReturn(value: false);
        $childPage1->expects(self::never())
            ->method('get');
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::once())
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($uri);
        $childPage2->expects(self::once())
            ->method('isVisible')
            ->willReturn(value: false);
        $childPage2->expects(self::never())
            ->method('get');
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $uri->addPage($childPage1);
        $uri->addPage($childPage2);

        self::assertTrue($uri->hasPages());
        self::assertFalse($uri->hasPages(onlyVisible: true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testHasVisiblePages(): void
    {
        $uri = new Uri();

        self::assertFalse($uri->hasPages());

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
            ->with($uri);
        $childPage1->expects(self::once())
            ->method('isVisible')
            ->willReturn(value: false);
        $childPage1->expects(self::never())
            ->method('get');
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::once())
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($uri);
        $childPage2->expects(self::once())
            ->method('isVisible')
            ->willReturn(value: true);
        $childPage2->expects(self::never())
            ->method('get');
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        $uri->addPage($childPage1);
        $uri->addPage($childPage2);

        self::assertTrue($uri->hasPages());
        self::assertTrue($uri->hasPages(onlyVisible: true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindOneBy(): void
    {
        $uri      = new Uri();
        $property = 'route';
        $value    = 'test';

        self::assertNull($uri->findOneBy($property, $value));

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
            ->with($uri);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($uri);
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
        $uri->addPage($childPage1);
        $uri->addPage($childPage2);

        self::assertSame($childPage2, $uri->findOneBy($property, $value));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testFindAllBy(): void
    {
        $uri      = new Uri();
        $property = 'route';
        $value    = 'test';

        self::assertSame([], $uri->findAllBy($property, $value));

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
            ->with($uri);
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

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($uri);
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

        $childPage3 = $this->createMock(PageInterface::class);
        $childPage3->expects(self::once())
            ->method('hashCode')
            ->willReturn($code3);
        $childPage3->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage3->expects(self::once())
            ->method('setParent')
            ->with($uri);
        $childPage3->expects(self::never())
            ->method('isVisible');
        $childPage3->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn(value: null);
        $childPage3->expects(self::never())
            ->method('hasPage');
        $childPage3->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof PageInterface);
        assert($childPage2 instanceof PageInterface);
        assert($childPage3 instanceof PageInterface);
        $uri->addPage($childPage1);
        $uri->addPage($childPage2);
        $uri->addPage($childPage3);

        self::assertSame([$childPage2, $childPage1], $uri->findAllBy($property, $value));
    }

    /** @throws InvalidArgumentException */
    public function testCallFindAllByException(): void
    {
        $uri   = new Uri();
        $value = 'test';

        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage(
            'Bad method call: Unknown method Mimmi20\Mezzio\Navigation\Page\Uri::findAlllByTest',
        );
        $this->expectExceptionCode(0);

        $uri->findAlllByTest($value);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testCallFindAllBy(): void
    {
        $uri      = new Uri();
        $property = 'Route';
        $value    = 'test';

        self::assertSame([], $uri->findAllByRoute($value));

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
            ->with($uri);
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

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($uri);
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
        $uri->addPage($childPage1);
        $uri->addPage($childPage2);

        self::assertSame([$childPage2, $childPage1], $uri->findAllByRoute($value));
    }

    /**
     * @throws OutOfBoundsException
     * @throws InvalidArgumentException
     */
    public function testCurrentException(): void
    {
        $uri = new Uri();

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage(
            'container is currently empty, could not find any key in internal iterator',
        );
        $this->expectExceptionCode(0);

        $uri->current();
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testCurrent(): void
    {
        $uri   = new Uri();
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
            ->with($uri);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($uri);
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
        $uri->addPage($childPage1);
        $uri->addPage($childPage2);

        self::assertSame($childPage2, $uri->current());
        self::assertSame($code2, $uri->key());
        self::assertTrue($uri->valid());

        $uri->next();

        self::assertSame($childPage1, $uri->current());
        self::assertSame($code1, $uri->key());
        self::assertTrue($uri->valid());

        $uri->next();

        self::assertSame('', $uri->key());
        self::assertFalse($uri->valid());

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage(
            'Corruption detected in container; invalid key found in internal iterator',
        );
        $this->expectExceptionCode(0);

        self::assertSame($childPage1, $uri->current());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     * @throws NoPreviousThrowableException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testRewind(): void
    {
        $uri   = new Uri();
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
            ->with($uri);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

        $childPage2 = $this->createMock(PageInterface::class);
        $childPage2->expects(self::once())
            ->method('hashCode')
            ->willReturn($code2);
        $childPage2->expects(self::exactly(2))
            ->method('getOrder')
            ->willReturn(value: null);
        $childPage2->expects(self::once())
            ->method('setParent')
            ->with($uri);
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
        $uri->addPage($childPage1);
        $uri->addPage($childPage2);

        self::assertSame($childPage2, $uri->current());
        self::assertSame($code2, $uri->key());
        self::assertTrue($uri->valid());

        $uri->next();

        self::assertSame($childPage1, $uri->current());
        self::assertSame($code1, $uri->key());
        self::assertTrue($uri->valid());

        $uri->rewind();

        self::assertSame($childPage2, $uri->current());
        self::assertSame($code2, $uri->key());
        self::assertTrue($uri->valid());
    }
}
