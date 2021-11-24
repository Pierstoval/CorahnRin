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

use CorahnRin\Data\Orientation;

class Step10OrientationTest extends AbstractStepTest
{
    /**
     * @group functional
     */
    public function testWaysDependency(): void
    {
        $client = $this->getHttpClient();

        $client->request('GET', '/fr/character/generate/'.$this->getStepName());

        self::assertSame(302, $client->getResponse()->getStatusCode());
        self::assertTrue($client->getResponse()->isRedirect('/fr/character/generate'));
    }

    /**
     * @group functional
     */
    public function testValidInstinctiveOrientation(): void
    {
        $result = $this->submitAction([
            '08_ways' => [
                'ways.combativeness' => 5,
                'ways.creativity' => 4,
                'ways.empathy' => 3,
                'ways.reason' => 2,
                'ways.conviction' => 1,
            ],
        ]);

        self::assertSame(302, $result->getResponse()->getStatusCode());
        self::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/11_advantages'));
        self::assertSame(Orientation::INSTINCTIVE, $result->getSession()->get('character.corahn_rin')[$this->getStepName()]);
    }

    /**
     * @group functional
     */
    public function testValidRationalOrientation(): void
    {
        $result = $this->submitAction([
            '08_ways' => [
                'ways.combativeness' => 1,
                'ways.creativity' => 2,
                'ways.empathy' => 3,
                'ways.reason' => 4,
                'ways.conviction' => 5,
            ],
        ]);

        self::assertSame(302, $result->getResponse()->getStatusCode());
        self::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/11_advantages'));
        self::assertSame(Orientation::RATIONAL, $result->getSession()->get('character.corahn_rin')[$this->getStepName()]);
    }

    /**
     * @group functional
     */
    public function testValidManualInstinctiveOrientation(): void
    {
        $result = $this->submitAction([
            '08_ways' => [
                'ways.combativeness' => 3,
                'ways.creativity' => 4,
                'ways.empathy' => 1,
                'ways.reason' => 4,
                'ways.conviction' => 3,
            ],
        ], $values = [
            'gen-div-choice' => Orientation::INSTINCTIVE,
        ]);

        self::assertSame(302, $result->getResponse()->getStatusCode());
        self::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/11_advantages'));
        self::assertSame(Orientation::INSTINCTIVE, $result->getSession()->get('character.corahn_rin')[$this->getStepName()]);
    }

    /**
     * @group functional
     */
    public function testValidManualRationalOrientation(): void
    {
        $result = $this->submitAction([
            '08_ways' => [
                'ways.combativeness' => 3,
                'ways.creativity' => 4,
                'ways.empathy' => 1,
                'ways.reason' => 4,
                'ways.conviction' => 3,
            ],
        ], $values = [
            'gen-div-choice' => Orientation::RATIONAL,
        ]);

        self::assertSame(302, $result->getResponse()->getStatusCode());
        self::assertTrue($result->getResponse()->isRedirect('/fr/character/generate/11_advantages'));
        self::assertSame(Orientation::RATIONAL, $result->getSession()->get('character.corahn_rin')[$this->getStepName()]);
    }

    /**
     * @group functional
     */
    public function testInvalidManualOrientation(): void
    {
        $result = $this->submitAction([
            '08_ways' => [
                'ways.combativeness' => 3,
                'ways.creativity' => 4,
                'ways.empathy' => 1,
                'ways.reason' => 4,
                'ways.conviction' => 3,
            ],
        ], $values = [
            'gen-div-choice' => 'INVALID',
        ]);

        self::assertSame(200, $result->getResponse()->getStatusCode());
        self::assertCount(1, $result->getCrawler()->filter('#flash-messages > .card-panel.error'));
        self::assertEquals('L\'orientation de la personnalité est incorrecte, veuillez vérifier.', $result->getCrawler()->filter('#flash-messages > .card-panel.error')->text('', true));
    }
}
