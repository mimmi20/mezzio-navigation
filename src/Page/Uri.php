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
namespace Mezzio\Navigation\Page;

use Psr\Http\Message\ServerRequestInterface as Request;
use Mezzio\Navigation\Exception;

/**
 * Represents a page that is defined by specifying a URI
 */
final class Uri extends AbstractPage
{
    /**
     * Page URI
     *
     * @var string|null
     */
    private $uri;

    /**
     * Request object used to determine uri path
     *
     * @var Request|null
     */
    private $request;

    /**
     * Sets page URI
     *
     * @param string $uri page URI, must a string or null
     *
     * @throws Exception\InvalidArgumentException if $uri is invalid
     *
     * @return void
     */
    public function setUri(string $uri): void
    {
        if (null !== $uri && !is_string($uri)) {
            throw new Exception\InvalidArgumentException(
                'Invalid argument: $uri must be a string or null'
            );
        }

        $this->uri = $uri;
    }

    /**
     * Returns URI
     *
     * @return string|null
     */
    public function getUri(): ?string
    {
        return $this->uri;
    }

    /**
     * Returns href for this page
     *
     * Includes the fragment identifier if it is set.
     *
     * @return string
     */
    public function getHref(): string
    {
        $uri = $this->getUri();
        $fragment = $this->getFragment();

        if (null !== $fragment) {
            if ('#' === mb_substr($uri, -1)) {
                return $uri . $fragment;
            }

            return $uri . '#' . $fragment;
        }

        return $uri;
    }

    /**
     * Returns whether page should be considered active or not
     *
     * This method will compare the page properties against the request uri.
     *
     * @param bool $recursive
     *                        [optional] whether page should be considered
     *                        active if any child pages are active. Default is
     *                        false.
     *
     * @return bool whether page should be considered active or not
     */
    public function isActive(bool $recursive = false): bool
    {
        if (!$this->active
            && $this->getRequest() instanceof Request
            && $this->getRequest()->getUri()->getPath() === $this->getUri()
        ) {
            $this->active = true;

            return true;
        }

        return parent::isActive($recursive);
    }

    /**
     * Get the request
     *
     * @return Request|null
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }

    /**
     * Sets request for assembling URLs
     *
     * @param Request|null $request
     *
     * @return void
     */
    public function setRequest(?Request $request = null): void
    {
        $this->request = $request;
    }

    /**
     * Returns an array representation of the page
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            [
                'uri' => $this->getUri(),
            ]
        );
    }
}
