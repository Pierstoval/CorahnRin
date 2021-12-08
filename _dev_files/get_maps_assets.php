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

const BASE_URL = 'https://esterenmaps.pierstoval.com';

define('ROOT_DIR', dirname(__DIR__));

require ROOT_DIR.'/vendor/autoload.php';

require __DIR__.'/_login_to_esteren_maps.php';

$input = new ArgvInput();
$output = new ConsoleOutput();
$io = new SymfonyStyle($input, $output);

try {
    $browser = login($io);

    $browser->request('GET', BASE_URL.'/build/entrypoints.json');
    $json = json_decode($browser->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);

    $jsFiles = $json['entrypoints']['maps']['js'];
    $cssFiles = $json['entrypoints']['maps_styles']['css'];

    foreach ($jsFiles as $jsFile) {
        $browser->request('GET', BASE_URL.$jsFile);
        file_put_contents(ROOT_DIR.'/public'.$jsFile, $browser->getResponse()->getContent());
    }
    foreach ($cssFiles as $cssFile) {
        $browser->request('GET', BASE_URL.$cssFile);
        file_put_contents(ROOT_DIR.'/public'.$cssFile, $browser->getResponse()->getContent());
    }

    $entrypointsFile = ROOT_DIR.'/public/build/entrypoints.json';
    $entrypointsJson = json_decode(file_get_contents($entrypointsFile), true, 512, \JSON_THROW_ON_ERROR);
    $entrypointsJson['entrypoints']['maps']['js'] = $jsFiles;
    $entrypointsJson['entrypoints']['maps_styles']['css'] = $cssFiles;
    file_put_contents($entrypointsFile, json_encode($entrypointsJson, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_LINE_TERMINATORS));

    //$manifestFile = ROOT_DIR.'/public/build/manifest.json';
    //$manifest = json_decode(file_get_contents($manifestFile), true, 512, JSON_THROW_ON_ERROR);
    //$manifest['build/maps.js'] = $jsFile;
    //$manifest['build/maps_styles.css'] = $cssFile;
    //file_put_contents($manifestFile, json_encode($manifest, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON8_JSON_UNESCAPED_LINE_TERMINATORS));

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
