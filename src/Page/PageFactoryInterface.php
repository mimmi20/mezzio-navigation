<?php
/**
 * This file is part of the mimmi20/mezzio-navigation package.
 *
 * Copyright (c) 2020-2021, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mezzio\Navigation\Page;

use Mezzio\Navigation\Exception;

/**
 * Base class for Mezzio\Navigation\Page pages
 */
interface PageFactoryInterface
{
    /**
     * Factory for Mezzio\Navigation\Page classes
     * A specific type to construct can be specified by specifying the key
     * 'type' in $options. If type is 'uri' or 'route', the type will be resolved
     * to Mezzio\Navigation\Page\Uri or Mezzio\Navigation\Page\Route. Any other value
     * for 'type' will be considered the full name of the class to construct.
     * A valid custom page class must implement Mezzio\Navigation\Page\PageInterface.
     * If 'type' is not given, the type of page to construct will be determined
     * by the following rules:
     * - If $options contains the key 'route', a Mezzio\Navigation\Page\Route page will be created.
     * - If $options contains the key 'uri', a Mezzio\Navigation\Page\Uri page will be created.
     *
     * @param array<string, array<string>|bool|string> $options options used for creating page
     *
     * @return PageInterface a page instance
     *
     * @throws Exception\InvalidArgumentException
     */
    public function factory(array $options): PageInterface;
}
