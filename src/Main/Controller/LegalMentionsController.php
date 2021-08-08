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

class LegalMentionsController extends AbstractController
{
    /**
     * @Route("/legal", name="legal_mentions", methods={"GET"})
     */
    public function legalMentionsAction(string $_locale): Response
    {
        $response = new Response();

        $response->setCache([
            'max_age' => 600,
            's_maxage' => 3600,
            'public' => $this->getUser() ? false : true,
        ]);

        if ('fr' !== $_locale) {
            throw $this->createNotFoundException();
        }

        return $this->render('agate/legal/mentions_'.$_locale.'.html.twig', [], $response);
    }
}
