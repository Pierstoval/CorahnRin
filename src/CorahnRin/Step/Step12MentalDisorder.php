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

use CorahnRin\Repository\MentalDisorderWayRepository;
use Symfony\Component\HttpFoundation\Response;

class Step12MentalDisorder extends AbstractStepAction
{
    private $disorderWayRepository;

    public function __construct(
        MentalDisorderWayRepository $disorderWayRepository
    ) {
        $this->disorderWayRepository = $disorderWayRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $disorderValue = $this->getCharacterProperty();

        /** @var int[] $ways */
        $ways = $this->getCharacterProperty('08_ways');

        // They MUST be indexed by ids by the repository.
        $disordersWays = $this->disorderWayRepository->findAll();

        $definedDisorders = [];

        // Fetch "minor" and "major" ways to check for compatible disorders.
        $majorWays = $minorWays = [];
        foreach ($ways as $id => $value) {
            if ($value < 3) {
                $minorWays[$id] = 1;
            } elseif ($value > 3) {
                $majorWays[$id] = 1;
            }
        }

        // Test all disorders with current ways major and minor values.
        foreach ($disordersWays as $disorderWay) {
            if (
                ($disorderWay->isMajor() && \array_key_exists($disorderWay->getWay(), $majorWays))
                || (!$disorderWay->isMajor() && \array_key_exists($disorderWay->getWay(), $minorWays))
            ) {
                $definedDisorders[$disorderWay->getDisorder()->getId()] = $disorderWay->getDisorder();
            }
        }

        // Validate form.
        if ($this->request->isMethod('POST')) {
            $disorderValue = $this->request->request->get('gen-div-choice');

            // Success!
            if (\array_key_exists($disorderValue, $definedDisorders)) {
                $this->updateCharacterStep($disorderValue);

                return $this->nextStep();
            }

            if (!$disorderValue) {
                $this->flashMessage('Veuillez choisir un désordre mental.');
            } else {
                $this->flashMessage('Le désordre mental choisi n\'existe pas.');
            }
        }

        return $this->renderCurrentStep([
            'disorder_value' => $disorderValue,
            'disorders' => $definedDisorders,
        ], 'corahn_rin/Steps/12_mental_disorder.html.twig');
    }
}
