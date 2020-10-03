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

/**
 * Laminas_Navigation
 */

/**
 * @group      Laminas_Navigation
 */
final class NavigationTest extends TestCase
{
    /** @var \Mezzio\Navigation\Navigation */
    private $navigation;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->navigation = new Navigation();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->navigation = null;
        parent::tearDown();
    }

    /**
     * Testing that navigation order is done correctly
     *
     * @return void
     *
     * @group   Laminas-8337
     * @group   Laminas-8313
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
        self::assertEquals('page3', $pages[0]['uri'], var_export($pages, 1));
        self::assertEquals('page1', $pages[1]['uri']);
        self::assertEquals('page2', $pages[2]['uri']);
    }
}
