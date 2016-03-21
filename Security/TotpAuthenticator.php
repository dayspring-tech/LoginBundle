<?php
/**
 * Created by PhpStorm.
 * User: jeffreywong
 * Date: 3/20/16
 * Time: 9:31 PM
 */

namespace Dayspring\LoginBundle\Security;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class TotpAuthenticator extends AbstractFormLoginAuthenticator
{

    protected $container;

    /**
     * UsernamePasswordAuthenticator constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function getLoginUrl()
    {
        return $this->container->get('router')
            ->generate('_totp_login');
    }

    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->container->get('router')
            ->generate('demo_secure');
    }

    /**
     * Intercept login check request and get the MFA token from the request.
     * Check if user has MFA configured but has not provided their token.
     *
     * @param Request $request
     * @return array|void
     */
    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() == '/totp_login') {
            return;
        }

        $token = $this->container->get('security.token_storage')->getToken();

        if ($request->getPathInfo() == '/_totp_login_check') {
            $oneTime = $request->request->get('_one_time');

            return array(
                'user' => $token ? $token->getUser() : null,
                'one_time' => $oneTime
            );
        }

        if (null !== $token && $token->isAuthenticated()) {
            $user = $token->getUser();

            if ($user->hasTotp() && !$token instanceof TotpToken) {
                $e = new AuthenticationException("Enter your MFA token.");
                $e->setToken($token);
                throw $e;
            }
        }

        return;
    }

    /**
     * Refresh user found in the username/password token.
     *
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $this->container->get('logger')->debug("getUser", $credentials);
        if (null === $credentials['user']) {
            throw new UsernameNotFoundException();
        }
        return $userProvider->refreshUser($credentials['user']);
    }

    /**
     * Check the user's MFA token against their active secrets
     *
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $this->container->get('logger')->debug("checkCredentials", $credentials);

        $oneTime = $credentials['one_time'];
        $user = $credentials['user'];

        $helper = $this->container->get('dayspring_login.totp_authenticator_helper');

        if (!$helper->checkUserCodes($user, $oneTime)) {
            // throw any AuthenticationException
            $e = new AuthenticationException("Invalid token");
            throw $e;
        }

        return true;
    }

    /**
     * Override default behavior to set the token back into token storage so that the user is still partially logged in
     * for the TOTP prompt.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return RedirectResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $tokenStorage = $this->container->get('security.token_storage');
        $tokenStorage->setToken($exception->getToken());

        $this->container->get('logger')->debug("onAuthenticationFailure setting token");

        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        $url = $this->getLoginUrl();

        return new RedirectResponse($url);
    }


    /**
     * Create a TotpToken which identifies that the user provided their MFA token.
     *
     * @param UserInterface $user
     * @param string        $providerKey
     *
     * @return TotpToken
     */
    public function createAuthenticatedToken(UserInterface $user, $providerKey)
    {
        return new TotpToken(
            $user,
            $providerKey,
            $user->getRoles()
        );
    }

}