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
        $tmp = $this->zonesRepository->findAll();
        $regions = [];
        foreach ($tmp as $item) {
            $id = $item->getId()->getValue();
            $regions[$id] = $id;
        }

        $map = $this->mapsRepository->getMap();

        if ($this->request->isMethod('POST')) {
            $regionValue = (int) $this->request->request->get('region_value');
            if (isset($regions[$regionValue])) {
                $this->updateCharacterStep($regionValue);

                return $this->nextStep();
            }
            $this->flashMessage('Veuillez choisir une région de naissance correcte.');
        }

        return $this->renderCurrentStep([
            'map' => $map->getId(),
            'tile_size' => self::TILE_SIZE,
            'regions_list' => $regions,
            'region_value' => $this->getCharacterProperty(),
        ], 'corahn_rin/Steps/03_birthplace.html.twig');
    }
}
