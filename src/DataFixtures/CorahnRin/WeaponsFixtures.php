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

use CorahnRin\Data\ItemAvailability;
use CorahnRin\Document\Weapon;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class WeaponsFixtures extends ArrayFixture implements ORMFixtureInterface
{
    protected function getEntityClass(): string
    {
        return Weapon::class;
    }

    protected function getReferencePrefix(): ?string
    {
        return 'corahnrin-weapons-';
    }

    protected function getObjects(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Dague,poignard,couteau',
                'description' => '',
                'damage' => 1,
                'price' => 4,
                'availability' => ItemAvailability::FREQUENT,
                'melee' => true,
                'range' => 4,
            ],
            [
                'id' => 2,
                'name' => 'Fronde',
                'description' => '',
                'damage' => 1,
                'price' => 2,
                'availability' => ItemAvailability::FREQUENT,
                'range' => 8,
            ],
            [
                'id' => 3,
                'name' => 'Javelot',
                'description' => '',
                'damage' => 2,
                'price' => 8,
                'availability' => ItemAvailability::COMMON,
                'melee' => true,
                'range' => 4,
            ],
            [
                'id' => 4,
                'name' => 'Arc',
                'description' => '',
                'damage' => 2,
                'price' => 30,
                'availability' => ItemAvailability::FREQUENT,
                'range' => 20,
            ],
            [
                'id' => 5,
                'name' => 'Arbalète',
                'description' => '',
                'damage' => 2,
                'price' => 50,
                'availability' => ItemAvailability::COMMON,
                'range' => 24,
            ],
            [
                'id' => 6,
                'name' => 'Francisque',
                'description' => '',
                'damage' => 2,
                'price' => 20,
                'availability' => ItemAvailability::FREQUENT,
                'melee' => true,
                'range' => 2,
            ],
            [
                'id' => 7,
                'name' => 'Lance courte, épieu',
                'description' => '',
                'damage' => 2,
                'price' => 20,
                'availability' => ItemAvailability::FREQUENT,
                'melee' => true,
                'range' => 3,
            ],
            [
                'id' => 8,
                'name' => 'Gourdin',
                'description' => '',
                'damage' => 1,
                'price' => 2,
                'availability' => ItemAvailability::FREQUENT,
                'melee' => true,
                'range' => 0,
            ],
            [
                'id' => 9,
                'name' => 'Marteau d\'artisan',
                'description' => '',
                'damage' => 2,
                'price' => 10,
                'availability' => ItemAvailability::FREQUENT,
                'melee' => true,
                'range' => 0,
            ],
            [
                'id' => 10,
                'name' => 'Masse d\'armes',
                'description' => '',
                'damage' => 2,
                'price' => 20,
                'availability' => ItemAvailability::COMMON,
                'melee' => true,
                'range' => 0,
            ],
            [
                'id' => 11,
                'name' => 'Carath',
                'description' => '',
                'damage' => 2,
                'price' => 8,
                'availability' => ItemAvailability::FREQUENT,
                'melee' => true,
                'range' => 0,
            ],
            [
                'id' => 12,
                'name' => 'Hache de bataille',
                'description' => '',
                'damage' => 3,
                'price' => 50,
                'availability' => ItemAvailability::COMMON,
                'melee' => true,
                'range' => 0,
            ],
            [
                'id' => 13,
                'name' => 'Epée longue droite Osag',
                'description' => '',
                'damage' => 3,
                'price' => 70,
                'availability' => ItemAvailability::COMMON,
                'melee' => true,
                'range' => 0,
            ],
            [
                'id' => 14,
                'name' => 'Glaive continental',
                'description' => '',
                'damage' => 2,
                'price' => 50,
                'availability' => ItemAvailability::COMMON,
                'melee' => true,
                'range' => 0,
            ],
            [
                'id' => 15,
                'name' => 'Arme d\'hast',
                'description' => '',
                'damage' => 3,
                'price' => 80,
                'availability' => ItemAvailability::RARE,
                'melee' => true,
                'range' => 0,
            ],
            [
                'id' => 16,
                'name' => 'Lance longue',
                'description' => '',
                'damage' => 3,
                'price' => 40,
                'availability' => ItemAvailability::COMMON,
                'melee' => true,
                'range' => 0,
            ],
            [
                'id' => 17,
                'name' => 'Marteau à deux mains',
                'description' => '',
                'damage' => 4,
                'price' => 100,
                'availability' => ItemAvailability::RARE,
                'melee' => true,
                'range' => 0,
            ],
            [
                'id' => 18,
                'name' => 'Claymore',
                'description' => '',
                'damage' => 4,
                'price' => 100,
                'availability' => ItemAvailability::RARE,
                'melee' => true,
                'range' => 0,
            ],
            [
                'id' => 19,
                'name' => 'Épée courte',
                'description' => '',
                'damage' => 2,
                'price' => 50,
                'availability' => ItemAvailability::COMMON,
                'melee' => true,
                'range' => 0,
            ],
            [
                'id' => 20,
                'name' => 'Épée longue',
                'description' => '',
                'damage' => 3,
                'price' => 70,
                'availability' => ItemAvailability::FREQUENT,
                'melee' => true,
                'range' => 0,
            ],
            [
                'id' => 21,
                'name' => 'Arc court',
                'description' => '',
                'damage' => 2,
                'price' => 30,
                'availability' => ItemAvailability::FREQUENT,
                'range' => 20,
            ],
            [
                'id' => 22,
                'name' => 'Bâton',
                'description' => '',
                'damage' => 2,
                'price' => 4,
                'availability' => ItemAvailability::FREQUENT,
                'melee' => true,
                'range' => 0,
            ],
        ];
    }
}
