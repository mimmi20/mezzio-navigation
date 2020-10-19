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
}
