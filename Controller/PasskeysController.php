<?php

namespace Dayspring\LoginBundle\Controller;

use Dayspring\LoginBundle\Security\User\DayspringUserProvider;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        protected SessionInterface $session,
        protected WebauthnService $webauthnService,
    ) {
    }


    /**
     * @Route("/account/passkeys/registrationOptions", name="passkeys_registration_options")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function generateRegistrationOptionsAction()
    {
        $registrationOptions = $this->webauthnService->generateRegistrationOptions();
        $this->session->set('passkeys.registrationOptions', json_encode($registrationOptions));
        return new JsonResponse($registrationOptions);
    }

    /**
     * @Route("/account/passkeys/verifyRegistration", name="passkeys_registration_verify")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function verifyRegistrationResponseAction(Request $request)
    {
        $body = $request->getContent();
        $registrationOptions = json_decode($this->session->get('passkeys.registrationOptions'), true);

        if ($this->webauthnService->verifyRegistrationResponse($body, $registrationOptions)) {
            return new JsonResponse(['verified' => true]);
        } else {
            throw new Exception('Registration failed');
        }
    }

    /**
     * @Route("/login/passkeys/authenticationOptions", name="passkeys_authentication_options")
     */
    public function generateAuthenticationOptionsAction(Request $request)
    {
        $body = $request->getContent();
        $username = json_decode($body, true)['username'];

        $authenticationOptions = $this->webauthnService->generateAuthenticationOptions($username);
        $this->session->set('passkeys.authenticationOptions', json_encode($authenticationOptions));
        return new JsonResponse($authenticationOptions);
    }

    /**
     * @Route("/login/passkeys/verifyAuthentication", name="passkeys_authentication_verify")
     */
    public function verifyAuthenticationResponseAction(Request $request)
    {

    }
}
