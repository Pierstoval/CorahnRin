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

namespace Tests\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\GetHttpClientTestTrait;

class DefaultEasyAdminTest extends WebTestCase
{
    use GetHttpClientTestTrait;

    /**
     * @group functional
     */
    public function test index returns 200 when logged in as admin(): void
    {
        $client = $this->getHttpClient();
        $this->loginAsUser($client, 'standard-admin');
        $crawler = $client->request('GET', '/fr/admin');

        self::assertSame(200, $client->getResponse()->getStatusCode(), \sprintf('Page title: "%s".', $crawler->filter('title')->html()));
        self::assertSame('Studio Agate', $crawler->filter('.content-wrapper h1')->text('', true));
    }
}
