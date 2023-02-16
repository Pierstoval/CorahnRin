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
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Environment;
use User\Entity\User;

class MyCharactersController implements PublicService
{
    private CharactersRepository $charactersRepository;
    private Environment $twig;
    private Security $security;

    public function __construct(CharactersRepository $charactersRepository, Environment $twig, Security $security)
    {
        $this->charactersRepository = $charactersRepository;
        $this->twig = $twig;
        $this->security = $security;
    }

    /**
     * @Route("/characters/me", name="corahnrin_my_characters", methods={"GET"})
     */
    public function __invoke(Request $request): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $characters = $this->charactersRepository->findForUser($currentUser);

        return new Response($this->twig->render('corahn_rin/character_view/my_list.html.twig', [
            'characters' => $characters,
        ]));
    }
}
