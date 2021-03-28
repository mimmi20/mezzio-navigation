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

use Psr\Http\Message\ServerRequestInterface;

/**
 * Represents a page that is defined by specifying a URI
 */
interface UriInterface extends PageInterface
{
    /**
     * Sets page URI
     *
     * @param string|null $uri page URI, must a string or null
     */
    public function setUri(?string $uri): void;

    /**
     * Returns URI
     */
    public function getUri(): ?string;

    public function getRequest(): ?ServerRequestInterface;

    public function setRequest(?ServerRequestInterface $request): void;
}
