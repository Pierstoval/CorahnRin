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

use DataFixtures\CorahnRin\AdvantagesFixtures;
use const JSON_THROW_ON_ERROR;

class Step11AdvantagesTest extends AbstractStepTest
{
    /**
     * @group functional
     */
    public function testValidAdvantagesStep(): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => [
                AdvantagesFixtures::ID_INFLUENT_ALLY => 1,
                AdvantagesFixtures::ID_FINANCIAL_EASE_5 => 1,
            ],
            'disadvantages' => [
                AdvantagesFixtures::ID_CRIPPLED => 1,
                AdvantagesFixtures::ID_POOR => 1,
                AdvantagesFixtures::ID_PHOBIA => 1,
            ],
            'advantages_indications' => [
                AdvantagesFixtures::ID_INFLUENT_ALLY => 'Influent ally',
                AdvantagesFixtures::ID_PHOBIA => 'Some phobia',
            ],
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/12_mental_disorder'));
        static::assertSame([
            'advantages' => [
                AdvantagesFixtures::ID_INFLUENT_ALLY => 1,
                AdvantagesFixtures::ID_FINANCIAL_EASE_5 => 1,
            ],
            'disadvantages' => [
                AdvantagesFixtures::ID_CRIPPLED => 1,
                AdvantagesFixtures::ID_POOR => 1,
                AdvantagesFixtures::ID_PHOBIA => 1,
            ],
            'advantages_indications' => [
                AdvantagesFixtures::ID_INFLUENT_ALLY => 'Influent ally',
                AdvantagesFixtures::ID_PHOBIA => 'Some phobia',
            ],
            'remainingExp' => 80,
        ], $result->getSession()->get('character.corahn_rin')[$this->getStepName()]);
    }

    /**
     * @group functional
     */
    public function testWithFullScoreTrauma(): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => [
            ],
            'disadvantages' => [
                AdvantagesFixtures::ID_TRAUMA => 3,
            ],
            'advantages_indications' => [
            ],
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/12_mental_disorder'));
        static::assertSame([
            'advantages' => [
            ],
            'disadvantages' => [
                AdvantagesFixtures::ID_TRAUMA => 3,
            ],
            'advantages_indications' => [
            ],
            'remainingExp' => 130,
        ], $result->getSession()->get('character.corahn_rin')[$this->getStepName()]);
    }

    /**
     * @group functional
     */
    public function testTooMuchExpHasBeenSpent(): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => [
                AdvantagesFixtures::ID_INFLUENT_ALLY => 1,
                AdvantagesFixtures::ID_FINANCIAL_EASE_5 => 1,
                AdvantagesFixtures::ID_GOOD_HEALTH => 2,
                AdvantagesFixtures::ID_STRONG => 1,
            ],
            'disadvantages' => [],
            'advantages_indications' => [
                AdvantagesFixtures::ID_INFLUENT_ALLY => 'Influent ally',
            ],
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode());
        static::assertCount(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error'));
        static::assertSame('Vous n\'avez pas assez d\'expérience.', $result->getCrawler()->filter('#flash-messages > .card-panel.error')->text('', true));
    }

    /**
     * @group functional
     */
    public function testTooMuchExpHasBeenGained(): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => [],
            'disadvantages' => [
                AdvantagesFixtures::ID_BAD_HEALTH => 1,
                AdvantagesFixtures::ID_NEARSIGHTED => 2,
                AdvantagesFixtures::ID_PHOBIA => 1,
                AdvantagesFixtures::ID_TRAUMA => 3,
            ],
            'advantages_indications' => [
                AdvantagesFixtures::ID_PHOBIA => 'Some phobia',
            ],
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode(), \json_encode($result->getSession()->get('character.corahn_rin'), JSON_THROW_ON_ERROR));
        static::assertCount(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error'));
        static::assertSame('Vos désavantages vous donnent un gain d\'expérience supérieur à 80.', $result->getCrawler()->filter('#flash-messages > .card-panel.error')->text('', true));
    }

    /**
     * @dataProvider provideAllyTests
     * @group functional
     */
    public function testCannotChoseAllyMultipleTimes($values, array $indications): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => $values,
            'disadvantages' => [
                // This is to have enough exp to maybe buy them all
                AdvantagesFixtures::ID_BAD_HEALTH => 1,
                AdvantagesFixtures::ID_PHOBIA => 1,
            ],
            'advantages_indications' => $indications + [AdvantagesFixtures::ID_PHOBIA => 'Some phobia'],
        ]);

        $code = $result->getResponse()->getStatusCode();
        if (500 === $code) {
            $msg = $result->getCrawler()->filter('title')->text('', true);
        } else {
            $msg = \json_encode($result->getSession()->get('character.corahn_rin'), JSON_THROW_ON_ERROR);
        }
        static::assertSame(200, $code, $msg);
        static::assertCount(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error'));

        $txt = \trim($result->getCrawler()->filter('#flash-messages > .card-panel.error')->text('', true));
        static::assertSame('Vous ne pouvez pas combiner plusieurs avantages ou désavantages de type "advantages.group.ally".', $txt);
    }

    public function provideAllyTests(): array
    {
        // Test all "Ally" advantage possibilities so we're sure every case is covered
        $ids = [1, 2, 3];

        // initialize by adding the empty set
        $results = [[]];

        foreach ($ids as $element) {
            foreach ($results as $combination) {
                $newElement = \array_merge([$element], $combination);
                $results[\implode(', ', $newElement)] = $newElement;
            }
        }

        $results = \array_filter($results, static function ($item) {
            return \count($item) > 1;
        });

        return \array_map(static function ($item) {
            return [
                \array_combine($item, \array_fill(0, \count($item), 1)),
                \array_combine($item, \array_fill(0, \count($item), 'Indication for : '.\implode(', ', $item))),
            ];
        }, $results);
    }

    /**
     * @dataProvider provideFinancialEaseTests
     * @group functional
     */
    public function testCannotChoseFinancialEaseMultipleTimes($values): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => $values,
            'disadvantages' => [
                // This is to have enough exp to maybe buy them all
                AdvantagesFixtures::ID_UNLUCKY => 1,
                AdvantagesFixtures::ID_BAD_HEALTH => 1,
                AdvantagesFixtures::ID_PHOBIA => 1,
            ],
            'advantages_indications' => [
                AdvantagesFixtures::ID_PHOBIA => 'Some phobia',
            ],
        ]);

        $code = $result->getResponse()->getStatusCode();
        if (500 === $code) {
            $msg = $result->getCrawler()->filter('title')->text('', true);
        } else {
            $msg = \json_encode($result->getSession()->get('character.corahn_rin'), JSON_THROW_ON_ERROR);
        }
        static::assertSame(200, $code, $msg);
        static::assertCount(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error'));
        static::assertSame('Vous ne pouvez pas combiner plusieurs avantages ou désavantages de type "advantages.group.financial_ease".', $result->getCrawler()->filter('#flash-messages > .card-panel.error')->text('', true));
    }

    public function provideFinancialEaseTests(): array
    {
        // Test all "FinancialEase" advantage possibilities so we're sure every case is covered
        $ids = AdvantagesFixtures::FINANCIAL_EASE_IDS;

        // initialize by adding the empty set
        $results = [[]];

        foreach ($ids as $element) {
            foreach ($results as $combination) {
                $newElement = \array_merge([$element], $combination);
                $results[\implode(', ', $newElement)] = $newElement;
            }
        }

        $results = \array_filter($results, static function ($item) {
            // Only need from 2 to 4 because 1 is valid, and more than 4 is invalid.
            $c = \count($item);

            return $c > 1 && $c < 5;
        });

        return \array_map(
            static function ($item) {
                return [
                    \array_combine($item, \array_fill(0, \count($item), 1)),
                ];
            },
            $results
        );
    }

    /**
     * @group functional
     */
    public function testIncorrectAdvantageValue(): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => [
                AdvantagesFixtures::ID_GOOD_HEALTH => 10000,
            ],
            'disadvantages' => [],
            'advantages_indications' => [],
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode(), \json_encode($result->getSession()->get('character.corahn_rin'), JSON_THROW_ON_ERROR));
        static::assertCount(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error'));
        static::assertSame('Une valeur incorrecte a été donnée à un avantage.', $result->getCrawler()->filter('#flash-messages > .card-panel.error')->text('', true));
    }

    /**
     * @group functional
     */
    public function testIncorrectDisadvantageValue(): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => [],
            'disadvantages' => [
                AdvantagesFixtures::ID_POOR => 2,
            ],
            'advantages_indications' => [],
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode(), \json_encode($result->getSession()->get('character.corahn_rin'), JSON_THROW_ON_ERROR));
        static::assertCount(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error'));
        static::assertSame('Une valeur incorrecte a été donnée à un désavantage.', $result->getCrawler()->filter('#flash-messages > .card-panel.error')->text('', true));
    }

    /**
     * @group functional
     */
    public function testIncorrectAdvantageId(): void
    {
        $client = $this->getHttpClient();

        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', [
            '07_setbacks' => [],
        ]);
        $session->save();

        $crawler = $client->request('POST', '/fr/character/generate/'.$this->getStepName(), [
            'advantages' => [
                99999 => 1,
            ],
            'disadvantages' => [],
            'advantages_indications' => [],
        ]);

        static::assertSame(200, $client->getResponse()->getStatusCode(), $crawler->filter('title')->text('', true));
        static::assertEquals('Les avantages soumis sont incorrects.', $crawler->filter('#flash-messages > .card-panel.error')->text('', true));
    }

    /**
     * @group functional
     */
    public function testIncorrectDisadvantageId(): void
    {
        $client = $this->getHttpClient();

        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', [
            '07_setbacks' => [],
        ]);
        $session->save();

        $crawler = $client->request('POST', '/fr/character/generate/'.$this->getStepName(), [
            'advantages' => [],
            'disadvantages' => [
                99999 => 1,
            ],
            'advantages_indications' => [],
        ]);

        static::assertSame(200, $client->getResponse()->getStatusCode(), $crawler->filter('title')->text('', true));
        static::assertEquals('Les désavantages soumis sont incorrects.', $crawler->filter('#flash-messages > .card-panel.error')->text('', true));
    }

    /**
     * @group functional
     */
    public function testCannotHaveMoreThan4Advantages(): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => [
                AdvantagesFixtures::ID_ISOLATED_ALLY => 1,
                AdvantagesFixtures::ID_FINANCIAL_EASE_2 => 1,
                AdvantagesFixtures::ID_FAST => 1,
                AdvantagesFixtures::ID_FINE_NOSE => 1,
                AdvantagesFixtures::ID_FINE_TONGUE => 1,
            ],
            'disadvantages' => [],
            'advantages_indications' => [
                AdvantagesFixtures::ID_ISOLATED_ALLY => 'Ally',
            ],
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode(), \json_encode($result->getSession()->get('character.corahn_rin'), JSON_THROW_ON_ERROR));
        static::assertCount(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error'));
        static::assertSame('Vous ne pouvez pas avoir plus de 4 avantages.', $result->getCrawler()->filter('#flash-messages > .card-panel.error')->text('', true));
    }

    /**
     * @group functional
     */
    public function testCannotHaveMoreThan4Disadvantages(): void
    {
        $result = $this->submitAction([
            '07_setbacks' => [],
        ], [
            'advantages' => [],
            'disadvantages' => [
                AdvantagesFixtures::ID_SLOW => 1,
                AdvantagesFixtures::ID_UNLUCKY => 1,
                AdvantagesFixtures::ID_POOR => 1,
                AdvantagesFixtures::ID_SHY => 1,
                AdvantagesFixtures::ID_TRAUMA => 1,
            ],
        ]);

        static::assertSame(200, $result->getResponse()->getStatusCode(), \json_encode($result->getSession()->get('character.corahn_rin'), JSON_THROW_ON_ERROR));
        static::assertCount(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error'));
        static::assertSame('Vous ne pouvez pas avoir plus de 4 désavantages.', $result->getCrawler()->filter('#flash-messages > .card-panel.error')->text('', true));
    }

    /**
     * @dataProvider provideFinancialEaseForPoor
     * @group functional
     */
    public function testPoorCannotUseFinancialEase($values): void
    {
        $client = $this->getHttpClient();

        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', ['07_setbacks' => [9 => ['id' => 9, 'avoided' => false]]]);
        $session->save();

        $crawler = $client->request('POST', '/fr/character/generate/'.$this->getStepName(), [
            'advantages' => $values,
            'disadvantages' => [],
            'advantages_indications' => [],
        ]);

        static::assertSame(200, $client->getResponse()->getStatusCode(), $crawler->filter('title')->text('', true));
        static::assertMatchesRegularExpression('~^L\'avantage "Aisance financière \d" a été désactivé par le revers "Pauvreté".~', $crawler->filter('#flash-messages > .card-panel.error')->text('', true));
    }

    public function provideFinancialEaseForPoor(): array
    {
        return [
            [[AdvantagesFixtures::ID_FINANCIAL_EASE_1 => 1]],
            [[AdvantagesFixtures::ID_FINANCIAL_EASE_2 => 1]],
            [[AdvantagesFixtures::ID_FINANCIAL_EASE_3 => 1]],
            [[AdvantagesFixtures::ID_FINANCIAL_EASE_4 => 1]],
            [[AdvantagesFixtures::ID_FINANCIAL_EASE_5 => 1]],
        ];
    }
}
