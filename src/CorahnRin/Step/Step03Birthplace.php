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

namespace CorahnRin\Step;

use EsterenMaps\Entity\Zone;
use EsterenMaps\Map\MapOptions;
use EsterenMaps\Repository\MapsRepository;
use EsterenMaps\Repository\ZonesRepository;
use Symfony\Component\HttpFoundation\Response;

class Step03Birthplace extends AbstractStepAction
{
    public const TILE_SIZE = 128;

    public function __construct(
        private MapsRepository $mapsRepository,
        private ZonesRepository $zonesRepository,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $validRegions = MapOptions::ESTEREN_MAPS_STEP_3_ZONES;

        $allZonesIds = \array_map(static fn (Zone $zone) => $zone->getId()->getValue(), $this->zonesRepository->findAll());

        foreach ($validRegions as $regionId) {
            if (!\in_array($regionId, $allZonesIds, true)) {
                throw new \RuntimeException(\sprintf('Could not find region ID "%d". Are your maps data corrupted?', $regionId));
            }
        }

        $map = $this->mapsRepository->getMap();

        if ($this->request->isMethod('POST')) {
            $regionValue = (int) $this->request->request->get('region_value');

            if (\in_array($regionValue, $validRegions, true)) {
                $this->updateCharacterStep($regionValue);

                return $this->nextStep();
            }

            $this->flashMessage('Veuillez choisir une région de naissance correcte.');
        }

        return $this->renderCurrentStep([
            'map' => $map,
            'tile_size' => self::TILE_SIZE,
            'regions_list' => $validRegions,
            'region_value' => $this->getCharacterProperty(),
        ], 'corahn_rin/Steps/03_birthplace.html.twig');
    }
}
