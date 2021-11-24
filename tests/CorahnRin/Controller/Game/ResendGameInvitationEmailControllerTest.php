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
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Tests\GetHttpClientTestTrait;
use User\Util\TokenGenerator;

class ResendGameInvitationEmailControllerTest extends WebTestCase
{
    use GetHttpClientTestTrait;

    /**
     * @group functional
     */
    public function test inexistent token returns custom 404(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client);

        $crawler = $client->request('GET', '/fr/games/invitation/'.TokenGenerator::generateToken().'/send-email');

        self::assertResponseStatusCodeSame(404);
        self::assertSame(
            'games.invitation.no_invitation_found (404 Not Found)',
            $crawler->filter('title')->text('', true)
        );
    }

    /**
     * @group functional
     */
    public function test token with wrong length returns native 404(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client);

        $crawler = $client->request('GET', '/fr/games/invitation/0/send-email');

        self::assertResponseStatusCodeSame(404);
        self::assertSame(
            'No route found for "GET http://localhost/fr/games/invitation/0/send-email" (404 Not Found)',
            $crawler->filter('title')->text('', true)
        );
    }

    /**
     * @dataProvider provide tokens
     * @group functional
     */
    public function test not logged in returns 401(string $token): void
    {
        $client = $this->getHttpClient();

        $client->request('GET', '/fr/games/invitation/'.$token.'/send-email');

        self::assertResponseStatusCodeSame(401);
    }

    public function provide tokens(): \Generator
    {
        yield 'random token' => [TokenGenerator::generateToken()];
        yield 'default fixture token' => [GamesInvitationsFixtures::DEFAULT_INVITATION_TOKEN];
    }

    /**
     * @group functional
     */
    public function test not game master returns 403(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client);

        $client->request('GET', '/fr/games/invitation/'.GamesInvitationsFixtures::DEFAULT_INVITATION_TOKEN.'/send-email');

        self::assertResponseStatusCodeSame(403);
        self::assertSelectorTextSame('title', 'games.invitation.only_game_master_can_resend_email (403 Forbidden)');
    }

    /**
     * @group functional
     */
    public function test action with default invitation sends email(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'game-master');

        $client->enableProfiler();
        $client->request('GET', '/fr/games/invitation/'.GamesInvitationsFixtures::DEFAULT_INVITATION_TOKEN.'/send-email');

        self::assertResponseRedirects('/fr/games/'.GamesFixtures::ID_WITH_INVITATIONS);
    }

    /**
     * @group functional
     */
    public function test action with default invitation shows flash message(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'game-master');

        $client->request('GET', '/fr/games/invitation/'.GamesInvitationsFixtures::DEFAULT_INVITATION_TOKEN.'/send-email');

        self::assertResponseRedirects('/fr/games/'.GamesFixtures::ID_WITH_INVITATIONS);

        $flashes = self::getContainer()->get(SessionInterface::class)->getFlashBag()->peekAll();
        self::assertArrayHasKey('success', $flashes);
        self::assertSame(['games.invitation.email_has_been_resent'], $flashes['success']);
    }
}
