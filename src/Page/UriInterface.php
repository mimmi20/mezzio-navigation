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

namespace Mimmi20\Mezzio\Navigation\Page;

use Mimmi20\Mezzio\Navigation\Exception;
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
     * @throws void
     */
    public function setUri(string | null $uri): void;

    /**
     * Returns URI
     *
     * @throws void
     */
    public function getUri(): string | null;

    /** @throws void */
    public function getRequest(): ServerRequestInterface | null;

    /** @throws void */
    public function setRequest(ServerRequestInterface | null $request): void;

    /**
     * Sets resource associated with this page
     *
     * @param string $resource [optional] resource to associate
     *                         with page. Default is null, which
     *                         sets no resource.
     *
     * @throws Exception\InvalidArgumentException if $resource is invalid
     */
    public function setResource(string $resource): void;
}
