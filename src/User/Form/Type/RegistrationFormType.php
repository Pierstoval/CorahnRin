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

namespace User\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;
use User\Document\User;
use User\Util\Canonicalizer;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'form.email',
                'translation_domain' => 'user',
            ])
            ->add('username', null, [
                'label' => 'form.username',
                'translation_domain' => 'user',
            ])
            ->add('plainPassword', PasswordType::class, [
                'translation_domain' => 'user',
                'label' => 'form.password',
                'invalid_message' => 'user.password.mismatch',
                'constraints' => [
                    new Constraints\NotBlank(),
                ],
            ])
            ->add('optin', CheckboxType::class, [
                'translation_domain' => 'user',
                'label' => 'registration.optin',
                'mapped' => false,
                'constraints' => [
                    new Constraints\IsTrue(),
                ],
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
                /** @var User $user */
                $user = $event->getForm()->getData();
                $user->setUsername(Canonicalizer::urlize($user->getUsername()));
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => User::class,
            ])
            ->setRequired('request')
            ->setAllowedTypes('request', Request::class)
        ;
    }
}
