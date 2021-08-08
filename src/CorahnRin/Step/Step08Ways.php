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

use CorahnRin\Data\Ways;
use Symfony\Component\HttpFoundation\Response;

class Step08Ways extends AbstractStepAction
{
    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $ways = Ways::ALL;

        $waysValues = $this->getCharacterProperty();

        if (!$waysValues) {
            $waysValues = $this->resetWays($ways);
        }

        if ($this->request->isMethod('POST')) {
            $waysValues = (array) $this->request->request->all('ways');

            $error = false;
            $errorWayNotExists = false;
            $errorValueNotInRange = false;
            $sum = 0;
            $has1or5 = false;

            foreach ($waysValues as $id => $value) {
                $value = (int) $value;

                // Make sure every way clearly exists.
                if (false === $errorWayNotExists && !isset($ways[$id])) {
                    $error = true;
                    $errorWayNotExists = true;
                    $this->flashMessage('Erreur dans le formulaire. Merci de vérifier les valeurs soumises.');
                }

                // Make sure values are in proper ranges.
                if (false === $errorValueNotInRange && ($value <= 0 || $value > 5)) {
                    $error = true;
                    $errorValueNotInRange = true;
                    $this->flashMessage('Les voies doivent être comprises entre 1 et 5.');
                }

                // To be correct, we need the character to have at least 1 or 5 to at least one Way.
                if (1 === $value || 5 === $value) {
                    $has1or5 = true;
                }

                // Force integer value
                $waysValues[$id] = (int) $value;

                $sum += $value;
            }

            if (15 !== $sum) {
                $error = true;
                if ($sum > 5) {
                    $this->flashMessage('La somme des voies doit être égale à 15. Merci de corriger les valeurs de certaines voies.', 'warning');
                } else {
                    $this->flashMessage('Veuillez indiquer vos scores de Voies.');
                }
            }

            if (!$has1or5) {
                $error = true;
                $this->flashMessage('Au moins une des voies doit avoir un score de 1 ou de 5.', 'warning');
            }

            if (false === $error) {
                $this->updateCharacterStep($waysValues);

                return $this->nextStep();
            }

            $waysValues = $this->resetWays($ways);
        }

        return $this->renderCurrentStep([
            'ways_values' => $waysValues,
            'ways_list' => $ways,
        ], 'corahn_rin/Steps/08_ways.html.twig');
    }

    /**
     * @param Ways[] $ways
     *
     * @return array
     */
    private function resetWays(array $ways = [])
    {
        $values = [];

        foreach ($ways as $way => $d) {
            $values[$way] = 1;
        }

        return $values;
    }
}
