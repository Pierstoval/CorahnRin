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
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ProfileHandler
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly RouterInterface $router,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function handle(Request $request, FormInterface $editProfileForm): ?Response
    {
        if ($result = $this->handleEditProfileForm($editProfileForm, $request)) {
            return $result;
        }

        return null;
    }

    private function handleEditProfileForm(FormInterface $form, Request $request): ?Response
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();

            $request->getSession()->getFlashBag()->add('success', $this->translator->trans('profile.flash.updated', [], 'user'));

            return new RedirectResponse($this->router->generate('user_profile_edit').'#edit_profile');
        }

        return null;
    }
}
