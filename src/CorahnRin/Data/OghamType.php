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

class OghamType
{
    public const AIR = 'ogham_type.air';
    public const ANIMAL = 'ogham_type.animal';
    public const EARTH = 'ogham_type.earth';
    public const FIRE = 'ogham_type.fire';
    public const LIFE = 'ogham_type.life';
    public const VEGETAL = 'ogham_type.vegetal';
    public const WATER = 'ogham_type.water';

    public const ALL = [
        self::AIR => self::AIR,
        self::ANIMAL => self::ANIMAL,
        self::EARTH => self::EARTH,
        self::FIRE => self::FIRE,
        self::LIFE => self::LIFE,
        self::VEGETAL => self::VEGETAL,
        self::WATER => self::WATER,
    ];
}
