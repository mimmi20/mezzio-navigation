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

use Laminas\Http\Request;
use Mezzio\Navigation;
use Mezzio\Navigation\Page;
use PHPUnit\Framework\TestCase;

/**
 * Tests the class Laminas_Navigation_Page_Uri
 *
 * @group      Laminas_Navigation
 */
final class UriTest extends TestCase
{
    /**
     * @return void
     */
    public function testUriOptionAsString(): void
    {
        $page = new Page\Uri([
            'label' => 'foo',
            'uri' => '#',
        ]);

        self::assertEquals('#', $page->getUri());
    }

    /**
     * @return void
     */
    public function testUriOptionAsNull(): void
    {
        $page = new Page\Uri([
            'label' => 'foo',
            'uri' => null,
        ]);

        self::assertNull($page->getUri(), 'getUri() should return null');
    }

    /**
     * @return void
     */
    public function testUriOptionAsInteger(): void
    {
        $this->expectException(
            Navigation\Exception\InvalidArgumentException::class
        );

        new Page\Uri(['uri' => 1337]);
    }

    /**
     * @return void
     */
    public function testUriOptionAsObject(): void
    {
        $this->expectException(
            Navigation\Exception\InvalidArgumentException::class
        );

        $uri      = new \stdClass();
        $uri->foo = 'bar';

        new Page\Uri(['uri' => $uri]);
    }

    /**
     * @return void
     */
    public function testSetAndGetUri(): void
    {
        $page = new Page\Uri([
            'label' => 'foo',
            'uri' => '#',
        ]);

        $page->setUri('http://www.example.com/')->setUri('about:blank');

        self::assertEquals('about:blank', $page->getUri());
    }

    /**
     * @return void
     */
    public function testGetHref(): void
    {
        $uri = 'spotify:album:4YzcWwBUSzibRsqD9Sgu4A';

        $page = new Page\Uri();
        $page->setUri($uri);

        self::assertEquals($uri, $page->getHref());
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsTrueWhenHasMatchingRequestUri(): void
    {
        $page = new Page\Uri([
            'label' => 'foo',
            'uri' => '/bar',
        ]);

        $request = new Request();
        $request->setUri('/bar');
        $request->setMethod('GET');

        $page->setRequest($request);

        self::assertInstanceOf('Laminas\Http\Request', $page->getRequest());

        self::assertTrue($page->isActive());
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsFalseOnNonMatchingRequestUri(): void
    {
        $page = new Page\Uri([
            'label' => 'foo',
            'uri' => '/bar',
        ]);

        $request = new Request();
        $request->setUri('/baz');
        $request->setMethod('GET');

        $page->setRequest($request);

        self::assertFalse($page->isActive());
    }

    /**
     * @return void
     */
    public function testGetHrefWithFragmentIdentifier(): void
    {
        $uri = 'http://www.example.com/foo.html';

        $page = new Page\Uri();
        $page->setUri($uri);
        $page->setFragment('bar');

        self::assertEquals($uri . '#bar', $page->getHref());

        $page->setUri('#');

        self::assertEquals('#bar', $page->getHref());
    }
}
