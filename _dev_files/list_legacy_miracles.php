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

use Doctrine\DBAL\Connection;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

/** @var Connection $conn */
$conn = $kernel->getContainer()->get('doctrine.dbal.legacy_connection');

$stmt = $conn->query('select char_content from est_characters;');
$chars = $stmt->fetchAll();

$miracleList = [
    'maj' => [],
    'min' => [],
];

foreach ($chars as $content) {
    $content = json_decode($content['char_content'], true, 512, \JSON_THROW_ON_ERROR);

    get_miracles($miracleList, $content['miracles']['maj'] ?? [], 'maj');
    get_miracles($miracleList, $content['miracles']['min'] ?? [], 'min');
    get_miracles($miracleList, $content['miracles']['majeurs'] ?? [], 'maj');
    get_miracles($miracleList, $content['miracles']['mineurs'] ?? [], 'min');

    $miracleList['maj'] = array_unique($miracleList['maj']);
    $miracleList['min'] = array_unique($miracleList['min']);
    sort($miracleList['maj']);
    sort($miracleList['min']);
}

$formattedList = [];

foreach ($miracleList['min'] as $miracle) {
    $formattedList[] = [
        'name' => $miracle,
        'major' => false,
    ];
}

foreach ($miracleList['maj'] as $miracle) {
    $formattedList[] = [
        'name' => $miracle,
        'major' => true,
    ];
}

var_export($formattedList);

function get_miracles(array &$miraclesList, array $content, string $type): void
{
    foreach ($content as $miracle) {
        $miracle = trim($miracle, " \t\n\r\0\x0B/\\-_.");
        if (!$miracle) {
            continue;
        }
        $miraclesList[$type][] = ucwords(strtolower($miracle));
    }
}
