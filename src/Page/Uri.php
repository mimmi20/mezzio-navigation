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

namespace Mimmi20\Mezzio\Navigation\Page;

use Override;
use Psr\Http\Message\ServerRequestInterface;

use function array_merge;
use function mb_substr;

/**
 * Represents a page that is defined by specifying a URI
 */
final class Uri extends AbstractPage implements UriInterface
{
    /**
     * Page URI
     */
    private string | null $uri = null;

    /**
     * Request object used to determine uri path
     */
    private ServerRequestInterface | null $request = null;

    /**
     * Sets page URI
     *
     * @param string|null $uri page URI, must a string or null
     *
     * @throws void
     */
    #[Override]
    public function setUri(string | null $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * Returns URI
     *
     * @throws void
     */
    #[Override]
    public function getUri(): string | null
    {
        return $this->uri;
    }

    /** @throws void */
    #[Override]
    public function getRequest(): ServerRequestInterface | null
    {
        return $this->request;
    }

    /** @throws void */
    #[Override]
    public function setRequest(ServerRequestInterface | null $request): void
    {
        $this->request = $request;
    }

    /**
     * Returns href for this page
     *
     * Includes the fragment identifier if it is set.
     *
     * @throws void
     */
    #[Override]
    public function getHref(): string
    {
        $uri      = (string) $this->getUri();
        $fragment = $this->getFragment();

        if ($fragment !== null) {
            if (mb_substr($fragment, 0, 1) !== '#') {
                $fragment = '#' . $fragment;
            }

            if (mb_substr($uri, -1) === '#') {
                return mb_substr($uri, 0, -1) . $fragment;
            }

            return $uri . $fragment;
        }

        return $uri;
    }

    /**
     * Returns whether page should be considered active or not
     *
     * This method will compare the page properties against the request uri.
     *
     * @param bool $recursive [optional] whether page should be considered
     *                        active if any child pages are active. Default is
     *                        false.
     *
     * @return bool whether page should be considered active or not
     *
     * @throws void
     */
    #[Override]
    public function isActive(bool $recursive = false): bool
    {
        if (
            !$this->active
            && $this->getRequest() instanceof ServerRequestInterface
            && $this->getRequest()->getUri()->getPath() === $this->getUri()
        ) {
            $this->active = true;

            return true;
        }

        return parent::isActive($recursive);
    }

    /**
     * Returns an array representation of the page
     *
     * @return array<string, mixed>
     *
     * @throws void
     */
    #[Override]
    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            [
                'uri' => $this->getUri(),
            ],
        );
    }
}
