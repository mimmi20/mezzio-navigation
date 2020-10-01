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

use Mezzio\Navigation\Exception\InvalidArgumentException;
use Mezzio\Navigation\Page\AbstractPage;
use Mezzio\Navigation\Page\Mvc;
use Mezzio\Navigation\Page\Uri;
use MezzioTest\Navigation\TestAsset\Page;
use PHPUnit\Framework\TestCase;

/**
 * Tests Laminas_Navigation_Page::factory()
 *
 * @group      Laminas_Navigation
 */
final class PageFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testDetectFactoryPage(): void
    {
        AbstractPage::addFactory(static function ($page) {
            if (isset($page['factory_uri'])) {
                return new Uri($page);
            }

            if (isset($page['factory_mvc'])) {
                return new Mvc($page);
            }

            return null;
        });

        self::assertInstanceOf(
            Uri::class,
            AbstractPage::factory([
                'label' => 'URI Page',
                'factory_uri' => '#',
            ])
        );

        self::assertInstanceOf(
            Mvc::class,
            AbstractPage::factory([
                'label' => 'URI Page',
                'factory_mvc' => '#',
            ])
        );
    }

    /**
     * @return void
     */
    public function testDetectMvcPage(): void
    {
        $pages = [
            AbstractPage::factory([
                'label' => 'MVC Page',
                'action' => 'index',
            ]),
            AbstractPage::factory([
                'label' => 'MVC Page',
                'controller' => 'index',
            ]),
            AbstractPage::factory([
                'label' => 'MVC Page',
                'route' => 'home',
            ]),
        ];

        self::assertContainsOnly(Mvc::class, $pages);
    }

    /**
     * @return void
     */
    public function testDetectUriPage(): void
    {
        $page = AbstractPage::factory([
            'label' => 'URI Page',
            'uri' => '#',
        ]);

        self::assertInstanceOf(Uri::class, $page);
    }

    /**
     * @return void
     */
    public function testMvcShouldHaveDetectionPrecedence(): void
    {
        $page = AbstractPage::factory([
            'label' => 'MVC Page',
            'action' => 'index',
            'controller' => 'index',
            'uri' => '#',
        ]);

        self::assertInstanceOf(Mvc::class, $page);
    }

    /**
     * @return void
     */
    public function testSupportsMvcShorthand(): void
    {
        $mvcPage = AbstractPage::factory([
            'type' => 'mvc',
            'label' => 'MVC Page',
            'action' => 'index',
            'controller' => 'index',
        ]);

        self::assertInstanceOf(Mvc::class, $mvcPage);
    }

    /**
     * @return void
     */
    public function testSupportsUriShorthand(): void
    {
        $uriPage = AbstractPage::factory([
            'type' => 'uri',
            'label' => 'URI Page',
            'uri' => 'http://www.example.com/',
        ]);

        self::assertInstanceOf(Uri::class, $uriPage);
    }

    /**
     * @return void
     */
    public function testSupportsCustomPageTypes(): void
    {
        $page = AbstractPage::factory([
            'type' => 'LaminasTest\Navigation\TestAsset\Page',
            'label' => 'My Custom Page',
        ]);

        self::assertInstanceOf(Page::class, $page);
    }

    /**
     * @return void
     */
    public function testShouldFailForInvalidType(): void
    {
        $this->expectException(
            InvalidArgumentException::class
        );

        AbstractPage::factory([
            'type' => 'LaminasTest\Navigation\TestAsset\InvalidPage',
            'label' => 'My Invalid Page',
        ]);
    }

    /**
     * @return void
     */
    public function testShouldFailForNonExistantType(): void
    {
        $this->expectException(
            InvalidArgumentException::class
        );

        $pageConfig = [
            'type' => 'My_NonExistent_Page',
            'label' => 'My non-existent Page',
        ];

        AbstractPage::factory($pageConfig);
    }

    /**
     * @return void
     */
    public function testShouldFailIfUnableToDetermineType(): void
    {
        $this->expectException(
            InvalidArgumentException::class
        );

        AbstractPage::factory(['label' => 'My Invalid Page']);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionOnInvalidMethodArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);

        AbstractPage::factory([]);
    }
}
