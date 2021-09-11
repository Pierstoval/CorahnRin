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
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
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

        self::setToken($client, $user, $user->getRoles());
    }

    protected function getHttpClient(): KernelBrowser
    {
        if (!$this instanceof KernelTestCase) {
            throw new \RuntimeException(\sprintf('Test case must extend %s to use Kernel features', KernelTestCase::class));
        }

        /** @var KernelBrowser $client */
        return self::createClient();
        // Disable reboot, allows client to be reused for other requests.
//        $client->disableReboot();
    }

    protected static function setToken(KernelBrowser $client, UserInterface $user, array $roles = ['ROLE_USER']): void
    {
        if (!\is_a(self::class, KernelTestCase::class, true)) {
            throw new \RuntimeException(\sprintf('Test case must extend %s to use Kernel features', KernelTestCase::class));
        }

        $firewallName = 'main';

        $session = $client->getContainer()->get('session');

        $token = new PostAuthenticationGuardToken($user, $firewallName, $roles);
        self::getContainer()->get(AuthenticationManagerInterface::class)->authenticate($token);

        $session->set('_security_'.$firewallName, \serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }
}
