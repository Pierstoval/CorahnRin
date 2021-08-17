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

namespace EsterenMaps\Entity;

use EsterenMaps\Model\MapBounds;

final class Map
{
    private int $id;

    private string $name;

    private string $nameSlug;

    private string $description;

    private string $image;

    private int $maxZoom;

    private int $startZoom;

    private int $startX;

    private int $startY;

    private MapBounds $bounds;

    private int $coordinatesRatio;

    /** @var Zone[] */
    private array $zones;

    private function __construct()
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Zone[]
     */
    public function getZones(): array
    {
        return $this->zones;
    }

    public function getMaxZoom(): int
    {
        return $this->maxZoom;
    }

    public function getStartZoom(): int
    {
        return $this->startZoom;
    }

    /**
     * @return MapBounds
     */
    public function getBounds(): MapBounds
    {
        return $this->bounds;
    }
}
