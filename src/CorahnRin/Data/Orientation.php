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

namespace CorahnRin\Data;

final class Orientation
{
    public const INSTINCTIVE = 'character.orientation.instinctive';
    public const RATIONAL = 'character.orientation.rational';

    public const ALL = [
        self::INSTINCTIVE => self::INSTINCTIVE.'.description',
        self::RATIONAL => self::RATIONAL.'.description',
    ];
}
