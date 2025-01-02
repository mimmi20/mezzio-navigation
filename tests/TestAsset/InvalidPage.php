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

namespace Mimmi20\MezzioTest\Navigation\TestAsset;

final class InvalidPage
{
    /**
     * Returns the page's href
     *
     * @throws void
     *
     * @api
     */
    public function getHref(): string
    {
        return '#';
    }
}
