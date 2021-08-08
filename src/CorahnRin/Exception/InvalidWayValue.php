<?php

declare(strict_types=1);

/*
 * This file is part of the Corahn-Rin package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Exception;

use InvalidArgumentException;

class InvalidWayValue extends InvalidArgumentException
{
    public function __construct(string $way)
    {
        parent::__construct(\sprintf(
            'Provided way "%s" does not have a right value. Expected a value from 1 to 5',
            $way
        ));
    }
}
