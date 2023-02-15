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

use EsterenMaps\Controller\MapApiController;
use Main\Controller\RootController;
use Symfony\Component\Routing\Loader\Configurator\ImportConfigurator;
use Symfony\Component\Routing\Loader\Configurator\RouteConfigurator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/*
 * Closures $_route and $_import are here to provide defaults.
 * These are necessary for prefixing and for locale.
 */
return static function (RoutingConfigurator $routes, Kernel $kernel) {
    $projectDir = dirname(__DIR__);

    $environment = $kernel->getEnvironment();

    $routes
        ->add('root', '/')
        ->controller(RootController::class)
    ;

    $_route = static function (string $name, string $path) use ($routes, $environment): RouteConfigurator {
        return $routes
            ->add($name, $path)
            ->defaults(['_locale' => '%locale%'])
            ->requirements(['_locale' => '^(?:%locales_regex%)$'])
            ->schemes('prod' === $environment ? ['https'] : ['http', 'https'])
        ;
    };

    $_import = static function (string $resource, string $type = 'annotation') use ($routes, $environment): ImportConfigurator {
        return $routes
            ->import($resource, $type)
            ->defaults(['_locale' => '%locale%'])
            ->requirements(['_locale' => '^(?:%locales_regex%)$'])
            ->schemes('prod' === $environment ? ['https'] : ['http', 'https'])
        ;
    };

    $_import($projectDir.'/src/Main/Controller/AssetsController.php')
        ->prefix('/{_locale}', false)
    ;

    $_route('user_login_check', '/{_locale}/login_check')
        ->methods(['POST'])
    ;

    $_route('user_logout', '/{_locale}/logout')
        ->methods(['GET', 'POST'])
    ;

    $_import($projectDir.'/src/User/Controller/')
        ->prefix('/{_locale}', false)
    ;

    $_import($projectDir.'/src/CorahnRin/Controller/')
        ->prefix('/{_locale}', false)
    ;

    $_import('@PierstovalCharacterManagerBundle/Resources/config/routing.xml', 'xml')
        ->prefix('/{_locale}/character', false)
    ;

    $_route('map_api', '/{_locale}/api/maps/{id}')
        ->methods(['GET'])
        ->requirements(['id' => '\d+'])
        ->controller(MapApiController::class)
    ;

    $_import($projectDir.'/src/Admin/Controller/')
        ->prefix('/{_locale}/admin', false)
    ;

    $_import($projectDir.'/src/Main/Controller/')
        ->prefix('/{_locale}/', false)
    ;

    return $routes;
};
