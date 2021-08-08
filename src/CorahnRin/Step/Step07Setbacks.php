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

use CorahnRin\GeneratorTools\RandomSetbacksProvider;
use CorahnRin\Repository\SetbacksRepository;
use Symfony\Component\HttpFoundation\Response;

class Step07Setbacks extends AbstractStepAction
{
    private SetbacksRepository $setbacksRepository;
    private RandomSetbacksProvider $randomSetbackProvider;

    public function __construct(SetbacksRepository $setbacksRepository, RandomSetbacksProvider $randomSetbackProvider)
    {
        $this->randomSetbackProvider = $randomSetbackProvider;
        $this->setbacksRepository = $setbacksRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $tmp = $this->setbacksRepository->findAll();
        $setbacks = [];
        foreach ($tmp as $item) {
            $setbacks[$item->getId()] = $item;
        }

        $setbacksValue = $this->getCharacterProperty() ?: [];

        $age = $this->getCharacterProperty('06_age');

        // The user should be able to determine setbacks automatically OR manually.
        $chooseStepsManually = $this->request->query->has('manual') ?: $this->request->request->has('manual');

        // Setbacks number depends on the age, according to the books.
        $numberOfSetbacks = 0;
        if ($age >= 21) {
            ++$numberOfSetbacks;
        }
        if ($age >= 26) {
            ++$numberOfSetbacks;
        }
        if ($age >= 31) {
            ++$numberOfSetbacks;
        }

        // Determine setbacks' specific calculation.
        if (!$numberOfSetbacks) {
            // No setback if the character is less than 21 years old.
            $setbacksValue = [];
            $this->updateCharacterStep([]);
        } elseif (!$chooseStepsManually && !\count($setbacksValue)) {
            // Automatic calculation (roll dices, etc.)
            $setbacksValue = $this->randomSetbackProvider->getRandomSetbacks($setbacks, $numberOfSetbacks);
            $this->updateCharacterStep($setbacksValue);
        } elseif ($chooseStepsManually && !$this->request->isMethod('POST')) {
            // Reset setbacks only for the view when user clicked "Choose setbacks manually".
            $setbacksValue = [];
        }

        if ($this->request->isMethod('POST')) {
            if ($chooseStepsManually) {
                $setbacksValue = $this->request->request->all('setbacks_value');

                // Make sure every setback sent in POST are valid
                $anyWrongSetbackId = false;

                foreach ($setbacksValue as $id) {
                    if (
                        !\array_key_exists((int) $id, $setbacks)    // Setback has to exist
                        || \in_array((int) $id, [1, 10], true) // If manually set, setback cannot be good/bad luck
                    ) {
                        $anyWrongSetbackId = true;

                        break;
                    }
                }

                if (!$anyWrongSetbackId) {
                    $finalSetbacks = [];
                    foreach ($setbacksValue as $id) {
                        $finalSetbacks[$id] = ['id' => (int) $id, 'avoided' => false];
                    }
                    $this->updateCharacterStep($finalSetbacks);

                    return $this->nextStep();
                }

                $this->flashMessage('Veuillez entrer des revers correct(s).');
            } else {
                return $this->nextStep();
            }
        }

        return $this->renderCurrentStep([
            'age' => $age,
            'setbacks_number' => $numberOfSetbacks,
            'setbacks_value' => $setbacksValue,
            'setbacks_list' => $setbacks,
            'choice_available' => $chooseStepsManually,
        ], 'corahn_rin/Steps/07_setbacks.html.twig');
    }
}
