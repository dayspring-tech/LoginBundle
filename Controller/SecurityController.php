<?php

namespace Dayspring\LoginBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    protected $authenticationUtils;

    public function __construct(AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils;
    }

    /**
     * @Route("/login", name="_login")
     */
    public function loginAction()
    {
        return $this->render('@DayspringLogin/Security/login.html.twig', array(
            // last username entered by the user (if any)
            'last_username' => $this->authenticationUtils->getLastUsername(),
            // last authentication error (if any)
            'error' => $this->authenticationUtils->getLastAuthenticationError(),
        ));
    }

    /**
     * @Route("/_login_check", name="_login_check")
     * @codeCoverageIgnore
     */
    public function loginCheckAction()
    {
        // will never be executed
    }

    /**
     * @Route("/logout", name="_logout")
     * @codeCoverageIgnore
     */
    public function logoutAction()
    {
    }
}
