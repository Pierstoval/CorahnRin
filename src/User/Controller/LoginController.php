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
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use User\Document\User;
use User\Security\FormLoginAuthenticator;

class LoginController extends AbstractController
{
    use TargetPathTrait;

    /**
     * @Route("/login", name="user_login", methods={"GET", "POST"})
     */
    public function loginAction(Request $request, Session $session)
    {
        if ($this->getUser() instanceof User) {
            return $this->redirect('/'.$request->getLocale().'/');
        }

        $redirectUrl = $request->query->get('redirect_url');

        if ($redirectUrl) {
            $this->saveTargetPath($request->getSession(), 'main', $redirectUrl);
        }

        $error = null;
        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif ($session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        }

        $template = 'user/Security/login.html.twig';
        if ($error instanceof AuthenticationException) {
            $this->addFlash('error', $error->getMessage() ?: $error->getMessageKey());
        }

        return $this->render($template, [
            'last_username' => $session->get($lastUsernameKey),
            'error' => $error,
            'action' => $this->generateUrl('user_login_check'),
            'csrf_token_intention' => 'authenticate',
            'username_parameter' => FormLoginAuthenticator::USERNAME_OR_EMAIL_FORM_FIELD,
            'password_parameter' => FormLoginAuthenticator::PASSWORD_FORM_FIELD,
        ]);
    }
}
