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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\Security;
use User\Entity\User;
use User\Form\Type\ResettingFormType;
use User\Mailer\UserMailer;
use User\Repository\UserRepository;
use User\Util\TokenGenerator;

class ResettingController extends AbstractController
{
    private UserRepository $userRepository;
    private UserMailer $mailer;
    private RoleHierarchyInterface $roleHierarchy;
    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct(
        UserRepository $userRepository,
        UserMailer $mailer,
        UserPasswordHasherInterface $passwordEncoder,
        RoleHierarchyInterface $roleHierarchy
    ) {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
        $this->roleHierarchy = $roleHierarchy;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/resetting/request", name="user_resetting_request", methods={"GET"})
     */
    public function requestAction(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('user/Resetting/request.html.twig');
    }

    /**
     * @Route("/resetting/send-email", name="user_resetting_send_email", methods={"POST"})
     */
    public function sendEmailAction(Request $request): ?RedirectResponse
    {
        $username = $request->request->get('username');

        $user = $this->userRepository->findByUsernameOrEmail($username);

        if (null !== $user && $response = $this->sendEmailToUser($user)) {
            return $response;
        }

        $this->addFlash('user_success', 'resetting.check_email');

        return new RedirectResponse($this->generateUrl('user_login'));
    }

    /**
     * @Route("/resetting/reset/{token}", name="user_resetting_reset", methods={"GET", "POST"})
     */
    public function resetAction(Request $request, $token)
    {
        $user = $this->userRepository->findOneByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(\sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
        }

        $form = $this->createForm(ResettingFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setConfirmationToken(null);
            $user->setPassword($this->passwordEncoder->hashPassword($user, $form->get('plainPassword')->getData()));
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('user_success', 'resetting.flash.success');
            $request->getSession()->set(Security::LAST_USERNAME, $user->getUsername());

            return new RedirectResponse($this->generateUrl('user_login'));
        }

        return $this->render('user/Resetting/reset.html.twig', [
            'token' => $token,
            'form' => $form->createView(),
        ]);
    }

    private function sendEmailToUser(User $user): ?RedirectResponse
    {
        /** @deprecated @todo */
        $userRoles = $this->roleHierarchy->getReachableRoleNames($user->getRoles());

        if (\in_array('ROLE_VISITOR', $userRoles, true)) {
            return null;
        }

        if (null === $user->getConfirmationToken()) {
            $user->setConfirmationToken(TokenGenerator::generateToken());
        }

        try {
            $this->mailer->sendResettingEmailMessage($user);
            $this->getDoctrine()->getManager()->flush();
        } catch (\Exception $e) {
            $this->addFlash('user_error', 'resetting.email_failed');

            return new RedirectResponse($this->generateUrl('user_resetting_request'));
        }

        return null;
    }
}
