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

namespace CorahnRin\Controller\Game;

use CorahnRin\Mailer\CampaignInvitationMailer;
use CorahnRin\Repository\GameInvitationRepository;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use User\Entity\User;

class ResendGameInvitationEmailController implements PublicService
{
    private CampaignInvitationMailer $mailer;
    private UrlGeneratorInterface $router;
    private Security $security;
    private GameInvitationRepository $invitationRepository;

    public function __construct(
        CampaignInvitationMailer $mailer,
        UrlGeneratorInterface $router,
        Security $security,
        GameInvitationRepository $invitationRepository
    ) {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->security = $security;
        $this->invitationRepository = $invitationRepository;
    }

    /**
     * @Route("/games/invitation/{token}/send-email", name="game_resend_invitation_email", methods={"GET"}, requirements={"token" = "[a-zA-Z0-9_-]{43}"})
     */
    public function resendInvitationEmail(string $token, Session $session, Request $request): RedirectResponse
    {
        $invitation = $this->invitationRepository->findToResendEmail($token);

        if (!$invitation) {
            throw new NotFoundHttpException('games.invitation.no_invitation_found');
        }

        /** @var User $user */
        $user = $this->security->getUser();

        if ($user->getId() !== $invitation->getGameMasterId()) {
            throw new AccessDeniedException('games.invitation.only_game_master_can_resend_email');
        }

        $this->mailer->sendInvitationMail($invitation, $request->getHost());

        $session->getFlashBag()->add('success', 'games.invitation.email_has_been_resent');

        return new RedirectResponse($this->router->generate('game_view', ['id' => $invitation->getGameId()]));
    }
}
