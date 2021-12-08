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

use CorahnRin\Entity\Character;
use CorahnRin\Repository\CharactersRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\GetHttpClientTestTrait;

class CharacterEditControllerTest extends WebTestCase
{
    use GetHttpClientTestTrait;

    /**
     * @group functional
     */
    public function test edit form with valid values(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'lambda-user');

        $client->request('GET', '/fr/characters/4-invited-character');
        $client->clickLink('Modifier');
        self::assertResponseIsSuccessful();
        static::assertSame('/fr/characters/4-invited-character/edit', $client->getRequest()->getPathInfo());

        $client->submit($client->getCrawler()->filter('form[name="character_edit"]')->form(), [
            'character_edit[descriptionAndFacts][description]' => 'New description',
            'character_edit[descriptionAndFacts][story]' => 'New story',
            'character_edit[descriptionAndFacts][notableFacts]' => 'New notable facts',
            'character_edit[inventory][items][0]' => 'New first item',
            'character_edit[inventory][preciousObjects][0]' => 'New first precious item',
        ]);

        self::assertResponseRedirects('/fr/characters/4-invited-character', 302, 'Form values were invalid');
        $client->followRedirect();
        self::assertSelectorTextSame('.card-panel.green', 'Mise à jour réussie !');

        /** @var Character $character */
        $character = self::getContainer()->get(CharactersRepository::class)->findByIdAndSlug(4, 'invited-character');
        static::assertInstanceOf(Character::class, $character);
        static::assertSame('New description', $character->getDescription());
        static::assertSame('New story', $character->getStory());
        static::assertSame('New notable facts', $character->getFacts());
        static::assertSame(['New first item'], $character->getInventory());
        static::assertSame(['New first precious item'], $character->getTreasures());
    }

    /**
     * @group functional
     */
    public function test edit form with blank inventory values triggers 2 form errors(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'lambda-user');

        $client->request('GET', '/fr/characters/4-invited-character');
        $client->clickLink('Modifier');
        self::assertResponseIsSuccessful();
        static::assertSame('/fr/characters/4-invited-character/edit', $client->getRequest()->getPathInfo());

        $client->submit($client->getCrawler()->filter('form[name="character_edit"]')->form(), [
            'character_edit[inventory][items][0]' => '',
            'character_edit[inventory][preciousObjects][0]' => '',
        ]);

        self::assertResponseStatusCodeSame(200, 'Form values were unexpectedly valid');
        $errors = $client->getCrawler()->filter('.card-panel.red');
        static::assertCount(2, $errors, 'Expected 2 errors caused by "Not blank" fields.');
        static::assertSame('Cette valeur ne doit pas être vide.', \trim($errors->getNode(0)->textContent));
        static::assertSame('Cette valeur ne doit pas être vide.', \trim($errors->getNode(1)->textContent));
    }
}
