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

final class MapBounds
{
    private LatLng $southWest;

    private LatLng $northEast;

    private function __construct()
    {
    }

    public static function fromArray(array $bounds): self
    {
        $self = new self();

        $self->southWest = LatLng::create($bounds[0][0], $bounds[0][1]);
        $self->northEast = LatLng::create($bounds[1][0], $bounds[1][1]);

        return $self;
    }

    public function getSouthWest(): LatLng
    {
        return $this->southWest;
    }

    public function getNorthEast(): LatLng
    {
        return $this->northEast;
    }
}
