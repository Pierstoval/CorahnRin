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

use CorahnRin\Entity\SocialClass;
use CorahnRin\Repository\SocialClassRepository;
use Symfony\Component\HttpFoundation\Response;

class Step05SocialClass extends AbstractStepAction
{
    private $socialClassRepository;

    public function __construct(SocialClassRepository $socialClassRepository)
    {
        $this->socialClassRepository = $socialClassRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $tmp = $this->socialClassRepository->findAll();
        $allSocialClasses = [];
        foreach ($tmp as $item) {
            $allSocialClasses[$item->getId()] = $item;
        }

        $currentStepCharacterValue = $this->getCharacterProperty();

        $selectedDomains = $currentStepCharacterValue['domains'] ?? [];

        $domainId1 = \array_shift($selectedDomains);
        $domainId2 = \array_shift($selectedDomains);

        $socialClassId = $currentStepCharacterValue['id'] ?? null;

        if ($this->request->isMethod('POST')) {
            $socialClassId = $this->request->request->getInt('gen-div-choice');
            $selectedDomains = $this->request->request->all('domains') ?: [];

            $domainId1 = \array_shift($selectedDomains);
            $domainId2 = \array_shift($selectedDomains);

            if (
                $socialClassId && $domainId1 && $domainId2
                && isset($allSocialClasses[$socialClassId])
            ) {
                // Let's check that the two chosen domains are really available in this social class
                /** @var SocialClass $socialClass */
                $socialClass = $allSocialClasses[$socialClassId];

                // S'il y a une erreur c'est que l'un des domaines n'est pas associé à la classe sociale choisie.
                if (!$socialClass->hasDomain($domainId1) || !$socialClass->hasDomain($domainId2)) {
                    $this->flashMessage('Les domaines choisis ne sont pas associés à la classe sociale sélectionnée.');
                } else {
                    $this->updateCharacterStep([
                        'id' => $socialClassId,
                        'domains' => [
                            $domainId1,
                            $domainId2,
                        ],
                    ]);

                    return $this->nextStep();
                }
            } elseif (!\array_key_exists($socialClassId, $allSocialClasses)) {
                $this->flashMessage('Veuillez sélectionner une classe sociale valide.');
            } elseif (!$domainId1 || !$domainId2) {
                $this->flashMessage('Vous devez choisir 2 domaines pour lesquels vous obtiendrez un bonus de +1. Ces domaines doivent être choisi dans la classe sociale sélectionnée.', 'warning');
            }
        }

        return $this->renderCurrentStep([
            'socialClasses' => $allSocialClasses,
            'socialClass_value' => $socialClassId,
            'socialClassDomains' => [
                $domainId1,
                $domainId2,
            ],
        ], 'corahn_rin/Steps/05_social_class.html.twig');
    }
}
