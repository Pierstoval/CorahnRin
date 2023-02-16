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

use CorahnRin\DTO\CreateGameDTO;
use CorahnRin\Form\CreateGameType;
use CorahnRin\Form\Handler\CreateGameHandler;
use Main\DependencyInjection\PublicService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use User\Entity\User;

class CreateGameController implements PublicService
{
    private FormFactoryInterface $formFactory;
    private UrlGeneratorInterface $router;
    private Environment $twig;
    private Security $security;
    private CreateGameHandler $createGameHandler;

    public function __construct(
        FormFactoryInterface $formFactory,
        Security $security,
        CreateGameHandler $createGameHandler,
        Environment $twig,
        UrlGeneratorInterface $router
    ) {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->twig = $twig;
        $this->security = $security;
        $this->createGameHandler = $createGameHandler;
    }

    /**
     * @Route("/games/create", name="create_game", methods={"GET", "POST"})
     */
    public function create(Request $request, Session $session): Response
    {
        $gameDto = new CreateGameDTO();
        $form = $this->formFactory->create(CreateGameType::class, $gameDto)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->security->getUser();

            $game = $this->createGameHandler->handle($gameDto, $user, $request->getHost());

            $session->getFlashBag()->add('success', 'games.create.success_message');

            return new RedirectResponse($this->router->generate('game_view', ['id' => $game->getId()]));
        }

        return new Response($this->twig->render('corahn_rin/games/create.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}
