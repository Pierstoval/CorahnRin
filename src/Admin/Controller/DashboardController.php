<?php

namespace App\Admin\Controller;

use CorahnRin\Entity\Ogham;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use User\Entity\User;

class DashboardController extends AbstractDashboardController
{
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Studio Agate');
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->overrideTemplate('layout', 'easy_admin/layout.html.twig');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('admin.links.admin_home', 'fas fa-home', 'easyadmin');
        yield MenuItem::linkToRoute('admin.links.back_to_site', 'fas fa-arrow-left', 'corahn_rin_home');

        yield MenuItem::section('admin.menu.users', 'fas fa-folder-open')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class)->setPermission('ROLE_ADMIN');

        yield MenuItem::section('admin.menu.corahn_rin', 'fas fa-folder-open')->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('admin.menu.corahn_rin.ogham', 'fas fa-folder-open', Ogham::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToRoute('admin.menu.corahn_rin.import_character', 'fas fa-file-import', 'admin_legacy_characters')->setPermission('ROLE_ADMIN');
    }
}
