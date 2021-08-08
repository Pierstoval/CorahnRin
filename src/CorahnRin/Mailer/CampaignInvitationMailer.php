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

namespace CorahnRin\Mailer;

use CorahnRin\Entity\GameInvitation;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class CampaignInvitationMailer
{
    public function __construct(
        private MailerInterface $mailer,
        private TranslatorInterface $translator,
        private Environment $twig,
    ) {
    }

    public function sendInvitationMail(GameInvitation $invitation, string $hostname): bool
    {
        $email = new Email();

        $characterOwner = $invitation->getCharacter()->getUser();

        $email
            ->subject($this->translator->trans('games.mail.invitation.subject', [], 'corahn_rin'))
            ->from(new Address("no-reply@{$hostname}", 'CorahnRin team'))
            ->to(new Address($characterOwner->getEmail(), $characterOwner->getUsername()))
            ->bcc('pierstoval+newportal@gmail.com')
            ->html($html = $this->twig->render('corahn_rin/games/email/invitation_email.html.twig', [
                'invitation' => $invitation,
            ]))
            ->text(\strip_tags($html))
        ;

        try {
            $this->mailer->send($email);

            return true;
        } catch (TransportExceptionInterface $e) {
            return false;
        }
    }
}
