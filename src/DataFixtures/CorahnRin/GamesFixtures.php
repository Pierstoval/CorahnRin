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

use CorahnRin\Entity\Game;
use DataFixtures\UsersFixtures;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class GamesFixtures extends ArrayFixture implements ORMFixtureInterface, DependentFixtureInterface
{
    public const ID_EMPTY = 1;
    public const ID_WITH_CHARACTER = 2;
    public const ID_WITH_INVITATIONS = 3;

    public function getEntityClass(): string
    {
        return Game::class;
    }

    public function getReferencePrefix(): ?string
    {
        return 'corahnrin-game-';
    }

    public function getObjects(): iterable
    {
        return [
            [
                'id' => self::ID_EMPTY,
                'name' => 'Empty campaign',
                'summary' => '',
                'gmNotes' => '',
                'gameMaster' => $this->getReference('user-game-master'),
                'characters' => [],
            ],
            [
                'id' => self::ID_WITH_CHARACTER,
                'name' => 'Campaign with a character',
                'summary' => '',
                'gmNotes' => '',
                'gameMaster' => $this->getReference('user-game-master'),
                'characters' => [], // Is added through the CharactersFixtures
            ],
            [
                'id' => self::ID_WITH_INVITATIONS,
                'name' => 'Campaign with invitations',
                'summary' => '',
                'gmNotes' => '',
                'gameMaster' => $this->getReference('user-game-master'),
                'characters' => [],
            ],
        ];
    }

    public function getDependencies(): array
    {
        return [
            UsersFixtures::class,
        ];
    }
}
