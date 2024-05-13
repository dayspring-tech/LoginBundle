<?php

namespace Dayspring\LoginBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(protected \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils)
    {
    }

    #[Route(path: '/login', name: '_login')]
    public function loginAction()
    {
        return $this->render('@DayspringLogin/Security/login.html.twig', [
            // last username entered by the user (if any)
            'last_username' => $this->authenticationUtils->getLastUsername(),
            // last authentication error (if any)
            'error' => $this->authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @codeCoverageIgnore
     */
    #[Route(path: '/_login_check', name: '_login_check')]
    public function loginCheckAction()
    {
        // will never be executed
    }

    /**
     * @codeCoverageIgnore
     */
    #[Route(path: '/logout', name: '_logout')]
    public function logoutAction()
    {
    }
}
