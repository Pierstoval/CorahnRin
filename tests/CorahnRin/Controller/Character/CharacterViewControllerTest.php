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

namespace Tests\CorahnRin\Controller\Character;

use CorahnRin\Controller\Character\CharacterViewController;
use CorahnRin\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\GetHttpClientTestTrait;

/**
 * @see \CorahnRin\Controller\Character\CharacterViewController
 */
class CharacterViewControllerTest extends WebTestCase
{
    use GetHttpClientTestTrait;

    /**
     * @see CharacterViewController::listAction
     * @group functional
     */
    public function testList(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'standard-admin');

        $crawler = $client->request('GET', '/fr/characters/');

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertCount(1, $crawler->filter('table.table.table-condensed'));
    }

    /**
     * @group functional
     */
    public function test list with invalid query must return 400(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'standard-admin');

        $client->request('GET', '/fr/characters/?order=undefined');

        static::assertSame(400, $client->getResponse()->getStatusCode());
    }

    /**
     * @see CharacterViewController::viewAction
     * @group functional
     */
    public function testView404(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'standard-admin');

        $client->request('GET', '/fr/characters/9999999-aaaaaaaa');

        static::assertSame(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @see CharacterViewController::viewAction
     * @group functional
     */
    public function testView(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'standard-admin');

        // Check fixtures for character name & id
        $client->request('GET', '/fr/characters/1-steren-slaine');

        static::assertResponseStatusCodeSame(200);
        static::assertSelectorTextSame('h1', 'Steren Slaine');
        static::assertSelectorExists('#character-original-page-1');
        static::assertSelectorExists('#character-original-page-2');
        static::assertSelectorExists('#character-original-page-3');
        static::assertSelectorExists('#character-details-name h2 span.character-details-subtitle');
        static::assertSelectorTextSame('#character-details-name h2 span.character-details-subtitle', 'Nom : ');
        static::assertSelectorTextSame('#character-details-name h2 span.character-details-subitem', 'Steren Slaine');

        // Don't do all the rest, it's cumbersome,
        // and we just might create other assertions
        // for edge-cases if we find some.
    }
}
