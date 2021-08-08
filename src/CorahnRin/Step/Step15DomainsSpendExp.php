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

class Step15DomainsSpendExp extends AbstractStepAction
{
    /**
     * @var array<DomainItem>
     */
    private $allDomains;

    /**
     * @var array<string, array|int>
     */
    private $domainsSpentWithExp;

    /**
     * @var int
     */
    private $expRemainingFromAdvantages;

    private $domainsCalculator;
    private $geoEnvironmentsRepository;

    public function __construct(DomainsCalculator $domainsCalculator, GeoEnvironmentsRepository $geoEnvironmentsRepository)
    {
        $this->domainsCalculator = $domainsCalculator;
        $this->geoEnvironmentsRepository = $geoEnvironmentsRepository;
    }

    /**
     * {@inheritdoc}
     *
     * @internal int[] $socialClassValues
     */
    public function execute(): Response
    {
        $this->allDomains = DomainsData::allAsObjects();

        $primaryDomains = $this->getCharacterProperty('13_primary_domains');
        $socialClassValues = $this->getCharacterProperty('05_social_class')['domains'];
        $domainBonuses = $this->getCharacterProperty('14_use_domain_bonuses');
        $geoEnvironment = $this->geoEnvironmentsRepository->find($this->getCharacterProperty('04_geo'));

        $domainsBaseValues = $this->domainsCalculator->calculateFromGeneratorData(
            $socialClassValues,
            $primaryDomains['ost'],
            $geoEnvironment,
            $primaryDomains['domains'],
            $domainBonuses['domains']
        );

        $this->expRemainingFromAdvantages = $this->getCharacterProperty('11_advantages')['remainingExp'];

        $this->domainsSpentWithExp = $this->getCharacterProperty();

        if (null === $this->domainsSpentWithExp) {
            $this->resetDomains();
        }

        // Manage form submit
        if ($this->request->isMethod('POST')) {
            /** @var int[] $domainsValues */
            $domainsValues = $this->request->get('domains_spend_exp');

            if (!\is_array($domainsValues)) {
                $this->flashMessage('errors.incorrect_values');
            } else {
                $remainingExp = $this->expRemainingFromAdvantages;

                $errors = false;

                // First, check the ids
                foreach ($domainsValues as $id => $value) {
                    if (
                        !\array_key_exists($id, $this->allDomains)
                        || !\is_numeric($value)
                        || $value < 0
                        || ($remainingExp - ($value * 10)) < 0
                        || $value + $domainsBaseValues[$id] > 5
                    ) {
                        $errors = true;
                        $this->flashMessage('errors.incorrect_values');

                        break;
                    }

                    $value = (int) $value;

                    $domainsValues[$id] = 0 === $value ? null : $value;

                    $remainingExp -= ($value * 10);
                }

                if (false === $errors) {
                    $this->domainsSpentWithExp = [
                        'domains' => $domainsValues,
                        'remainingExp' => $remainingExp,
                    ];

                    $this->updateCharacterStep($this->domainsSpentWithExp);

                    return $this->nextStep();
                }
            }
        }

        return $this->renderCurrentStep([
            'all_domains' => $this->allDomains,
            'domains_base_values' => $domainsBaseValues,
            'domains_spent_with_exp' => $this->domainsSpentWithExp['domains'],
            'exp_max' => $this->expRemainingFromAdvantages,
            'exp_value' => $this->domainsSpentWithExp['remainingExp'],
        ], 'corahn_rin/Steps/15_domains_spend_exp.html.twig');
    }

    private function resetDomains(): void
    {
        $this->domainsSpentWithExp = [
            'domains' => [],
            'remainingExp' => $this->expRemainingFromAdvantages,
        ];

        foreach ($this->allDomains as $id => $value) {
            $this->domainsSpentWithExp['domains'][$id] = null;
        }
    }
}
