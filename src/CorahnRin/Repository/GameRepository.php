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

namespace CorahnRin\Repository;

use CorahnRin\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use User\Entity\User;

class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @return Game[]
     */
    public function findAsGameMaster(User $gameMaster): array
    {
        return $this->createQueryBuilder('game')
            ->where('game.gameMaster = :gameMaster')
            ->setParameter('gameMaster', $gameMaster)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Game[]
     */
    public function findAsPlayer(User $player): array
    {
        return $this->_em->createQuery(<<<DQL
                SELECT game, characters
                FROM {$this->_entityName} game
                INNER JOIN game.characters characters
                WHERE characters.user = :player
            DQL)
            ->setParameter('player', $player)
            ->getResult()
        ;
    }

    public function findForView(int $id): ?Game
    {
        return $this->createQueryBuilder('game')
            ->where('game.id = :id')
            ->leftJoin('game.characters', 'characters')
            ->addSelect('characters')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findToAddInvitations(int $id): ?Game
    {
        return $this->createQueryBuilder('game')
            ->where('game.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
