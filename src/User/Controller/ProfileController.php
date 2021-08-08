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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use User\Entity\User;
use User\Form\Handler\ProfileHandler;
use User\Form\Type\ProfileFormType;

class ProfileController extends AbstractController
{
    private $profileHandler;

    public function __construct(ProfileHandler $profileHandler)
    {
        $this->profileHandler = $profileHandler;
    }

    /**
     * @Route("/profile", name="user_profile_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request): Response
    {
        if ($this->isGranted('ROLE_VISITOR')) {
            throw $this->createNotFoundException();
        }

        $user = $this->getUser();

        if (!\is_object($user) || !$user instanceof User) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $editProfileForm = $this->createForm(ProfileFormType::class, $user);

        if ($response = $this->profileHandler->handle($request, $editProfileForm)) {
            return $response;
        }

        return $this->render('user/Profile/edit.html.twig', [
            'form_edit_profile' => $editProfileForm->createView(),
        ]);
    }
}
