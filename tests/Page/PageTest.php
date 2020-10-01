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

use Laminas\Config;
use Mezzio\Navigation;
use Mezzio\Navigation\Exception;
use Mezzio\Navigation\Page\AbstractPage;
use Mezzio\Navigation\Page\Uri;
use PHPUnit\Framework\TestCase;

/**
 * Tests the class Laminas_Navigation_Page
 *
 * @group      Laminas_Navigation
 */
final class PageTest extends TestCase
{
    /**
     * @return void
     */
    public function testSetShouldMapToNativeProperties(): void
    {
        $page = AbstractPage::factory(['type' => 'mvc']);

        $page->set('action', 'foo');
        self::assertEquals('foo', $page->getAction());

        $page->set('Action', 'bar');
        self::assertEquals('bar', $page->getAction());
    }

    /**
     * @return void
     */
    public function testGetShouldMapToNativeProperties(): void
    {
        $page = AbstractPage::factory(['type' => 'mvc']);

        $page->setAction('foo');
        self::assertEquals('foo', $page->get('action'));

        $page->setAction('bar');
        self::assertEquals('bar', $page->get('Action'));
    }

    /**
     * @return void
     */
    public function testShouldSetAndGetShouldMapToProperties(): void
    {
        $page = AbstractPage::factory(['type' => 'uri']);

        $page->set('action', 'Laughing Out Loud');
        self::assertEquals('Laughing Out Loud', $page->get('action'));
    }

    /**
     * @return void
     */
    public function testSetShouldNotMapToSetOptionsToPreventRecursion(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'label' => 'foo',
        ]);

        $options = ['label' => 'bar'];
        $page->set('options', $options);

        self::assertEquals('foo', $page->getLabel());
        self::assertEquals($options, $page->get('options'));
    }

    /**
     * @return void
     */
    public function testSetShouldNotMapToSetConfigToPreventRecursion(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'label' => 'foo',
        ]);

        $options = ['label' => 'bar'];
        $page->set('config', $options);

        self::assertEquals('foo', $page->getLabel());
        self::assertEquals($options, $page->get('config'));
    }

    /**
     * @return void
     */
    public function testSetShouldThrowExceptionIfPropertyIsNotString(): void
    {
        $page = AbstractPage::factory(['type' => 'uri']);

        $this->expectException(Exception\InvalidArgumentException::class);
        $page->set([], true);
    }

    /**
     * @return void
     */
    public function testSetShouldThrowExceptionIfPropertyIsEmpty(): void
    {
        $page = AbstractPage::factory(['type' => 'uri']);

        $this->expectException(Exception\InvalidArgumentException::class);
        $page->set('', true);
    }

    /**
     * @return void
     */
    public function testGetShouldThrowExceptionIfPropertyIsNotString(): void
    {
        $page = AbstractPage::factory(['type' => 'uri']);

        $this->expectException(Exception\InvalidArgumentException::class);
        $page->get([]);
    }

    /**
     * @return void
     */
    public function testGetShouldThrowExceptionIfPropertyIsEmpty(): void
    {
        $page = AbstractPage::factory(['type' => 'uri']);

        $this->expectException(Exception\InvalidArgumentException::class);
        $page->get('');
    }

    /**
     * @return void
     */
    public function testSetAndGetLabel(): void
    {
        $page = AbstractPage::factory([
            'label' => 'foo',
            'uri' => '#',
        ]);

        self::assertEquals('foo', $page->getLabel());
        $page->setLabel('bar');
        self::assertEquals('bar', $page->getLabel());

        $invalids = [42, (object) null];
        foreach ($invalids as $invalid) {
            try {
                $page->setLabel($invalid);
                self::fail('An invalid value was set, but a ' .
                        'Mezzio\Navigation\Exception\InvalidArgumentException was not thrown');
            } catch (Navigation\Exception\InvalidArgumentException $e) {
                self::assertContains('Invalid argument: $label', $e->getMessage());
            }
        }
    }

    /**
     * @return void
     */
    public function testSetAndGetFragmentIdentifier(): void
    {
        $page = AbstractPage::factory([
            'uri' => '#',
            'fragment' => 'foo',
        ]);

        self::assertEquals('foo', $page->getFragment());

        $page->setFragment('bar');
        self::assertEquals('bar', $page->getFragment());

        $invalids = [42, (object) null];
        foreach ($invalids as $invalid) {
            try {
                $page->setFragment($invalid);
                self::fail('An invalid value was set, but a ' .
                            'Mezzio\Navigation\Exception\InvalidArgumentException was not thrown');
            } catch (Navigation\Exception\InvalidArgumentException $e) {
                self::assertContains(
                    'Invalid argument: $fragment',
                    $e->getMessage()
                );
            }
        }
    }

    /**
     * @return void
     */
    public function testSetAndGetId(): void
    {
        $page = AbstractPage::factory([
            'label' => 'foo',
            'uri' => '#',
        ]);

        self::assertNull($page->getId());

        $page->setId('bar');
        self::assertEquals('bar', $page->getId());

        $invalids = [true, (object) null];
        foreach ($invalids as $invalid) {
            try {
                $page->setId($invalid);
                self::fail('An invalid value was set, but a ' .
                        'Mezzio\Navigation\Exception\InvalidArgumentException was not thrown');
            } catch (Navigation\Exception\InvalidArgumentException $e) {
                self::assertContains('Invalid argument: $id', $e->getMessage());
            }
        }
    }

    /**
     * @return void
     */
    public function testIdCouldBeAnInteger(): void
    {
        $page = AbstractPage::factory([
            'label' => 'foo',
            'uri' => '#',
            'id' => 10,
        ]);

        self::assertEquals(10, $page->getId());
    }

    /**
     * @return void
     */
    public function testSetAndGetClass(): void
    {
        $page = AbstractPage::factory([
            'label' => 'foo',
            'uri' => '#',
        ]);

        self::assertNull($page->getClass());
        $page->setClass('bar');
        self::assertEquals('bar', $page->getClass());

        $invalids = [42, true, (object) null];
        foreach ($invalids as $invalid) {
            try {
                $page->setClass($invalid);
                self::fail('An invalid value was set, but a ' .
                        'Mezzio\Navigation\Exception\InvalidArgumentException was not thrown');
            } catch (Navigation\Exception\InvalidArgumentException $e) {
                self::assertContains('Invalid argument: $class', $e->getMessage());
            }
        }
    }

    /**
     * @return void
     */
    public function testSetAndGetTitle(): void
    {
        $page = AbstractPage::factory([
            'label' => 'foo',
            'uri' => '#',
        ]);

        self::assertNull($page->getTitle());
        $page->setTitle('bar');
        self::assertEquals('bar', $page->getTitle());

        $invalids = [42, true, (object) null];
        foreach ($invalids as $invalid) {
            try {
                $page->setTitle($invalid);
                self::fail('An invalid value was set, but a ' .
                        'Mezzio\Navigation\Exception\InvalidArgumentException was not thrown');
            } catch (Navigation\Exception\InvalidArgumentException $e) {
                self::assertContains('Invalid argument: $title', $e->getMessage());
            }
        }
    }

    /**
     * @return void
     */
    public function testSetAndGetTarget(): void
    {
        $page = AbstractPage::factory([
            'label' => 'foo',
            'uri' => '#',
        ]);

        self::assertNull($page->getTarget());
        $page->setTarget('bar');
        self::assertEquals('bar', $page->getTarget());

        $invalids = [42, true, (object) null];
        foreach ($invalids as $invalid) {
            try {
                $page->setTarget($invalid);
                self::fail('An invalid value was set, but a ' .
                        'Mezzio\Navigation\Exception\InvalidArgumentException was not thrown');
            } catch (Navigation\Exception\InvalidArgumentException $e) {
                self::assertContains('Invalid argument: $target', $e->getMessage());
            }
        }
    }

    /**
     * @return void
     */
    public function testConstructingWithRelationsInArray(): void
    {
        $page = AbstractPage::factory([
            'label' => 'bar',
            'uri' => '#',
            'rel' => [
                'prev' => 'foo',
                'next' => 'baz',
            ],
            'rev' => ['alternate' => 'bat'],
        ]);

        $expected = [
            'rel' => [
                'prev' => 'foo',
                'next' => 'baz',
            ],
            'rev' => ['alternate' => 'bat'],
        ];

        $actual = [
            'rel' => $page->getRel(),
            'rev' => $page->getRev(),
        ];

        self::assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function testConstructingWithRelationsInConfig(): void
    {
        $page = AbstractPage::factory(new Config\Config([
            'label' => 'bar',
            'uri' => '#',
            'rel' => [
                'prev' => 'foo',
                'next' => 'baz',
            ],
            'rev' => ['alternate' => 'bat'],
        ]));

        $expected = [
            'rel' => [
                'prev' => 'foo',
                'next' => 'baz',
            ],
            'rev' => ['alternate' => 'bat'],
        ];

        $actual = [
            'rel' => $page->getRel(),
            'rev' => $page->getRev(),
        ];

        self::assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function testConstructingWithTraversableOptions(): void
    {
        $options = ['label' => 'bar'];

        $page = new Uri(new Config\Config($options));

        $actual = ['label' => $page->getLabel()];

        self::assertEquals($options, $actual);
    }

    /**
     * @return void
     */
    public function testGettingSpecificRelations(): void
    {
        $page = AbstractPage::factory([
            'label' => 'bar',
            'uri' => '#',
            'rel' => [
                'prev' => 'foo',
                'next' => 'baz',
            ],
            'rev' => ['next' => 'foo'],
        ]);

        $expected = [
            'foo',
            'foo',
        ];

        $actual = [
            $page->getRel('prev'),
            $page->getRev('next'),
        ];

        self::assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function testSetAndGetOrder(): void
    {
        $page = AbstractPage::factory([
            'label' => 'foo',
            'uri' => '#',
        ]);

        self::assertNull($page->getOrder());

        $page->setOrder('1');
        self::assertEquals(1, $page->getOrder());

        $page->setOrder(1337);
        self::assertEquals(1337, $page->getOrder());

        $page->setOrder('-25');
        self::assertEquals(-25, $page->getOrder());

        $invalids = [3.14, 'e', "\n", '0,4', true, (object) null];
        foreach ($invalids as $invalid) {
            try {
                $page->setOrder($invalid);
                self::fail('An invalid value was set, but a ' .
                        'Mezzio\Navigation\Exception\InvalidArgumentException was not thrown');
            } catch (Navigation\Exception\InvalidArgumentException $e) {
                self::assertContains('Invalid argument: $order', $e->getMessage());
            }
        }
    }

    /**
     * @return void
     */
    public function testSetResourceString(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'label' => 'hello',
        ]);

        $page->setResource('foo');
        self::assertEquals('foo', $page->getResource());
    }

    /**
     * @return void
     */
    public function testSetResourceNoParam(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'label' => 'hello',
            'resource' => 'foo',
        ]);

        $page->setResource();
        self::assertNull($page->getResource());
    }

    /**
     * @return void
     */
    public function testSetResourceNull(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'label' => 'hello',
            'resource' => 'foo',
        ]);

        $page->setResource(null);
        self::assertNull($page->getResource());
    }

    /**
     * @return void
     */
    public function testSetResourceInterface(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'label' => 'hello',
        ]);

        $resource = new \Laminas\Permissions\Acl\Resource\GenericResource('bar');

        $page->setResource($resource);
        self::assertEquals($resource, $page->getResource());
    }

    /**
     * @return void
     */
    public function testSetResourceShouldThrowExceptionWhenGivenInteger(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'label' => 'hello',
        ]);

        try {
            $page->setResource(0);
            self::fail('An invalid value was set, but a ' .
                        'Mezzio\Navigation\Exception\InvalidArgumentException was not thrown');
        } catch (Navigation\Exception\InvalidArgumentException $e) {
            self::assertContains('Invalid argument: $resource', $e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testSetResourceShouldThrowExceptionWhenGivenObject(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'label' => 'hello',
        ]);

        try {
            $page->setResource(new \stdClass());
            self::fail('An invalid value was set, but a ' .
                        'Mezzio\Navigation\Exception\InvalidArgumentException was not thrown');
        } catch (Navigation\Exception\InvalidArgumentException $e) {
            self::assertContains('Invalid argument: $resource', $e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testSetPrivilegeNoParams(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'label' => 'hello',
            'privilege' => 'foo',
        ]);

        $page->setPrivilege();
        self::assertNull($page->getPrivilege());
    }

    /**
     * @return void
     */
    public function testSetPrivilegeNull(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'label' => 'hello',
            'privilege' => 'foo',
        ]);

        $page->setPrivilege(null);
        self::assertNull($page->getPrivilege());
    }

    /**
     * @return void
     */
    public function testSetPrivilegeString(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'label' => 'hello',
            'privilege' => 'foo',
        ]);

        $page->setPrivilege('bar');
        self::assertEquals('bar', $page->getPrivilege());
    }

    /**
     * @return void
     */
    public function testGetActiveOnNewlyConstructedPageShouldReturnFalse(): void
    {
        $page = new Uri();
        self::assertFalse($page->getActive());
    }

    /**
     * @return void
     */
    public function testIsActiveOnNewlyConstructedPageShouldReturnFalse(): void
    {
        $page = new Uri();
        self::assertFalse($page->isActive());
    }

    /**
     * @return void
     */
    public function testIsActiveRecursiveOnNewlyConstructedPageShouldReturnFalse(): void
    {
        $page = new Uri();
        self::assertFalse($page->isActive(true));
    }

    /**
     * @return void
     */
    public function testGetActiveShouldReturnTrueIfPageIsActive(): void
    {
        $page = new Uri(['active' => true]);
        self::assertTrue($page->getActive());
    }

    /**
     * @return void
     */
    public function testIsActiveShouldReturnTrueIfPageIsActive(): void
    {
        $page = new Uri(['active' => true]);
        self::assertTrue($page->isActive());
    }

    /**
     * @return void
     */
    public function testIsActiveWithRecursiveTrueShouldReturnTrueIfChildActive(): void
    {
        $page = new Uri([
            'label' => 'Page 1',
            'active' => false,
            'pages' => [
                new Uri([
                    'label' => 'Page 1.1',
                    'active' => false,
                    'pages' => [
                        new Uri([
                            'label' => 'Page 1.1',
                            'active' => true,
                        ]),
                    ],
                ]),
            ],
        ]);

        self::assertFalse($page->isActive(false));
        self::assertTrue($page->isActive(true));
    }

    /**
     * @return void
     */
    public function testGetActiveWithRecursiveTrueShouldReturnTrueIfChildActive(): void
    {
        $page = new Uri([
            'label' => 'Page 1',
            'active' => false,
            'pages' => [
                new Uri([
                    'label' => 'Page 1.1',
                    'active' => false,
                    'pages' => [
                        new Uri([
                            'label' => 'Page 1.1',
                            'active' => true,
                        ]),
                    ],
                ]),
            ],
        ]);

        self::assertFalse($page->getActive(false));
        self::assertTrue($page->getActive(true));
    }

    /**
     * @return void
     */
    public function testSetActiveWithNoParamShouldSetFalse(): void
    {
        $page = new Uri();
        $page->setActive();
        self::assertTrue($page->getActive());
    }

    /**
     * @return void
     */
    public function testSetActiveShouldJuggleValue(): void
    {
        $page = new Uri();

        $page->setActive(1);
        self::assertTrue($page->getActive());

        $page->setActive('true');
        self::assertTrue($page->getActive());

        $page->setActive(0);
        self::assertFalse($page->getActive());

        $page->setActive([]);
        self::assertFalse($page->getActive());
    }

    /**
     * @return void
     */
    public function testIsVisibleOnNewlyConstructedPageShouldReturnTrue(): void
    {
        $page = new Uri();
        self::assertTrue($page->isVisible());
    }

    /**
     * @return void
     */
    public function testGetVisibleOnNewlyConstructedPageShouldReturnTrue(): void
    {
        $page = new Uri();
        self::assertTrue($page->getVisible());
    }

    /**
     * @return void
     */
    public function testIsVisibleShouldReturnFalseIfPageIsNotVisible(): void
    {
        $page = new Uri(['visible' => false]);
        self::assertFalse($page->isVisible());
    }

    /**
     * @return void
     */
    public function testGetVisibleShouldReturnFalseIfPageIsNotVisible(): void
    {
        $page = new Uri(['visible' => false]);
        self::assertFalse($page->getVisible());
    }

    /**
     * @return void
     */
    public function testIsVisibleRecursiveTrueShouldReturnFalseIfParentInivisble(): void
    {
        $page = new Uri([
            'label' => 'Page 1',
            'visible' => false,
            'pages' => [
                new Uri([
                    'label' => 'Page 1.1',
                    'pages' => [
                        new Uri(['label' => 'Page 1.1']),
                    ],
                ]),
            ],
        ]);

        $childPage = $page->findOneByLabel('Page 1.1');
        self::assertTrue($childPage->isVisible(false));
        self::assertFalse($childPage->isVisible(true));
    }

    /**
     * @return void
     */
    public function testGetVisibleRecursiveTrueShouldReturnFalseIfParentInivisble(): void
    {
        $page = new Uri([
            'label' => 'Page 1',
            'visible' => false,
            'pages' => [
                new Uri([
                    'label' => 'Page 1.1',
                    'pages' => [
                        new Uri(['label' => 'Page 1.1']),
                    ],
                ]),
            ],
        ]);

        $childPage = $page->findOneByLabel('Page 1.1');
        self::assertTrue($childPage->getVisible(false));
        self::assertFalse($childPage->getVisible(true));
    }

    /**
     * @return void
     */
    public function testSetVisibleWithNoParamShouldSetVisble(): void
    {
        $page = new Uri(['visible' => false]);
        $page->setVisible();
        self::assertTrue($page->isVisible());
    }

    /**
     * @return void
     */
    public function testSetVisibleShouldJuggleValue(): void
    {
        $page = new Uri();

        $page->setVisible(1);
        self::assertTrue($page->isVisible());

        $page->setVisible('true');
        self::assertTrue($page->isVisible());

        $page->setVisible(0);
        self::assertFalse($page->isVisible());

        /*
         * Laminas-10146
         *
         * @link https://getlaminas.org/issues/browse/Laminas-10146
         */
        $page->setVisible('False');
        self::assertFalse($page->isVisible());

        $page->setVisible([]);
        self::assertFalse($page->isVisible());
    }

    /**
     * @return void
     */
    public function testSetTranslatorTextDomainString(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'label' => 'hello',
        ]);

        $page->setTextdomain('foo');
        self::assertEquals('foo', $page->getTextdomain());
    }

    /**
     * @return void
     */
    public function testMagicOverLoadsShouldSetAndGetNativeProperties(): void
    {
        $page = AbstractPage::factory([
            'label' => 'foo',
            'uri' => 'foo',
        ]);

        self::assertSame('foo', $page->getUri());
        self::assertSame('foo', $page->uri);

        $page->uri = 'bar';
        self::assertSame('bar', $page->getUri());
        self::assertSame('bar', $page->uri);
    }

    /**
     * @return void
     */
    public function testMagicOverLoadsShouldCheckNativeProperties(): void
    {
        $page = AbstractPage::factory([
            'label' => 'foo',
            'uri' => 'foo',
        ]);

        self::assertTrue(isset($page->uri));

        try {
            $page->uri = null;
            self::fail('Should not be possible to unset native properties');
        } catch (Navigation\Exception\InvalidArgumentException $e) {
            self::assertContains('Unsetting native property', $e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testMagicOverLoadsShouldHandleCustomProperties(): void
    {
        $page = AbstractPage::factory([
            'label' => 'foo',
            'uri' => 'foo',
        ]);

        self::assertFalse(isset($page->category));

        $page->category = 'music';
        self::assertTrue(isset($page->category));
        self::assertSame('music', $page->category);

        $page->category = null;
        self::assertFalse(isset($page->category));
    }

    /**
     * @return void
     */
    public function testMagicToStringMethodShouldReturnLabel(): void
    {
        $page = AbstractPage::factory([
            'label' => 'foo',
            'uri' => '#',
        ]);

        self::assertEquals('foo', (string) $page);
    }

    /**
     * @return void
     */
    public function testSetOptionsShouldTranslateToAccessor(): void
    {
        $page = AbstractPage::factory([
            'label' => 'foo',
            'action' => 'index',
            'controller' => 'index',
        ]);

        $options = [
            'label' => 'bar',
            'action' => 'baz',
            'controller' => 'bat',
            'id' => 'foo-test',
        ];

        $page->setOptions($options);

        $expected = [
            'label' => 'bar',
            'action' => 'baz',
            'controller' => 'bat',
            'id' => 'foo-test',
        ];

        $actual = [
            'label' => $page->getLabel(),
            'action' => $page->getAction(),
            'controller' => $page->getController(),
            'id' => $page->getId(),
        ];

        self::assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function testSetOptionsShouldSetCustomProperties(): void
    {
        $page = AbstractPage::factory([
            'label' => 'foo',
            'uri' => '#',
        ]);

        $options = [
            'test' => 'test',
            'meaning' => 42,
        ];

        $page->setOptions($options);

        $actual = [
            'test' => $page->test,
            'meaning' => $page->meaning,
        ];

        self::assertEquals($options, $actual);
    }

    /**
     * @return void
     */
    public function testAddingRelations(): void
    {
        $page = AbstractPage::factory([
            'label' => 'page',
            'uri' => '#',
        ]);

        $page->addRel('alternate', 'foo');
        $page->addRev('alternate', 'bar');

        $expected = [
            'rel' => ['alternate' => 'foo'],
            'rev' => ['alternate' => 'bar'],
        ];

        $actual = [
            'rel' => $page->getRel(),
            'rev' => $page->getRev(),
        ];

        self::assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function testRemovingRelations(): void
    {
        $page = AbstractPage::factory([
            'label' => 'page',
            'uri' => '#',
        ]);

        $page->addRel('alternate', 'foo');
        $page->addRev('alternate', 'bar');
        $page->removeRel('alternate');
        $page->removeRev('alternate');

        $expected = [
            'rel' => [],
            'rev' => [],
        ];

        $actual = [
            'rel' => $page->getRel(),
            'rev' => $page->getRev(),
        ];

        self::assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function testSetRelShouldWorkWithArray(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'rel' => [
                'foo' => 'bar',
                'baz' => 'bat',
            ],
        ]);

        $value = ['alternate' => 'format/xml'];
        $page->setRel($value);
        self::assertEquals($value, $page->getRel());
    }

    /**
     * @return void
     */
    public function testSetRelShouldWorkWithConfig(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'rel' => [
                'foo' => 'bar',
                'baz' => 'bat',
            ],
        ]);

        $value = ['alternate' => 'format/xml'];
        $page->setRel(new Config\Config($value));
        self::assertEquals($value, $page->getRel());
    }

    /**
     * @return void
     */
    public function testSetRelShouldWithNoParamsShouldResetRelations(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'rel' => [
                'foo' => 'bar',
                'baz' => 'bat',
            ],
        ]);

        $value = [];
        $page->setRel();
        self::assertEquals($value, $page->getRel());
    }

    /**
     * @return void
     */
    public function testSetRelShouldThrowExceptionWhenNotNullOrArrayOrConfig(): void
    {
        $page = AbstractPage::factory(['type' => 'uri']);

        try {
            $page->setRel('alternate');
            self::fail('An invalid value was set, but a ' .
                        'Mezzio\Navigation\Exception\InvalidArgumentException was not thrown');
        } catch (Navigation\Exception\InvalidArgumentException $e) {
            self::assertContains('Invalid argument: $relations', $e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testSetRevShouldWorkWithArray(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'rev' => [
                'foo' => 'bar',
                'baz' => 'bat',
            ],
        ]);

        $value = ['alternate' => 'format/xml'];
        $page->setRev($value);
        self::assertEquals($value, $page->getRev());
    }

    /**
     * @return void
     */
    public function testSetRevShouldWorkWithConfig(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'rev' => [
                'foo' => 'bar',
                'baz' => 'bat',
            ],
        ]);

        $value = ['alternate' => 'format/xml'];
        $page->setRev(new Config\Config($value));
        self::assertEquals($value, $page->getRev());
    }

    /**
     * @return void
     */
    public function testSetRevShouldWithNoParamsShouldResetRelations(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'rev' => [
                'foo' => 'bar',
                'baz' => 'bat',
            ],
        ]);

        $value = [];
        $page->setRev();
        self::assertEquals($value, $page->getRev());
    }

    /**
     * @return void
     */
    public function testSetRevShouldThrowExceptionWhenNotNullOrArrayOrConfig(): void
    {
        $page = AbstractPage::factory(['type' => 'uri']);

        try {
            $page->setRev('alternate');
            self::fail('An invalid value was set, but a ' .
                        'Mezzio\Navigation\Exception\InvalidArgumentException was not thrown');
        } catch (Navigation\Exception\InvalidArgumentException $e) {
            self::assertContains('Invalid argument: $relations', $e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testGetRelWithArgumentShouldRetrieveSpecificRelation(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'rel' => ['foo' => 'bar'],
        ]);

        self::assertEquals('bar', $page->getRel('foo'));
    }

    /**
     * @return void
     */
    public function testGetRevWithArgumentShouldRetrieveSpecificRelation(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'rev' => ['foo' => 'bar'],
        ]);

        self::assertEquals('bar', $page->getRev('foo'));
    }

    /**
     * @return void
     */
    public function testGetDefinedRel(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'rel' => [
                'alternate' => 'foo',
                'foo' => 'bar',
            ],
        ]);

        $expected = ['alternate', 'foo'];
        self::assertEquals($expected, $page->getDefinedRel());
    }

    /**
     * @return void
     */
    public function testGetDefinedRev(): void
    {
        $page = AbstractPage::factory([
            'type' => 'uri',
            'rev' => [
                'alternate' => 'foo',
                'foo' => 'bar',
            ],
        ]);

        $expected = ['alternate', 'foo'];
        self::assertEquals($expected, $page->getDefinedRev());
    }

    /**
     * @return void
     */
    public function testGetCustomProperties(): void
    {
        $page = AbstractPage::factory([
            'label' => 'foo',
            'uri' => '#',
            'baz' => 'bat',
        ]);

        $options = [
            'test' => 'test',
            'meaning' => 42,
        ];

        $page->setOptions($options);

        $expected = [
            'baz' => 'bat',
            'test' => 'test',
            'meaning' => 42,
        ];

        self::assertEquals($expected, $page->getCustomProperties());
    }

    /**
     * @return void
     */
    public function testToArrayMethod(): void
    {
        $options = [
            'label' => 'foo',
            'uri' => 'http://www.example.com/foo.html',
            'fragment' => 'bar',
            'id' => 'my-id',
            'class' => 'my-class',
            'title' => 'my-title',
            'target' => 'my-target',
            'rel' => [],
            'rev' => [],
            'order' => 100,
            'active' => true,
            'visible' => false,

            'resource' => 'joker',
            'privilege' => null,
            'permission' => null,

            'foo' => 'bar',
            'meaning' => 42,

            'pages' => [
                [
                    'type' => 'Mezzio\Navigation\Page\Uri',
                    'label' => 'foo.bar',
                    'fragment' => null,
                    'id' => null,
                    'class' => null,
                    'title' => null,
                    'target' => null,
                    'rel' => [],
                    'rev' => [],
                    'order' => null,
                    'resource' => null,
                    'privilege' => null,
                    'permission' => null,
                    'active' => null,
                    'visible' => 1,
                    'pages' => [],
                    'uri' => 'http://www.example.com/foo.html',
                ],
                [
                    'label' => 'foo.baz',
                    'type' => 'Mezzio\Navigation\Page\Uri',
                    'label' => 'foo.bar',
                    'fragment' => null,
                    'id' => null,
                    'class' => null,
                    'title' => null,
                    'target' => null,
                    'rel' => [],
                    'rev' => [],
                    'order' => null,
                    'resource' => null,
                    'privilege' => null,
                    'permission' => null,
                    'active' => null,
                    'visible' => 1,
                    'pages' => [],
                    'uri' => 'http://www.example.com/foo.html',
                ],
            ],
        ];

        $page    = AbstractPage::factory($options);
        $toArray = $page->toArray();

        // tweak options to what we expect toArray() to contain
        $options['type'] = 'Mezzio\Navigation\Page\Uri';

        ksort($options);
        ksort($toArray);
        self::assertEquals($options, $toArray);
    }

    /**
     * @return void
     */
    public function testSetPermission(): void
    {
        $page = AbstractPage::factory(['type' => 'uri']);

        $page->setPermission('my_permission');
        self::assertEquals('my_permission', $page->getPermission());
    }

    /**
     * @return void
     */
    public function testSetArrayPermission(): void
    {
        $page = AbstractPage::factory(['type' => 'uri']);

        $page->setPermission(['my_permission', 'other_permission']);
        self::assertIsArray($page->getPermission());
        self::assertCount(2, $page->getPermission());
    }

    /**
     * @return void
     */
    public function testSetObjectPermission(): void
    {
        $page = AbstractPage::factory(['type' => 'uri']);

        $permission       = new \stdClass();
        $permission->name = 'my_permission';

        $page->setPermission($permission);
        self::assertInstanceOf('stdClass', $page->getPermission());
        self::assertEquals('my_permission', $page->getPermission()->name);
    }

    /**
     * @return void
     */
    public function testSetParentShouldThrowExceptionIfPageItselfIsParent(): void
    {
        $page = AbstractPage::factory(
            ['type' => 'uri']
        );

        $this->expectException(Exception\InvalidArgumentException::class);
        $page->setParent($page);
    }
}
