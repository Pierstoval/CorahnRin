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

use CorahnRin\Document\Setback;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|Setback findOneBy(array $criteria, array $orderBy = null)
 * @method Setback[]    findBy(array $criteria, array $orderBy = null)
 * @method null|Setback find($id, $lockMode = null, $lockVersion = null)
 */
class SetbacksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Setback::class);
    }

    /**
     * @param int[]|Setback[] $setbacks
     *
     * @return Setback[]
     */
    public function findWithDisabledAdvantages(array $setbacks): array
    {
        return $this->createQueryBuilder('setback')
            ->leftJoin('setback.disabledAdvantages', 'disabledAdvantages')
            ->addSelect('disabledAdvantages')
            ->where('setback.id IN (:setbacks)')
            ->setParameter('setbacks', $setbacks)
            ->getQuery()
            ->getResult()
        ;
    }
}
