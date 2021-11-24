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

class Step06AgeTest extends AbstractStepTest
{
    /**
     * @group functional
     */
    public function testValidAge(): void
    {
        $result = $this->submitAction([], [
            'age' => 16,
        ]);

        self::assertSame(302, $result->getResponse()->getStatusCode());
        self::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/07_setbacks'));
        self::assertSame([$this->getStepName() => 16], $result->getSession()->get('character.corahn_rin'));
    }

    /**
     * @group functional
     */
    public function testInvalidAge(): void
    {
        $result = $this->submitAction([], [
            'age' => 0,
        ]);

        $crawler = $result->getCrawler();

        self::assertSame(200, $result->getResponse()->getStatusCode());
        self::assertCount(1, $crawler->filter('#flash-messages > .card-panel.error'));
        self::assertEquals('L\'âge doit être compris entre 16 et 35 ans.', $crawler->filter('#flash-messages > .card-panel.error')->text('', true));
    }
}
