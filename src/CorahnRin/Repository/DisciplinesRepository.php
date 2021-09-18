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

use CorahnRin\Document\Discipline;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|Discipline find($id, $lockMode = null, $lockVersion = null)
 */
class DisciplinesRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Discipline::class);
    }

    /**
     * @return array<string, array<Discipline>>
     */
    public function findAllIndexedByDomains(): array
    {
        /** @var Discipline[] $disciplines */
        $disciplines = $this->createQueryBuilder('discipline')
            ->from($this->_entityName, 'disciplines')
            ->orderBy('disciplines.name', 'asc')
            ->getQuery()
            ->getResult()
        ;

        $final = [];

        foreach ($disciplines as $discipline) {
            foreach ($discipline->getDomains() as $domain) {
                if (!isset($final[$domain])) {
                    $final[$domain] = [];
                }

                $final[$domain][] = $discipline;
            }
        }

        return $final;
    }

    /**
     * @param int[] $domains
     *
     * @return Discipline[]
     */
    public function findAllByDomains(array $domains): array
    {
        return $this->createQueryBuilder('discipline', 'discipline.id')
            ->from($this->_entityName, 'disciplines', 'disciplines.id')
            ->where('discipline.domains in (:ids)')
            ->setParameter('ids', $domains)
            ->orderBy('disciplines.name', 'asc')
            ->getQuery()->getResult()
        ;
    }
}
