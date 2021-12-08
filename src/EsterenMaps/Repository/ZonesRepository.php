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

namespace EsterenMaps\Repository;

use EsterenMaps\Entity\Zone;
use EsterenMaps\Id\ZoneId;

class ZonesRepository
{
    public function __construct(private MapsRepository $mapsRepository)
    {
    }

    public function findIdByValue(int|string $id): ?ZoneId
    {
        $zoneId = ZoneId::from($id);

        foreach ($this->mapsRepository->getMap()->getZones() as $zone) {
            if ($zone->getId()->equals($zoneId)) {
                return $zone->getId();
            }
        }

        return null;
    }

    /**
     * @return Zone[]
     */
    public function findAll(): array
    {
        return $this->mapsRepository->getMap()->getZones();
    }
}
