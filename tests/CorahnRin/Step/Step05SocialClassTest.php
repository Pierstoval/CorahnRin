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

class Step05SocialClassTest extends AbstractStepTest
{
    /**
     * @group functional
     */
    public function testValidSocialClass(): void
    {
        $result = $this->submitAction([], [
            'gen-div-choice' => 1,
            'domains' => ['domains.natural_environment', 'domains.perception'],
        ]);

        self::assertSame(302, $result->getResponse()->getStatusCode());
        self::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/06_age'));
        self::assertSame([$this->getStepName() => [
            'id' => 1,
            'domains' => ['domains.natural_environment', 'domains.perception'],
        ]], $result->getSession()->get('character.corahn_rin'));
    }

    /**
     * @group functional
     */
    public function testInvalidSocialClass(): void
    {
        $result = $this->submitAction([], [
            'gen-div-choice' => 0,
        ]);

        $crawler = $result->getCrawler();

        self::assertSame(200, $result->getResponse()->getStatusCode());
        self::assertCount(1, $crawler->filter('#flash-messages > .card-panel.error'));
        self::assertEquals('Veuillez sélectionner une classe sociale valide.', $crawler->filter('#flash-messages > .card-panel.error')->text('', true));
    }

    /**
     * @group functional
     */
    public function testValidSocialClassButNotAssociatedDomains(): void
    {
        $result = $this->submitAction([], [
            'gen-div-choice' => 1,
            'domains' => ['domains.relation', 'domains.erudition'],
        ]);

        $crawler = $result->getCrawler();

        self::assertSame(200, $result->getResponse()->getStatusCode());
        self::assertCount(1, $crawler->filter('#flash-messages > .card-panel.error'));
        self::assertEquals('Les domaines choisis ne sont pas associés à la classe sociale sélectionnée.', $crawler->filter('#flash-messages > .card-panel.error')->text('', true));
    }

    /**
     * @group functional
     */
    public function testValidSocialClassButNotEnoughDomains(): void
    {
        $result = $this->submitAction([], [
            'gen-div-choice' => 1,
            'domains' => ['domains.natural_environment'],
        ]);

        $crawler = $result->getCrawler();

        self::assertSame(200, $result->getResponse()->getStatusCode());
        self::assertCount(1, $crawler->filter('#flash-messages > .card-panel.warning'));
        self::assertEquals('Vous devez choisir 2 domaines pour lesquels vous obtiendrez un bonus de +1. Ces domaines doivent être choisi dans la classe sociale sélectionnée.', $crawler->filter('#flash-messages > .card-panel.warning')->text('', true));
    }
}
