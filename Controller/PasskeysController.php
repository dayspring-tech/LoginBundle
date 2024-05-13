<?php

namespace Dayspring\LoginBundle\Controller;

use Dayspring\LoginBundle\Security\User\DayspringUserProvider;
use Dayspring\LoginBundle\Model\User;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Dayspring\LoginBundle\Service\WebauthnService;
use Symfony\Component\HttpFoundation\Request;
use function json_decode;
use function json_encode;

class PasskeysController extends AbstractController
{
    public function __construct(
        protected RequestStack $requestStack,
        protected WebauthnService $webauthnService,
    ) {
    }


    #[Route(path: '/account/passkeys/registrationOptions', name: 'passkeys_registration_options')]
    public function generateRegistrationOptionsAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new Exception('User type not supported. Got ' . ($user !== null ? $user::class : self::class) . ' instead of Dayspring/LoginBundle/User.');
        }

        $registrationOptions = $this->webauthnService->generateRegistrationOptions($user->getUsername());
        $this->requestStack->getSession()->set('passkeys.registrationOptions', json_encode($registrationOptions));
        return new JsonResponse($registrationOptions);
    }

    #[Route(path: '/account/passkeys/verifyRegistration', name: 'passkeys_registration_verify')]
    public function verifyRegistrationResponseAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $body = $request->getContent();
        $registrationOptions = json_decode((string) $this->requestStack->getSession()->get('passkeys.registrationOptions'), true);

        if ($this->webauthnService->verifyRegistrationResponse($body, $registrationOptions)) {
            return new JsonResponse(['verified' => true]);
        } else {
            throw new Exception('Registration failed');
        }
    }

    #[Route(path: '/login/passkeys/authenticationOptions', name: 'passkeys_authentication_options')]
    public function generateAuthenticationOptionsAction(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            $body = $request->getContent();
            $username = json_decode($body, true)['username'];
        } else {
            $username = null;
        }

        $authenticationOptions = $this->webauthnService->generateAuthenticationOptions($username);
        $this->session->set('passkeys.authenticationOptions', json_encode($authenticationOptions));
        return new JsonResponse($authenticationOptions);
    }

    #[Route(path: '/login/passkeys/verifyAuthentication', name: 'passkeys_authentication_verify')]
    public function verifyAuthenticationResponseAction(Request $request)
    {
        // request should be intercepted by WebauthnService as a Symfony Authenticator
    }
}
