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

use CorahnRin\Data\Ways;

class InvalidWay extends \InvalidArgumentException
{
    public function __construct(string $way)
    {
        parent::__construct(\sprintf(
            'Provided way "%s" is not a valid way. Possible values: %s',
            $way,
            \implode(', ', \array_keys(Ways::ALL))
        ));
    }
}
