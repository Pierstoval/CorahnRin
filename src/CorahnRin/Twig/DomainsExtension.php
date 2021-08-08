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

namespace CorahnRin\Twig;

use CorahnRin\Data\DomainsData;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DomainsExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('domain_as_object', [DomainsData::class, 'getAsObject']),
            new TwigFilter('short_domain_to_title', [DomainsData::class, 'shortNameToTitle']),
        ];
    }
}
