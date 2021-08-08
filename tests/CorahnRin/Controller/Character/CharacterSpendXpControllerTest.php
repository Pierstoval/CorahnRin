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
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\GetHttpClientTestTrait;

class CharacterSpendXpControllerTest extends WebTestCase
{
    use GetHttpClientTestTrait;

    /**
     * @group functional
     */
    public function test no experience spent shows message(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'lambda-user');

        $this->submitXpForm($client, []);

        static::assertResponseStatusCodeSame(200);
        static::assertSelectorTextContains(
            'form[name="character_spend_xp"] .card-panel.red',
            'Vous n\'avez pas dépensé d\'expérience... 😟 Avez-vous oublié de sélectionner vos bonus ?'
        );
    }

    /**
     * @group functional
     */
    public function test spend too much xp returns smirky error(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'lambda-user');

        $this->submitXpForm($client, [
            '[speed]' => 5,
            '[defense]' => 10,
            '[domains][craft]' => 5,
            '[domains][closeCombat]' => 5,
            '[domains][stealth]' => 5,
            '[domains][magience]' => 5,
            '[domains][naturalEnvironment]' => 5,
        ]);

        static::assertResponseStatusCodeSame(200);
        static::assertSelectorTextContains(
            'form[name="character_spend_xp"] .card-panel.red',
            '😏'
        );
    }

    /**
     * @group functional
     */
    public function test spend too much xp for disciplines returns smirky error(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'lambda-user');

        $this->submitXpForm($client, [
            '[disciplines][occultism][0][score]' => 10, // Combat artifact
            '[disciplines][occultism][1][score]' => 10, // Esoterism
        ]);

        static::assertResponseStatusCodeSame(200);
        static::assertSelectorTextContains(
            'form[name="character_spend_xp"] .card-panel.red',
            '😏'
        );
    }

    /**
     * @group functional
     */
    public function test spend xp on discipline which domain is less than 5 returns error(): void
    {
        //character_spend_xp[disciplines][craft][0][score]
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'lambda-user');

        $this->submitXpForm($client, [
            '[disciplines][craft][0][score]' => 10, // Jewellery
        ]);

        static::assertResponseStatusCodeSame(200);
        static::assertSelectorTextContains(
            'form[name="character_spend_xp"] .card-panel.red',
            'Vous avez tenté d\'acheter une discipline pour un domaine qui n\'a qu\'un score de 3. Un score de domaine de 5 minimum est obligatoire pour pouvoir acheter une discipline.'
        );
    }

    /**
     * @dataProvider provide discipline scores out of range
     * @group functional
     */
    public function test spend xp on discipline with score out of range returns error(int $score): void
    {
        //character_spend_xp[disciplines][craft][0][score]
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'lambda-user');

        $this->submitXpForm($client, [
            '[disciplines][occultism][0][score]' => $score, // Combat artifact
        ]);

        static::assertResponseStatusCodeSame(200);
        static::assertSelectorTextContains(
            'form[name="character_spend_xp"] .card-panel.red',
            'Le score de discipline doit être compris entre 0 et 10. Veuillez vérifier les valeurs. Ou ne tentez pas de tricher.'
        );
    }

    /**
     * @group functional
     */
    public function test give lesser values for domain shows form error(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'lambda-user');

        // Check DataFixtures\CorahnRin\CharactersFixtures::getDefaults() for base values
        $this->submitXpForm($client, [
            '[domains][craft]' => 0,
            '[domains][closeCombat]' => 0,
            '[domains][stealth]' => 0,
            '[domains][naturalEnvironment]' => 0,
        ]);

        static::assertResponseStatusCodeSame(200);
        $errors = $client->getCrawler()->filter('.card-panel.red');
        static::assertCount(4, $errors);
        static::assertSame('Cette valeur doit être supérieure ou égale à 3.', \trim($errors->getNode(0)->textContent));
        static::assertSame('Cette valeur doit être supérieure ou égale à 1.', \trim($errors->getNode(1)->textContent));
        static::assertSame('Cette valeur doit être supérieure ou égale à 3.', \trim($errors->getNode(2)->textContent));
        static::assertSame('Cette valeur doit être supérieure ou égale à 1.', \trim($errors->getNode(3)->textContent));
    }

    /**
     * @group functional
     */
    public function test spend all xp on speed and defense(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'lambda-user');

        $this->submitXpForm($client, [
            '[speed]' => 5,
            '[defense]' => 10,
        ]);

        static::assertResponseRedirects('/fr/characters/4-invited-character', 302);
        $client->followRedirect();

        static::assertSelectorTextContains(
            '#flash-messages',
            'Vous avez dépensé avec succès 350 points d\'expérience !'
        );

        /** @var Character $character */
        $character = static::$container->get(CharactersRepository::class)->findOneBy(['nameSlug' => 'invited-character']);
        static::assertInstanceOf(Character::class, $character);
        // Remember: remaining XP for this char is 500.
        static::assertSame(350, $character->getExperienceSpent());
        static::assertSame(150, $character->getExperienceActual());
        static::assertSame(5, $character->getSpeedBonus());
        static::assertSame(10, $character->getDefenseBonus());
    }

    public function provide discipline scores out of range(): ?\Generator
    {
        yield [-1 => -1];
        yield [11 => 11];
    }

    public function test spending xp to buy ogham(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'lambda-user');

        $this->submitXpForm($client, [
            '[ogham][0]' => 1, // First element: key 0 with Ogham ID = 1
        ], '6-character-to-spend-ogham-with');

        static::assertResponseRedirects('/fr/characters/6-character-to-spend-ogham-with', 302);
        $client->followRedirect();

        static::assertSelectorTextContains(
            '#flash-messages',
            'Vous avez dépensé avec succès 5 points d\'expérience !'
        );

        /** @var Character $character */
        $character = static::$container->get(CharactersRepository::class)->findOneBy(['nameSlug' => 'character-to-spend-ogham-with']);
        static::assertInstanceOf(Character::class, $character);
        // Remember: remaining XP for this char is 500.
        static::assertSame(5, $character->getExperienceSpent());
        static::assertSame(495, $character->getExperienceActual());

        $ogham = $character->getOgham();
        if ($ogham instanceof Collection) {
            $ogham = $ogham->toArray();
        }
        static::assertCount(1, $ogham);
        static::assertSame(1, \current($ogham)->getId());
    }

    public function test spending xp to buy miracles(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'lambda-user');

        $this->submitXpForm($client, [
            '[miracles][minor][0]' => 1, // First element: key 0 with Ogham ID = 1
        ], '7-character-to-spend-miracles-with');

        static::assertResponseRedirects('/fr/characters/7-character-to-spend-miracles-with', 302);
        $client->followRedirect();

        static::assertSelectorTextContains(
            '#flash-messages',
            'Vous avez dépensé avec succès 5 points d\'expérience !'
        );

        /** @var Character $character */
        $character = static::$container->get(CharactersRepository::class)->findOneBy(['nameSlug' => 'character-to-spend-miracles-with']);
        static::assertInstanceOf(Character::class, $character);
        // Remember: remaining XP for this char is 500.
        static::assertSame(5, $character->getExperienceSpent());
        static::assertSame(495, $character->getExperienceActual());

        $miracles = $character->getMiracles();
        if ($miracles instanceof Collection) {
            $miracles = $miracles->toArray();
        }
        static::assertCount(1, $miracles);
        static::assertSame(1, \current($miracles)->getMiracleId());
    }

    private function submitXpForm(KernelBrowser $client, array $formData, string $characterSlug = '4-invited-character'): void
    {
        $client->request('GET', \sprintf('/fr/characters/%s/spend-xp', $characterSlug));

        static::assertResponseStatusCodeSame(200);

        $form = $client->getCrawler()->filter('form[name="character_spend_xp"]');

        static::assertCount(1, $form, 'No spend xp form found');

        $form = $form->form();
        foreach ($formData as $key => $value) {
            $form['character_spend_xp'.$key]->setValue((string) $value);
        }

        $client->submit($form);
    }
}
