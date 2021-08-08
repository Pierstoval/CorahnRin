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

namespace Tests\CorahnRin\Step;

use Symfony\Bundle\FrameworkBundle\KernelBrowser as Client;
use Symfony\Component\HttpFoundation\Session\Session;

trait StepsWithDomainsTrait
{
    /**
     * These should be freely changed when inside a test, because bigger logics are needed sometimes.
     * This method only guarantees a working base requirement.
     */
    protected function getValidRequirements(): array
    {
        return [
            '02_job' => 1, // Artisan
            '04_geo' => 1, // Rural
            '05_social_class' => [
                'id' => 1, // Paysan
                'domains' => [
                    'domains.craft',
                    'domains.perception',
                ],
            ],
            '06_age' => 16,
            '11_advantages' => [
                'advantages' => [],
                'disadvantages' => [],
                'remainingExp' => 100,
            ],
            '13_primary_domains' => [
                'domains' => [
                    'domains.craft' => 5,
                    'domains.close_combat' => 2,
                    'domains.stealth' => 0,
                    'domains.magience' => 0,
                    'domains.natural_environment' => 1,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 3,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 2,
                    'domains.erudition' => 1,
                ],
                'ost' => 'domains.close_combat',
            ],
        ];
    }

    protected function getClientWithRequirements($requirements): Client
    {
        /** @var Client $client */
        $client = $this->getHttpClient();

        /** @var Session $session */
        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', $requirements);
        $session->save();

        return $client;
    }

    protected function assertSessionEquals(array $domains, int $remaining, Client $client): void
    {
        $finalDomains = \array_merge([
            'domains.craft' => 0,
            'domains.close_combat' => 0,
            'domains.stealth' => 0,
            'domains.magience' => 0,
            'domains.natural_environment' => 0,
            'domains.demorthen_mysteries' => 0,
            'domains.occultism' => 0,
            'domains.perception' => 0,
            'domains.prayer' => 0,
            'domains.feats' => 0,
            'domains.relation' => 0,
            'domains.performance' => 0,
            'domains.science' => 0,
            'domains.shooting_and_throwing' => 0,
            'domains.travel' => 0,
            'domains.erudition' => 0,
        ], $domains);

        $results = [
            'domains' => $finalDomains,
            'remaining' => $remaining,
        ];

        static::assertEquals($results, $client->getContainer()->get('session')->get('character.corahn_rin')[$this->getStepName()]);
    }
}
