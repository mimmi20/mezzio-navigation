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
namespace MezzioTest\Navigation;

use Mezzio\Navigation\Exception\InvalidArgumentException;
use Mezzio\Navigation\Navigation;
use Mezzio\Navigation\Page;
use PHPUnit\Framework\TestCase;

final class NavigationTest extends TestCase
{
    /** @var \Mezzio\Navigation\Navigation */
    private $navigation;

    /**
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->navigation = new Navigation();
    }

    /**
     * Testing that navigation order is done correctly
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testNavigationArraySortsCorrectly(): void
    {
        $page1 = new Page\Uri(['uri' => 'page1']);
        $page2 = new Page\Uri(['uri' => 'page2']);
        $page3 = new Page\Uri(['uri' => 'page3']);

        $this->navigation->setPages([$page1, $page2, $page3]);

        $page1->setOrder(1);
        $page3->setOrder(0);
        $page2->setOrder(2);

        $pages = $this->navigation->toArray();

        self::assertCount(3, $pages);
        self::assertEquals('page3', $pages[0]['uri'], var_export($pages, true));
        self::assertEquals('page1', $pages[1]['uri']);
        self::assertEquals('page2', $pages[2]['uri']);
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
            ->with($this->navigation);

        /* @var Page\PageInterface $childPage */
        $this->navigation->addPage($childPage);
        $this->navigation->addPage($childPage);
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

        $this->navigation->addPages(['test']);
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
            ->with($this->navigation);

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
            ->with($this->navigation);

        /* @var Page\PageInterface $childPage1 */
        /* @var Page\PageInterface $childPage2 */
        $this->navigation->addPage($childPage1);
        $this->navigation->addPage($childPage2);

        self::assertTrue($this->navigation->removePage(1));
        self::assertSame([$code2 => $childPage2], $this->navigation->getPages());
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
            ->with($this->navigation);

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
            ->with($this->navigation);

        /* @var Page\PageInterface $childPage1 */
        /* @var Page\PageInterface $childPage2 */
        $this->navigation->addPage($childPage1);
        $this->navigation->addPage($childPage2);

        self::assertTrue($this->navigation->removePage($childPage2));
        self::assertSame([$code1 => $childPage1], $this->navigation->getPages());
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
            ->with($this->navigation);

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
            ->with($this->navigation);

        /* @var Page\PageInterface $childPage1 */
        /* @var Page\PageInterface $childPage2 */
        $this->navigation->addPage($childPage1);
        $this->navigation->addPage($childPage2);

        self::assertFalse($this->navigation->removePage($code1));
        self::assertSame([$code1 => $childPage1, $code2 => $childPage2], $this->navigation->getPages());
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
            ->with($this->navigation);

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
            ->with($this->navigation);

        /* @var Page\PageInterface $childPage1 */
        /* @var Page\PageInterface $childPage2 */
        $this->navigation->addPage($childPage1);
        $this->navigation->addPage($childPage2);

        self::assertFalse($this->navigation->removePage(3));
        self::assertSame([$code1 => $childPage1, $code2 => $childPage2], $this->navigation->getPages());
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
            ->with($this->navigation);

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
            ->with($this->navigation);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(true);
        $childPage1->expects(self::once())
            ->method('removePage')
            ->with($childPage2, true);

        /* @var Page\PageInterface $childPage1 */
        /* @var Page\PageInterface $childPage2 */
        $this->navigation->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertTrue($this->navigation->removePage($childPage2, true));
        self::assertSame([$code1 => $childPage1], $this->navigation->getPages());
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
            ->with($this->navigation);

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
            ->with($this->navigation);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(false);
        $childPage1->expects(self::never())
            ->method('removePage');

        /* @var Page\PageInterface $childPage1 */
        /* @var Page\PageInterface $childPage2 */
        $this->navigation->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertFalse($this->navigation->removePage($childPage2, true));
        self::assertSame([$code1 => $childPage1], $this->navigation->getPages());
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
            ->with($this->navigation);

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
            ->with($this->navigation);

        /* @var Page\PageInterface $childPage1 */
        /* @var Page\PageInterface $childPage2 */
        $this->navigation->addPage($childPage1);
        $this->navigation->addPage($childPage2);

        self::assertTrue($this->navigation->hasPage(1));
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
            ->with($this->navigation);

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
            ->with($this->navigation);

        /* @var Page\PageInterface $childPage1 */
        /* @var Page\PageInterface $childPage2 */
        $this->navigation->addPage($childPage1);
        $this->navigation->addPage($childPage2);

        self::assertTrue($this->navigation->hasPage($childPage2));
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
            ->with($this->navigation);

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
            ->with($this->navigation);

        /* @var Page\PageInterface $childPage1 */
        /* @var Page\PageInterface $childPage2 */
        $this->navigation->addPage($childPage1);
        $this->navigation->addPage($childPage2);

        self::assertFalse($this->navigation->hasPage($code1));
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
            ->with($this->navigation);

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
            ->with($this->navigation);

        /* @var Page\PageInterface $childPage1 */
        /* @var Page\PageInterface $childPage2 */
        $this->navigation->addPage($childPage1);
        $this->navigation->addPage($childPage2);

        self::assertFalse($this->navigation->hasPage(3));
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
            ->with($this->navigation);

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
            ->with($this->navigation);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(true);
        $childPage1->expects(self::never())
            ->method('removePage');

        /* @var Page\PageInterface $childPage1 */
        /* @var Page\PageInterface $childPage2 */
        $this->navigation->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertTrue($this->navigation->hasPage($childPage2, true));
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
            ->with($this->navigation);

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
            ->with($this->navigation);

        $childPage1->expects(self::once())
            ->method('hasPage')
            ->with($childPage2, true)
            ->willReturn(false);
        $childPage1->expects(self::never())
            ->method('removePage');

        /* @var Page\PageInterface $childPage1 */
        /* @var Page\PageInterface $childPage2 */
        $this->navigation->addPage($childPage1);
        $childPage1->addPage($childPage2);

        self::assertFalse($this->navigation->hasPage($childPage2, true));
    }
}
