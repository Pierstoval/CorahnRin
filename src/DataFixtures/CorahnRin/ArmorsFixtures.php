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

use CorahnRin\Entity\Armor;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class ArmorsFixtures extends ArrayFixture implements ORMFixtureInterface, DependentFixtureInterface
{
    public function getEntityClass(): string
    {
        return Armor::class;
    }

    public function getObjects(): iterable
    {
        $book = $this->getReference('corahnrin-book-'.BooksFixtures::ID_BOOK_1_UNIVERSE);

        return [
            [
                'id' => 1,
                'name' => 'Bouclier rond',
                'description' => 'bois renforcé de métal',
                'protection' => 1,
                'price' => 20,
                'availability' => 'FR',
                'book' => $book,
            ],
            [
                'id' => 2,
                'name' => 'Bouclier Osag',
                'description' => 'bois et renforts de métal, rectangulaire',
                'protection' => 1,
                'price' => 20,
                'availability' => 'CO',
                'book' => $book,
            ],
            [
                'id' => 3,
                'name' => 'Écu Hilderin',
                'description' => 'en métal, de forme triangulaire',
                'protection' => 1,
                'price' => 30,
                'availability' => 'RA',
                'book' => $book,
            ],
            [
                'id' => 4,
                'name' => 'Cotte de cuir',
                'description' => '',
                'protection' => 1,
                'price' => 20,
                'availability' => 'FR',
                'book' => $book,
            ],
            [
                'id' => 5,
                'name' => 'Cotte de cuir clouté',
                'description' => '',
                'protection' => 2,
                'price' => 30,
                'availability' => 'CO',
                'book' => $book,
            ],
            [
                'id' => 6,
                'name' => 'Cotte de mailles',
                'description' => '',
                'protection' => 3,
                'price' => 0,
                'availability' => 'CO',
                'book' => $book,
            ],
            [
                'id' => 7,
                'name' => 'Cuirasse en roseau',
                'description' => '',
                'protection' => 2,
                'price' => 10,
                'availability' => 'RA',
                'book' => $book,
            ],
            [
                'id' => 8,
                'name' => 'Cuirasse continentale',
                'description' => ' en lamelles, cuir et métal',
                'protection' => 3,
                'price' => 100,
                'availability' => 'RA',
                'book' => $book,
            ],
            [
                'id' => 9,
                'name' => 'Armure de plaques',
                'description' => '',
                'protection' => 4,
                'price' => 300,
                'availability' => 'EX',
                'book' => $book,
            ],
            [
                'id' => 10,
                'name' => 'Cagoule de cuir',
                'description' => ' (Chaque armure est fournie avec le casque. Ne pas le porter suscite un malus de 1 au score de Protection, minimum 1)',
                'protection' => 0,
                'price' => 4,
                'availability' => 'FR',
                'book' => $book,
            ],
            [
                'id' => 11,
                'name' => 'Cagoule de mailles',
                'description' => ' (Chaque armure est fournie avec le casque. Ne pas le porter suscite un malus de 1 au score de Protection, minimum 1)',
                'protection' => 0,
                'price' => 8,
                'availability' => 'CO',
                'book' => $book,
            ],
            [
                'id' => 12,
                'name' => 'Casque Osag',
                'description' => ' (Chaque armure est fournie avec le casque. Ne pas le porter suscite un malus de 1 au score de Protection, minimum 1)',
                'protection' => 0,
                'price' => 10,
                'availability' => 'FR',
                'book' => $book,
            ],
            [
                'id' => 13,
                'name' => 'Casque ouvert',
                'description' => ' (Chaque armure est fournie avec le casque. Ne pas le porter suscite un malus de 1 au score de Protection, minimum 1)',
                'protection' => 0,
                'price' => 20,
                'availability' => 'CO',
                'book' => $book,
            ],
            [
                'id' => 14,
                'name' => 'Heaume',
                'description' => ' (Chaque armure est fournie avec le casque. Ne pas le porter suscite un malus de 1 au score de Protection, minimum 1)',
                'protection' => 0,
                'price' => 40,
                'availability' => 'RA',
                'book' => $book,
            ],
            [
                'id' => 15,
                'name' => 'Pavois du temple',
                'description' => 'gravé du symbole de l\'Unique',
                'protection' => 1,
                'price' => 20,
                'availability' => 'CO',
                'book' => $book,
            ],
        ];
    }

    public function getDependencies(): array
    {
        return [
            BooksFixtures::class,
        ];
    }

    protected function getReferencePrefix(): ?string
    {
        return 'corahnrin-armors-';
    }
}
