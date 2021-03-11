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
     *
     * @return void
     */
    public function setUri(?string $uri): void;

    /**
     * Returns URI
     *
     * @return string|null
     */
    public function getUri(): ?string;

    /**
     * @return \Psr\Http\Message\ServerRequestInterface|null
     */
    public function getRequest(): ?ServerRequestInterface;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface|null $request
     *
     * @return void
     */
    public function setRequest(?ServerRequestInterface $request): void;
}
