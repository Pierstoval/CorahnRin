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

namespace User\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use User\Entity\User;
use User\Mailer\UserMailer;
use User\Util\TokenGenerator;

class UserRegistrator
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordEncoder,
        private readonly EntityManagerInterface $em,
        private readonly TranslatorInterface $translator,
        private readonly UserMailer $mailer
    ) {
    }

    public function registerNewUser(User $user, Session $session): void
    {
        $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPlainPassword()));
        $user->setConfirmationToken(TokenGenerator::generateToken());
        $user->setEmailConfirmed(false);

        $this->em->persist($user);
        $this->em->flush();

        $this->mailer->sendRegistrationEmail($user);

        $session->getFlashBag()->add('success', $this->translator->trans('registration.confirmed', ['%username%' => $user->getUsername()], 'user'));

        $session->set(Security::LAST_USERNAME, $user->getUsername());
    }
}
