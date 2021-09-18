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

use CorahnRin\Document\CombatArt;
use Doctrine\Bundle\MongoDBBundle\Fixture\ODMFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class CombatArtsFixtures extends ArrayFixture implements ODMFixtureInterface, DependentFixtureInterface
{
    public function getEntityClass(): string
    {
        return CombatArt::class;
    }

    public function getObjects(): iterable
    {
        $book = $this->getReference('corahnrin-book-2');

        return [
            [
                'id' => 1,
                'melee' => 1,
                'ranged' => 0,
                'name' => 'Attaque sournoise',
                'description' => 'Si le personnage tient un ennemi en embuscade et qu\'il touche sa cible, il inflige +5 dégâts. Il ne doit utiliser qu\'une arme courte (dague, épée courte, couteau...)',
                'book' => $book,
            ],
            [
                'id' => 2,
                'melee' => 1,
                'ranged' => 1,
                'name' => 'Combat à 2 armes',
                'description' => 'La deuxième arme doit être courte ou de petite taille. Il ajoute +2 au bonus de son attitude de combat. Le bonus est au choix s\'il est en atittude standard. Sinon, le bonus s\'ajoute à l\'attaque en posture offensive, et en défense en posture défensive.',
                'book' => $book,
            ],
            [
                'id' => 3,
                'melee' => 1,
                'ranged' => 0,
                'name' => 'Parade',
                'description' => 'En attitude Standard, Défensive ou Rapide, et en bénéficiant de l\'initiative, le personnage peut effectuer un jet d\'attaque contre une cible. Si le résultat est supérieur au jet d\'attaque de la cible, le personnage pare l\'attaque. Sinon, l\'attaque est résolue normalement',
                'book' => $book,
            ],
            [
                'id' => 4,
                'melee' => 0,
                'ranged' => 1,
                'name' => 'Archerie',
                'description' => 'Niveau 5 requis en Tir et Lancer. Le personnage agit en dernier pendant le tour où il utilise cet art, mais il dispose d\'un bonus de 2 en Tir & Lancer pour son attaque, et annule tout malus sur une cible en mouvement.',
                'book' => $book,
            ],
            [
                'id' => 5,
                'melee' => 1,
                'ranged' => 0,
                'name' => 'Cavalerie',
                'description' => 'Discipline Equitation nécessaire. Si le personnage sur une monture subit moins de 5 dégâts, il n\'est pas désarçonné. Le personnage peut également charger en premier round d\'un combat, et bénéficier d\'un bonus de +3 à son jet (+4 avec une lance)',
                'book' => $book,
            ],
        ];
    }

    public function getDependencies()
    {
        return [
            BooksFixtures::class,
        ];
    }

    protected function getReferencePrefix(): ?string
    {
        return 'corahnrin-combat-arts-';
    }
}
