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

namespace Mimmi20\MezzioTest\Navigation\Page;

use Mimmi20\Mezzio\Navigation\Exception\InvalidArgumentException;
use Mimmi20\Mezzio\Navigation\Page\PageFactory;
use Mimmi20\Mezzio\Navigation\Page\PageInterface;
use Mimmi20\Mezzio\Navigation\Page\Route;
use Mimmi20\Mezzio\Navigation\Page\Uri;
use Mimmi20\MezzioTest\Navigation\TestAsset\InvalidPage;
use Mimmi20\MezzioTest\Navigation\TestAsset\Page;
use Override;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

use function sprintf;

final class PageFactoryTest extends TestCase
{
    private PageFactory $factory;

    /** @throws void */
    #[Override]
    protected function setUp(): void
    {
        $this->factory = new PageFactory();
    }

    /** @throws InvalidArgumentException */
    public function testFactoryInvalidType(): void
    {
        $options = ['type' => 'test'];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot find class test');
        $this->expectExceptionCode(0);

        $this->factory->factory($options);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testDetectFactoryPage(): void
    {
        PageFactory::addFactory(
            static function (array $page): PageInterface | null {
                if (isset($page['factory_uri'])) {
                    return new Uri($page);
                }

                if (isset($page['factory_mvc'])) {
                    return new Route($page);
                }

                return null;
            },
        );

        self::assertInstanceOf(
            Uri::class,
            $this->factory->factory(
                [
                    'label' => 'URI Page',
                    'factory_uri' => '#',
                ],
            ),
        );

        self::assertInstanceOf(
            Route::class,
            $this->factory->factory(
                [
                    'label' => 'URI Page',
                    'factory_mvc' => '#',
                ],
            ),
        );
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testDetectMvcPage(): void
    {
        $pages = [
            $this->factory->factory(
                [
                    'label' => 'MVC Page',
                    'route' => 'home',
                ],
            ),
        ];

        self::assertContainsOnlyInstancesOf(Route::class, $pages);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testDetectUriPage(): void
    {
        $page = $this->factory->factory(
            [
                'label' => 'URI Page',
                'uri' => '#',
            ],
        );

        self::assertInstanceOf(Uri::class, $page);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testMvcShouldHaveDetectionPrecedence(): void
    {
        $page = $this->factory->factory(
            [
                'label' => 'MVC Page',
                'route' => 'index',
                'uri' => '#',
            ],
        );

        self::assertInstanceOf(Route::class, $page);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSupportsMvcShorthand(): void
    {
        $mvcPage = $this->factory->factory(
            [
                'type' => 'route',
                'label' => 'MVC Page',
            ],
        );

        self::assertInstanceOf(Route::class, $mvcPage);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSupportsUriShorthand(): void
    {
        $uriPage = $this->factory->factory(
            [
                'type' => 'uri',
                'label' => 'URI Page',
                'uri' => 'http://www.example.com/',
            ],
        );

        self::assertInstanceOf(Uri::class, $uriPage);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testSupportsCustomPageTypes(): void
    {
        $page = $this->factory->factory(
            [
                'type' => Page::class,
                'label' => 'My Custom Page',
            ],
        );

        self::assertInstanceOf(Page::class, $page);
    }

    /** @throws InvalidArgumentException */
    public function testShouldFailForInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Invalid argument: Detected type "%s", which is not an instance of %s',
                InvalidPage::class,
                PageInterface::class,
            ),
        );
        $this->expectExceptionCode(0);

        $this->factory->factory(
            [
                'type' => InvalidPage::class,
                'label' => 'My Invalid Page',
            ],
        );
    }

    /** @throws InvalidArgumentException */
    public function testShouldFailIfUnableToDetermineType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument: Unable to determine class to instantiate');
        $this->expectExceptionCode(0);

        $this->factory->factory(
            ['label' => 'My Invalid Page'],
        );
    }
}
