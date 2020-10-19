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

use Mezzio\Navigation;
use Mezzio\Navigation\Page;
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
    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
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
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
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
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testUriOptionAsObject(): void
    {
        $uri      = new \stdClass();
        $uri->foo = 'bar';

        $this->expectException(
            Navigation\Exception\InvalidArgumentException::class
        );

        new Page\Uri(['uri' => $uri]);
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testSetAndGetUri(): void
    {
        $page = new Page\Uri([
            'label' => 'foo',
            'uri' => '#',
        ]);

        $page->setUri('http://www.example.com/');
        $page->setUri('about:blank');

        self::assertEquals('about:blank', $page->getUri());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     *
     * @return void
     */
    public function testIsActiveReturnsTrueWhenHasMatchingRequestUri(): void
    {
        $url  = '/bar';
        $page = new Page\Uri(
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
        $page->setRequest($request);

        self::assertSame($request, $page->getRequest());
        self::assertTrue($page->isActive());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     *
     * @return void
     */
    public function testIsActiveReturnsFalseOnNonMatchingRequestUri(): void
    {
        $url1 = '/bar';
        $url2 = '/baz';
        $page = new Page\Uri(
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
        $page->setRequest($request);

        self::assertSame($request, $page->getRequest());
        self::assertFalse($page->isActive());
    }

    /**
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \Mezzio\Navigation\Exception\InvalidArgumentException
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
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
