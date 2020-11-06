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
use Mezzio\Navigation\Exception\BadMethodCallException;
use Mezzio\Navigation\Exception\InvalidArgumentException;
use Mezzio\Navigation\Exception\OutOfBoundsException;
use Mezzio\Navigation\Page\PageInterface;
use Mezzio\Navigation\Page\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * Tests the class Laminas_Navigation_Page_Uri
 *
 * @group      Laminas_Navigation
 */
final class UriTest extends TestCase
{
    /** @var \Mezzio\Navigation\Page\Uri */
    private $page;

    /**
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->page = new Uri();
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
    public function testConstructorWithoutParameters(): void
    {
        self::assertSame([], $this->page->getPages());
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

        $page = new Uri(['label' => $label]);

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

        $this->page->setOptions(['label' => $label]);

        self::assertSame($label, $this->page->getLabel());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
    public function testSetLabel(): void
    {
        $label = 'test';

        $this->page->setLabel($label);

        self::assertSame($label, $this->page->getLabel());
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

        $page = new Uri(['fragment' => $fragment]);

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

        $this->page->setOptions(['fragment' => $fragment]);

        self::assertSame($fragment, $this->page->getFragment());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
    public function testSetFragment(): void
    {
        $fragment = 'test';

        $this->page->setFragment($fragment);

        self::assertSame($fragment, $this->page->getFragment());
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

        $page = new Uri(['id' => $id]);

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

        $this->page->setOptions(['id' => $id]);

        self::assertSame($id, $this->page->getId());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
    public function testSetId(): void
    {
        $id = 'test';

        $this->page->setId($id);

        self::assertSame($id, $this->page->getId());
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

        $page = new Uri(['class' => $class]);

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

        $this->page->setOptions(['class' => $class]);

        self::assertSame($class, $this->page->getClass());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
    public function testSetClass(): void
    {
        $class = 'test';

        $this->page->setClass($class);

        self::assertSame($class, $this->page->getClass());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testConstructorLiClass(): void
    {
        $class = 'test';

        $this->page = new Uri(['liClass' => $class]);

        self::assertSame($class, $this->page->getLiClass());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetOptionsWithLiClass(): void
    {
        $class = 'test';

        $this->page->setOptions(['liClass' => $class]);

        self::assertSame($class, $this->page->getLiClass());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
    public function testSetLiClass(): void
    {
        $class = 'test';

        $this->page->setLiClass($class);

        self::assertSame($class, $this->page->getLiClass());
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

        $page = new Uri(['title' => $title]);

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

        $this->page->setOptions(['title' => $title]);

        self::assertSame($title, $this->page->getTitle());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
    public function testSetTitle(): void
    {
        $title = 'test';

        $this->page->setTitle($title);

        self::assertSame($title, $this->page->getTitle());
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

        $page = new Uri(['target' => $target]);

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

        $this->page->setOptions(['target' => $target]);

        self::assertSame($target, $this->page->getTarget());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
    public function testSetTarget(): void
    {
        $target = 'test';

        $this->page->setTarget($target);

        self::assertSame($target, $this->page->getTarget());
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetParentException(): void
    {
        self::assertNull($this->page->getParent());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');

        $this->page->setParent($this->page);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var ContainerInterface $parent */
        $this->page->setParent($parent);
        $this->page->setParent($parent);
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

        /* @var ContainerInterface $parent1 */
        /* @var ContainerInterface $parent2 */
        $this->page->setParent($parent1);
        self::assertSame($parent1, $this->page->getParent());

        $this->page->setParent($parent2);
        self::assertSame($parent2, $this->page->getParent());
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

        self::assertNull($this->page->getOrder());

        $this->page->setOrder($order);

        self::assertSame($order, $this->page->getOrder());
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetVisibleWithParent(): void
    {
        $parent1 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parent1->expects(self::exactly(2))
            ->method('isVisible')
            ->willReturn(true);

        /* @var PageInterface $parent1 */
        $this->page->setParent($parent1);

        self::assertTrue($this->page->isVisible(true));
        self::assertTrue($this->page->getVisible(true));

        $parent2 = $this->getMockBuilder(PageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parent2->expects(self::exactly(2))
            ->method('isVisible')
            ->willReturn(false);

        /* @var PageInterface $parent2 */
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
    public function testSetActive(): void
    {
        $active = true;

        self::assertFalse($this->page->isActive());
        self::assertFalse($this->page->getActive());

        $this->page->setActive($active);

        self::assertTrue($this->page->isActive());
        self::assertTrue($this->page->getActive());
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

        self::assertFalse($this->page->isActive(true));
        self::assertFalse($this->page->getActive(true));

        $this->page->addPage($childPage1);

        self::assertTrue($this->page->isActive(true));
        self::assertTrue($this->page->getActive(true));
    }

    /**
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetWithException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $property must be a non-empty string');

        $this->page->set('', null);
    }

    /**
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testGetWithException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $property must be a non-empty string');

        $this->page->get('');
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

    /**
     * @return void
     */
    public function testUnset(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsetting native property "target" is not allowed');

        unset($this->page->target);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testUriOptionAsString(): void
    {
        $this->page = new Uri(
            [
                'label' => 'foo',
                'uri' => '#',
            ]
        );

        self::assertEquals('#', $this->page->getUri());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testUriOptionAsNull(): void
    {
        $this->page = new Uri(
            [
                'label' => 'foo',
                'uri' => null,
            ]
        );

        self::assertNull($this->page->getUri(), 'getUri() should return null');
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetAndGetUri(): void
    {
        $this->page = new Uri(
            [
                'label' => 'foo',
                'uri' => '#',
            ]
        );

        $this->page->setUri('http://www.example.com/');
        $this->page->setUri('about:blank');

        self::assertEquals('about:blank', $this->page->getUri());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
    public function testGetHref(): void
    {
        $uri = 'spotify:album:4YzcWwBUSzibRsqD9Sgu4A';

        $this->page->setUri($uri);

        self::assertEquals($uri, $this->page->getHref());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     *
     * @return void
     */
    public function testIsActiveReturnsTrueWhenHasMatchingRequestUri(): void
    {
        $url        = '/bar';
        $this->page = new Uri(
            [
                'label' => 'foo',
                'uri' => $url,
            ]
        );

        $uri = $this->createMock(UriInterface::class);
        $uri->expects(self::once())
            ->method('getPath')
            ->willReturn($url);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects(self::once())
            ->method('getUri')
            ->willReturn($uri);

        /* @var ServerRequestInterface $request */
        $this->page->setRequest($request);

        self::assertSame($request, $this->page->getRequest());
        self::assertTrue($this->page->isActive());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     *
     * @return void
     */
    public function testIsActiveReturnsFalseOnNonMatchingRequestUri(): void
    {
        $url1       = '/bar';
        $url2       = '/baz';
        $this->page = new Uri(
            [
                'label' => 'foo',
                'uri' => $url1,
            ]
        );

        $uri = $this->createMock(UriInterface::class);
        $uri->expects(self::once())
            ->method('getPath')
            ->willReturn($url2);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects(self::once())
            ->method('getUri')
            ->willReturn($uri);

        /* @var ServerRequestInterface $request */
        $this->page->setRequest($request);

        self::assertSame($request, $this->page->getRequest());
        self::assertFalse($this->page->isActive());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     *
     * @return void
     */
    public function testGetHrefWithFragmentIdentifier(): void
    {
        $uri = 'http://www.example.com/foo.html';

        $this->page->setUri($uri);
        $this->page->setFragment('bar');

        self::assertEquals($uri . '#bar', $this->page->getHref());

        $this->page->setUri('#');

        self::assertEquals('#bar', $this->page->getHref());
    }

    /**
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testAddSelfAsChild(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A page cannot have itself as a parent');

        $this->page->addPage($this->page);
    }

    /**
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage */
        $this->page->addPage($childPage);
        $this->page->addPage($childPage);
    }

    /**
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testAddPages(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $page must be an Instance of PageInterface');

        $this->page->addPages(['test']);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->removePage(1));
        self::assertSame([$code2 => $childPage2], $this->page->getPages());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->removePage($childPage2));
        self::assertSame([$code1 => $childPage1], $this->page->getPages());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertFalse($this->page->removePage($code1));
        self::assertSame([$code1 => $childPage1, $code2 => $childPage2], $this->page->getPages());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertFalse($this->page->removePage(3));
        self::assertSame([$code1 => $childPage1, $code2 => $childPage2], $this->page->getPages());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        $this->page->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertTrue($this->page->removePage($childPage2, true));
        self::assertSame([$code1 => $childPage1], $this->page->getPages());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        $this->page->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertFalse($this->page->removePage($childPage2, true));
        self::assertSame([$code1 => $childPage1], $this->page->getPages());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->hasPage(1));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->hasPage($childPage2));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertFalse($this->page->hasPage($code1));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertFalse($this->page->hasPage(3));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        $this->page->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertTrue($this->page->hasPage($childPage2, true));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        $this->page->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertFalse($this->page->hasPage($childPage2, true));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->hasPages());
        self::assertFalse($this->page->hasPages(true));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertTrue($this->page->hasPages());
        self::assertTrue($this->page->hasPages(true));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertSame($childPage2, $this->page->findOneBy($property, $value));
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        /* @var PageInterface $childPage3 */
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);
        $this->page->addPage($childPage3);

        self::assertSame([$childPage2, $childPage1], $this->page->findAllBy($property, $value));
    }

    /**
     * @throws \Mezzio\Navigation\Exception\BadMethodCallException
     * @throws \ErrorException
     *
     * @return void
     */
    public function testCallFindAllByException(): void
    {
        $value = 'test';

        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Bad method call: Unknown method Mezzio\Navigation\Page\Uri::findAlllByTest');

        $this->page->findAlllByTest($value);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Mezzio\Navigation\Exception\BadMethodCallException
     * @throws \ErrorException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
        $this->page->addPage($childPage1);
        $this->page->addPage($childPage2);

        self::assertSame([$childPage2, $childPage1], $this->page->findAllByRoute($value));
    }

    /**
     * @throws \Mezzio\Navigation\Exception\OutOfBoundsException
     *
     * @return void
     */
    public function testCurrentException(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('container is currently empty, could not find any key in internal iterator');

        $this->page->current();
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Mezzio\Navigation\Exception\OutOfBoundsException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
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

        self::assertSame($childPage1, $this->page->current());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Mezzio\Navigation\Exception\OutOfBoundsException
     *
     * @return void
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

        /* @var PageInterface $childPage1 */
        /* @var PageInterface $childPage2 */
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
