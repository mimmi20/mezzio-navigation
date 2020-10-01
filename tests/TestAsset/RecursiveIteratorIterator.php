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

final class RecursiveIteratorIterator extends \RecursiveIteratorIterator
{
    /** @var array|\ArrayAccess */
    public $logger = [];

    /**
     * @return void
     */
    public function beginIteration(): void
    {
        $this->logger[] = 'beginIteration';
    }

    /**
     * @return void
     */
    public function endIteration(): void
    {
        $this->logger[] = 'endIteration';
    }

    /**
     * @return void
     */
    public function beginChildren(): void
    {
        $this->logger[] = 'beginChildren';
    }

    /**
     * @return void
     */
    public function endChildren(): void
    {
        $this->logger[] = 'endChildren';
    }

    /**
     * @return void
     */
    public function current(): void
    {
        $this->logger[] = parent::current()->getLabel();
    }
}
