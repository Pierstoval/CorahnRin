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

use CorahnRin\Repository\GameInvitationRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Tests\GetHttpClientTestTrait;

class CreateGameControllerTest extends WebTestCase
{
    use GetHttpClientTestTrait;

    /**
     * @group functional
     */
    public function test game creation creates invitation and sends email(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client);

        $client->request('GET', '/fr/games/create');

        self::assertResponseStatusCodeSame(200);

        $client->enableProfiler();
        $client->submitForm('Créer la campagne', [
            'create_game[name]' => 'test_name',
            'create_game[summary]' => 'dummy summary',
            'create_game[charactersToInvite]' => [2], // 2 = id of "bloated characters",
        ]);

        // Assert form is correctly submitted and redirects (no redirection = form errors)
        self::assertResponseRedirects();
        $location = $client->getResponse()->headers->get('Location');
        static::assertMatchesRegularExpression('~^/fr/games/\d+$~', $location);

        // Assert flash message is added to session
        $flashes = self::getContainer()->get(SessionInterface::class)->getFlashBag()->peekAll();
        static::assertArrayHasKey('success', $flashes);
        static::assertSame(['games.create.success_message'], $flashes['success']);

        // Assert there's 1 invitation in the db
        /** @var GameInvitationRepository $invitationsRepo */
        $invitationsRepo = self::getContainer()->get(GameInvitationRepository::class);
        $invitations = $invitationsRepo->findAll();
        // One existing invitation in tests, + 1 here
        static::assertCount(2, $invitations);
    }
}
