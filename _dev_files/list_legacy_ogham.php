<?php

use Doctrine\DBAL\Connection;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->bootEnv(\dirname(__DIR__).'/.env');

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

/** @var Connection $conn */
$conn = $kernel->getContainer()->get('doctrine.dbal.legacy_connection');

$stmt = $conn->query('select char_content from est_characters;');
$chars = $stmt->fetchAll();

$oghamList = [];

foreach ($chars as $content) {
    $content = json_decode($content['char_content'], true, 512, JSON_THROW_ON_ERROR);
    foreach ($content['ogham'] as $ogham) {
        if (!$ogham) {
            continue;
        }
        $oghamList[] = $ogham;
    }
}

$oghamList = array_unique(array_map('trim', $oghamList));
sort($oghamList);

var_export($oghamList);
