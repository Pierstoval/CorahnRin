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

use CorahnRin\Document\Character;
use CorahnRin\Document\Game;
use CorahnRin\Document\GameInvitation;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Persistence\ManagerRegistry;
use User\Document\User;

class GameInvitationRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameInvitation::class);
    }

    /**
     * @return GameInvitation[]
     */
    public function findForGame(Game $game): array
    {
        return $this->createQueryBuilder('invitation')
            ->where('invitation.game = :game')
            ->leftJoin('invitation.character', 'character')
            ->addSelect('character')
            ->setParameter('game', $game)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findForAccept(string $token): ?GameInvitation
    {
        return $this->createQueryBuilder('invitation')
            ->where('invitation.token = :token')
            ->leftJoin('invitation.character', 'character')
            ->addSelect('character')
            ->leftJoin('invitation.game', 'game')
            ->addSelect('game')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return GameInvitation[]
     */
    public function findForCharacter(Character $character): array
    {
        return $this->createQueryBuilder('invitation')
            ->where('invitation.character = :character')
            ->leftJoin('invitation.character', 'character')
            ->addSelect('character')
            ->setParameter('character', $character)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return GameInvitation[]
     */
    public function findForPlayer(User $user): array
    {
        return $this->createQueryBuilder('invitation')
            ->leftJoin('invitation.character', 'character')
            ->where('character.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Character[] $characters
     *
     * @return GameInvitation[]
     */
    public function getForCharactersAndGame(iterable $characters, Game $game): array
    {
        return $this->createQueryBuilder('invitation')
            ->where('invitation.character IN (:ids)')
            ->andWhere('invitation.game = :game')
            ->setParameter('ids', $characters)
            ->setParameter('game', $game)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findToResendEmail(string $token): ?GameInvitation
    {
        return $this->_em->createQuery(
            <<<DQL
                SELECT invitation
                FROM {$this->_entityName} invitation
                WHERE invitation.token = :token
            DQL
        )
            ->setParameter('token', $token)
            ->getOneOrNullResult()
        ;
    }
}
