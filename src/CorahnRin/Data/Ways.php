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

use CorahnRin\Exception\InvalidWay;
use CorahnRin\Exception\InvalidWayValue;

final class Ways
{
    public const COMBATIVENESS = 'ways.combativeness';
    public const CREATIVITY = 'ways.creativity';
    public const EMPATHY = 'ways.empathy';
    public const REASON = 'ways.reason';
    public const CONVICTION = 'ways.conviction';

    public const ALL = [
        self::COMBATIVENESS => self::COMBATIVENESS.'.description',
        self::CREATIVITY => self::CREATIVITY.'.description',
        self::EMPATHY => self::EMPATHY.'.description',
        self::REASON => self::REASON.'.description',
        self::CONVICTION => self::CONVICTION.'.description',
    ];

    public static function validateWay(string $way): void
    {
        if (!isset(static::ALL[$way])) {
            throw new InvalidWay($way);
        }
    }

    public static function validateWayValue(string $way, int $value): void
    {
        self::validateWay($way);

        if ($value < 1 || $value > 5) {
            throw new InvalidWayValue($way);
        }
    }
}
