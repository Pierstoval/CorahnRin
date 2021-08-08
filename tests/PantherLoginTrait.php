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

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use PHPUnit\Framework\Assert;
use Symfony\Component\Panther\Client;

trait PantherLoginTrait
{
    protected function loginAs(
        Client $client,
        string $username,
        string $plainPassword
    ): void {
        $crawler = $client->request('GET', '/fr/login');

        Assert::assertSame(200, $client->getInternalResponse()->getStatusCode());

        $driver = $client->getWebDriver();

        // Check login in main app
        try {
            $driver->wait(2, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('#profile_link')));

            return;
        } catch (NoSuchElementException $e) {
        }

        // Check login in backoffice
        try {
            $driver->wait(2, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('.user-menu-wrapper .user-name')));

            @\trigger_error('Already logged in with backoffice.', \E_USER_DEPRECATED);

            return;
        } catch (NoSuchElementException $e) {
        }

        $host = \parse_url($driver->getCurrentURL(), \PHP_URL_HOST);

        if ('back.esteren.docker' === $host) {
            $formSelector = '.login-wrapper form[method="post"]';
        } else {
            $formSelector = '#form_login';
        }

        $driver->wait(2, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector($formSelector)));

        $form = $crawler->filter($formSelector)->form();

        $client->submit($form, [
            '_username_or_email' => $username,
            '_password' => $plainPassword,
        ]);

        $crawler = $client->request('GET', '/fr/profile');

        Assert::assertSame('Modifier mon profil', $crawler->filter('h1')->text('', true));
    }
}
