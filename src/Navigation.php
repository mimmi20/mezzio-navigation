<?php
/**
 * This file is part of the mimmi20/mezzio-navigation package.
 *
 * Copyright (c) 2020-2024, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Mezzio\Navigation;

use Mimmi20\Mezzio\Navigation\Page\PageInterface;

/**
 * A simple container class for {@link \Mimmi20\Mezzio\Navigation\Navigation} pages
 *
 * @implements ContainerInterface<PageInterface>
 */
final class Navigation implements ContainerInterface
{
    use ContainerTrait;
}
