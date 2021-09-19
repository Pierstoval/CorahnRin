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

use CorahnRin\Document\Weapon;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

final class WeaponsRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Weapon::class);
    }

    /**
     * @return Weapon[]
     */
    public function findAllSortedByName()
    {
        return $this->_em->createQueryBuilder()
            ->select('weapon')
            ->from($this->_entityName, 'weapon', 'weapon.id')
            ->orderBy('weapon.name', 'asc')
            ->getQuery()->getResult()
        ;
    }
}
