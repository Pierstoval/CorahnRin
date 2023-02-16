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

namespace User\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use User\Entity\User;
use User\Repository\UserRepository;

final class FormLoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const USERNAME_OR_EMAIL_FORM_FIELD = '_username_or_email';
    public const PASSWORD_FORM_FIELD = '_password';

    private const PROVIDER_KEY = 'main'; // Firewall name

    private const LOGIN_ROUTE = 'user_login';

    private const NO_REFERER_ROUTES = [
        self::LOGIN_ROUTE,
        'user_login_check',
        'user_register',
        'user_logout',
        'user_check_email',
        'user_registration_confirm',
        'user_registration_confirmed',
        'user_resetting_request',
        'user_resetting_send_email',
        'user_resetting_check_email',
        'user_resetting_reset',
        'user_change_password',
        'pierstoval_tools_assets_jstranslations',
    ];

    private HttpKernelInterface $httpKernel;
    private HttpUtils $httpUtils;
    private RouterInterface $router;
    private UserPasswordHasherInterface $encoder;
    private UserRepository $userRepository;

    public function __construct(
        HttpKernelInterface $kernel,
        HttpUtils $httpUtils,
        RouterInterface $router,
        UserPasswordHasherInterface $encoder,
        UserRepository $userRepository,
    ) {
        $this->httpKernel = $kernel;
        $this->httpUtils = $httpUtils;
        $this->router = $router;
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        if (\in_array($request->attributes->get('_route'), static::NO_REFERER_ROUTES, true)) {
            $this->removeTargetPath($request->getSession(), static::PROVIDER_KEY);
        } elseif (!$this->getTargetPath($request->getSession(), static::PROVIDER_KEY)) {
            $this->saveTargetPath($request->getSession(), static::PROVIDER_KEY, $request->getUri());
        }

        // Forward the request to the login controller, to avoid too many redirections.
        $subRequest = $this->httpUtils->createRequest($request, $this->getLoginUrl($request));

        $response = $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        if (200 === $response->getStatusCode()) {
            $response->setStatusCode(401);
        }

        return $response;
    }

    public function supports(Request $request): bool
    {
        return
            $request->isMethod('POST')
            && $request->request->has(self::USERNAME_OR_EMAIL_FORM_FIELD)
            && $request->request->has(self::PASSWORD_FORM_FIELD)
            && 'user_login_check' === $request->attributes->get('_route')
        ;
    }

    public function getCredentials(Request $request): UsernamePasswordCredentials
    {
        $usernameOrEmail = $request->request->get(self::USERNAME_OR_EMAIL_FORM_FIELD);
        $request->getSession()->set(Security::LAST_USERNAME, $usernameOrEmail);
        $password = $request->request->get(self::PASSWORD_FORM_FIELD);

        return UsernamePasswordCredentials::create(
            $usernameOrEmail,
            $password
        );
    }

    public function getUser(UsernamePasswordCredentials $credentials): User
    {
        try {
            $user = $this->userRepository->loadUserByUsername($credentials->getUsernameOrEmail());
        } catch (UserNotFoundException $e) {
            throw new BadCredentialsException('security.bad_credentials');
        }

        return $user;
    }

    public function checkCredentials(UsernamePasswordCredentials $credentials, User $user): bool
    {
        if (!$this->encoder->isPasswordValid($user, $credentials->getPassword())) {
            throw new BadCredentialsException('security.bad_credentials');
        }

        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): RedirectResponse
    {
        $session = $request->getSession();

        $targetPath = $this->getTargetPath($session, $firewallName);

        if (!$targetPath) {
            $targetPath = \rtrim($this->router->generate('root'), '/').'/'.$request->getLocale();
        }

        // Make sure username is not stored for next login
        $session->remove(Security::LAST_USERNAME);
        $this->removeTargetPath($session, $firewallName);

        return new RedirectResponse($targetPath);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);
    }

    public function supportsRememberMe(): bool
    {
        return true;
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $credentials = $this->getCredentials($request);
        $user = $this->getUser($credentials);

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier(), fn () => $user));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate(self::LOGIN_ROUTE);
    }
}
