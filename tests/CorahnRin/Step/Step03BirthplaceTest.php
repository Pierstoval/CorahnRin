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

class Step03BirthplaceTest extends AbstractStepTest
{
    /**
     * @group functional
     */
    public function testValidBirthplace(): void
    {
        $result = $this->submitAction([], [
            'region_value' => 25,
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/04_geo'));
        static::assertSame([$this->getStepName() => 25], $result->getSession()->get('character.corahn_rin'));
    }

    /**
     * @group functional
     */
    public function testInvalidBirthplace(): void
    {
        $result = $this->submitAction([], [
            'region_value' => 0,
        ]);

        $crawler = $result->getCrawler();

        static::assertSame(200, $result->getResponse()->getStatusCode(), $crawler->filter('title')->text('', true));
        static::assertCount(1, $crawler->filter('#flash-messages > .card-panel.error'));
        static::assertEquals('Veuillez choisir une région de naissance correcte.', $crawler->filter('#flash-messages > .card-panel.error')->text('', true));
    }
}
