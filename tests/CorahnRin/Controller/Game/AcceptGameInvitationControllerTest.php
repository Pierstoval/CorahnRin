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

use DataFixtures\CorahnRin\GamesFixtures;
use DataFixtures\CorahnRin\GamesInvitationsFixtures;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\GetHttpClientTestTrait;
use User\Util\TokenGenerator;

class AcceptGameInvitationControllerTest extends WebTestCase
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
     * @dataProvider provide http methods
     * @group functional
     */
    public function test inexistent token returns custom 404(string $method): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client);

        $crawler = $client->request($method, '/fr/games/invitation/'.TokenGenerator::generateToken());

        self::assertResponseStatusCodeSame(404);
        self::assertSame('games.invitation.no_invitation_found (404 Not Found)', $crawler->filter('title')->text('', true));
    }

    /**
     * @dataProvider provide http methods
     * @group functional
     */
    public function test token with wrong length returns native 404(string $method): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client);

        $crawler = $client->request($method, '/fr/games/invitation/0');

        self::assertResponseStatusCodeSame(404);
        self::assertSame("No route found for \"{$method} http://localhost/fr/games/invitation/0\" (404 Not Found)", $crawler->filter('title')->text('', true));
    }

    public function provide http methods(): \Generator
    {
        yield 'GET' => ['GET'];
        yield 'POST' => ['POST'];
    }

    /**
     * @group functional
     */
    public function test basic form with existing token renders necessary fields(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client);

        $client->request('GET', '/fr/games/invitation/'.GamesInvitationsFixtures::DEFAULT_INVITATION_TOKEN);

        self::assertSelectorExists('i.fa-exclamation-triangle', 'Warning message is missing in form');
        self::assertSelectorExists('button#accept_game_invitation_accept', 'Accept button is missing in form');
    }

    /**
     * @group functional
     */
    public function test submitting form invites character(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client);

        /** @var Connection $cn */
        $cn = self::getContainer()->get(Connection::class);

        $client->request('GET', '/fr/games/invitation/'.GamesInvitationsFixtures::DEFAULT_INVITATION_TOKEN);

        self::assertSelectorExists('i.fa-exclamation-triangle', 'Warning message is missing in form');

        $client->submitForm('Accepter l\'invitation');

        self::assertResponseStatusCodeSame(302, 'Accepting invitation does not seem to redirect');
        self::assertResponseRedirects('/fr/games/'.GamesFixtures::ID_WITH_INVITATIONS);

        $gameId = $cn->fetchOne('select c.game_id from characters as c where c.name_slug = "invited-character";');

        self::assertNotEmpty($gameId);
        self::assertSame((string) GamesFixtures::ID_WITH_INVITATIONS, (string) $gameId);
    }

    /**
     * @group functional
     */
    public function test accepting invitation removes all other invitations for same character(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client);

        /** @var Connection $cn */
        $cn = self::getContainer()->get(Connection::class);

        $characterId = $cn->fetchOne('select c.id from characters as c where c.name_slug = "invited-character";');

        self::assertNotNull($characterId);
        self::assertNotFalse($characterId);

        $cn->insert('game_invitations', [
            'token' => TokenGenerator::generateToken(),
            'character_id' => $characterId,
            'game_id' => GamesFixtures::ID_EMPTY,
        ]);
        $cn->insert('game_invitations', [
            'token' => TokenGenerator::generateToken(),
            'character_id' => $characterId,
            'game_id' => GamesFixtures::ID_WITH_CHARACTER,
        ]);

        $client->request('GET', '/fr/games/invitation/'.GamesInvitationsFixtures::DEFAULT_INVITATION_TOKEN);

        self::assertSelectorExists('i.fa-exclamation-triangle', 'Warning message is missing in form');

        $client->submitForm('Accepter l\'invitation');

        self::assertResponseStatusCodeSame(302, 'Accepting invitation does not seem to redirect');
        self::assertResponseRedirects('/fr/games/'.GamesFixtures::ID_WITH_INVITATIONS);

        $gamesCount = $cn->fetchOne('select count(gi.id) as games_count from game_invitations as gi');

        self::assertSame('0', $gamesCount);
    }
}
