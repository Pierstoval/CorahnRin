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

use CorahnRin\Repository\SetbacksRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser as Client;
use Tests\CorahnRin\ManualRandomSetbacksProvider;

class Step07SetbacksTest extends AbstractStepTest
{
    /**
     * @group functional
     */
    public function testNoSetback(): void
    {
        $result = $this->submitAction([
            '06_age' => 20,
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/08_ways'));
        static::assertSame([
            '06_age' => 20,
            $this->getStepName() => [],
        ], $result->getSession()->get('character.corahn_rin'));
    }

    /**
     * @group functional
     */
    public function test age that provides one setback(): void
    {
        $client = $this->getHttpClient();

        /** @var SetbacksRepository $setbacksRepo */
        $setbacksRepo = self::getContainer()->get(SetbacksRepository::class);
        $setbacksFromTheDb = $setbacksRepo->findBy([
            'name' => ['Séquelle'],
        ]);

        self::assertCount(1, $setbacksFromTheDb);

        $setbacks = $this->getCharacterSetbacksFromStepSubmit(21, $client, $setbacksFromTheDb);

        $idOfFirstSetback = $setbacksFromTheDb[0]->getId();

        static::assertSame([
            $idOfFirstSetback => ['id' => $idOfFirstSetback, 'avoided' => false],
        ], $setbacks);
    }

    /**
     * @group functional
     */
    public function test age that provides two setbacks(): void
    {
        $client = $this->getHttpClient();

        /** @var SetbacksRepository $setbacksRepo */
        $setbacksRepo = self::getContainer()->get(SetbacksRepository::class);
        $setbacksFromTheDb = $setbacksRepo->findBy([
            'name' => ['Séquelle', 'Adversaire'],
        ]);

        $setbacks = $this->getCharacterSetbacksFromStepSubmit(26, $client, $setbacksFromTheDb);

        $idOfFirstSetback = $setbacksFromTheDb[0]->getId();
        $idOfSecondSetback = $setbacksFromTheDb[1]->getId();

        static::assertSame([
            $idOfFirstSetback => ['id' => $idOfFirstSetback, 'avoided' => false],
            $idOfSecondSetback => ['id' => $idOfSecondSetback, 'avoided' => false],
        ], $setbacks);
    }

    /**
     * @group functional
     */
    public function test age that provides three setbacks(): void
    {
        $client = $this->getHttpClient();

        /** @var SetbacksRepository $setbacksRepo */
        $setbacksRepo = self::getContainer()->get(SetbacksRepository::class);
        $setbacksFromTheDb = $setbacksRepo->findBy([
            'name' => ['Séquelle', 'Adversaire', 'Rumeur'],
        ]);

        $setbacks = $this->getCharacterSetbacksFromStepSubmit(31, $client, $setbacksFromTheDb);

        $idOfFirstSetback = $setbacksFromTheDb[0]->getId();
        $idOfSecondSetback = $setbacksFromTheDb[1]->getId();
        $idOfThirdSetback = $setbacksFromTheDb[2]->getId();

        static::assertSame([
            $idOfFirstSetback => ['id' => $idOfFirstSetback, 'avoided' => false],
            $idOfSecondSetback => ['id' => $idOfSecondSetback, 'avoided' => false],
            $idOfThirdSetback => ['id' => $idOfThirdSetback, 'avoided' => false],
        ], $setbacks);
    }

    /**
     * @group functional
     */
    public function test one setback plus unlucky one(): void
    {
        $client = $this->getHttpClient();

        /** @var SetbacksRepository $setbacksRepo */
        $setbacksRepo = self::getContainer()->get(SetbacksRepository::class);
        // Force the order here so unlucky one is picked first
        $setbacksFromTheDb = [
            0 => $setbacksRepo->findOneBy(['name' => 'Poisse']),
            1 => $setbacksRepo->findOneBy(['name' => 'Adversaire']),
        ];

        $setbacks = $this->getCharacterSetbacksFromStepSubmit(21, $client, $setbacksFromTheDb);

        $idOfFirstSetback = $setbacksFromTheDb[0]->getId();
        $idOfSecondSetback = $setbacksFromTheDb[1]->getId();

        static::assertSame([
            $idOfFirstSetback => ['id' => $idOfFirstSetback, 'avoided' => false],
            $idOfSecondSetback => ['id' => $idOfSecondSetback, 'avoided' => false],
        ], $setbacks);
    }

    /**
     * @group functional
     */
    public function test one setback plus lucky one(): void
    {
        $client = $this->getHttpClient();

        /** @var SetbacksRepository $setbacksRepo */
        $setbacksRepo = self::getContainer()->get(SetbacksRepository::class);
        // Force the order here so unlucky one is picked first
        $setbacksFromTheDb = [
            0 => $setbacksRepo->findOneBy(['name' => 'Chance']),
            1 => $setbacksRepo->findOneBy(['name' => 'Adversaire']),
        ];

        $setbacks = $this->getCharacterSetbacksFromStepSubmit(21, $client, $setbacksFromTheDb);

        $idOfFirstSetback = $setbacksFromTheDb[0]->getId();
        $idOfSecondSetback = $setbacksFromTheDb[1]->getId();

        static::assertSame([
            $idOfFirstSetback => ['id' => $idOfFirstSetback, 'avoided' => false],
            $idOfSecondSetback => ['id' => $idOfSecondSetback, 'avoided' => true],
        ], $setbacks);
    }

    /**
     * @group functional
     */
    public function testAgeNotDefinedRedirectsToStepOne(): void
    {
        $client = $this->getHttpClient();

        $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate'));
        $client->followRedirect();
        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate/01_people'));
        $crawler = $client->followRedirect();
        static::assertEquals(
            'L\'étape "07 Setbacks" dépend de "06 Age", mais celle-ci n\'est pas présente dans le personnage en cours de création...',
            $crawler->filter('#flash-messages > .card-panel.error')->text('', true)
        );
    }

    /**
     * @group functional
     */
    public function testManualWithValidSetbacks(): void
    {
        $client = $this->getHttpClient();

        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', ['06_age' => 21]);
        $session->save();

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName().'?manual=');
        $formNode = $crawler->filter('#generator_form');
        static::assertCount(1, $formNode);

        $form = $formNode->form()
            ->disableValidation()
            ->setValues([
                'setbacks_value' => [2, 3],
            ])
        ;

        $client->submit($form);

        static::assertSame(302, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate/08_ways'));
        static::assertSame([2 => ['id' => 2, 'avoided' => false], 3 => ['id' => 3, 'avoided' => false]], $session->get('character.corahn_rin')[$this->getStepName()]);
    }

    /**
     * @group functional
     */
    public function testManualWithInValidSetbacks(): void
    {
        $client = $this->getHttpClient();

        $session = $client->getContainer()->get('session');
        $session->set('character.corahn_rin', ['06_age' => 21]);
        $session->save();

        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName().'?manual=');
        $formNode = $crawler->filter('#generator_form');
        static::assertCount(1, $formNode);

        $form = $formNode->form()
            ->disableValidation()
            ->setValues([
                'setbacks_value' => [1, 10], // 1 and 10 exists, but they cannot be chosen with manual setup
            ])
        ;

        $crawler = $client->submit($form);

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertEquals(
            'Veuillez entrer des revers correct(s).',
            $crawler->filter('#flash-messages > .card-panel.error')->text('', true)
        );
    }

    private function getCharacterSetbacksFromStepSubmit(int $age, Client $client, array $setbacksFromTheDb)
    {
        /** @var ManualRandomSetbacksProvider $provider */
        $provider = self::getContainer()->get(ManualRandomSetbacksProvider::class);
        // Pick two so we make sure only one is picked.
        $provider->setCustomSetbacksToPick($setbacksFromTheDb);

        // We need a simple session to be sure it's updated when submitting form.
        $session = self::getContainer()->get('session');
        $session->set('character.corahn_rin', ['06_age' => $age]);
        $session->save();

        // Make the request.
        $crawler = $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        $statusCode = $client->getResponse()->getStatusCode();
        $errorBlock = $crawler->filter('title');

        $msg = 'Could not execute step request...';
        $msg .= $errorBlock->count() ? ("\n".$errorBlock->text('', true)) : (' For step "'.$this->getStepName().'"');
        static::assertSame(200, $statusCode, $msg);

        // Prepare form values.
        $form = $crawler->filter('#generator_form')->form();
        $form->disableValidation()->setValues([]);
        $client->submit($form);

        static::assertSame(302, $client->getResponse()->getStatusCode());
        static::assertTrue($client->getResponse()->isRedirect('/fr/character/generate/08_ways'));

        return self::getContainer()->get('session')->get('character.corahn_rin')[$this->getStepName()];
    }
}
