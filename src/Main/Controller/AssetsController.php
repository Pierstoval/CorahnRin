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

namespace Main\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssetsController extends AbstractController
{
    /**
     * @Route("/js/translations", name="pierstoval_tools_assets_jstranslations", methods={"GET"})
     */
    public function __invoke(string $_locale): Response
    {
        $response = new Response();
        $response->setCache([
            'max_age' => 600,
            's_maxage' => 3600,
            'public' => true,
        ]);

        $response->headers->add(['Content-type' => 'application/javascript']);

        return $this->render('assets/js_translations.js.twig', [
            'locale' => $_locale,
        ], $response);
    }
}
