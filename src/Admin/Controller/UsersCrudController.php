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

namespace Admin\Controller;

use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use User\Entity\User;
use User\Mailer\UserMailer;
use User\Util\Canonicalizer;
use User\Util\TokenGenerator;

class UsersCrudController extends AbstractCrudController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private UserMailer $mailer,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'username', 'usernameCanonical', 'email', 'emailCanonical', 'confirmationToken', 'roles'])
            ->setPaginatorPageSize(50)
            ->overrideTemplate('layout', 'easy_admin/layout.html.twig')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermission(Action::NEW, 'ROLE_SUPER_ADMIN')
            ->disable('edit', 'delete')
        ;
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $builder = parent::createNewFormBuilder($entityDto, $formOptions, $context);

        $builder
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
                /** @var User $user */
                $user = $event->getForm()->getData();
                $user->setUsernameCanonical(Canonicalizer::urlize((string) $user->getUsername()));
                $user->setEmailCanonical(Canonicalizer::urlize((string) $user->getEmail()));
            })
        ;

        return $builder;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) {
            throw new \RuntimeException('Unexpected non-user error. Maybe the framework is broken :)');
        }

        if (!$hasPassword = $entityInstance->getPlainPassword()) {
            $entityInstance->setConfirmationToken(TokenGenerator::generateToken());
            $entityInstance->setPlainPassword(TokenGenerator::generateToken());
        }
        $entityInstance->setPassword($this->passwordEncoder->hashPassword($entityInstance, $entityInstance->getPlainPassword()));
        $entityInstance->setEmailConfirmed(true);
        $entityInstance->eraseCredentials();

        // Causes the persist + flush
        parent::persistEntity($entityManager, $entityInstance);

        if (!$hasPassword) {
            // With no password, we send a "reset password" email to the user
            $this->mailer->sendResettingEmailMessage($entityInstance);
        }
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
        $emailConfirmed = BooleanField::new('emailConfirmed')->renderAsSwitch(false);
        $createdAt = DateTimeField::new('createdAt');
        $updatedAt = DateTimeField::new('updatedAt');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $username, $email, $roles, $emailConfirmed, $createdAt];
        }

        if (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $username, $usernameCanonical, $email, $emailCanonical, $roles, $emailConfirmed, $createdAt, $updatedAt];
        }

        if (Crud::PAGE_NEW === $pageName) {
            return [$username, $email, $plainPassword];
        }

        if (Crud::PAGE_EDIT === $pageName) {
            return [$username, $usernameCanonical, $email, $emailCanonical, $password, $confirmationToken, $roles, $emailConfirmed, $createdAt, $updatedAt];
        }
    }
}
