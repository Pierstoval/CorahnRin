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

namespace Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use User\Repository\UserRepository;

trait GetHttpClientTestTrait
{
    protected function loginAsUser(KernelBrowser $client, string $username = 'lambda-user'): void
    {
        if (!$this instanceof KernelTestCase) {
            throw new \RuntimeException(\sprintf('Test case must extend %s to use Kernel features', KernelTestCase::class));
        }

        /** @var UserRepository $repo */
        $repo = self::getContainer()->get(UserRepository::class);

        $user = $repo->findByUsernameOrEmail($username);

        if (!$user) {
            self::fail(\sprintf('Cannot find user "%s" to log in.', $username));
        }

        $client->loginUser($user);
    }

    protected function getHttpClient(): KernelBrowser
    {
        if (!$this instanceof WebTestCase) {
            throw new \RuntimeException(\sprintf('Test case must extend %s to use Kernel features', WebTestCase::class));
        }

        $server = [];

        $client = self::createClient([], $server);
        // Disable reboot, allows client to be reused for other requests.
        $client->disableReboot();

        return $client;
    }
}
