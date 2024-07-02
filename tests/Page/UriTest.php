<?php
/**
 * This file is part of the mimmi20/mezzio-navigation package.
 *
 * Copyright (c) 2020-2024, Thomas Mueller <mimmi20@live.de>
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
    private Uri $page;

    /** @throws InvalidArgumentException */
    protected function setUp(): void
    {
        $this->page = new Uri();
    }

    /** @throws Exception */
    public function testConstructorWithoutParameters(): void
    {
        self::assertSame([], $this->page->getPages());
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
        $label = 'test';

        $this->page->setOptions(['label' => $label]);

        self::assertSame($label, $this->page->getLabel());
    }

    /** @throws Exception */
    public function testSetLabel(): void
    {
        $label = 'test';

        $this->page->setLabel($label);

        self::assertSame($label, $this->page->getLabel());
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
        $fragment = 'test';

        $this->page->setOptions(['fragment' => $fragment]);

        self::assertSame($fragment, $this->page->getFragment());
    }

    /** @throws Exception */
    public function testSetFragment(): void
    {
        $fragment = 'test';

        $this->page->setFragment($fragment);

        self::assertSame($fragment, $this->page->getFragment());
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
        $id = 'test';

        $this->page->setOptions(['id' => $id]);

        self::assertSame($id, $this->page->getId());
    }

    /** @throws Exception */
    public function testSetId(): void
    {
        $id = 'test';

        $this->page->setId($id);

        self::assertSame($id, $this->page->getId());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorClass(): void
    {
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
        $class = 'test';

        $this->page->setOptions(['class' => $class]);

        self::assertSame($class, $this->page->getClass());
    }

    /** @throws Exception */
    public function testSetClass(): void
    {
        $class = 'test';

        $this->page->setClass($class);

        self::assertSame($class, $this->page->getClass());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorLiClass(): void
    {
        $class = 'test';

        $this->page = new Uri(['liClass' => $class]);

        self::assertSame($class, $this->page->getLiClass());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetOptionsWithLiClass(): void
    {
        $class = 'test';

        $this->page->setOptions(['liClass' => $class]);

        self::assertSame($class, $this->page->getLiClass());
    }

    /** @throws Exception */
    public function testSetLiClass(): void
    {
        $class = 'test';

        $this->page->setLiClass($class);

        self::assertSame($class, $this->page->getLiClass());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorTitle(): void
    {
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
        $title = 'test';

        $this->page->setOptions(['title' => $title]);

        self::assertSame($title, $this->page->getTitle());
    }

    /** @throws Exception */
    public function testSetTitle(): void
    {
        $title = 'test';

        $this->page->setTitle($title);

        self::assertSame($title, $this->page->getTitle());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testConstructorTarget(): void
    {
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
        $target = 'test';

        $this->page->setOptions(['target' => $target]);

        self::assertSame($target, $this->page->getTarget());
    }

    /** @throws Exception */
    public function testSetTarget(): void
    {
        $target = 'test';

        $this->page->setTarget($target);

        self::assertSame($target, $this->page->getTarget());
    }

    /** @throws Exception */
    public function testSetRel(): void
    {
        $relValue = 'test1';
        $relKey   = 'test';

        $this->page->setRel();

        self::assertSame([], $this->page->getRel());

        $this->page->setRel([$relKey => $relValue, 42 => 'tests']);

        self::assertSame([$relKey => $relValue], $this->page->getRel());
        self::assertSame($relValue, $this->page->getRel($relKey));

        self::assertCount(1, $this->page->getRel());

        $this->page->addRel('test2', 'test2');

        self::assertCount(2, (array) $this->page->getRel());

        $this->page->removeRel('test');

        self::assertCount(1, (array) $this->page->getRel());

        $this->page->removeRel('test4');

        self::assertCount(1, (array) $this->page->getRel());

        self::assertSame(['test2'], $this->page->getDefinedRel());
    }

    /** @throws Exception */
    public function testSetRev(): void
    {
        $revValue = 'test1';
        $revKey   = 'test';

        $this->page->setRev();

        self::assertSame([], $this->page->getRev());

        $this->page->setRev([$revKey => $revValue, 42 => 'tests']);

        self::assertSame([$revKey => $revValue], $this->page->getRev());
        self::assertSame($revValue, $this->page->getRev($revKey));

        self::assertCount(1, $this->page->getRev());

        $this->page->addRev('test2', 'test2');

        self::assertCount(2, (array) $this->page->getRev());

        $this->page->removeRev('test');

        self::assertCount(1, (array) $this->page->getRev());

        $this->page->removeRev('test4');

        self::assertCount(1, (array) $this->page->getRev());

        self::assertSame(['test2'], $this->page->getDefinedRev());
    }

    /**
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

    /** @throws Exception */
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

    /** @throws Exception */
    public function testSetResource(): void
    {
        $resource = 'test';

        self::assertNull($this->page->getResource());

        $this->page->setResource($resource);

        self::assertSame($resource, $this->page->getResource());
    }

    /** @throws Exception */
    public function testSetPrivilege(): void
    {
        $privilege = 'test';

        self::assertNull($this->page->getPrivilege());

        $this->page->setPrivilege($privilege);

        self::assertSame($privilege, $this->page->getPrivilege());
    }

    /** @throws Exception */
    public function testSetPermission(): void
    {
        $permission = 'test';

        self::assertNull($this->page->getPermission());

        $this->page->setPermission($permission);

        self::assertSame($permission, $this->page->getPermission());
    }

    /** @throws Exception */
    public function testSetTextDomain(): void
    {
        $textDomain = 'test';

        self::assertNull($this->page->getTextDomain());

        $this->page->setTextDomain($textDomain);

        self::assertSame($textDomain, $this->page->getTextDomain());
    }

    /** @throws Exception */
    public function testSetVisible(): void
    {
        $visible = false;

        self::assertTrue($this->page->isVisible());
        self::assertTrue($this->page->getVisible());

        $this->page->setVisible($visible);

        self::assertFalse($this->page->isVisible());
        self::assertFalse($this->page->getVisible());
    }

    /**
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

        $this->page->setVisible('1');

        self::assertTrue($this->page->isVisible());
        self::assertTrue($this->page->getVisible());

        $this->page->setVisible('false');

        self::assertFalse($this->page->isVisible());
        self::assertFalse($this->page->getVisible());
    }

    /** @throws Exception */
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

    /** @throws InvalidArgumentException */
    public function testSetWithException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $property must be a non-empty string');
        $this->expectExceptionCode(0);

        $this->page->set('', null);
    }

    /** @throws InvalidArgumentException */
    public function testGetWithException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $property must be a non-empty string');
        $this->expectExceptionCode(0);

        $this->page->get('');
    }

    /**
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

        self::assertObjectNotHasProperty('test', $this->page);
        self::assertSame(['abc' => '4711'], $this->page->getCustomProperties());
    }

    /** @throws void */
    public function testUnset(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsetting native property "target" is not allowed');
        $this->expectExceptionCode(0);

        unset($this->page->target);
    }

    /** @throws Exception */
    public function testToString(): void
    {
        self::assertSame('', (string) $this->page);

        $label = 'test';

        $this->page->setLabel($label);

        self::assertSame($label, (string) $this->page);
    }

    /** @throws Exception */
    public function testHashCode(): void
    {
        $label = 'test';

        $this->page->setLabel($label);

        $expected = spl_object_hash($this->page);

        self::assertSame($expected, $this->page->hashCode());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testUriOptionAsString(): void
    {
        $this->page = new Uri(
            [
                'label' => 'foo',
                'uri' => '#',
            ],
        );

        self::assertSame('#', $this->page->getUri());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testUriOptionAsNull(): void
    {
        $this->page = new Uri(
            [
                'label' => 'foo',
                'uri' => null,
            ],
        );

        self::assertNull($this->page->getUri(), 'getUri() should return null');
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetAndGetUri(): void
    {
        $this->page = new Uri(
            [
                'label' => 'foo',
                'uri' => '#',
            ],
        );

        $this->page->setUri('http://www.example.com/');
        $this->page->setUri('about:blank');

        self::assertSame('about:blank', $this->page->getUri());
    }

    /** @throws Exception */
    public function testGetHref(): void
    {
        $uri = 'spotify:album:4YzcWwBUSzibRsqD9Sgu4A';

        $this->page->setUri($uri);

        self::assertSame($uri, $this->page->getHref());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testIsActiveReturnsTrueWhenHasMatchingRequestUri(): void
    {
        $url        = '/bar';
        $this->page = new Uri(
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
        $this->page->setRequest($request);

        self::assertSame($request, $this->page->getRequest());
        self::assertTrue($this->page->isActive());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testIsActiveReturnsFalseOnNonMatchingRequestUri(): void
    {
        $url1       = '/bar';
        $url2       = '/baz';
        $this->page = new Uri(
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
        $this->page->setRequest($request);

        self::assertSame($request, $this->page->getRequest());
        self::assertFalse($this->page->isActive());
    }

    /** @throws Exception */
    public function testGetHrefWithFragmentIdentifier(): void
    {
        $uri = 'http://www.example.com/foo.html';

        $this->page->setUri($uri);
        $this->page->setFragment('bar');

        self::assertSame($uri . '#bar', $this->page->getHref());

        $this->page->setUri('#');

        self::assertSame('#bar', $this->page->getHref());
    }

    /** @throws InvalidArgumentException */
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

    /** @throws InvalidArgumentException */
    public function testAddChildPageSelf(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');
        $this->expectExceptionCode(0);

        $this->page->addPage($this->page);
    }

    /** @throws InvalidArgumentException */
    public function testAddPages(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $page must be an Instance of PageInterface');
        $this->expectExceptionCode(0);

        $this->page->addPages(['test']);
    }

    /**
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
            ->with($this->page);
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
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->removePage(1));
        self::assertSame([$code2 => $childPage2], $this->page->getPages());
    }

    /**
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
            ->with($this->page);
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
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->removePage($childPage2));
        self::assertSame([$code1 => $childPage1], $this->page->getPages());
    }

    /**
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
            ->with($this->page);
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
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertFalse($this->page->removePage(3));
        self::assertSame([$code1 => $childPage1, $code2 => $childPage2], $this->page->getPages());
    }

    /**
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
            ->with($this->page);
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
        $this->page->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertTrue($this->page->removePage($childPage2, true));
        self::assertSame([$code1 => $childPage1], $this->page->getPages());
    }

    /**
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
            ->with($this->page);
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
        $this->page->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertFalse($this->page->removePage($childPage2, true));
        self::assertSame([$code1 => $childPage1], $this->page->getPages());
    }

    /**
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
            ->with($this->page);
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
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->hasPage(1));
    }

    /**
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
            ->with($this->page);
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
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->hasPage($childPage2));
    }

    /**
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
            ->with($this->page);
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
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertFalse($this->page->hasPage(3));
    }

    /**
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
            ->with($this->page);
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
        $this->page->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertTrue($this->page->hasPage($childPage2, true));
    }

    /**
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
            ->with($this->page);
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
        $this->page->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertFalse($this->page->hasPage($childPage2, true));
    }

    /**
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
            ->with($this->page);
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
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->hasPages());
        self::assertFalse($this->page->hasPages(true));
    }

    /**
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
            ->with($this->page);
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
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->hasPages());
        self::assertTrue($this->page->hasPages(true));
    }

    /**
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
            ->with($this->page);
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
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertSame($childPage2, $this->page->findOneBy($property, $value));
    }

    /**
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
            ->with($this->page);
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
            ->with($this->page);
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
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);
        $this->page->addPage($childPage3);

        self::assertSame([$childPage2, $childPage1], $this->page->findAllBy($property, $value));
    }

    /** @throws void */
    public function testCallFindAllByException(): void
    {
        $value = 'test';

        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage(
            'Bad method call: Unknown method Mimmi20\Mezzio\Navigation\Page\Uri::findAlllByTest',
        );
        $this->expectExceptionCode(0);

        $this->page->findAlllByTest($value);
    }

    /**
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
            ->with($this->page);
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
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertSame([$childPage2, $childPage1], $this->page->findAllByRoute($value));
    }

    /** @throws OutOfBoundsException */
    public function testCurrentException(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage(
            'container is currently empty, could not find any key in internal iterator',
        );
        $this->expectExceptionCode(0);

        $this->page->current();
    }

    /**
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
            ->with($this->page);
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
        $this->expectExceptionMessage(
            'Corruption detected in container; invalid key found in internal iterator',
        );
        $this->expectExceptionCode(0);

        self::assertSame($childPage1, $this->page->current());
    }

    /**
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
            ->with($this->page);
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
}
