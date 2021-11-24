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

namespace Tests\CorahnRin\Entity\CharacterProperties;

use CorahnRin\Entity\CharacterProperties\Bonuses;
use CorahnRin\Exception\InvalidBonus;
use PHPUnit\Framework\TestCase;

class BonusesTest extends TestCase
{
    /**
     * @dataProvider provide valid bonuses
     * @group unit
     */
    public function test valid bonus(string $bonus): void
    {
        Bonuses::validateBonus($bonus);

        self::assertTrue(true);
    }

    public function provide valid bonuses(): \Generator
    {
        yield 'speed' => ['speed'];
        yield 'mental_resistance' => ['mental_resistance'];
        yield 'health' => ['health'];
        yield 'survival' => ['survival'];
        yield 'stamina' => ['stamina'];
        yield 'trauma' => ['trauma'];
        yield 'defense' => ['defense'];
        yield 'luck' => ['luck'];
        yield 'money_0' => ['money_0'];
        yield 'money_azure_20' => ['money_azure_20'];
        yield 'money_azure_50' => ['money_azure_50'];
        yield 'money_frost_10' => ['money_frost_10'];
        yield 'money_frost_20' => ['money_frost_20'];
        yield 'money_frost_50' => ['money_frost_50'];
        yield 'money_frost_100' => ['money_frost_100'];
    }

    /**
     * @group unit
     */
    public function test invalid bonus(): void
    {
        $this->expectException(InvalidBonus::class);
        $this->expectExceptionMessage('Provided bonus "inexistent_bonus" is not valid. Possible values: speed, mental_resistance, health, survival, stamina, trauma, defense, luck, money_0, money_azure_20, money_azure_50, money_frost_10, money_frost_20, money_frost_50, money_frost_100');

        Bonuses::validateBonus('inexistent_bonus');
    }
}
