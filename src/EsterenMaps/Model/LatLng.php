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

namespace EsterenMaps\Model;

final class LatLng
{
    private function __construct(
        private float $lat,
        private float $lng,
    ) {
    }

    public static function create(float $lat, float $lng): self
    {
        return new self($lat, $lng);
    }

    public function toArray(): array
    {
        return [$this->lat, $this->lng];
    }
}
