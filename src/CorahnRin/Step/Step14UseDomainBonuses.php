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

use CorahnRin\Data\DomainItem;
use CorahnRin\Data\DomainsData;
use CorahnRin\GeneratorTools\DomainsCalculator;
use CorahnRin\Repository\GeoEnvironmentsRepository;
use Symfony\Component\HttpFoundation\Response;

class Step14UseDomainBonuses extends AbstractStepAction
{
    /**
     * @var array<DomainItem>
     */
    private array $allDomains;

    private DomainsCalculator $domainsCalculator;
    private GeoEnvironmentsRepository $geoEnvironmentsRepository;

    public function __construct(DomainsCalculator $domainsCalculator, GeoEnvironmentsRepository $geoEnvironmentsRepository)
    {
        $this->domainsCalculator = $domainsCalculator;
        $this->geoEnvironmentsRepository = $geoEnvironmentsRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $this->allDomains = DomainsData::allAsObjects();

        $step13Domains = $this->getCharacterProperty('13_primary_domains');
        $socialClassValues = $this->getCharacterProperty('05_social_class')['domains'];
        $geoEnvironment = $this->geoEnvironmentsRepository->find($this->getCharacterProperty('04_geo'));

        if (!$geoEnvironment) {
            throw new \RuntimeException('Could not fetch GeoEnvironment: possibly the character generation steps data are corrupted.');
        }

        $domainsCalculatedValues = $this->domainsCalculator->calculateFromGeneratorData(
            $socialClassValues,
            $step13Domains['ost'],
            $geoEnvironment,
            $step13Domains['domains']
        );

        /**
         * Bonuses will be calculated based on primary domains,
         *  and all other advantages/properties of the character can
         *  add bonuses depending on their values.
         */
        $bonus = $this->domainsCalculator->getBonus();

        /** @var null|int[][] $characterBonuses */
        $characterBonuses = $this->getCharacterProperty();

        if (null === $characterBonuses) {
            $characterBonuses = $this->resetBonuses();
        }

        // If "mentor ally" is selected, then the character has a bonus to one domain.
        // Thanks to him! :D
        $advantages = $this->getCharacterProperty('11_advantages');
        $mentor = (int) ($advantages['advantages'][2] ?? 0);
        $bonus += $mentor; // $mentor can be 0 or 1 only so no problem with this operation.

        /** @var int $age */
        $age = $this->getCharacterProperty('06_age');
        if ($age > 20) {
            $bonus++;
        }
        if ($age > 25) {
            $bonus++;
        }
        if ($age > 30) {
            $bonus++;
        }

        $bonusValue = $bonus;

        // Manage form submit
        if ($this->request->isMethod('POST')) {
            if (0 === $bonus) {
                $finalArray = $this->resetBonuses();
                $finalArray['remaining'] = $bonus;
                $this->updateCharacterStep($finalArray);

                return $this->nextStep();
            }

            $postedValues = $this->request->request->all('domains_bonuses');

            $remainingPoints = $bonusValue;
            $spent = 0;

            $error = false;

            foreach (\array_keys($characterBonuses['domains']) as $id) {
                $value = (int) ($postedValues[$id] ?? 0);
                if (
                    !\array_key_exists($id, $postedValues)
                    || ($value && 5 === $domainsCalculatedValues[$id])
                    || !\in_array($value, [0, 1], true)
                ) {
                    // If there is any error, we do nothing.
                    $this->flashMessage('errors.incorrect_values');
                    $error = true;

                    break;
                }

                if (1 === $value) {
                    $remainingPoints--;
                    $spent++;
                }

                $characterBonuses['domains'][$id] = $value;
            }

            if ($remainingPoints < 0) {
                $this->flashMessage('domains_bonuses.errors.too_many_points', null, ['%base%' => $bonus, '%spent%' => $spent]);
                $error = true;
            }

            if (false === $error) {
                if ($remainingPoints > 2) {
                    $this->flashMessage('domains_bonuses.errors.more_than_two', null, ['%count%' => $remainingPoints]);
                } elseif ($remainingPoints >= 0) {
                    $characterBonuses['remaining'] = $remainingPoints;
                    $this->updateCharacterStep($characterBonuses);

                    return $this->nextStep();
                }
            } else {
                $characterBonuses = $this->resetBonuses();
                $this->updateCharacterStep(null);
                $bonusValue = $bonus;
            }
        }

        return $this->renderCurrentStep([
            'all_domains' => $this->allDomains,
            'domains_values' => $domainsCalculatedValues,
            'domains_bonuses' => $characterBonuses,
            'bonus_max' => $bonus,
            'bonus_value' => $bonusValue,
        ], 'corahn_rin/Steps/14_use_domain_bonuses.html.twig');
    }

    /**
     * @return array<string, array<int|string, int>|int>
     */
    private function resetBonuses(): array
    {
        $domainsBonuses = [
            'domains' => [],
            'remaining' => 0,
        ];

        foreach ($this->allDomains as $id => $domain) {
            $domainsBonuses['domains'][$id] = 0;
        }

        return $domainsBonuses;
    }
}
