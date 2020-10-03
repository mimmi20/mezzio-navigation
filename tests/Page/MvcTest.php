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

use PHPUnit\Framework\TestCase;

/**
 * Tests the class Laminas_Navigation_Page_Mvc
 *
 * @group      Laminas_Navigation
 */
final class MvcTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        // @todo: define
    }

    /**
     * @return void
     */
    public function testHrefGeneratedByRouterWithDefaultRoute(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testHrefGeneratedByRouterRequiresNoRoute(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testHrefRouteMatchEnabledWithoutRouteMatchObject(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testHrefGeneratedIsRouteAware(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsTrueWhenMatchingRoute(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsTrueWhenMatchingRouteWhileUsingModuleRouteListener(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsFalseWhenMatchingRouteButNonMatchingParams(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsFalseWhenNoRouteAndNoMatchedRouteNameIsSet(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testGetHrefWithFragmentIdentifier(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testGetHrefPassesQueryPartToRouter(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsTrueOnIdenticalControllerAction(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsFalseOnDifferentControllerAction(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsTrueOnIdenticalIncludingPageParams(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsTrueWhenRequestHasMoreParams(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testIsActiveReturnsFalseWhenRequestHasLessParams(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testActionAndControllerAccessors(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testRouteAccessor(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testSetAndGetParams(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testToArrayMethod(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testSpecifyingAnotherUrlHelperToGenerateHrefs(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testDefaultRouterCanBeSetWithConstructor(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testDefaultRouterCanBeSetWithGetter(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testNoExceptionForGetHrefIfDefaultRouterIsSet(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testBoolSetAndGetUseRouteMatch(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testMvcPageParamsInheritRouteMatchParams(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testInheritedRouteMatchParamsWorkWithModuleRouteListener(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testMistakeDetectIsActiveOnIndexController(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testRecursiveDetectIsActiveWhenRouteNameIsKnown(): void
    {
        self::markTestSkipped();
    }

    /**
     * @return void
     */
    public function testSetRouteMatchThrowsExceptionOnInvalidParameter(): void
    {
        self::markTestSkipped();
    }
}
