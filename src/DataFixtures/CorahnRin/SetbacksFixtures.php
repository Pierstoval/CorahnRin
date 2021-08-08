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

use CorahnRin\Entity\CharacterProperties\Bonuses;
use CorahnRin\Entity\Setback;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class SetbacksFixtures extends ArrayFixture implements ORMFixtureInterface, DependentFixtureInterface
{
    public const ID_UNLUCKY = 1;
    public const ID_AFTEREFFECT = 2;
    public const ID_ADVERSARY = 3;
    public const ID_RUMOR = 4;
    public const ID_TRAGIC_LOVE = 5;
    public const ID_DISEASE = 6;
    public const ID_VIOLENCE = 7;
    public const ID_SOLITUDE = 8;
    public const ID_POOR = 9;
    public const ID_LUCKY = 10;

    public function getDependencies()
    {
        return [
            BooksFixtures::class,
        ];
    }

    protected function getEntityClass(): string
    {
        return Setback::class;
    }

    protected function getReferencePrefix(): ?string
    {
        return 'corahnrin-setbacks-';
    }

    protected function getObjects(): array
    {
        $book = $this->getReference('corahnrin-book-2');

        return [
            [
                'id' => 1,
                'name' => 'Poisse',
                'description' => 'Tirer une deuxième fois, ignorer les 1 supplémentaires',
                'malus' => '',
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => true,
            ],
            [
                'id' => 2,
                'name' => 'Séquelle',
                'description' => '-1 Vigueur, et une séquelle physique (cicatrice...)',
                'malus' => Bonuses::STAMINA,
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => false,
            ],
            [
                'id' => 3,
                'name' => 'Adversaire',
                'description' => 'Le personnage s\'est fait un ennemi (à la discrétion du MJ)',
                'malus' => '',
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => false,
            ],
            [
                'id' => 4,
                'name' => 'Rumeur',
                'description' => 'Une information, vraie ou non, circule à propos du personnage',
                'malus' => '',
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => false,
            ],
            [
                'id' => 5,
                'name' => 'Amour tragique',
                'description' => '+1 point de Trauma définitif, mauvais souvenir',
                'malus' => Bonuses::TRAUMA,
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => false,
            ],
            [
                'id' => 6,
                'name' => 'Maladie',
                'description' => '-1 Vigueur, mais a survécu à une maladie normalement mortelle',
                'malus' => Bonuses::STAMINA,
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => false,
            ],
            [
                'id' => 7,
                'name' => 'Violence',
                'description' => '+1 point de Trauma définitif, souvenir violent, gore, horrible...',
                'malus' => Bonuses::TRAUMA,
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => false,
            ],
            [
                'id' => 8,
                'name' => 'Solitude',
                'description' => 'Les proches, amis ou famille du personnage sont morts de façon douteuse',
                'malus' => '',
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => false,
            ],
            [
                'id' => self::ID_POOR,
                'name' => 'Pauvreté',
                'description' => 'Le personnage ne possède qu\'une mauvaise arme, ou outil, a des dettes d\'héritage, de vol... Il n\'a plus d\'argent, sa famille a été ruinée ou lui-même est ruiné d\'une façon ou d\'une autre, et aucun évènement ou avantage ne peut y remédier.',
                'malus' => Bonuses::MONEY_0,
                'book' => $book,
                'isLucky' => false,
                'isUnlucky' => false,
                'disabledAdvantages' => [
                    $this->getReference('corahnrin-advantage-4'),
                    $this->getReference('corahnrin-advantage-5'),
                    $this->getReference('corahnrin-advantage-6'),
                    $this->getReference('corahnrin-advantage-7'),
                    $this->getReference('corahnrin-advantage-8'),
                    $this->getReference('corahnrin-advantage-42'),
                ],
            ],
            [
                'id' => 10,
                'name' => 'Chance',
                'description' => 'Le personnage est passé à côté de la catastrophe !',
                'malus' => '',
                'book' => $book,
                'isLucky' => true,
                'isUnlucky' => false,
            ],
        ];
    }
}
