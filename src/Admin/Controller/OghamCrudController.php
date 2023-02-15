<?php

namespace App\Admin\Controller;

use CorahnRin\Entity\Ogham;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OghamCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ogham::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'name', 'description', 'type'])
            ->setPaginatorPageSize(50)
            ->overrideTemplate('layout', 'easy_admin/layout.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name', 'admin.entities.common.name');
        $description = TextareaField::new('description', 'admin.entities.common.description');
        $type = TextareaField::new('type');
        $book = AssociationField::new('book', 'admin.entities.common.book');
        $id = IntegerField::new('id', 'admin.entities.common.id');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name, $description, $book];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $name, $description, $type, $book];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$name, $description, $type, $book];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$name, $description, $type, $book];
        }
    }
}
