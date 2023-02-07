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

namespace Admin\Controller;

use Admin\DependencyInjection\AdminRouteParamPass;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController implements PublicService
{
    /**
     * The "easyadmin_entities_list_regex" param is defined in the AdminRouteParamPass compiler pass.
     *
     * @see AdminRouteParamPass
     *
     * @Route(
     *     "/{entity}/{action}/{id}",
     *     name="easyadmin",
     *     methods={"GET", "POST", "DELETE"},
     *     defaults={
     *         "entity" = null,
     *         "action" = null,
     *         "id" = null
     *     }
     * )
     */
    public function indexAction(Request $request, string $entity = null, string $action = null, string $id = null): RedirectResponse|Response
    {
        if (!$id && \in_array($action, ['delete', 'show', 'edit'], true)) {
            throw $this->createNotFoundException('An id must be specified for this action.');
        }

        return new Response('TODO: remove this');
    }

    protected function redirectToBackendHomepage(): RedirectResponse|Response
    {
        return $this->render('easy_admin/backend_homepage.html.twig');
    }
}
