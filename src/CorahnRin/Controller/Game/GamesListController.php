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

use CorahnRin\Repository\GameInvitationRepository;
use CorahnRin\Repository\GameRepository;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Environment;
use User\Entity\User;

class GamesListController implements PublicService
{
    private $security;
    private $gameRepository;
    private $twig;
    private $invitationRepository;

    public function __construct(
        Security $security,
        Environment $twig,
        GameRepository $gameRepository,
        GameInvitationRepository $invitationRepository
    ) {
        $this->security = $security;
        $this->gameRepository = $gameRepository;
        $this->twig = $twig;
        $this->invitationRepository = $invitationRepository;
    }

    /**
     * @Route("/games", name="my_games", methods={"GET"})
     */
    public function __invoke(): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $gamesAsGameMaster = $this->gameRepository->findAsGameMaster($user);
        $gamesAsPlayer = $this->gameRepository->findAsPlayer($user);
        $gameInvitations = $this->invitationRepository->findForPlayer($user);

        return new Response($this->twig->render('corahn_rin/games/my_games.html.twig', [
            'games_as_game_master' => $gamesAsGameMaster,
            'games_as_player' => $gamesAsPlayer,
            'games_invitations' => $gameInvitations,
        ]));
    }
}
