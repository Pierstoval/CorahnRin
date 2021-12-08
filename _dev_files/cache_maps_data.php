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

use EsterenMaps\Repository\MapsRepository;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return static function (): void {
    $input = new ArgvInput();
    $output = new ConsoleOutput();
    $io = new SymfonyStyle($input, $output);

    $kernel = new Kernel('dev', true);
    $kernel->boot();

    /** @var MapsRepository $mapsRepository */
    $mapsRepository = $kernel->getContainer()->get(MapsRepository::class);

    $mapsRepository->getMap();

    $io->success('Done!');
};
