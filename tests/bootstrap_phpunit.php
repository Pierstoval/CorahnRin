<?php

declare(strict_types=1);

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require dirname(__DIR__).'/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

echo "\n[Test bootstrap] Bootstraping test suite...";

(new Dotenv())->bootEnv(\dirname(__DIR__).'/.env');

if (function_exists('xdebug_set_filter')) {
    echo "\n[Test bootstrap] Xdebug enabled, activate coverage whitelist filter...";
    xdebug_set_filter(
        constant('XDEBUG_FILTER_CODE_COVERAGE'),
        constant('XDEBUG_PATH_INCLUDE'),
        [
            dirname(__DIR__).'/src/',
        ]
    );
}

echo "\n[Test bootstrap] Done!\n";
