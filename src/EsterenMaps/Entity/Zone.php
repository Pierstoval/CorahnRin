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

use EsterenMaps\Id\ZoneId;

final class Zone
{
    private int $id;

    private string $name;

    private ?string $description;

    private array $coordinates;

    private ZoneType $zoneType;

    public function getId(): ZoneId
    {
        return ZoneId::fromInt($this->id);
    }
}
