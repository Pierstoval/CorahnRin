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

namespace CorahnRin\Controller\Admin;

use CorahnRin\Legacy\Repository\LegacyCharacterRepository;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class LegacyCharactersListAdminController implements PublicService
{
    private $twig;
    private $legacyCharacterRepository;

    public function __construct(
        LegacyCharacterRepository $legacyCharacterRepository,
        Environment $twig
    ) {
        $this->twig = $twig;
        $this->legacyCharacterRepository = $legacyCharacterRepository;
    }

    /**
     * @Route("/import-character", name="admin_legacy_characters")
     */
    public function listCharactersToImport(Request $request): Response
    {
        $currentPage = $request->query->getInt('page', 1);

        $paginator = $this->legacyCharacterRepository->paginateLegacyCharactersForAdminList($currentPage);

        return new Response($this->twig->render('corahn_rin/admin/characters_to_import_list.html.twig', [
            'legacy_characters_paginator' => $paginator,
        ]));
    }
}
