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

use CorahnRin\Repository\PeopleRepository;
use Symfony\Component\HttpFoundation\Response;

class Step01People extends AbstractStepAction
{
    private $peopleRepository;

    public function __construct(PeopleRepository $peopleRepository)
    {
        $this->peopleRepository = $peopleRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $tmp = $this->peopleRepository->findAll();
        $peoples = [];
        foreach ($tmp as $item) {
            $peoples[$item->getId()] = $item;
        }

        if ($this->request->isMethod('POST')) {
            $peopleId = (int) $this->request->request->get('gen-div-choice');
            if (isset($peoples[$peopleId])) {
                $this->updateCharacterStep($peopleId);

                return $this->nextStep();
            }

            $this->flashMessage('Veuillez indiquer un peuple correct.');
        }

        return $this->renderCurrentStep([
            'peoples' => $peoples,
            'people_value' => $this->getCharacterProperty(),
        ], 'corahn_rin/Steps/01_people.html.twig');
    }
}
