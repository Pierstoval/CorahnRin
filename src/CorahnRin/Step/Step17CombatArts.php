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

use CorahnRin\Data\DomainsData;
use CorahnRin\Document\CombatArt;
use CorahnRin\GeneratorTools\DomainsCalculator;
use CorahnRin\Repository\CombatArtsRepository;
use CorahnRin\Repository\GeoEnvironmentsRepository;
use Symfony\Component\HttpFoundation\Response;

class Step17CombatArts extends AbstractStepAction
{
    /**
     * @var CombatArt[]
     */
    private $combatArts;

    /**
     * @var int
     */
    private $remainingExp;
    private $combatArtsRepository;
    private $geoEnvironmentsRepository;
    private $domainsCalculator;

    public function __construct(
        CombatArtsRepository $combatArtsRepository,
        DomainsCalculator $domainsCalculator,
        GeoEnvironmentsRepository $geoEnvironmentsRepository
    ) {
        $this->domainsCalculator = $domainsCalculator;
        $this->geoEnvironmentsRepository = $geoEnvironmentsRepository;
        $this->combatArtsRepository = $combatArtsRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $allDomains = DomainsData::allAsObjects();
        $this->combatArts = $this->combatArtsRepository->findAllSortedByName();

        $socialClassValues = $this->getCharacterProperty('05_social_class')['domains'];
        $primaryDomains = $this->getCharacterProperty('13_primary_domains');
        $domainBonuses = $this->getCharacterProperty('14_use_domain_bonuses');
        $geoEnvironment = $this->geoEnvironmentsRepository->find($this->getCharacterProperty('04_geo'));

        // Calculate final values from previous steps
        $domainsBaseValues = $this->domainsCalculator->calculateFromGeneratorData(
            $socialClassValues,
            $primaryDomains['ost'],
            $geoEnvironment,
            $primaryDomains['domains'],
            $domainBonuses['domains']
        );

        $finalDomainsValues = $this->domainsCalculator->calculateFinalValues(
            $allDomains,
            $domainsBaseValues,
            \array_map(function ($e) { return (int) $e; }, $this->getCharacterProperty('15_domains_spend_exp')['domains'])
        );

        $this->remainingExp = $this->getCharacterProperty('16_disciplines')['remainingExp'];

        $closeCombat = $finalDomainsValues[DomainsData::CLOSE_COMBAT['title']];
        $shootingAndThrowing = $finalDomainsValues[DomainsData::SHOOTING_AND_THROWING['title']];

        $canHaveCombatArts = $this->remainingExp >= 20 && (5 === $closeCombat || 5 === $shootingAndThrowing);

        $availableCombatArts = [];
        foreach ($this->combatArts as $id => $combatArt) {
            // A combat art can be acquired only if it corresponds to the correct domain.
            // For example, "Archery" cannot be acquired if shooting and throwing is below 5.
            if (
                ($combatArt->getRanged() && 5 === $shootingAndThrowing)
                || ($combatArt->getMelee() && 5 === $closeCombat)
            ) {
                $availableCombatArts[$id] = $combatArt;
            }
        }

        $characterCombatArts = $this->getCharacterProperty() ?: $this->resetCombatArts();

        if ($this->request->isMethod('POST')) {
            if (!$canHaveCombatArts) {
                $this->updateCharacterStep($characterCombatArts);

                return $this->nextStep();
            }

            $errors = false;

            /** @var int[] $combatArtsValues */
            $combatArtsValues = $this->request->get('combat_arts_spend_exp', []);

            if (!\is_array($combatArtsValues)) {
                $this->flashMessage('errors.incorrect_values');
            } else {
                $remainingExp = $this->remainingExp;

                foreach ($combatArtsValues as $id => $osef) {
                    if (!\array_key_exists($id, $availableCombatArts)) {
                        $errors = true;
                        $this->flashMessage('errors.incorrect_values');

                        break;
                    }

                    $xpCost = 20;

                    if ($remainingExp - $xpCost < 0) {
                        $errors = true;
                        $this->flashMessage('errors.incorrect_values');

                        break;
                    }

                    $remainingExp -= $xpCost;

                    $combatArtsValues[$id] = true;
                }

                if (true === $errors) {
                    $characterCombatArts = $this->resetCombatArts();
                } else {
                    $this->updateCharacterStep([
                        'combatArts' => $combatArtsValues,
                        'remainingExp' => $remainingExp,
                    ]);

                    return $this->nextStep();
                }
            }
        }

        return $this->renderCurrentStep([
            'can_have_combat_arts' => $canHaveCombatArts,
            'combat_arts' => $availableCombatArts,
            'close_combat' => $closeCombat,
            'shooting_and_throwing' => $shootingAndThrowing,
            'character_combat_arts' => $characterCombatArts['combatArts'],
            'exp_max' => $this->remainingExp,
            'exp_value' => $characterCombatArts['remainingExp'],
        ], 'corahn_rin/Steps/17_combat_arts.html.twig');
    }

    /**
     * @return array
     */
    private function resetCombatArts()
    {
        return [
            'combatArts' => [],
            'remainingExp' => $this->remainingExp,
        ];
    }
}
