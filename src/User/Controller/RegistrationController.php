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

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use User\Entity\User;
use User\Form\Handler\UserRegistrator;
use User\Form\Type\RegistrationFormType;
use User\Repository\UserRepository;

class RegistrationController extends AbstractController
{
    use TargetPathTrait;

    private UserRepository $userRepository;
    private UserRegistrator $registrationHandler;
    private EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        UserRegistrator $registrationHandler
    ) {
        $this->userRepository = $userRepository;
        $this->registrationHandler = $registrationHandler;
        $this->em = $em;
    }

    /**
     * @Route("/register", name="user_register", methods={"GET", "POST"})
     */
    public function registerAction(Request $request, Session $session): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('user_profile_edit');
        }

        $redirectUrl = $request->query->get('redirect_url');

        if ($redirectUrl) {
            $this->saveTargetPath($session, 'main', $redirectUrl);
        }

        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user, ['request' => $request]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->registrationHandler->registerNewUser($user, $session);
            } catch (\Exception $e) {
                $form->addError(new FormError($e->getMessage()));
            }

            return $this->redirectToRoute('user_login');
        }

        return $this->render('user/Registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/check-email", name="user_check_email", methods={"GET"})
     */
    public function checkEmailAction(Session $session): RedirectResponse|Response
    {
        $email = $session->get('user_send_confirmation_email/email');

        if (empty($email)) {
            return $this->redirectToRoute('user_register');
        }

        $session->remove('user_send_confirmation_email/email');
        $user = $this->userRepository->findOneByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(\sprintf('The user with email "%s" does not exist', $email));
        }

        return $this->render('user/Registration/check_email.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/register/confirm/{token}", name="user_registration_confirm", requirements={"token" = ".+"}, methods={"GET"})
     */
    public function confirmAction(string $token): RedirectResponse
    {
        /** @var null|User $user */
        $user = $this->userRepository->findOneBy(['confirmationToken' => $token]);

        if (null === $user) {
            return $this->redirectToRoute('user_login');
        }

        $user->setConfirmationToken(null);
        $user->setEmailConfirmed(true);

        $this->em->persist($user);
        $this->em->flush();

        $this->addFlash('user_success', 'registration.confirmed');

        return $this->redirectToRoute('user_login');
    }
}
