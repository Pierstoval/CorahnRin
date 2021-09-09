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

use CorahnRin\Document\Advantage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CharacterAdvantageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advantage::class);
    }

    /**
     * @return Advantage[][]
     */
    public function findAllDifferenciated()
    {
        /** @var Advantage[] $list */
        $list = $this->findAll();
        $advantages = $disadvantages = [];

        foreach ($list as $element) {
            $id = $element->getId();
            if ($element->isDisadvantage()) {
                $disadvantages[$id] = $element;
            } else {
                $advantages[$id] = $element;
            }
        }

        return [
            'advantages' => $advantages,
            'disadvantages' => $disadvantages,
        ];
    }
}
