<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/15/16
 * Time: 11:49 AM
 */

namespace Dayspring\LoginBundle\Security;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class UsernamePasswordAuthenticator extends AbstractFormLoginAuthenticator
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
            ->generate('_login');
    }

    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->container->get('router')
            ->generate('account_dashboard');
    }

    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/_login_check') {
            return;
        }

        $username = $request->request->get('_username');
        $request->getSession()->set(Security::LAST_USERNAME, $username);
        $password = $request->request->get('_password');

        return array(
            'username' => $username,
            'password' => $password
        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['username'];

        return $userProvider->loadUserByUsername($username);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $plainPassword = $credentials['password'];

        $encoder = $this->container->get('security.password_encoder');

        if (!$encoder->isPasswordValid($user, $plainPassword)) {
            // throw any AuthenticationException
            throw new BadCredentialsException();
        }

        return true;
    }
}
