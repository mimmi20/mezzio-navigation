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
namespace Mezzio\Navigation\Page;

use Laminas\Stdlib\ArrayUtils;
use Mezzio\Navigation\Exception;
use Traversable;

/**
 * Base class for Mezzio\Navigation\Page pages
 */
final class PageFactory
{
    /**
     * Static factories list for factory pages
     *
     * @var array
     */
    private static $factories = [];

    /**
     * Factory for Mezzio\Navigation\Page classes
     * A specific type to construct can be specified by specifying the key
     * 'type' in $options. If type is 'uri' or 'route', the type will be resolved
     * to Mezzio\Navigation\Page\Uri or Mezzio\Navigation\Page\Route. Any other value
     * for 'type' will be considered the full name of the class to construct.
     * A valid custom page class must extend Mezzio\Navigation\Page\AbstractPage.
     * If 'type' is not given, the type of page to construct will be determined
     * by the following rules:
     * - If $options contains the key 'route', a Mezzio\Navigation\Page\Route page will be created.
     * - If $options contains the key 'uri', a Mezzio\Navigation\Page\Uri page will be created.
     *
     * @param iterable $options options used for creating page
     *
     * @throws Exception\InvalidArgumentException                 if 'type' is specified but class not found
     * @throws Exception\InvalidArgumentException                 if something goes wrong during instantiation of the page
     * @throws Exception\InvalidArgumentException                 if 'type' is given, and the specified type does not extend this class
     * @throws Exception\InvalidArgumentException                 if unable to determine which class to instantiate
     * @throws Exception\InvalidArgumentException                 if $options is not array/Traversable
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     *
     * @return PageInterface a page instance
     */
    public static function factory(iterable $options): PageInterface
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (!is_array($options)) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $options must be an array or Traversable'
            );
        }

        if (isset($options['type'])) {
            $type = $options['type'];
            if (is_string($type) && !empty($type)) {
                switch (mb_strtolower($type)) {
                    case 'route':
                        $type = Route::class;
                        break;
                    case 'uri':
                        $type = Uri::class;
                        break;
                }

                if (!class_exists($type, true)) {
                    throw new Exception\InvalidArgumentException(
                        'Cannot find class ' . $type
                    );
                }

                $page = new $type($options);
                if (!$page instanceof PageInterface) {
                    throw new Exception\InvalidArgumentException(
                        sprintf(
                            'Invalid argument: Detected type "%s", which ' .
                            'is not an instance of Mezzio\Navigation\Page\PageInterface',
                            $type
                        )
                    );
                }

                return $page;
            }
        }

        if (self::$factories) {
            foreach (self::$factories as $factoryCallBack) {
                $page = call_user_func($factoryCallBack, $options);

                if ($page) {
                    return $page;
                }
            }
        }

        $hasUri   = isset($options['uri']);
        $hasRoute = isset($options['route']);

        if ($hasRoute) {
            return new Route($options);
        }

        if ($hasUri) {
            return new Uri($options);
        }

        throw new Exception\InvalidArgumentException(
            'Invalid argument: Unable to determine class to instantiate'
        );
    }

    /**
     * Add static factory for self::factory function
     *
     * @param callable $callback Any callable variable
     *
     * @return void
     */
    public static function addFactory(callable $callback): void
    {
        self::$factories[] = $callback;
    }
}
