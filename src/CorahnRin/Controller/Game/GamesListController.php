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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;
use User\Entity\User;

class GamesListController implements PublicService
{
    public function __construct(
        private readonly TokenStorageInterface $security,
        private readonly Environment $twig,
        private readonly GameRepository $gameRepository,
        private readonly GameInvitationRepository $invitationRepository
    ) {
    }

    /**
     * @Route("/games", name="my_games", methods={"GET"})
     */
    public function __invoke(): Response
    {
        /** @var User $user */
        $user = $this->security->getToken()->getUser();

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
