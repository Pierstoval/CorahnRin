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

namespace DataFixtures\CorahnRin;

use CorahnRin\Document\Flux;
use Doctrine\Bundle\MongoDBBundle\Fixture\ODMFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class FluxFixtures extends ArrayFixture implements ODMFixtureInterface
{
    public const ID_VEGETAL = 1;
    public const ID_MINERAL = 2;
    public const ID_ORGANIC = 3;
    public const ID_FOSSILE = 4;
    public const ID_M = 5;

    public function getEntityClass(): string
    {
        return Flux::class;
    }

    public function getReferencePrefix(): ?string
    {
        return 'corahnrin-flux-';
    }

    public function getObjects(): iterable
    {
        return [
            ['id' => self::ID_VEGETAL, 'name' => 'Végétal', 'description' => ''],
            ['id' => self::ID_MINERAL, 'name' => 'Minéral', 'description' => ''],
            ['id' => self::ID_ORGANIC, 'name' => 'Organique', 'description' => ''],
            ['id' => self::ID_FOSSILE, 'name' => 'Fossile', 'description' => ''],
            ['id' => self::ID_M, 'name' => 'M', 'description' => ''],
        ];
    }
}
