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

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

require dirname(__DIR__).'/vendor/autoload.php';

require __DIR__.'/_login_to_esteren_maps.php';

$input = new ArgvInput();
$output = new ConsoleOutput();
$io = new SymfonyStyle($input, $output);

$browser = login($io);

$browser->request('GET', 'https://esterenmaps.pierstoval.com/fr/api/api/maps/1');

$json = json_decode($browser->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);

unset(
    $json['map']['routes'],
    $json['map']['markers'],
    $json['references']['markers_types'],
    $json['references']['routes_types'],
    $json['references']['transports'],
    $json['templates']['LeafletPopupMarkerEditContent'],
    $json['templates']['LeafletPopupPolylineEditContent'],
    $json['templates']['LeafletPopupPolygonEditContent'],
);

$jsonString = json_encode($json, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE);

if (!file_put_contents(dirname(__DIR__).'/data/map_data.json', $jsonString)) {
    throw new \RuntimeException('Could not write maps data to file.');
}

$io->success('Done!');
