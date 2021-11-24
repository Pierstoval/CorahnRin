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

use DataFixtures\CorahnRin\DisordersFixtures;

class Step12MentalDisorderTest extends AbstractStepTest
{
    /**
     * @group functional
     */
    public function testWaysDependency(): void
    {
        $client = $this->getHttpClient();

        $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        self::assertSame(302, $client->getResponse()->getStatusCode());
        self::assertTrue($client->getResponse()->isRedirect('/fr/character/generate'));
    }

    /**
     * @group functional
     */
    public function testValidMentalDisorder(): void
    {
        $result = $this->submitAction([
            '08_ways' => [
                'ways.combativeness' => 5,
                'ways.creativity' => 4,
                'ways.empathy' => 3,
                'ways.reason' => 2,
                'ways.conviction' => 1,
            ],
        ], [
            'gen-div-choice' => DisordersFixtures::ID_FRENZY,
        ]);

        self::assertSame(302, $result->getResponse()->getStatusCode());
        self::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/13_primary_domains'));
        self::assertSame('1', $result->getSession()->get('character.corahn_rin')[$this->getStepName()]);
    }

    /**
     * @group functional
     */
    public function testEmptyValue(): void
    {
        $result = $this->submitAction([
            '08_ways' => [
                'ways.combativeness' => 5,
                'ways.creativity' => 4,
                'ways.empathy' => 3,
                'ways.reason' => 2,
                'ways.conviction' => 1,
            ],
        ], [
            'gen-div-choice' => 0,
        ]);

        $crawler = $result->getCrawler();

        self::assertSame(200, $result->getResponse()->getStatusCode());
        self::assertCount(1, $crawler->filter('#flash-messages > .card-panel.error'));
        self::assertEquals('Veuillez choisir un désordre mental.', $crawler->filter('#flash-messages > .card-panel.error')->text('', true));
    }

    /**
     * @group functional
     */
    public function testInvalidMentalDisorder(): void
    {
        $result = $this->submitAction([
            '08_ways' => [
                'ways.combativeness' => 5,
                'ways.creativity' => 4,
                'ways.empathy' => 3,
                'ways.reason' => 2,
                'ways.conviction' => 1,
            ],
        ], [
            'gen-div-choice' => 999999,
        ]);

        $crawler = $result->getCrawler();

        self::assertSame(200, $result->getResponse()->getStatusCode());
        self::assertCount(1, $crawler->filter('#flash-messages > .card-panel.error'));
        self::assertEquals('Le désordre mental choisi n\'existe pas.', $crawler->filter('#flash-messages > .card-panel.error')->text('', true));
    }
}
