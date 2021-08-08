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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CharacterViewController implements PublicService
{
    private CharactersRepository $charactersRepository;
    private Environment $twig;

    public function __construct(CharactersRepository $charactersRepository, Environment $twig)
    {
        $this->charactersRepository = $charactersRepository;
        $this->twig = $twig;
    }

    /**
     * @Route("/characters/{id}-{nameSlug}",
     *     name="corahnrin_characters_view",
     *     requirements={"id" = "\d+", "nameSlug" = "^[\w-]+$"},
     *     methods={"GET"}
     * )
     */
    public function __invoke(string $id, string $nameSlug): Response
    {
        $character = $this->charactersRepository->findForView($id, $nameSlug);

        if (!$character) {
            throw new NotFoundHttpException('Character not found.');
        }

        return new Response($this->twig->render('corahn_rin/character_view/view.html.twig', ['character' => $character]));
    }
}
