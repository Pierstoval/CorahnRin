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

namespace CorahnRin\Controller\Character;

use CorahnRin\Repository\CharactersRepository;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;
use User\Entity\User;

class MyCharactersController implements PublicService
{
    public function __construct(
        private readonly CharactersRepository $charactersRepository,
        private readonly Environment $twig,
        private readonly TokenStorageInterface $security,
    ) {
    }

    /**
     * @Route("/characters/me", name="corahnrin_my_characters", methods={"GET"})
     */
    public function __invoke(Request $request): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getToken()->getUser();

        $characters = $this->charactersRepository->findForUser($currentUser);

        return new Response($this->twig->render('corahn_rin/character_view/my_list.html.twig', [
            'characters' => $characters,
        ]));
    }
}
