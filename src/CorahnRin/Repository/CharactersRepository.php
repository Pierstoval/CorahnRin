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
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use User\Document\User;

class CharactersRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Character::class);
    }

    public function findFetched(int $id): ?Character
    {
        return $this->_em
            ->createQueryBuilder()
            ->select('characters')
            ->from($this->_entityName, 'characters')
            ->leftJoin('characters.job', 'job')->addSelect('job')
            ->leftJoin('characters.ways', 'ways')->addSelect('ways')
            ->leftJoin('characters.people', 'people')->addSelect('people')
            ->leftJoin('characters.armors', 'armors')->addSelect('armors')
            ->leftJoin('characters.weapons', 'weapons')->addSelect('weapons')
            ->leftJoin('characters.artifacts', 'artifacts')->addSelect('artifacts')
            ->leftJoin('characters.flux', 'flux')->addSelect('flux')
            ->leftJoin('characters.miracles', 'miracles')->addSelect('miracles')
            ->leftJoin('characters.domains', 'domains')->addSelect('domains')
            ->leftJoin('characters.disciplines', 'disciplines')->addSelect('disciplines')
            ->leftJoin('characters.avantages', 'avantages')->addSelect('avantages')
            ->where('characters.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param string $searchField Le champ dans lequel exécuter la requête
     * @param string $order       L'ordre (asc ou desc)
     * @param int    $limit       Le nombre d'éléments à récupérer
     * @param int    $offset      L'offset de départ
     */
    public function searchQueryBuilder(string $searchField = 'id', string $order = null, int $limit = null, int $offset = null): QueryBuilder
    {
        $qb = $this->_em
            ->createQueryBuilder()
            ->select('characters')
            ->from($this->_entityName, 'characters')
            ->leftJoin('characters.job', 'job')->addSelect('job')
            ->leftJoin('characters.people', 'people')->addSelect('people')
        ;

        if ($searchField && null !== $order) {
            if ('job' === $searchField) {
                $qb->addOrderBy('job.name', $order);
            } elseif ('people' === $searchField) {
                $qb->orderBy('people.name');
            } elseif ('birthplace' === $searchField) {
                $qb->orderBy('birthplace');
            } else {
                $qb->orderBy('characters.'.$searchField, $order);
            }
        }

        if (null !== $offset) {
            $qb->setFirstResult($offset);
        }

        if (null !== $limit) {
            $qb->setMaxResults($limit);
        }

        return $qb;
    }

    /**
     * @param string $searchField
     * @param string $order
     * @param int    $limit
     * @param int    $offset
     *
     * @return Character[]
     */
    public function findSearch($searchField = 'id', $order = 'asc', $limit = 20, $offset = 0): array
    {
        return $this
            ->searchQueryBuilder($searchField, $order, $limit, $offset)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param string $searchField
     * @param string $order
     */
    public function countSearch($searchField = 'id', $order = 'asc'): int
    {
        return (int) $this
            ->searchQueryBuilder($searchField, $order)
            ->select('count (characters.id) as number')
            ->getQuery()
            ->getScalarResult()[0]['number']
        ;
    }

    public function findForView(string $id, string $nameSlug): ?Character
    {
        $character = $this->_em
            ->createQuery(
                <<<DQL
                SELECT character, job, people
                FROM {$this->_entityName} character
                LEFT JOIN character.job job
                LEFT JOIN character.people people
                WHERE character.id = :id
                AND character.nameSlug = :nameSlug
            DQL
            )
            ->setParameter('id', $id)
            ->setParameter('nameSlug', $nameSlug)
            ->getOneOrNullResult()
        ;

        if (!$character) {
            return null;
        }

        // TODO: Optimize query to avoid lazy queries that break performances.

        return $character;
    }

    /**
     * Retrieve characters that can be invited to a new virtual campaign.
     */
    public function getInvitableCharactersBuilder(): QueryBuilder
    {
        return $this
            ->createQueryBuilder('characters')
            ->where('characters.game is null')
            ->orderBy('characters.name', 'ASC')
        ;
    }

    public function findByIdAndSlug(int $id, string $nameSlug): ?Character
    {
        $character = $this->_em
            ->createQuery(
                <<<DQL
                SELECT character
                FROM {$this->_entityName} character
                WHERE character.id = :id
                AND character.nameSlug = :nameSlug
            DQL
            )
            ->setParameter('id', $id)
            ->setParameter('nameSlug', $nameSlug)
            ->getOneOrNullResult()
        ;

        if (!$character) {
            return null;
        }

        return $character;
    }

    /**
     * @return array<Character>
     */
    public function findForUser(User $currentUser): array
    {
        return $this->searchQueryBuilder('name')
            ->where('characters.user = :user')
            ->setParameter('user', $currentUser)
            ->getQuery()
            ->getResult()
        ;
    }
}
