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

use CorahnRin\Document\CombatArt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class CombatArtsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CombatArt::class);
    }

    /**
     * @return CombatArt[]
     */
    public function findAllSortedByName()
    {
        return $this->createQueryBuilder('combat_art', 'combat_art.id')
            ->from($this->_entityName, 'combat_arts', 'combat_arts.id')
            ->orderBy('combat_arts.name', 'asc')
            ->getQuery()->getResult()
        ;
    }
}
