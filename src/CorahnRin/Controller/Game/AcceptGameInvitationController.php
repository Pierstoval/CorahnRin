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

use CorahnRin\Form\AcceptGameInvitationType;
use CorahnRin\Form\Handler\AcceptGameInvitationHandler;
use CorahnRin\Repository\GameInvitationRepository;
use Main\DependencyInjection\PublicService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class AcceptGameInvitationController implements PublicService
{
    private GameInvitationRepository $invitationRepository;
    private Environment $twig;
    private UrlGeneratorInterface $router;
    private FormFactoryInterface $formFactory;
    private AcceptGameInvitationHandler $acceptGameInvitationHandler;

    public function __construct(
        GameInvitationRepository $invitationRepository,
        Environment $twig,
        UrlGeneratorInterface $router,
        FormFactoryInterface $formFactory,
        AcceptGameInvitationHandler $acceptGameInvitationHandler
    ) {
        $this->invitationRepository = $invitationRepository;
        $this->twig = $twig;
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->acceptGameInvitationHandler = $acceptGameInvitationHandler;
    }

    /**
     * @Route("/games/invitation/{token}", name="game_invitation_accept", requirements={"token" = "[a-zA-Z0-9_-]{43}"}, methods={"GET", "POST"})
     */
    public function __invoke(string $token, Request $request, Session $session): Response
    {
        $invitation = $this->invitationRepository->findForAccept($token);

        if (!$invitation) {
            throw new NotFoundHttpException('games.invitation.no_invitation_found');
        }

        $form = $this->formFactory->create(AcceptGameInvitationType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game = $this->acceptGameInvitationHandler->handle($invitation);

            $session->getFlashBag()->add('success', 'games.mail.invitation.form.success');

            return new RedirectResponse($this->router->generate('game_view', ['id' => $game->getId()]));
        }

        return new Response($this->twig->render('corahn_rin/games/accept_invitation.html.twig', [
            'invitation' => $invitation,
            'form' => $form->createView(),
        ]));
    }
}
