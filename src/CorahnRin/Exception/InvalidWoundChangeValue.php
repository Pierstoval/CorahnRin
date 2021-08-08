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

class InvalidWoundChangeValue extends \InvalidArgumentException
{
    /**
     * @param bool $healing true if attempting to heal, false if attempting to wound
     */
    public function __construct(bool $healing)
    {
        if (true === $healing) {
            parent::__construct('One cannot heal a negative amount of wounds.');
        } else {
            parent::__construct('A character cannot lose a negative amount of wounds.');
        }
    }
}
