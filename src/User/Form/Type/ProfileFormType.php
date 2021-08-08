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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use User\Entity\User;
use User\Util\CanonicalizerTrait;

class ProfileFormType extends AbstractType
{
    use CanonicalizerTrait;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $canonicalizer = \Closure::fromCallable([$this, 'canonicalize']);

        $builder
            ->add('username', TextType::class, [
                'label' => 'form.username',
                'translation_domain' => 'user',
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.email',
                'translation_domain' => 'user',
            ])
            ->add('currentPassword', PasswordType::class, [
                'label' => 'form.current_password',
                'translation_domain' => 'user',
                'mapped' => false,
                'constraints' => new UserPassword(['message' => 'user.current_password.invalid']),
            ])
        ;

        // Form events
        $builder
            ->addEventListener(FormEvents::SUBMIT, static function (FormEvent $event) use ($canonicalizer): void {
                // Canonicalize properties
                /** @var User $user */
                $user = $event->getForm()->getData();
                $user->setUsernameCanonical($canonicalizer($user->getUsername()));
                $user->setEmailCanonical($canonicalizer($user->getEmail()));
            })
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
