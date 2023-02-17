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

namespace Tests\Admin;

use Admin\Controller\DashboardController;
use Protung\EasyAdminPlusBundle\Test\Controller\DashboardControllerTestCase;

class DashboardAdminTest extends DashboardControllerTestCase
{
    use AdminLoginTrait;

    protected function getDashboardControllerFqcn(): string
    {
        return DashboardController::class;
    }

    public function testMenu(): void
    {
        $this->assertMenu([
            'Accueil de l\'administration' => 'http://localhost/fr/admin?routeName=admin',
            'Retour au site' => 'http://localhost/fr/admin?routeName=corahn_rin_home',
            'Gestion des utilisateurs' => null,
            'Utilisateurs' => 'http://localhost/fr/admin?crudAction=index&crudControllerFqcn=Admin%5CController%5CUsersCrudController',
            'Corahn-Rin' => null,
            'Ogham' => 'http://localhost/fr/admin?crudAction=index&crudControllerFqcn=Admin%5CController%5COghamCrudController',
            'Importer un personnage' => 'http://localhost/fr/admin?routeName=admin_legacy_characters',
        ]);
    }
}
