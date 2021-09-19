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

use CorahnRin\Data\DomainsData;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class DomainsRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DomainsData::class);
    }

    /**
     * @return DomainsData[]
     */
    public function findAllSortedByName()
    {
        return $this->createQueryBuilder('domain', 'domain.id')
            ->from($this->_entityName, 'domains', 'domains.id')
            ->orderBy('domains.name', 'asc')
            ->getQuery()->getResult()
        ;
    }
}
