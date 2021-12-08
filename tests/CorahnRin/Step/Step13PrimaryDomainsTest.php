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

use DataFixtures\CorahnRin\JobsFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser as Client;

class Step13PrimaryDomainsTest extends AbstractStepTest
{
    /**
     * @dataProvider provideInvalidDependencies
     * @group functional
     */
    public function testStepDependency($dependencies): void
    {
        $client = $this->getHttpClient();

        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', $dependencies);
        $session->save();

        $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        static::assertSame(302, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate'));
    }

    public function provideInvalidDependencies(): array
    {
        return [
            [[]],
            [['02_job' => JobsFixtures::ID_ARTISAN]],
            [['08_ways' => ['ways.combativeness' => 1, 'ways.creativity' => 3, 'ways.empathy' => 5, 'ways.reason' => 5, 'ways.conviction' => 1]]],
            [['11_advantages' => []]],
            [['02_job' => 1, '08_ways' => ['ways.combativeness' => 1, 'ways.creativity' => 3, 'ways.empathy' => 5, 'ways.reason' => 5, 'ways.conviction' => 1]]],
            [['11_advantages' => [], '08_ways' => ['ways.combativeness' => 1, 'ways.creativity' => 3, 'ways.empathy' => 5, 'ways.reason' => 5, 'ways.conviction' => 1]]],
        ];
    }

    /**
     * @group functional
     */
    public function testVarigalHasTwoDomainsWithScore3(): void
    {
        $client = $this->getStepClient(JobsFixtures::ID_VARIGAL);

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        static::assertCount(15, $crawler->filter('[data-change="1"].domain-change'));
        static::assertCount(15, $crawler->filter('[data-change="2"].domain-change'));
        static::assertCount(2, $crawler->filter('[data-change="3"].domain-change'));
        static::assertCount(16, $crawler->filter('[data-change="5"].disabled'));
    }

    /**
     * @group functional
     */
    public function testSpyHasAllDomainsWithScore3(): void
    {
        $client = $this->getStepClient(JobsFixtures::ID_SPY); // Spy id in fixtures

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        // 15 because domain 8 (Perception) is already set to score 5
        static::assertCount(15, $crawler->filter('[data-change="1"].domain-change'));
        static::assertCount(15, $crawler->filter('[data-change="2"].domain-change'));
        static::assertCount(15, $crawler->filter('[data-change="3"].domain-change'));
        static::assertCount(16, $crawler->filter('[data-change="5"].disabled'));
    }

    /**
     * @group functional
     */
    public function testSubmitNoDomain(): void
    {
        $client = $this->getStepClient(1); // Artisan id in fixtures

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        static::assertCount(15, $crawler->filter('[data-change="1"].domain-change'));
        static::assertCount(15, $crawler->filter('[data-change="2"].domain-change'));
        static::assertCount(2, $crawler->filter('[data-change="3"].domain-change'));
        static::assertCount(16, $crawler->filter('[data-change="5"].disabled'));

        $form = $crawler->filter('#generator_form')->form();

        $crawler = $client->submit($form);

        $flashMessagesNode = $crawler->filter('#flash-messages');

        static::assertCount(1, $flashMessagesNode);

        $flashText = $flashMessagesNode->text('', true);

        static::assertStringContainsString('La valeur 1 doit être sélectionnée deux fois.', $flashText);
        static::assertStringContainsString('La valeur 2 doit être sélectionnée deux fois.', $flashText);
        static::assertStringContainsString('La valeur 3 doit être sélectionnée.', $flashText);
    }

    /**
     * @group functional
     */
    public function testSubmitInvalidDomain(): void
    {
        $client = $this->getStepClient(1); // Artisan id in fixtures

        $crawler = $client->request('POST', '/fr/character/generate/'.$this->getStepName(), [
            'domains' => [
                'inexistent_domain_name' => 1,
            ],
        ]);

        $flashMessagesNode = $crawler->filter('#flash-messages');

        static::assertCount(1, $flashMessagesNode);

        $flashText = $flashMessagesNode->text('', true);

        static::assertStringContainsString('Les domaines envoyés sont invalides.', $flashText);
    }

    /**
     * @group functional
     */
    public function testWrongValueForSecondaryDomain(): void
    {
        $client = $this->getStepClient(1); // Artisan id in fixtures

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        $form = $crawler->filter('#generator_form')->form();

        $form->setValues([
            'domains' => [
                'domains.close_combat' => 3, // Close combat is not one of Artisan's secondary domains
            ],
        ]);

        $crawler = $client->submit($form);

        $flashMessagesNode = $crawler->filter('#flash-messages');

        static::assertCount(1, $flashMessagesNode);

        static::assertStringContainsString('La valeur 3 ne peut être donnée qu\'à l\'un des domaines de prédilection du métier choisi.', $flashMessagesNode->text('', true));
    }

    /**
     * @group functional
     */
    public function testTooMuchValuesForSecondaryDomain(): void
    {
        $client = $this->getStepClient(1); // Artisan id in fixtures

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        $form = $crawler->filter('#generator_form')->form();

        $form->setValues([
            'domains' => [
                'domains.relation' => 3, // Both these domains are secondary domains for "Artisan"
                'domains.science' => 3,
            ],
        ]);

        $crawler = $client->submit($form);

        $flashMessagesNode = $crawler->filter('#flash-messages');

        static::assertCount(1, $flashMessagesNode);

        static::assertStringContainsString('La valeur 3 ne peut être donnée qu\'une seule fois.', $flashMessagesNode->text('', true));
    }

    /**
     * @group functional
     */
    public function testWrongDomainWithScore5(): void
    {
        $client = $this->getStepClient(1); // Artisan id in fixtures

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        $form = $crawler->filter('#generator_form')->form();

        $form->setValues([
            'domains' => [
                'domains.prayer' => 5, // Artisan's main job (crafting, id #1 in fixtures) should have a score of Five
            ],
        ]);

        $crawler = $client->submit($form);

        $flashMessagesNode = $crawler->filter('#flash-messages');

        static::assertCount(1, $flashMessagesNode);

        static::assertStringContainsString('Le score 5 ne peut pas être attribué à un autre domaine que celui défini par votre métier.', $flashMessagesNode->text('', true));
    }

    /**
     * @group functional
     */
    public function testMultipleValuesForDomainWithScore5(): void
    {
        $client = $this->getStepClient(1); // Artisan id in fixtures

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        $form = $crawler->filter('#generator_form')->form();

        $form->setValues([
            'domains' => [
                'domains.prayer' => 5, // Artisan's main job (crafting, id #1 in fixtures) should have a score of Five
                'domains.feats' => 5,
                'domains.relation' => 5,
            ],
        ]);

        $crawler = $client->submit($form);

        $flashMessagesNode = $crawler->filter('#flash-messages');

        static::assertCount(1, $flashMessagesNode);

        static::assertStringContainsString('Le score 5 ne peut pas être attribué à un autre domaine que celui défini par votre métier.', $flashMessagesNode->text('', true));
    }

    /**
     * @group functional
     */
    public function testWrongValueForPrimaryDomain(): void
    {
        $client = $this->getStepClient(1); // Artisan id in fixtures

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        $form = $crawler->filter('#generator_form')->form();

        $form->setValues([
            'domains' => [
                'domains.craft' => 1, // Artisan's main job (crafting, id #1 in fixtures) should have a score of Five
            ],
        ]);

        $crawler = $client->submit($form);

        $flashMessagesNode = $crawler->filter('#flash-messages');

        static::assertCount(1, $flashMessagesNode);

        static::assertStringContainsString('Le domaine principal doit avoir un score de 5, vous ne pouvez pas le changer car il est défini par votre métier.', $flashMessagesNode->text('', true));
    }

    /**
     * @group functional
     */
    public function testWrongValueForAnyDomain(): void
    {
        $client = $this->getStepClient(1); // Artisan id in fixtures

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        $form = $crawler->filter('#generator_form')->form();

        $form->setValues([
            'domains' => [
                'domains.close_combat' => 9999,
            ],
        ]);

        $crawler = $client->submit($form);

        $flashMessagesNode = $crawler->filter('#flash-messages');

        static::assertCount(1, $flashMessagesNode);

        static::assertStringContainsString('Le score d\'un domaine ne peut être que de 0, 1, 2 ou 3. Le score 5 est choisi par défaut en fonction de votre métier.', $flashMessagesNode->text('', true));
    }

    /**
     * @group functional
     */
    public function testSelectScore1MoreThanTwice(): void
    {
        $client = $this->getStepClient(1); // Artisan id in fixtures

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        $form = $crawler->filter('#generator_form')->form();

        $form->setValues([
            'domains' => [
                'domains.close_combat' => 1,
                'domains.stealth' => 1,
                'domains.magience' => 1,
            ],
        ]);

        $crawler = $client->submit($form);

        $flashMessagesNode = $crawler->filter('#flash-messages');

        static::assertCount(1, $flashMessagesNode);

        static::assertStringContainsString('La valeur 1 ne peut être donnée que deux fois.', $flashMessagesNode->text('', true));
    }

    /**
     * @group functional
     */
    public function testSelectScore2MoreThanTwice(): void
    {
        $client = $this->getStepClient(1); // Artisan id in fixtures

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        $form = $crawler->filter('#generator_form')->form();

        $form->setValues([
            'domains' => [
                'domains.close_combat' => 2,
                'domains.stealth' => 2,
                'domains.magience' => 2,
            ],
        ]);

        $crawler = $client->submit($form);

        $flashMessagesNode = $crawler->filter('#flash-messages');

        static::assertCount(1, $flashMessagesNode);

        static::assertStringContainsString('La valeur 2 ne peut être donnée que deux fois.', $flashMessagesNode->text('', true));
    }

    /**
     * @group functional
     */
    public function testWrongOstServiceId(): void
    {
        $client = $this->getStepClient(1); // Artisan id in fixtures

        $crawler = $client->request('POST', '/fr/character/generate/'.$this->getStepName(), [
            'ost' => '99999999',
            'domains' => [],
        ]);

        $flashMessagesNode = $crawler->filter('#flash-messages');

        static::assertCount(1, $flashMessagesNode);

        $flashText = $flashMessagesNode->text('', true);

        static::assertStringContainsString('Le domaine spécifié pour le service d\'Ost n\'est pas valide.', $flashText);
    }

    /**
     * @dataProvider provideValidDomainsData
     * @group functional
     */
    public function testValidDomains($jobId, array $submitted): void
    {
        $client = $this->getStepClient($jobId); // Artisan id in fixtures

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        $crawler = $client->submit($crawler->filter('#generator_form')->form(), $submitted);

        $error = 'Unknown error when evaluating valid domains with dataset.';
        if ($crawler->filter('#flash-messages')->count()) {
            $error .= "\n".$crawler->filter('#flash-messages')->text('', true);
        }

        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate/14_use_domain_bonuses'), $error);

        static::assertEquals($submitted, $client->getRequest()->getSession()->get('character.corahn_rin')[$this->getStepName()]);
    }

    public function provideValidDomainsData(): ?\Generator
    {
        /*
         * 1  => Artisanat
         * 2  => Combat au Contact
         * 3  => Discrétion
         * 4  => Magience
         * 5  => Milieu Naturel
         * 6  => Mystères Demorthèn
         * 7  => Occultisme
         * 8  => Perception
         * 9  => Prière
         * 10 => Prouesses
         * 11 => Relation
         * 12 => Représentation
         * 13 => Science
         * 14 => Tir et Lancer
         * 15 => Voyage
         * 16 => Érudition.
         */
        yield 0 => [
            JobsFixtures::ID_ARTISAN,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 5,
                    'domains.close_combat' => 2,
                    'domains.stealth' => 2,
                    'domains.magience' => 1,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 3,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 1,
                ],
            ],
        ];
        yield 1 => [
            JobsFixtures::ID_ARTISAN,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 5,
                    'domains.close_combat' => 2,
                    'domains.stealth' => 2,
                    'domains.magience' => 1,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 3,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 1,
                ],
            ],
        ];
        yield 2 => [
            JobsFixtures::ID_BARD,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 5,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 3,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 3 => [
            JobsFixtures::ID_BARD,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 3,
                    'domains.performance' => 5,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 4 => [
            JobsFixtures::ID_HUNTER,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 0,
                    'domains.close_combat' => 0,
                    'domains.stealth' => 0,
                    'domains.magience' => 0,
                    'domains.natural_environment' => 5,
                    'domains.demorthen_mysteries' => 1,
                    'domains.occultism' => 1,
                    'domains.perception' => 2,
                    'domains.prayer' => 2,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 3,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 5 => [
            JobsFixtures::ID_HUNTER,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 0,
                    'domains.close_combat' => 3,
                    'domains.stealth' => 0,
                    'domains.magience' => 0,
                    'domains.natural_environment' => 5,
                    'domains.demorthen_mysteries' => 1,
                    'domains.occultism' => 1,
                    'domains.perception' => 2,
                    'domains.prayer' => 2,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 6 => [
            JobsFixtures::ID_KNIGHT,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 0,
                    'domains.close_combat' => 5,
                    'domains.stealth' => 1,
                    'domains.magience' => 1,
                    'domains.natural_environment' => 2,
                    'domains.demorthen_mysteries' => 2,
                    'domains.occultism' => 0,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 3,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 7 => [
            JobsFixtures::ID_KNIGHT,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 0,
                    'domains.close_combat' => 5,
                    'domains.stealth' => 1,
                    'domains.magience' => 1,
                    'domains.natural_environment' => 2,
                    'domains.demorthen_mysteries' => 2,
                    'domains.occultism' => 0,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 3,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 8 => [
            JobsFixtures::ID_FIGHTER,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 0,
                    'domains.close_combat' => 5,
                    'domains.stealth' => 0,
                    'domains.magience' => 1,
                    'domains.natural_environment' => 1,
                    'domains.demorthen_mysteries' => 2,
                    'domains.occultism' => 2,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 3,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 9 => [
            JobsFixtures::ID_FIGHTER,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 0,
                    'domains.close_combat' => 5,
                    'domains.stealth' => 0,
                    'domains.magience' => 1,
                    'domains.natural_environment' => 1,
                    'domains.demorthen_mysteries' => 2,
                    'domains.occultism' => 2,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 3,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 10 => [
            JobsFixtures::ID_MERCHANT,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 3,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 1,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 2,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 5,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 11 => [
            JobsFixtures::ID_MERCHANT,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 0,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 1,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 2,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 5,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 3,
                ],
            ],
        ];
        yield 12 => [
            JobsFixtures::ID_DEMORTHEN,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 0,
                    'domains.close_combat' => 0,
                    'domains.stealth' => 0,
                    'domains.magience' => 0,
                    'domains.natural_environment' => 3,
                    'domains.demorthen_mysteries' => 5,
                    'domains.occultism' => 1,
                    'domains.perception' => 1,
                    'domains.prayer' => 2,
                    'domains.feats' => 2,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 13 => [
            JobsFixtures::ID_DEMORTHEN,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 0,
                    'domains.close_combat' => 0,
                    'domains.stealth' => 0,
                    'domains.magience' => 0,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 5,
                    'domains.occultism' => 1,
                    'domains.perception' => 1,
                    'domains.prayer' => 2,
                    'domains.feats' => 2,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 3,
                ],
            ],
        ];
        yield 14 => [
            JobsFixtures::ID_SCHOLAR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 3,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 5,
                ],
            ],
        ];
        yield 15 => [
            JobsFixtures::ID_SCHOLAR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 3,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 5,
                ],
            ],
        ];
        yield 16 => [
            JobsFixtures::ID_SPY,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 3,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 1,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 17 => [
            JobsFixtures::ID_SPY,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 3,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 1,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 18 => [
            JobsFixtures::ID_SPY,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 3,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 2,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 19 => [
            JobsFixtures::ID_SPY,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 3,
                    'domains.natural_environment' => 2,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 20 => [
            JobsFixtures::ID_SPY,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 3,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 21 => [
            JobsFixtures::ID_SPY,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 3,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 22 => [
            JobsFixtures::ID_SPY,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 3,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 23 => [
            JobsFixtures::ID_SPY,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 3,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 24 => [
            JobsFixtures::ID_SPY,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 3,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 25 => [
            JobsFixtures::ID_SPY,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 3,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 26 => [
            JobsFixtures::ID_SPY,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 3,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 27 => [
            JobsFixtures::ID_SPY,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 3,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 28 => [
            JobsFixtures::ID_SPY,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 3,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 29 => [
            JobsFixtures::ID_SPY,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 3,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 30 => [
            JobsFixtures::ID_SPY,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 3,
                ],
            ],
        ];
        yield 31 => [
            JobsFixtures::ID_EXPLORER,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 3,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 5,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 32 => [
            JobsFixtures::ID_EXPLORER,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 0,
                    'domains.prayer' => 0,
                    'domains.feats' => 5,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 3,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 33 => [
            JobsFixtures::ID_INVESTIGATOR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 3,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 1,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 2,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 34 => [
            JobsFixtures::ID_INVESTIGATOR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 3,
                    'domains.stealth' => 1,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 2,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 35 => [
            JobsFixtures::ID_INVESTIGATOR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 3,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 2,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 36 => [
            JobsFixtures::ID_INVESTIGATOR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 3,
                    'domains.natural_environment' => 2,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 37 => [
            JobsFixtures::ID_INVESTIGATOR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 3,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 38 => [
            JobsFixtures::ID_INVESTIGATOR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 3,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 39 => [
            JobsFixtures::ID_INVESTIGATOR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 3,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 40 => [
            JobsFixtures::ID_INVESTIGATOR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 3,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 41 => [
            JobsFixtures::ID_INVESTIGATOR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 3,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 42 => [
            JobsFixtures::ID_INVESTIGATOR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 3,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 43 => [
            JobsFixtures::ID_INVESTIGATOR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 3,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 44 => [
            JobsFixtures::ID_INVESTIGATOR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 3,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 45 => [
            JobsFixtures::ID_INVESTIGATOR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 3,
                    'domains.travel' => 0,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 46 => [
            JobsFixtures::ID_INVESTIGATOR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 3,
                    'domains.erudition' => 0,
                ],
            ],
        ];
        yield 47 => [
            JobsFixtures::ID_INVESTIGATOR,
            [
                'ost' => 'domains.close_combat',
                'domains' => [
                    'domains.craft' => 1,
                    'domains.close_combat' => 1,
                    'domains.stealth' => 2,
                    'domains.magience' => 2,
                    'domains.natural_environment' => 0,
                    'domains.demorthen_mysteries' => 0,
                    'domains.occultism' => 0,
                    'domains.perception' => 5,
                    'domains.prayer' => 0,
                    'domains.feats' => 0,
                    'domains.relation' => 0,
                    'domains.performance' => 0,
                    'domains.science' => 0,
                    'domains.shooting_and_throwing' => 0,
                    'domains.travel' => 0,
                    'domains.erudition' => 3,
                ],
            ],
        ];
    }

    private function getStepClient(int $jobId, array $step11data = []): Client
    {
        if (!isset($step11data['advantages'])) {
            $step11data['advantages'] = [];
        }
        if (!isset($step11data['disadvantages'])) {
            $step11data['disadvantages'] = [];
        }
        if (!isset($step11data['advantages_indications'])) {
            $step11data['advantages_indications'] = [];
        }

        $client = $this->getHttpClient();

        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', [
            '02_job' => $jobId,
            '08_ways' => ['ways.combativeness' => 1, 'ways.creativity' => 3, 'ways.empathy' => 5, 'ways.reason' => 5, 'ways.conviction' => 1],
            '11_advantages' => $step11data,
        ]);
        $session->save();

        return $client;
    }
}
