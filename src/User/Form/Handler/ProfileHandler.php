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

namespace User\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ProfileHandler
{
    private $em;
    private $router;
    private $translator;

    /**
     * @var null|Request
     */
    private $request;

    public function __construct(EntityManagerInterface $em, RouterInterface $router, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->router = $router;
        $this->translator = $translator;
    }

    public function handle(Request $request, FormInterface $editProfileForm): ?Response
    {
        $this->request = $request;

        if ($result = $this->handleEditProfileForm($editProfileForm)) {
            return $this->returnResponse($result);
        }

        return null;
    }

    private function returnResponse(Response $response): Response
    {
        $this->request = null;

        return $response;
    }

    private function handleEditProfileForm(FormInterface $form): ?Response
    {
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();

            $this->addFlash('success', $this->translator->trans('profile.flash.updated', [], 'user'));

            return new RedirectResponse($this->router->generate('user_profile_edit').'#edit_profile');
        }

        return null;
    }

    private function addFlash(string $type, string $message): void
    {
        $session = $this->request->getSession();

        if ($session instanceof Session) {
            $session->getFlashBag()->add($type, $message);
        }
    }
}
