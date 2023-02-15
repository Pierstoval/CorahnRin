<?php

namespace App\Admin\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use User\Entity\User;

class UsersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'username', 'usernameCanonical', 'email', 'emailCanonical', 'confirmationToken', 'roles'])
            ->setPaginatorPageSize(50)
            ->overrideTemplate('layout', 'easy_admin/layout.html.twig');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable('edit', 'delete');
    }

    public function configureFields(string $pageName): iterable
    {
        $username = TextField::new('username');
        $email = TextField::new('email');
        $plainPassword = Field::new('plainPassword')->setHelp('admin.entities.users.password_help');
        $usernameCanonical = TextField::new('usernameCanonical');
        $emailCanonical = TextField::new('emailCanonical');
        $password = TextField::new('password');
        $confirmationToken = TextField::new('confirmationToken');
        $roles = ArrayField::new('roles');
        $emailConfirmed = BooleanField::new('emailConfirmed');
        $createdAt = DateTimeField::new('createdAt');
        $updatedAt = DateTimeField::new('updatedAt');
        $id = IntegerField::new('id', 'ID');
        $ululeUsername = TextareaField::new('ululeUsername');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $username, $email, $roles, $emailConfirmed, $createdAt];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $username, $usernameCanonical, $email, $emailCanonical, $roles, $emailConfirmed, $ululeUsername, $createdAt, $updatedAt];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$username, $email, $plainPassword];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$username, $usernameCanonical, $email, $emailCanonical, $password, $confirmationToken, $roles, $emailConfirmed, $createdAt, $updatedAt];
        }
    }
}
