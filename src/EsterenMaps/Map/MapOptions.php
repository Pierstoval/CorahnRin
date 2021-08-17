<?php

declare(strict_types=1);

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\Map;

use EsterenMaps\Entity\Map;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class MapOptions
{
    public const TILE_SIZE = 168;
    public const MAP_API_DOMAIN = 'maps.esteren.org';
    public const ESTEREN_MAPS_STEP_3_ZONES = [
        25,
        56,
        57,
    ];

    public function __construct(
        private Packages $packages,
        private RequestStack $requestStack,
    ) {
    }

    public function getCorahnRinViewOptions(Map $map): string
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            throw new \RuntimeException('Cannot get map options without a Request object.');
        }

        $mapOptions = $this->getDefaultOptions($map, $request);

        $mapOptions['canAddNotes'] = false;
        $mapOptions['visitor'] = null;
        $mapOptions['showDirections'] = false;
        $mapOptions['showMarkers'] = false;
        $mapOptions['showRoutes'] = false;
        $mapOptions['zonesToDisplay'] = self::ESTEREN_MAPS_STEP_3_ZONES;

        return \json_encode($mapOptions, \JSON_THROW_ON_ERROR | \JSON_UNESCAPED_SLASHES);
    }

    private function getDefaultOptions(Map $map, Request $request): array
    {
        $scheme = $request->getScheme();
        $mapId = $map->getId();
        $host = $request->getHost();
        $port = $request->getPort();
        if (!\in_array($port, [80, 443], true)) {
            $host .= ':'.$port;
        }
        $locale = $request->getLocale();

        $tilesUrl = $scheme.'://'.self::MAP_API_DOMAIN.''.$this->packages->getUrl('maps_tiles/'.$mapId.'/{z}/{y}/{x}.jpg');
        $mapApiUrl = $scheme.'://'.$host.'/'.$locale.'/api/maps/'.$mapId;
        $endpoint = $scheme.'://'.$host.'/'.$locale.'/api';

        $mapOptions = [
            'id' => $mapId,
            'apiUrls' => [
                'map' => $mapApiUrl,
                'tiles' => $tilesUrl,
                'endpoint' => $endpoint,
            ],
            'LeafletMapBaseOptions' => [
                'zoom' => $map->getStartZoom(),
                'maxZoom' => $map->getMaxZoom(),
            ],
            'LeafletLayerBaseOptions' => [
                'maxZoom' => $map->getMaxZoom(),
                'maxNativeZoom' => $map->getMaxZoom(),
                'tileSize' => self::TILE_SIZE,
            ],
        ];

        if ($map->getBounds()) {
            $mapOptions['LeafletMapBaseOptions']['maxBounds'] = $map->getBounds()->toArray();
        }

        return $mapOptions;
    }
}
