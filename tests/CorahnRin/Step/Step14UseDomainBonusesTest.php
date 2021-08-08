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

class Step14UseDomainBonusesTest extends AbstractStepTest
{
    use StepsWithDomainsTrait;

    /**
     * @dataProvider provideInvalidDependencies
     * @group functional
     */
    public function testStepDependency($dependencies): void
    {
        $client = $this->getHttpClient();

        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', $dependencies); // Varigal
        $session->save();

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        $errorMessage = $crawler->filter('head title')->text('', true);

        static::assertSame(302, $client->getResponse()->getStatusCode(), $errorMessage);
        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate'));
    }

    public function provideInvalidDependencies(): array
    {
        return [
            [[]],
            [['02_job' => 1]],
            [['08_ways' => [1, 2, 3, 4, 5]]],
            [['11_advantages' => []]],
            [['02_job' => 1, '08_ways' => [1, 2, 3, 4, 5]]],
            [['11_advantages' => [], '08_ways' => [1, 2, 3, 4, 5]]],
            [['13_primary_domains' => ['domains' => [], 'ost' => 2, 'scholar' => null]]],
        ];
    }

    /**
     * @group functional
     */
    public function testNoBonusesSpent(): void
    {
        $client = $this->getClientWithRequirements($this->getValidRequirements());

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        static::assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('#generator_form')->form();

        $client->submit($form);

        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate/15_domains_spend_exp'));
        $this->assertSessionEquals([], 1, $client);
    }

    /**
     * @group functional
     */
    public function testPrimaryDomainThrowsError(): void
    {
        $client = $this->getClientWithRequirements($this->getValidRequirements());

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        static::assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('#generator_form')->form();

        $form['domains_bonuses[domains.craft]'] = '1';

        $crawler = $client->submit($form);

        // Redirection means error
        static::assertSame(200, $client->getResponse()->getStatusCode());
        $flashMessages = $crawler->filter('#flash-messages');
        static::assertStringContainsString('Certaines valeurs envoyées sont incorrectes, veuillez recommencer (et sans tricher).', $flashMessages->text('', true));
    }

    /**
     * @group functional
     */
    public function testStep(): void
    {
        static::markTestIncomplete();
    }
}
