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
use CorahnRin\Document\Advantage;
use CorahnRin\Document\Job;
use CorahnRin\Repository\CharacterAdvantageRepository;
use CorahnRin\Repository\JobsRepository;
use Symfony\Component\HttpFoundation\Response;

class Step13PrimaryDomains extends AbstractStepAction
{
    /**
     * @var Advantage[]
     */
    protected array $advantages;

    /**
     * @var Advantage[]
     */
    protected array $disadvantages;

    /**
     * @var array[]
     */
    protected array $step11AdvantagesData;

    /**
     * @var array<DomainItem>
     */
    private array $allDomains;

    private Job $job;

    /**
     * @var int[]
     */
    private array $secondaryDomains;

    /**
     * Keys are the following:
     * "domains":          domain_id=>domain_value
     * "military_service": domain_id|null.
     *
     * @var array<string, array|string>
     */
    private array $submittedDomains;

    private JobsRepository $jobsRepository;
    private CharacterAdvantageRepository $advantageRepository;

    public function __construct(JobsRepository $jobsRepository, CharacterAdvantageRepository $advantageRepository)
    {
        $this->jobsRepository = $jobsRepository;
        $this->advantageRepository = $advantageRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $this->allDomains = DomainsData::allAsObjects();
        $job = $this->jobsRepository->find($this->getCharacterProperty('02_job'));
        if (!$job) {
            throw new \RuntimeException('This step should not have been started as the Job dependency is not fulfilled.');
        }
        $this->job = $job;
        $this->step11AdvantagesData = $this->getCharacterProperty('11_advantages');
        $advantages = $this->step11AdvantagesData['advantages'];
        $disadvantages = $this->step11AdvantagesData['disadvantages'];

        $this->advantages = $this->advantageRepository->findBy(['id' => \array_keys($advantages)]);
        $this->disadvantages = $this->advantageRepository->findBy(['id' => \array_keys($disadvantages)]);

        // This makes sure that session is not polluted with wrong data.
        $this->resetStep();

        if (!\array_key_exists('domains', $this->submittedDomains)) {
            $this->submittedDomains['domains'] = [];
        }

        if (!\array_key_exists('ost', $this->submittedDomains)) {
            $this->submittedDomains['ost'] = DomainsData::CLOSE_COMBAT['title'];
        }

        // The number of domains set for each possible value.
        $numberOf = [
            1 => 0, // Max: 2
            2 => 0, // Max: 2
            3 => 0, // Max: 1
        ];

        // Setup the number of domains set depending on their "value"
        foreach ($this->submittedDomains['domains'] as $value) {
            if ($value >= 1 && $value <= 3) {
                $numberOf[$value]++;
            }
        }

        // Setup all values to 0 if unset.
        foreach ($this->allDomains as $id => $domain) {
            if (!\array_key_exists($id, $this->submittedDomains['domains'])) {
                $this->submittedDomains['domains'][$id] = 0;
            }
        }

        // Determine secondary domains if applicable (some jobs don't have any).
        $this->secondaryDomains = [];
        foreach ($this->job->getSecondaryDomains() as $domain) {
            $this->secondaryDomains[] = $domain;
        }

        if ($this->managePost()) {
            return $this->nextStep();
        }

        return $this->renderCurrentStep([
            'job' => $this->job,
            'all_domains' => $this->allDomains,
            'number_of' => $numberOf,
            'domains_values' => $this->submittedDomains,
            'secondary_domains' => $this->secondaryDomains,
        ], 'corahn_rin/Steps/13_primary_domains.html.twig');
    }

    private function managePost(): bool
    {
        $primaryDomainId = $this->job->getPrimaryDomain();

        // Primary domain, impossible to change.
        $this->submittedDomains['domains'][$primaryDomainId] = 5;

        if (!$this->request->isMethod('POST')) {
            return false;
        }

        /** @var null|int[] $submittedDomains */
        $submittedDomains = $this->request->request->all('domains');

        if (null === $submittedDomains) {
            return false;
        }

        foreach ($this->allDomains as $id => $domain) {
            // Allow sending partial requests with only some of the few domains.
            // Strictness is nice, but sometimes, being a bit more flexible is cool to lighten our tests :D
            if (!\array_key_exists($id, $submittedDomains)) {
                $submittedDomains[$id] = 0;
            }
        }

        // There should be twice 1 and 2, and once 3.
        // We don't take 5 in account because it can never be replaced.
        $numberOf1 = 0;
        $numberOf2 = 0;
        $numberOf3 = 0;

        $error = false;

        $submittedDomains = \array_map(function ($v) {return (int) $v; }, $submittedDomains);

        foreach ($submittedDomains as $id => $domainValue) {
            if (!\array_key_exists($id, $this->allDomains)) {
                $this->flashMessage('Les domaines envoyés sont invalides.');
                unset($submittedDomains[$id]);
            }

            if (!\in_array($domainValue, [0, 1, 2, 3, 5], true)) {
                $this->flashMessage('Le score d\'un domaine ne peut être que de 0, 1, 2 ou 3. Le score 5 est choisi par défaut en fonction de votre métier.');
                $error = true;
                $submittedDomains[$id] = 0;
                $domainValue = 0;
            }

            if (5 === $domainValue && $id !== $primaryDomainId) {
                $this->flashMessage('Le score 5 ne peut pas être attribué à un autre domaine que celui défini par votre métier.');
                $error = true;
                $submittedDomains[$id] = 0;
            }
            if (5 !== $domainValue && $id === $primaryDomainId) {
                $this->flashMessage('Le domaine principal doit avoir un score de 5, vous ne pouvez pas le changer car il est défini par votre métier.');
                $error = true;
                $submittedDomains[$id] = 5;
            }

            // If value is 3, it must be for a secondary domain.
            if (3 === $domainValue) {
                if (\count($this->secondaryDomains) && !\in_array($id, $this->secondaryDomains, true)) {
                    $this->flashMessage('La valeur 3 ne peut être donnée qu\'à l\'un des domaines de prédilection du métier choisi.');
                    $error = true;
                    $submittedDomains[$id] = 0;
                }
                $numberOf3++;
                if ($numberOf3 > 1) {
                    $this->flashMessage('La valeur 3 ne peut être donnée qu\'une seule fois.');
                    $error = true;
                    $submittedDomains[$id] = 0;
                }
            }

            if (2 === $domainValue) {
                $numberOf2++;
                if ($numberOf2 > 2) {
                    $this->flashMessage('La valeur 2 ne peut être donnée que deux fois.');
                    $error = true;
                    $submittedDomains[$id] = 0;
                }
            }

            if (1 === $domainValue) {
                $numberOf1++;
                if ($numberOf1 > 2) {
                    $this->flashMessage('La valeur 1 ne peut être donnée que deux fois.');
                    $error = true;
                    $submittedDomains[$id] = 0;
                }
            }
        }

        if (2 !== $numberOf1) {
            $this->flashMessage('La valeur 1 doit être sélectionnée deux fois.');
            $error = true;
        }
        if (2 !== $numberOf2) {
            $this->flashMessage('La valeur 2 doit être sélectionnée deux fois.');
            $error = true;
        }
        if (1 !== $numberOf3) {
            $this->flashMessage('La valeur 3 doit être sélectionnée.');
            $error = true;
        }

        $this->submittedDomains['domains'] = $submittedDomains;

        if (!$this->checkOst()) {
            $error = true;
        }

        // Reset again the primary domain, because impossible to change it.
        $this->submittedDomains['domains'][$primaryDomainId] = 5;

        if (!$error) {
            $this->updateCharacterStep($this->submittedDomains);
        }

        return !$error;
    }

    /**
     * Makes sure the "ost" domain is valid.
     *
     * @return bool false if any error occurs
     */
    private function checkOst(): bool
    {
        $id = \trim((string) $this->request->request->get('ost', ''));

        $keyExists = $id ? \array_key_exists($id, $this->allDomains) : null;

        if (!$keyExists) {
            if (!$id) {
                $this->submittedDomains['ost'] = DomainsData::CLOSE_COMBAT['title'];
            } else {
                $this->flashMessage('Le domaine spécifié pour le service d\'Ost n\'est pas valide.');

                return false;
            }
        } else {
            $this->submittedDomains['ost'] = $id;
        }

        return true;
    }

    private function resetStep(): void
    {
        $sessionValue = $this->getCharacterProperty() ?: [
            'domains' => [],
            'ost' => DomainsData::CLOSE_COMBAT['title'],
        ];

        $this->submittedDomains = [
            'domains' => $sessionValue['domains'],
            'ost' => $sessionValue['ost'],
        ];

        foreach ($this->allDomains as $id => $domain) {
            $this->submittedDomains['domains'][$id] = 0;
        }

        // Reset again the primary domain, because impossible to change it.
        $this->submittedDomains['domains'][$this->job->getPrimaryDomain()] = 5;
    }
}
