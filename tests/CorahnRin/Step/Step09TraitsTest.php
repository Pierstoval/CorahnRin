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

class Step09TraitsTest extends AbstractStepTest
{
    /**
     * @group functional
     */
    public function test ways dependency redirects to generator(): void
    {
        $client = $this->getHttpClient();

        $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        static::assertSame(302, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate'));
    }

    /**
     * @group functional
     */
    public function test invalid ways redirect to step 8(): void
    {
        $client = $this->getHttpClient();

        // Inject the Ways step dependency in the session to test it's redirecting properly
        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', [
            '08_ways' => ['1', '2', '3', '4', '5'],
        ]);
        $session->save();

        $result = new StepActionTestResult($client->request('GET', '/fr/character/generate/'.$this->getStepName()), $client);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/08_ways'));
        static::assertSame(['Traits coming from Step 08 Ways are not correct, please check them back.'], $result->getSession()->getFlashBag()->get('error'));
    }

    /**
     * @group functional
     */
    public function test valid setbacks(): void
    {
        $result = $this->submitAction([
            '08_ways' => [
                'ways.combativeness' => 1,
                'ways.creativity' => 2,
                'ways.empathy' => 3,
                'ways.reason' => 4,
                'ways.conviction' => 5,
            ],
        ], $values = [
            'quality' => 6,
            'flaw' => 64,
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/10_orientation'));
        static::assertSame($values, $result->getSession()->get('character.corahn_rin')[$this->getStepName()]);
    }

    /**
     * @group functional
     */
    public function test in valid setbacks(): void
    {
        $result = $this->submitAction([
            '08_ways' => [
                'ways.combativeness' => 1,
                'ways.creativity' => 2,
                'ways.empathy' => 3,
                'ways.reason' => 4,
                'ways.conviction' => 5,
            ],
        ], $values = [
            'quality' => 0,
            'flaw' => 0,
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode());
        static::assertCount(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error'));
        static::assertEquals('Les traits de caractère choisis sont incorrects.', $result->getCrawler()->filter('#flash-messages > .card-panel.error')->text('', true));
    }
}
