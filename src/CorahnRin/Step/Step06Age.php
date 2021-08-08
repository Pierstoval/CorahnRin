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

use Symfony\Component\HttpFoundation\Response;

class Step06Age extends AbstractStepAction
{
    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        if ($this->request->isMethod('POST')) {
            $age = (int) $this->request->request->get('age');
            if (16 <= $age && $age <= 35) {
                $this->updateCharacterStep($age);

                return $this->nextStep();
            }
            $this->flashMessage('L\'âge doit être compris entre 16 et 35 ans.');
        }

        return $this->renderCurrentStep([
            'age' => $this->getCharacterProperty() ?: 16,
        ], 'corahn_rin/Steps/06_age.html.twig');
    }
}
