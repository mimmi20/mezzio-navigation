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

namespace Mimmi20\MezzioTest\Navigation\TestAsset;

use Mimmi20\Mezzio\Navigation\Page\AbstractPage;
use Mimmi20\Mezzio\Navigation\Page\PageInterface;
use Override;

final class Page extends AbstractPage implements PageInterface
{
    /** @throws void */
    #[Override]
    public function getHref(): string
    {
        return '';
    }
}
