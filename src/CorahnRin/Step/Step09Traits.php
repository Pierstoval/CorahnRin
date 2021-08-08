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

use CorahnRin\Exception\InvalidWay;
use CorahnRin\Repository\TraitsRepository;
use Symfony\Component\HttpFoundation\Response;

class Step09Traits extends AbstractStepAction
{
    private $traitsRepository;

    public function __construct(TraitsRepository $traitsRepository)
    {
        $this->traitsRepository = $traitsRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $ways = $this->getCharacterProperty('08_ways');

        try {
            $traitsList = $this->traitsRepository->findAllDependingOnWays($ways);
        } catch (InvalidWay $exception) {
            $this->flashMessage('Traits coming from Step 08 Ways are not correct, please check them back.');

            return $this->goToStep(8);
        }

        $traits = $this->getCharacterProperty();

        $quality = $traits['quality'] ?? null;
        $flaw = $traits['flaw'] ?? null;

        if ($this->request->isMethod('POST')) {
            $quality = (int) $this->request->request->get('quality');
            $flaw = (int) $this->request->request->get('flaw');

            $quality_exists = \array_key_exists($quality, $traitsList['qualities']);
            $flaw_exists = \array_key_exists($flaw, $traitsList['flaws']);

            if ($quality_exists && $flaw_exists) {
                $this->updateCharacterStep([
                    'quality' => $quality,
                    'flaw' => $flaw,
                ]);

                return $this->nextStep();
            }
            $this->flashMessage('Les traits de caractère choisis sont incorrects.');
        }

        return $this->renderCurrentStep([
            'quality' => $quality,
            'flaw' => $flaw,
            'traits_list' => $traitsList,
        ], 'corahn_rin/Steps/09_traits.html.twig');
    }
}
