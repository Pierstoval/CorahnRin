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

namespace EsterenMaps\Id;

use Main\Doctrine\AbstractIdType;

class ZoneIdType extends AbstractIdType
{
    public function getTypeClassName(): string
    {
        return ZoneId::class;
    }

    public function getName(): string
    {
        return 'zone_id';
    }
}
