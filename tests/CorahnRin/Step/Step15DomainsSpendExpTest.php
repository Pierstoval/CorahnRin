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

class Step15DomainsSpendExpTest extends AbstractStepTest
{
    use StepsWithDomainsTrait;

    /**
     * @group functional
     */
    public function testStepDependency(): void
    {
        $client = $this->getHttpClient();

        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', []); // Varigal
        $session->save();

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        $errorMessage = $crawler->filter('head title')->text('', true);

        self::assertSame(302, $client->getResponse()->getStatusCode(), $errorMessage);
        self::assertTrue($client->getResponse()->isRedirect('/fr/character/generate'));
    }

    /**
     * @group functional
     */
    public function testStep(): void
    {
        self::markTestIncomplete();
    }
}
