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

class Step02JobTest extends AbstractStepTest
{
    /**
     * @group functional
     */
    public function testValidJob(): void
    {
        $result = $this->submitAction([], [
            'job_value' => 1,
        ]);

        static::assertSame(302, $result->getResponse()->getStatusCode());
        static::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/03_birthplace'));
        static::assertSame([$this->getStepName() => 1], $result->getSession()->get('character.corahn_rin'));
    }

    /**
     * @group functional
     */
    public function testInvalidJob(): void
    {
        $result = $this->submitAction([], [
            'job_value' => 0,
        ]);

        $crawler = $result->getCrawler();

        static::assertSame(200, $result->getResponse()->getStatusCode());
        static::assertCount(1, $crawler->filter('#flash-messages > .card-panel.error'));
        static::assertEquals('Veuillez entrer un métier correct.', $crawler->filter('#flash-messages > .card-panel.error')->text('', true));
    }
}
