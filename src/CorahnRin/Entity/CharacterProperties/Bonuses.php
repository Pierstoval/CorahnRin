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

namespace CorahnRin\Entity\CharacterProperties;

use CorahnRin\Exception\InvalidBonus;

final class Bonuses
{
    public const SPEED = 'speed';
    public const MENTAL_RESISTANCE = 'mental_resistance';
    public const HEALTH = 'health';
    public const SURVIVAL = 'survival';
    public const STAMINA = 'stamina';
    public const TRAUMA = 'trauma';
    public const DEFENSE = 'defense';
    public const LUCK = 'luck';

    public const MONEY_0 = 'money_0';

    public const MONEY_20A = 'money_azure_20';
    public const MONEY_50A = 'money_azure_50';

    public const MONEY_10G = 'money_frost_10';
    public const MONEY_20G = 'money_frost_20';
    public const MONEY_50G = 'money_frost_50';
    public const MONEY_100G = 'money_frost_100';

    public const ALL = [
        self::SPEED,
        self::MENTAL_RESISTANCE,
        self::HEALTH,
        self::SURVIVAL,
        self::STAMINA,
        self::TRAUMA,
        self::DEFENSE,
        self::LUCK,
        self::MONEY_0,
        self::MONEY_20A,
        self::MONEY_50A,
        self::MONEY_10G,
        self::MONEY_20G,
        self::MONEY_50G,
        self::MONEY_100G,
    ];

    public static function validateBonus(string $bonus): void
    {
        if (!\in_array($bonus, static::ALL, true)) {
            throw new InvalidBonus($bonus);
        }
    }
}
