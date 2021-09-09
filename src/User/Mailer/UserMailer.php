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

namespace User\Mailer;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use User\Document\User;

final class UserMailer
{
    private Environment $twig;
    private MailerInterface $mailer;
    private RouterInterface $router;
    private string $sender;
    private TranslatorInterface $translator;

    public function __construct(RequestStack $requestStack, MailerInterface $mailer, Environment $twig, RouterInterface $router, TranslatorInterface $translator)
    {
        $this->sender = 'no-reply@'.($requestStack->getMainRequest() ? $requestStack->getMainRequest()->getHost() : 'studio-agate.com');
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->router = $router;
        $this->translator = $translator;
    }

    public function sendRegistrationEmail(User $user): void
    {
        $url = $this->router->generate('user_registration_confirm', ['token' => $user->getConfirmationToken()], UrlGeneratorInterface::ABSOLUTE_URL);

        $rendered = $this->twig->render('user/Registration/email.html.twig', [
            'user' => $user,
            'confirmationUrl' => $url,
        ]);

        $message = new Email();

        $message
            ->subject($this->translator->trans('registration.email.subject', ['%username%' => $user->getUsername()], 'user'))
            ->from($this->sender)
            ->to($user->getEmail())
            ->html($rendered)
            ->text(\strip_tags($rendered))
        ;

        $this->mailer->send($message);
    }

    public function sendResettingEmailMessage(User $user): void
    {
        $url = $this->router->generate('user_resetting_reset', ['token' => $user->getConfirmationToken()], UrlGeneratorInterface::ABSOLUTE_URL);

        $rendered = $this->twig->render('user/Resetting/email.html.twig', [
            'user' => $user,
            'confirmationUrl' => $url,
        ]);

        $message = new Email();

        $message
            ->subject($this->translator->trans('resetting.email.subject', ['%username%' => $user->getUsername()], 'user'))
            ->from($this->sender)
            ->to(new Address($user->getEmail(), $user->getUsername()))
            ->html($rendered)
            ->text(\strip_tags($rendered))
        ;

        $this->mailer->send($message);
    }
}
