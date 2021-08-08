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
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

require \dirname(__DIR__).'/vendor/autoload.php';

$input = new ArgvInput();
$output = new ConsoleOutput();
$io = new SymfonyStyle($input, $output);

try {
    if (\file_exists(__DIR__.'/creds.json')) {
        ['username' => $username, 'password' => $password] = \json_decode(\file_get_contents(__DIR__.'/creds.json'), true, 512, \JSON_THROW_ON_ERROR);
    } else {
        $username = $_ENV['MAPS_USERNAME'] ?? $_SERVER['MAPS_USERNAME'] ?? \getenv('MAPS_USERNAME');
        $password = $_ENV['MAPS_PASSWORD'] ?? $_SERVER['MAPS_PASSWORD'] ?? \getenv('MAPS_PASSWORD');

        if (!$username || !$password) {
            $io->error([
                <<<'ERR'
                You must specify a username & password for the Esteren Maps application
                either via the "creds.json" file (with only "username" and "password" keys and values)
                or via the MAPS_USERNAME and MAPS_PASSWORD environment variables.
                ERR,
            ]);

            exit(1);
        }
    }

    $baseUri = 'https://maps.esteren.org';

    $browser = new HttpBrowser();

    $crawler = $browser->request('GET', $baseUri.'/fr/map-tri-kazel');
    $response = $browser->getResponse();

    // 401 means login form.
    if (401 !== $response->getStatusCode()) {
        throw new \RuntimeException(\sprintf(
            'Expected a 401 HTTP response, got "%s" instead.',
            $response->getStatusCode()
        ));
    }

    $csrfToken = $crawler->filter('input[name="_csrf_token"]')->attr('value');

    $crawler = $browser->request('POST', $baseUri.'/fr/login_check', [
        '_csrf_token' => $csrfToken,
        '_username_or_email' => $username,
        '_password' => $password,
    ]);
    $response = $browser->getResponse();

    $flash = $crawler->filter('#flash-messages');
    if ($flash && $flash->count()) {
        throw new RuntimeException($flash->text(null, true));
    }

    $browser->request('GET', 'https://maps.esteren.org/fr/api/maps/1');
    $json = \json_decode($browser->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);

    unset(
        $json['templates'],
        $json['map']['routes'],
        $json['map']['markers'],
    );
    $json['references'] = [
        'zones_types' => $json['references']['zones_types'],
    ];

    $jsonString = \json_encode($json, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE);

    if (!\file_put_contents(\dirname(__DIR__).'/data/map_data.json', $jsonString)) {
        throw new \RuntimeException('Could not write maps data to file.');
    }

    $io->success('Done!');
} catch (Exception $e) {
    do {
        $io->error($e->getMessage());
        if ($output->isVerbose()) {
            $io->error($e->getTrace());
        }
    } while ($e = $e->getPrevious());

    exit(1);
}
