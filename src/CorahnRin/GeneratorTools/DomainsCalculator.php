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

namespace CorahnRin\GeneratorTools;

use CorahnRin\Data\DomainItem;
use CorahnRin\Data\DomainsData;
use CorahnRin\Entity\GeoEnvironment;

final class DomainsCalculator
{
    /**
     * @var int[]
     */
    private array $finalCalculatedDomains = [];

    private int $bonus = 0;

    public function getBonus(): int
    {
        return $this->bonus;
    }

    /**
     * If $domainsBonuses IS NOT provided, then it will return calculated domains values with an array of this type:
     * [ 'bonus'=>0, 'domains'=>[...] ]
     * Mostly used for step 14.
     *
     * If $domainsBonuses IS provided, then it will add the correct bonuses if some domains exceed 5 points.
     *
     * @return int[]
     */
    public function calculateFromGeneratorData(
        array $characterSocialClassStepData,
        string $ostServiceDomain,
        GeoEnvironment $geoEnv,
        array $step13PrimaryDomains,
        array $step14DomainsBonuses = null
    ): array {
        $this->bonus = 0;
        $this->finalCalculatedDomains = [];

        $allDomains = DomainsData::allAsObjects();

        // Step 13 primary domains and step 14 bonuses
        foreach ($allDomains as $id => $domain) {
            // First, validate arguments.
            if (!isset($step13PrimaryDomains[$id])) {
                throw new \InvalidArgumentException(\sprintf(
                    'Invalid %s argument sent. It must be an array of %s, and the array key must correspond to the "%s" property.',
                    '$primaryDomains',
                    'integers',
                    'domain id'
                ));
            }

            // Step 13 primary domains
            $this->finalCalculatedDomains[$id] = $step13PrimaryDomains[$id];

            // Step 14 domain bonuses (if set)
            if (null !== $step14DomainsBonuses) {
                if (!\array_key_exists($id, $step14DomainsBonuses)) {
                    throw new \InvalidArgumentException(\sprintf(
                        'Invalid %s argument sent. It must be an array of %s, and the array key must correspond to the "%s" property.',
                        '$domainsBonuses',
                        'integers',
                        'domain id'
                    ));
                }
                $this->addValueToDomain($id, $step14DomainsBonuses[$id]);
            }
        }

        // Ost service
        if (!\array_key_exists($ostServiceDomain, $allDomains)) {
            throw new \InvalidArgumentException(\sprintf(
                'Invalid %s argument sent. It must be a valid %s.',
                '$ost',
                'domain id'
            ));
        }
        $this->addValueToDomain($ostServiceDomain);

        // Geo environment
        $this->addValueToDomain($geoEnv->getDomain());

        // Social classes
        foreach ($characterSocialClassStepData as $id) {
            if (!\array_key_exists($id, $allDomains)) {
                throw new \InvalidArgumentException(\sprintf(
                    'Invalid %s argument sent. It must be an array of %s, and the array values must correspond to the "%s" property.',
                    '$socialClasses',
                    'integers',
                    'domain id'
                ));
            }

            $this->addValueToDomain($id);
        }

        if (null !== $step14DomainsBonuses) {
            foreach ($this->finalCalculatedDomains as $id => $value) {
                if ($value > 5) {
                    $this->finalCalculatedDomains[$id] = 5;
                }
            }
        }

        return $this->finalCalculatedDomains;
    }

    /**
     * Based on all three specified steps, will calculate final domains values.
     * Mostly used in step 16 to calculate disciplines and in step 17 to check if combat arts are available.
     *
     * @param object[] $allDomains
     * @param int[]    $domainsBaseValues
     * @param int[]    $domainsSpendExp
     *
     * @return int[]
     */
    public function calculateFinalValues(array $allDomains, array $domainsBaseValues, array $domainsSpendExp)
    {
        $finalValues = [];

        foreach ($allDomains as $id => $domain) {
            // First, validate arguments.
            if (!($domain instanceof DomainItem)) {
                throw new \InvalidArgumentException(\sprintf(
                    'Invalid %s argument sent. It must be an array of %s instances, %s given.',
                    '$allDomains',
                    DomainItem::class,
                    \get_debug_type($domain)
                ));
            }

            if (!isset($domainsBaseValues[$id], $domainsSpendExp[$id])) {
                throw new \InvalidArgumentException('Invalid arguments sent for step domains. It must be an array of integers, and the array key must correspond to the domain id.');
            }

            $finalValues[$id] = $domainsBaseValues[$id] + $domainsSpendExp[$id];
        }

        return $finalValues;
    }

    private function addValueToDomain(string $domainId, int $value = 1): void
    {
        if (1 === $value) {
            if ($this->finalCalculatedDomains[$domainId] < 5) {
                $this->finalCalculatedDomains[$domainId]++;
            } else {
                $this->bonus++;
            }
        } else {
            for ($i = 1; $i <= $value; $i++) {
                $this->addValueToDomain($domainId);
            }
        }
    }
}
