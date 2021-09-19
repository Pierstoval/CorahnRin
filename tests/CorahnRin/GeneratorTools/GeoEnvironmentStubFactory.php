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

namespace Tests\CorahnRin\GeneratorTools;

use CorahnRin\Data\DomainsData;
use CorahnRin\Document\GeoEnvironment;

class GeoEnvironmentStubFactory
{
    public static function rural(): GeoEnvironment
    {
        return new GeoEnvironment(0, '', '', DomainsData::NATURAL_ENVIRONMENT['title']);
    }

    public static function urban(): GeoEnvironment
    {
        return new GeoEnvironment(0, '', '', DomainsData::RELATION['title']);
    }
}
