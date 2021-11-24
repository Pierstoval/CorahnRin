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

namespace Tests\CorahnRin\Controller\Game;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\GetHttpClientTestTrait;

class GameListControllerTest extends WebTestCase
{
    use GetHttpClientTestTrait;

    /**
     * @group functional
     */
    public function test not logged in is not allowed(): void
    {
        $client = $this->getHttpClient();

        $client->request('GET', '/fr/games');

        self::assertResponseStatusCodeSame(401);
    }

    /**
     * @group functional
     */
    public function test logged in shows list(): void
    {
        $client = $this->getHttpClient();

        $this->loginAsUser($client, 'game-master');

        $crawler = $client->request('GET', '/fr/games');

        self::assertResponseStatusCodeSame(200);

        $titles = $crawler->filter('h2');
        self::assertSame('En tant que MJ', $titles->eq(0)->text('', true));
        self::assertSame('En tant que PJ', $titles->eq(1)->text('', true));

        $charLines = $crawler->filter('#games_as_game_master tr');
        self::assertSame('Empty campaign', $charLines->eq(0)->filter('td')->eq(0)->text('', true));
        self::assertSame('Campaign with a character', $charLines->eq(1)->filter('td')->eq(0)->text('', true));
        self::assertSame('Campaign with a character', $crawler->filter('#games_as_player tr')->eq(0)->filter('td')->eq(0)->text('', true));
    }
}
