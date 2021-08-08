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

class Step08WaysTest extends AbstractStepTest
{
    /**
     * @group functional
     */
    public function testValidWays(): void
    {
        $ways = [
            'ways.combativeness' => 1,
            'ways.creativity' => 2,
            'ways.empathy' => 3,
            'ways.reason' => 4,
            'ways.conviction' => 5,
        ];
        $result = $this->submitAction([], [
            'ways' => $ways,
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/09_traits'));
        static::assertSame([$this->getStepName() => $ways], $result->getSession()->get('character.corahn_rin'));
    }

    /**
     * @group functional
     */
    public function testWaysSumIsFiveOnly(): void
    {
        $ways = [
            'ways.combativeness' => 1,
            'ways.creativity' => 1,
            'ways.empathy' => 1,
            'ways.reason' => 1,
            'ways.conviction' => 1,
        ];
        $result = $this->submitAction([], [
            'ways' => $ways,
        ]);

        $crawler = $result->getCrawler();

        static::assertSame(200, $result->getResponse()->getStatusCode());
        static::assertCount(1, $crawler->filter('#flash-messages > .card-panel.error'));
        $nodeText = $crawler->filter('#flash-messages > .card-panel.error')->text('', true);
        static::assertEquals('Veuillez indiquer vos scores de Voies.', $nodeText);
    }

    /**
     * @group functional
     */
    public function testWaysSumIsSuperiorToFiveButInferiorToFifteen(): void
    {
        $ways = [
            'ways.combativeness' => 1,
            'ways.creativity' => 1,
            'ways.empathy' => 1,
            'ways.reason' => 1,
            'ways.conviction' => 2,
        ];
        $result = $this->submitAction([], [
            'ways' => $ways,
        ]);

        $crawler = $result->getCrawler();

        static::assertSame(200, $result->getResponse()->getStatusCode());
        static::assertCount(1, $crawler->filter('#flash-messages > .card-panel.warning'));
        $nodeText = $crawler->filter('#flash-messages > .card-panel.warning')->text('', true);
        static::assertEquals('La somme des voies doit être égale à 15. Merci de corriger les valeurs de certaines voies.', $nodeText);
    }

    /**
     * @group functional
     */
    public function testNoWayHasScoreOfOneOrFive(): void
    {
        $ways = [
            'ways.combativeness' => 3,
            'ways.creativity' => 3,
            'ways.empathy' => 3,
            'ways.reason' => 3,
            'ways.conviction' => 3,
        ];
        $result = $this->submitAction([], [
            'ways' => $ways,
        ]);

        $crawler = $result->getCrawler();

        static::assertSame(200, $result->getResponse()->getStatusCode());
        static::assertCount(1, $crawler->filter('#flash-messages > .card-panel.warning'));
        $nodeText = $crawler->filter('#flash-messages > .card-panel.warning')->text('', true);
        static::assertEquals('Au moins une des voies doit avoir un score de 1 ou de 5.', $nodeText);
    }

    /**
     * @group functional
     */
    public function testWaysBeyondRange(): void
    {
        $ways = [
            'ways.combativeness' => 1,
            'ways.creativity' => 1,
            'ways.empathy' => 2,
            'ways.reason' => 2,
            'ways.conviction' => 6,
        ];
        $result = $this->submitAction([], [
            'ways' => $ways,
        ]);

        $crawler = $result->getCrawler();

        static::assertSame(200, $result->getResponse()->getStatusCode());
        static::assertCount(1, $crawler->filter('#flash-messages > .card-panel.error'));
        $nodeText = $crawler->filter('#flash-messages > .card-panel.error')->text('', true);
        static::assertEquals('Les voies doivent être comprises entre 1 et 5.', $nodeText);
    }

    /**
     * @group functional
     */
    public function testInexistentWays(): void
    {
        $client = $this->getHttpClient();

        $crawler = $client->request('POST', '/fr/character/generate/'.$this->getStepName(), [
            'ways' => [
                'wrong_way_for_combativeness' => 1,
                'wrong_way_for_creativity' => 1,
                'wrong_way_for_empathy' => 2,
                'wrong_way_for_reason' => 2,
                'wrong_way_for_conviction' => 6,
            ],
        ]);

        static::assertSame(200, $client->getResponse()->getStatusCode(), $crawler->filter('title')->count() > 0 ? $crawler->filter('title')->text('', true) : '');
        static::assertCount(1, $crawler->filter('#flash-messages > .card-panel.error'));
        $nodeText = \preg_replace('~(\\r)?\\n\s+~', '', $crawler->filter('#flash-messages > .card-panel.error')->text('', true));
        static::assertEquals('Erreur dans le formulaire. Merci de vérifier les valeurs soumises. Les voies doivent être comprises entre 1 et 5.', $nodeText);
    }
}
