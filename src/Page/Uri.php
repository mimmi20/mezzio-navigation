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

use Psr\Http\Message\ServerRequestInterface;

/**
 * Represents a page that is defined by specifying a URI
 */
final class Uri implements UriInterface
{
    use PageTrait {
        isActive as isActiveParent;
        toArray as toParentArray;
    }

    /**
     * Page URI
     *
     * @var string|null
     */
    private $uri;

    /**
     * Request object used to determine uri path
     *
     * @var ServerRequestInterface|null
     */
    private $request;

    /**
     * Sets page URI
     *
     * @param string|null $uri page URI, must a string or null
     *
     * @return void
     */
    public function setUri(?string $uri): void
    {
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
     * @return \Psr\Http\Message\ServerRequestInterface|null
     */
    public function getRequest(): ?ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface|null $request
     *
     * @return void
     */
    public function setRequest(?ServerRequestInterface $request): void
    {
        $this->request = $request;
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
        $uri      = (string) $this->getUri();
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
        if (
            !$this->active
            && $this->getRequest() instanceof ServerRequestInterface
            && $this->getRequest()->getUri()->getPath() === $this->getUri()
        ) {
            $this->active = true;

            return true;
        }

        return $this->isActiveParent($recursive);
    }

    /**
     * Returns an array representation of the page
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(
            $this->toParentArray(),
            [
                'uri' => $this->getUri(),
            ]
        );
    }
}
