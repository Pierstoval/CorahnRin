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
use DataFixtures\CorahnRin\GamesFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Tests\GetHttpClientTestTrait;

class GameViewControllerTest extends WebTestCase
{
    use GetHttpClientTestTrait;

    /**
     * @group functional
     */
    public function test invalid game shows 404(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'game-master');

        $client->request('GET', '/fr/games/000');

        static::assertResponseStatusCodeSame(404);
    }

    /**
     * @group functional
     */
    public function test empty campaign shows no character(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'game-master');

        $client->request('GET', '/fr/games/'.GamesFixtures::ID_EMPTY);

        static::assertSelectorTextSame('#game_characters_list tr td', 'Aucun personnage');
    }

    /**
     * @group functional
     */
    public function test campaign with character shows its name(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'game-master');

        $client->request('GET', '/fr/games/'.GamesFixtures::ID_WITH_CHARACTER);

        static::assertSelectorTextSame('h1', 'Campaign with a character');
        static::assertSelectorTextSame('h2', 'Personnages');
        static::assertSelectorTextSame('#game_characters_list tr td', 'Character inside a game');
    }

    /**
     * @group functional
     */
    public function test campaign invitations shows characters names(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'game-master');

        $client->request('GET', '/fr/games/'.GamesFixtures::ID_WITH_INVITATIONS);

        static::assertSelectorTextSame('h1', 'Campaign with invitations');
        static::assertSelectorTextSame('h2', 'Personnages');

        static::assertSelectorTextSame('#game_invitations_list tr td', 'Invited character');
    }

    /**
     * @group functional
     */
    public function test invitation from game view sends email(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'game-master');

        $crawler = $client->request('GET', '/fr/games/'.GamesFixtures::ID_EMPTY);

        static::assertResponseStatusCodeSame(200);
        static::assertSelectorTextSame('h1', 'Empty campaign');

        $formNode = $crawler->filter('form[name="character_invitation"]');

        // Position 1 must be "character to invite". Update it if characters fixtures change.
        $charNode = $formNode->filter('select#character_invitation option')->eq(1);
        static::assertSame('Character to invite', $charNode->text('', true));

        $client->enableProfiler();
        $client->submit($formNode->form(), [
            'character_invitation' => [
                $charNode->attr('value'),
            ],
        ]);

        // Assert form is correctly submitted and redirects (no redirection = form errors)
        static::assertResponseRedirects();
        $location = $client->getResponse()->headers->get('Location');
        static::assertMatchesRegularExpression('~^/fr/games/\d+$~', $location);

        // Assert flash message is added to session
        $flashes = static::$container->get(SessionInterface::class)->getFlashBag()->peekAll();
        static::assertArrayHasKey('success', $flashes);
        static::assertSame(['games.invite_characters.success'], $flashes['success']);

        // Assert there's 1 invitation in the db
        /** @var GameInvitationRepository $invitationsRepo */
        $invitationsRepo = static::$container->get(GameInvitationRepository::class);
        $invitations = $invitationsRepo->findAll();
        // One existing invitation in tests, + 1 here
        static::assertCount(2, $invitations);
    }

    /**
     * @group functional
     */
    public function test invitation for already invited character shows error(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'game-master');

        $crawler = $client->request('GET', '/fr/games/'.GamesFixtures::ID_WITH_INVITATIONS);

        static::assertResponseStatusCodeSame(200);
        static::assertSelectorTextSame('h1', 'Campaign with invitations');

        $formNode = $crawler->filter('form[name="character_invitation"]');

        // This position must be "Invited character". Update it if characters fixtures change.
        $charNode = $formNode->filter('select#character_invitation option')->eq(4);
        static::assertSame('Invited character', $charNode->text('', true));

        $crawler = $client->submit($formNode->form(), [
            'character_invitation' => [
                $charNode->attr('value'),
            ],
        ]);

        // Assert form is correctly submitted and redirects (no redirection = form errors)
        static::assertResponseStatusCodeSame(200);
        $formNode = $crawler->filter('form[name="character_invitation"]');
        $errors = $formNode->filter('.card-panel.red');

        static::assertCount(1, $errors);

        static::assertSame('Le personnage "Invited character" a déjà été invité à cette campagne.', $errors->text('', true));
    }
}
