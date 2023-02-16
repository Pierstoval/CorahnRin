<?php

namespace Admin\Controller;

use CorahnRin\Data\OghamType;
use CorahnRin\DTO\Admin\OghamAdminDTO;
use CorahnRin\Entity\Ogham;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Protung\EasyAdminPlusBundle\Controller\BaseCrudDtoController;

class OghamCrudController extends BaseCrudDtoController
{
    /**
     * @param OghamAdminDTO $dto
     */
    public function createEntityFromDto(object $dto): object|null
    {
        return Ogham::fromAdmin($dto);
    }

    /**
     * @param Ogham $entity
     */
    public function createDtoFromEntity(object $entity): object
    {
        return OghamAdminDTO::createFromEntity($entity);
    }

    /**
     * @param Ogham $entity
     * @param OghamAdminDTO $dto
     */
    public function updateEntityFromDto(object $entity, object $dto): void
    {
        $entity->updateFromAdmin($dto);
    }

    public static function getEntityFqcn(): string
    {
        return Ogham::class;
    }

    public static function getDtoFqcn(): string
    {
        return OghamAdminDTO::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'name', 'description', 'type'])
            ->setPaginatorPageSize(50)
            ->overrideTemplate('layout', 'easy_admin/layout.html.twig')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name', 'admin.entities.common.name');
        $description = TextareaField::new('description', 'admin.entities.common.description');
        $type = ChoiceField::new('type')->setFormTypeOption('choices', OghamType::ALL);
        $book = AssociationField::new('book', 'admin.entities.common.book');
        $id = IntegerField::new('id', 'admin.entities.common.id');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name, $description, $book];
        }

        if (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $name, $description, $type, $book];
        }

        if (Crud::PAGE_NEW === $pageName) {
            return [$name, $description, $type, $book];
        }

        if (Crud::PAGE_EDIT === $pageName) {
            return [$name, $description, $type, $book];
        }
    }
}
