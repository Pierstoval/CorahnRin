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

use CorahnRin\Data\OghamType;
use CorahnRin\Document\Book;
use CorahnRin\Document\Ogham;
use Doctrine\Bundle\MongoDBBundle\Fixture\ODMFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class OghamFixtures extends ArrayFixture implements ODMFixtureInterface, DependentFixtureInterface
{
    public function getEntityClass(): string
    {
        return Ogham::class;
    }

    public function getObjects(): iterable
    {
        /** @var Book $book1Universe */
        $book1Universe = $this->getReference('corahnrin-book-2');

        return [
            [
                'name' => 'Apnée',
                'type' => OghamType::WATER,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Appel des animaux aquatiques',
                'type' => OghamType::WATER,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Appel des animaux aériens',
                'type' => OghamType::AIR,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Appel des animaux terrestres',
                'type' => OghamType::EARTH,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Arbre animé',
                'type' => OghamType::VEGETAL,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Baies curatives',
                'type' => OghamType::VEGETAL,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Blessure',
                'type' => OghamType::LIFE,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Bruissement',
                'type' => OghamType::AIR,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Brume',
                'type' => OghamType::AIR,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Calmer les animaux',
                'type' => OghamType::ANIMAL,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Calyre',
                'type' => OghamType::ANIMAL,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Camouflage végétal',
                'type' => OghamType::VEGETAL,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Chaleur',
                'type' => OghamType::FIRE,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Croissance végétale',
                'type' => OghamType::VEGETAL,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Cuirasse de pierre',
                'type' => OghamType::EARTH,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Eau calme',
                'type' => OghamType::WATER,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Eau pure',
                'type' => OghamType::WATER,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Flamme',
                'type' => OghamType::FIRE,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Flèche de pierre',
                'type' => OghamType::EARTH,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Foudre',
                'type' => OghamType::AIR,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Fusion avec la terre',
                'type' => OghamType::EARTH,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Glace',
                'type' => OghamType::WATER,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Humidité',
                'type' => OghamType::WATER,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Lame d\'air',
                'type' => OghamType::AIR,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Lumière',
                'type' => OghamType::AIR,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Manipulation terrestre',
                'type' => OghamType::EARTH,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Mousse cicatrisante',
                'type' => OghamType::LIFE,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Mur de Flammes',
                'type' => OghamType::FIRE,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Nature animée',
                'type' => OghamType::VEGETAL,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Ours',
                'type' => OghamType::ANIMAL,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Peau d\'écorce',
                'type' => OghamType::VEGETAL,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Pieux de glace',
                'type' => OghamType::WATER,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Poison',
                'type' => OghamType::VEGETAL,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Purification de la terre',
                'type' => OghamType::EARTH,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Pétrification',
                'type' => OghamType::EARTH,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Rafale',
                'type' => OghamType::AIR,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Remède',
                'type' => OghamType::LIFE,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Régénération de la terre',
                'type' => OghamType::EARTH,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Régénération',
                'type' => OghamType::LIFE,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Résistance au froid',
                'type' => OghamType::LIFE,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Sculpture de la terre',
                'type' => OghamType::EARTH,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Soin',
                'type' => OghamType::LIFE,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Source d\'eau',
                'type' => OghamType::WATER,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Séisme',
                'type' => OghamType::EARTH,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Vie',
                'type' => OghamType::LIFE,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Vision de l\'Aigle',
                'type' => OghamType::ANIMAL,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Éclats de givre',
                'type' => OghamType::WATER,
                'description' => '',
                'book' => $book1Universe,
            ],
            [
                'name' => 'Étincelle',
                'type' => OghamType::AIR,
                'description' => '',
                'book' => $book1Universe,
            ],
        ];
    }

    public function getDependencies()
    {
        return [
            BooksFixtures::class,
        ];
    }
}
