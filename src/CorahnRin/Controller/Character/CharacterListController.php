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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CharacterListController implements PublicService
{
    private CharactersRepository $charactersRepository;
    private Environment $twig;

    public function __construct(CharactersRepository $charactersRepository, Environment $twig)
    {
        $this->charactersRepository = $charactersRepository;
        $this->twig = $twig;
    }

    /**
     * @Route("/characters/", name="corahnrin_characters_list", methods={"GET"})
     */
    public function __invoke(Request $request): Response
    {
        // GET variables used for searching
        $page = (int) $request->query->get('page') ?: 1;
        $searchField = $request->query->get('search_field') ?: 'name';
        $order = \mb_strtolower($request->query->get('order') ?: 'asc');

        $limit = 25;

        if (!\in_array($order, ['desc', 'asc'], true)) {
            throw new BadRequestHttpException('Filter order must be either "desc" or "asc".');
        }

        $countChars = $this->charactersRepository->countSearch($searchField, $order);
        $characters = $this->charactersRepository->findSearch($searchField, $order, $limit, ($page - 1) * $limit);
        $pages = \ceil($countChars / $limit);

        return new Response($this->twig->render('corahn_rin/character_view/list.html.twig', [
            'characters' => $characters,
            'count_chars' => $countChars,
            'count_pages' => $pages,
            'page' => $page,
            'order_swaped' => 'desc' === $order ? 'asc' : 'desc',
            'link_data' => [
                'search_field' => $searchField,
                'order' => $order,
                'page' => $page,
                'limit' => $limit,
            ],
        ]));
    }
}
