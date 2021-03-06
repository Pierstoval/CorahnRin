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

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @throws JsonException
 */
function login(SymfonyStyle $io): HttpBrowser
{
    if (is_file(__DIR__.'/creds.json')) {
        ['username' => $username, 'password' => $password] = json_decode(
            file_get_contents(__DIR__.'/creds.json'),
            true,
            512,
            \JSON_THROW_ON_ERROR
        );
    } else {
        $username = $_ENV['MAPS_USERNAME'] ?? $_SERVER['MAPS_USERNAME'] ?? getenv('MAPS_USERNAME');
        $password = $_ENV['MAPS_PASSWORD'] ?? $_SERVER['MAPS_PASSWORD'] ?? getenv('MAPS_PASSWORD');

        if (!$username || !$password) {
            $io->error(
                [
                    <<<'ERR'
                You must specify a username & password for the Esteren Maps application
                either via the "creds.json" file (with only "username" and "password" keys and values)
                or via the MAPS_USERNAME and MAPS_PASSWORD environment variables.
                ERR,
                ]
            );

            exit(1);
        }
    }

    $baseUri = 'https://esterenmaps.pierstoval.com';

    $browser = new HttpBrowser();

    $browser->request('GET', $baseUri.'/fr/map-tri-kazel');
    $response = $browser->getResponse();

    // 200 means map is visible.
    if (200 !== $response->getStatusCode()) {
        throw new RuntimeException(
            sprintf(
                'Expected a 200 HTTP response, got "%s" instead.',
                $response->getStatusCode()
            )
        );
    }

    return $browser;
}
