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
namespace MezzioTest\Navigation\TestAsset;

final class Router extends \Laminas\Router\Http\TreeRouteStack
{
    public const RETURN_URL = 'spotify:track:2nd6CTjR9zjHGT0QtpfLHe';

    /**
     * @param array $params
     * @param array $options
     *
     * @return string
     */
    public function assemble(array $params = [], array $options = []): string
    {
        return self::RETURN_URL;
    }
}
