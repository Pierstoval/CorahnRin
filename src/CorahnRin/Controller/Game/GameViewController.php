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

use CorahnRin\Entity\Game;
use CorahnRin\Form\CharacterInvitationType;
use CorahnRin\Form\Handler\InviteCharactersToGameHandler;
use CorahnRin\Repository\GameInvitationRepository;
use CorahnRin\Repository\GameRepository;
use CorahnRin\Security\GameViewVoter;
use Main\DependencyInjection\PublicService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;

class GameViewController implements PublicService
{
    private Environment $twig;
    private FormFactoryInterface $formFactory;
    private GameInvitationRepository $invitationRepository;
    private GameRepository $gameRepository;
    private InviteCharactersToGameHandler $formHandler;
    private UrlGeneratorInterface $router;
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(
        Environment $twig,
        FormFactoryInterface $formFactory,
        GameInvitationRepository $invitationRepository,
        GameRepository $gameRepository,
        InviteCharactersToGameHandler $formHandler,
        AuthorizationCheckerInterface $authorizationChecker,
        UrlGeneratorInterface $router
    ) {
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->invitationRepository = $invitationRepository;
        $this->gameRepository = $gameRepository;
        $this->formHandler = $formHandler;
        $this->router = $router;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @Route("/games/{id}", name="game_view", methods={"GET", "POST"}, requirements={"id" = "\d+"})
     */
    public function __invoke(string $id, Request $request, Session $session): Response
    {
        if (!$game = $this->gameRepository->findForView((int) $id)) {
            throw new NotFoundHttpException('Game not found.');
        }

        if (!$this->authorizationChecker->isGranted(GameViewVoter::MANAGE_GAME, $game)) {
            return new Response($this->twig->render('corahn_rin/games/view_game_player.html.twig', [
                'game' => $game,
            ]));
        }

        if (($formResult = $this->handleInvitationForm($game, $request, $session)) instanceof Response) {
            return $formResult;
        }

        return new Response($this->twig->render('corahn_rin/games/view_game_gm.html.twig', [
            'game' => $game,
            'invite_characters_form' => $formResult->createView(),
            'invitations' => $this->invitationRepository->findForGame($game),
        ]));
    }

    /**
     * @return FormInterface|RedirectResponse
     */
    private function handleInvitationForm(Game $game, Request $request, Session $session)
    {
        $form = $this->formFactory->create(CharacterInvitationType::class, null, ['game' => $game])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $characters = $form->getData();
            $this->formHandler->handle($characters, $game, $request->getHost());
            $session->getFlashBag()->add('success', 'games.invite_characters.success');

            return new RedirectResponse($this->router->generate('game_view', ['id' => $game->getId()]));
        }

        return $form;
    }
}
