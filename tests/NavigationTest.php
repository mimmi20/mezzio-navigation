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

namespace Mimmi20\MezzioTest\Navigation;

use Mimmi20\Mezzio\Navigation\Exception\BadMethodCallException;
use Mimmi20\Mezzio\Navigation\Exception\InvalidArgumentException;
use Mimmi20\Mezzio\Navigation\Exception\OutOfBoundsException;
use Mimmi20\Mezzio\Navigation\Navigation;
use Mimmi20\Mezzio\Navigation\Page;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function array_values;
use function assert;
use function var_export;

final class NavigationTest extends TestCase
{
    /**
     * Testing that navigation order is done correctly
     *
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSetPagesTwice(): void
    {
        $navigation = new Navigation();

        $page1 = new Page\Uri(['uri' => 'page1']);
        $page2 = new Page\Uri(['uri' => 'page2']);
        $page3 = new Page\Uri(['uri' => 'page3']);

        $navigation->setPages([$page3, $page2, $page1]);
        $navigation->setPages([$page1, $page2, $page3]);

        self::assertSame([$page1, $page2, $page3], array_values($navigation->getPages()));
    }

    /**
     * Testing that navigation order is done correctly
     *
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testNavigationArraySortsCorrectly(): void
    {
        $navigation = new Navigation();

        $page1 = new Page\Uri(['uri' => 'page1']);
        $page2 = new Page\Uri(['uri' => 'page2']);
        $page3 = new Page\Uri(['uri' => 'page3']);

        $navigation->setPages([$page1, $page2, $page3]);

        $page1->setOrder(1);
        $page3->setOrder(0);
        $page2->setOrder(2);

        $pages = $navigation->toArray();

        self::assertCount(3, $pages);
        self::assertCount(3, $navigation);
        self::assertIsArray($pages[$page3->hashCode()]);
        self::assertSame('page3', $pages[$page3->hashCode()]['uri'], var_export($pages, true));
        self::assertIsArray($pages[$page1->hashCode()]);
        self::assertSame('page1', $pages[$page1->hashCode()]['uri']);
        self::assertIsArray($pages[$page2->hashCode()]);
        self::assertSame('page2', $pages[$page2->hashCode()]['uri']);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testAddChildPageTwice(): void
    {
        $navigation = new Navigation();
        $hashCode   = 'abc';

        $childPage = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);

        assert($childPage instanceof Page\PageInterface);
        $navigation->addPage($childPage);
        $navigation->addPage($childPage);
    }

    /** @throws InvalidArgumentException */
    public function testAddPages(): void
    {
        $navigation = new Navigation();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: $page must be an Instance of PageInterface');
        $this->expectExceptionCode(0);

        $navigation->addPages(['test']);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageByIndex(): void
    {
        $navigation = new Navigation();
        $code1      = 'code 1';
        $code2      = 'code 2';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $navigation->addPage($childPage2);

        self::assertTrue($navigation->removePage(1));
        self::assertSame([$code2 => $childPage2], $navigation->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageByObject(): void
    {
        $navigation = new Navigation();
        $code1      = 'code 1';
        $code2      = 'code 2';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $navigation->addPage($childPage2);

        self::assertTrue($navigation->removePage($childPage2));
        self::assertSame([$code1 => $childPage1], $navigation->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageNotExistingPage(): void
    {
        $navigation = new Navigation();
        $code1      = 'code 1';
        $code2      = 'code 2';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage1->expects(self::never())
            ->method('hasPage');
        $childPage1->expects(self::never())
            ->method('removePage');

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage2->expects(self::never())
            ->method('hasPage');
        $childPage2->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $navigation->addPage($childPage2);

        self::assertFalse($navigation->removePage(3));
        self::assertSame([$code1 => $childPage1, $code2 => $childPage2], $navigation->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageRecursive(): void
    {
        $navigation = new Navigation();
        $code1      = 'code 1';
        $code2      = 'code 2';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
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

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertTrue($navigation->removePage($childPage2, true));
        self::assertSame([$code1 => $childPage1], $navigation->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testRemovePageRecursiveNotFound(): void
    {
        $navigation = new Navigation();
        $code1      = 'code 1';
        $code2      = 'code 2';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
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

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertFalse($navigation->removePage($childPage2, true));
        self::assertSame([$code1 => $childPage1], $navigation->getPages());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasPageByIndex(): void
    {
        $navigation = new Navigation();
        $code1      = 'code 1';
        $code2      = 'code 2';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $navigation->addPage($childPage2);

        self::assertTrue($navigation->hasPage(1));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasPageByObject(): void
    {
        $navigation = new Navigation();
        $code1      = 'code 1';
        $code2      = 'code 2';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $navigation->addPage($childPage2);

        self::assertTrue($navigation->hasPage($childPage2));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasNotExistingPage(): void
    {
        $navigation = new Navigation();
        $code1      = 'code 1';
        $code2      = 'code 2';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $navigation->addPage($childPage2);

        self::assertFalse($navigation->hasPage(3));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasPageRecursive(): void
    {
        $navigation = new Navigation();
        $code1      = 'code 1';
        $code2      = 'code 2';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(true);
        $childPage1->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertTrue($navigation->hasPage($childPage2, true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasPageRecursiveNotFound(): void
    {
        $navigation = new Navigation();
        $code1      = 'code 1';
        $code2      = 'code 2';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(false);
        $childPage1->expects(self::never())
            ->method('removePage');

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertFalse($navigation->hasPage($childPage2, true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasNoVisiblePages(): void
    {
        $navigation = new Navigation();

        self::assertFalse($navigation->hasPages());

        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage1->expects(self::once())
            ->method('isVisible')
            ->willReturn(false);

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage2->expects(self::once())
            ->method('isVisible')
            ->willReturn(false);

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $navigation->addPage($childPage2);

        self::assertTrue($navigation->hasPages());
        self::assertFalse($navigation->hasPages(true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testHasVisiblePages(): void
    {
        $navigation = new Navigation();

        self::assertFalse($navigation->hasPages());

        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage1->expects(self::once())
            ->method('isVisible')
            ->willReturn(false);

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage2->expects(self::once())
            ->method('isVisible')
            ->willReturn(true);

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $navigation->addPage($childPage2);

        self::assertTrue($navigation->hasPages());
        self::assertTrue($navigation->hasPages(true));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testFindOneBy(): void
    {
        $navigation = new Navigation();
        $property   = 'route';
        $value      = 'test';

        self::assertNull($navigation->findOneBy($property, $value));

        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $navigation->addPage($childPage2);

        self::assertSame($childPage2, $navigation->findOneBy($property, $value));
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testFindAllBy(): void
    {
        $navigation = new Navigation();
        $property   = 'route';
        $value      = 'test';

        self::assertSame([], $navigation->findAllBy($property, $value));

        $code1 = 'code 1';
        $code2 = 'code 2';
        $code3 = 'code 3';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        $childPage3 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage3->expects(self::never())
            ->method('isVisible');
        $childPage3->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn(null);

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        assert($childPage3 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $navigation->addPage($childPage2);
        $navigation->addPage($childPage3);

        self::assertSame([$childPage2, $childPage1], $navigation->findAllBy($property, $value));
    }

    /** @throws void */
    public function testCallFindAllByException(): void
    {
        $navigation = new Navigation();
        $value      = 'test';

        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage(
            'Bad method call: Unknown method Mimmi20\Mezzio\Navigation\Navigation::findAlllByTest',
        );
        $this->expectExceptionCode(0);

        $navigation->findAlllByTest($value);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testCallFindAllBy(): void
    {
        $navigation = new Navigation();
        $property   = 'Route';
        $value      = 'test';

        self::assertSame([], $navigation->findAllByRoute($value));

        $code1 = 'code 1';
        $code2 = 'code 2';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::once())
            ->method('get')
            ->with($property)
            ->willReturn($value);

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $navigation->addPage($childPage2);

        self::assertSame([$childPage2, $childPage1], $navigation->findAllByRoute($value));
    }

    /** @throws OutOfBoundsException */
    public function testCurrentException(): void
    {
        $navigation = new Navigation();

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage(
            'container is currently empty, could not find any key in internal iterator',
        );
        $this->expectExceptionCode(0);

        $navigation->current();
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    public function testCurrent(): void
    {
        $navigation = new Navigation();
        $code1      = 'code 1';
        $code2      = 'code 2';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::never())
            ->method('get');

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $navigation->addPage($childPage2);

        self::assertSame($childPage2, $navigation->current());
        self::assertSame($code2, $navigation->key());
        self::assertTrue($navigation->valid());

        $navigation->next();

        self::assertSame($childPage1, $navigation->current());
        self::assertSame($code1, $navigation->key());
        self::assertTrue($navigation->valid());

        $navigation->next();

        self::assertSame('', $navigation->key());
        self::assertFalse($navigation->valid());

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage(
            'Corruption detected in container; invalid key found in internal iterator',
        );
        $this->expectExceptionCode(0);

        self::assertSame($childPage1, $navigation->current());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws OutOfBoundsException
     */
    public function testRewind(): void
    {
        $navigation = new Navigation();
        $code1      = 'code 1';
        $code2      = 'code 2';

        $childPage1 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage1->expects(self::never())
            ->method('isVisible');
        $childPage1->expects(self::never())
            ->method('get');

        $childPage2 = $this->getMockBuilder(Page\PageInterface::class)
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
            ->with($navigation);
        $childPage2->expects(self::never())
            ->method('isVisible');
        $childPage2->expects(self::never())
            ->method('get');

        assert($childPage1 instanceof Page\PageInterface);
        assert($childPage2 instanceof Page\PageInterface);
        $navigation->addPage($childPage1);
        $navigation->addPage($childPage2);

        self::assertSame($childPage2, $navigation->current());
        self::assertSame($code2, $navigation->key());
        self::assertTrue($navigation->valid());

        $navigation->next();

        self::assertSame($childPage1, $navigation->current());
        self::assertSame($code1, $navigation->key());
        self::assertTrue($navigation->valid());

        $navigation->rewind();

        self::assertSame($childPage2, $navigation->current());
        self::assertSame($code2, $navigation->key());
        self::assertTrue($navigation->valid());
    }
}
