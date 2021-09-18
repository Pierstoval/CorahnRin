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

use CorahnRin\Data\Ways;
use CorahnRin\Document\PersonalityTrait;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Persistence\ManagerRegistry;

class TraitsRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonalityTrait::class);
    }

    /**
     * @return PersonalityTrait[][]
     */
    public function findAllDifferenciated()
    {
        $list = $this->findBy([], ['name' => 'asc']);
        $qualities = $flaws = [];
        foreach ($list as $element) {
            $id = $element->getId();
            if ($element instanceof PersonalityTrait) {
                if (!$element->isQuality()) {
                    $flaws[$id] = $element;
                } else {
                    $qualities[$id] = $element;
                }
            }
        }

        return [
            'qualities' => $qualities,
            'flaws' => $flaws,
        ];
    }

    /**
     * @param PersonalityTrait[] $traits
     *
     * @return array
     */
    public function sortQualitiesFlaws($traits)
    {
        $list = [
            'qualities' => [],
            'flaws' => [],
        ];

        foreach ($traits as $trait) {
            if ($trait->isQuality()) {
                $list['qualities'][$trait->getId()] = $trait;
            } else {
                $list['flaws'][$trait->getId()] = $trait;
            }
        }

        return $list;
    }

    /**
     * Récupère les données à partir des voies.
     * ATTENTION :
     * Le tableau $ways DOIT être structuré de cette façon :
     * (key) wayid => (value) way score.
     *
     * @param string[] $ways
     *
     * @throws \Exception
     *
     * @return array
     */
    public function findAllDependingOnWays(array $ways)
    {
        $qb = $this->_em
            ->createQueryBuilder()
            ->select('trait')
            ->from($this->_entityName, 'trait')
        ;

        $searchableWays = [
            Ways::COMBATIVENESS,
            Ways::CREATIVITY,
            Ways::REASON,
            Ways::CONVICTION,
        ];

        foreach ($ways as $id => $value) {
            Ways::validateWay((string) $id);
            if (\in_array($id, $searchableWays, true)) {
                $placeholder = \str_replace('ways.', '', $id);
                $qb->orWhere('trait.way = :way'.$placeholder.' AND trait.major = :way'.$placeholder.'major')
                    ->setParameter(':way'.$placeholder, $id)
                    ->setParameter(':way'.$placeholder.'major', $value >= 4)
                ;
            }
        }

        $qb->orderBy('trait.name', 'asc');

        $list = $qb->getQuery()->getResult();

        return $this->sortQualitiesFlaws($list);
    }
}
