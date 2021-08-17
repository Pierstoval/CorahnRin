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

namespace EsterenMaps\Twig;

use EsterenMaps\Map\MapOptions;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MapsExtension extends AbstractExtension
{
    private MapOptions $mapOptions;

    public function __construct(MapOptions $mapOptions)
    {
        $this->mapOptions = $mapOptions;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('map_corahn_rin_options', [$this->mapOptions, 'getCorahnRinViewOptions'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('urldecode', 'urldecode'),
        ];
    }
}
