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

namespace User\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Translation\TranslatorInterface;
use User\Document\User;
use User\Form\Type\ChangePasswordFormType;

class ChangePasswordController extends AbstractController
{
    private UserPasswordHasherInterface $passwordEncoder;
    private TranslatorInterface $translator;

    public function __construct(UserPasswordHasherInterface $passwordEncoder, TranslatorInterface $translator)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->translator = $translator;
    }

    /**
     * @Route("/profile/change-password", name="user_change_password", methods={"GET", "POST"})
     */
    public function changePasswordAction(Request $request): Response
    {
        if ($this->isGranted('ROLE_VISITOR')) {
            throw $this->createNotFoundException();
        }

        $user = $this->getUser();
        if (!\is_object($user) || !$user instanceof User) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->createForm(ChangePasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPlainPassword()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', $this->translator->trans('change_password.flash.success', [], 'user'));

            return $this->redirectToRoute('user_profile_edit');
        }

        return $this->render('user/ChangePassword/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
