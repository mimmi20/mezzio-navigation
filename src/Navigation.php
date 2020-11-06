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
namespace Mezzio\Navigation;

/**
 * A simple container class for {@link \Mezzio\Navigation\Navigation} pages
 */
final class Navigation implements ContainerInterface
{
    use ContainerTrait;

    /**
     * Creates a new navigation container
     *
     * @param array|\Traversable|null $pages [optional] pages to add
     *
     * @throws Exception\InvalidArgumentException                 if $pages is invalid
     * @throws \Laminas\Stdlib\Exception\InvalidArgumentException
     */
    public function __construct($pages = null)
    {
        if ($pages && (!is_array($pages) && !$pages instanceof \Traversable)) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $pages must be an array, an '
                . 'instance of Traversable, or null'
            );
        }

        if (!$pages) {
            return;
        }

        $this->addPages($pages);
    }
}
