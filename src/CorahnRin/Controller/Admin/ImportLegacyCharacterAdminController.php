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

use CorahnRin\Legacy\Exception\LegacyCharacterNotFoundException;
use CorahnRin\Legacy\LegacyCharacterImporter;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ImportLegacyCharacterAdminController implements PublicService
{
    private LegacyCharacterImporter $characterImporter;
    private Environment $twig;
    private UrlGeneratorInterface $router;

    public function __construct(
        LegacyCharacterImporter $characterImporter,
        Environment $twig,
        UrlGeneratorInterface $router
    ) {
        $this->characterImporter = $characterImporter;
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * @Route("/import-character/{id}", name="admin_import_character", requirements={"id" = "\d+"})
     */
    public function listCharactersToImport(string $id, Session $session): Response
    {
        try {
            $character = $this->characterImporter->importCharacterFromId((int) $id);
        } catch (LegacyCharacterNotFoundException $e) {
            $session->getFlashBag()->add('error', 'corahn_rin.admin.legacy_import.character_not_found');

            return new RedirectResponse($this->router->generate('admin_legacy_characters'));
        }

        // TODO: remove this when import process is finally tested
        dd($character);

        return new Response($this->twig->render('corahn_rin/admin/import_character.html.twig'));
    }
}
