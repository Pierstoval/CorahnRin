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

use CorahnRin\Document\Job;
use CorahnRin\Document\Miracle;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class MiraclesRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Miracle::class);
    }

    public function getQueryBuilderSortedByName(): QueryBuilder
    {
        return $this->_em->createQueryBuilder()
            ->select('miracle')
            ->from($this->_entityName, 'miracle')
            ->orderBy('miracle.name', 'asc')
        ;
    }

    public function getMajorForJobQueryBuilder(Job $job): QueryBuilder
    {
        return $this
            ->getQueryBuilderSortedByName()
            ->having(':job MEMBER OF miracle.majorForJobs')
            ->setParameter('job', $job)
        ;
    }

    public function getMinorForJobQueryBuilder(Job $job): QueryBuilder
    {
        return $this
            ->getQueryBuilderSortedByName()
            ->having(':job MEMBER OF miracle.minorForJobs')
            ->setParameter('job', $job)
        ;
    }
}
