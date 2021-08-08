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

use CorahnRin\Repository\GeoEnvironmentsRepository;
use Symfony\Component\HttpFoundation\Response;

class Step04Geo extends AbstractStepAction
{
    private $geoEnvironmentsRepository;

    public function __construct(GeoEnvironmentsRepository $geoEnvironmentsRepository)
    {
        $this->geoEnvironmentsRepository = $geoEnvironmentsRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $tmp = $this->geoEnvironmentsRepository->findAll();
        $geoEnvironments = [];
        foreach ($tmp as $item) {
            $geoEnvironments[$item->getId()] = $item;
        }

        if ($this->request->isMethod('POST')) {
            $geoEnvironmentId = (int) $this->request->request->get('gen-div-choice');
            if (isset($geoEnvironments[$geoEnvironmentId])) {
                $this->updateCharacterStep($geoEnvironmentId);

                return $this->nextStep();
            }
            $this->flashMessage('Veuillez indiquer un lieu de vie géographique correct.');
        }

        return $this->renderCurrentStep([
            'geoEnvironments' => $geoEnvironments,
            'geoEnvironment_value' => $this->getCharacterProperty(),
        ], 'corahn_rin/Steps/04_geo.html.twig');
    }
}
