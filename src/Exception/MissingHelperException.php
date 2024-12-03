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

namespace Mimmi20\Mezzio\Navigation\Exception;

use DomainException;
use Psr\Container\ContainerExceptionInterface;

final class MissingHelperException extends DomainException implements ContainerExceptionInterface, ExceptionInterface
{
}
