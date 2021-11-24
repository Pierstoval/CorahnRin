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

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Tests\GetHttpClientTestTrait;

class CharacterPdfControllerTest extends WebTestCase
{
    use GetHttpClientTestTrait;

    /**
     * @group functional
     */
    public function test Steren Slaine pdf(): void
    {
        $client = $this->getHttpClient();

        $client->request('GET', '/fr/characters/1-steren-slaine/print');

        self::assertResponseStatusCodeSame(200, 'Failed to generate a PDF for current character.');

        self::assertInstanceOf(BinaryFileResponse::class, $client->getResponse());
    }
}
