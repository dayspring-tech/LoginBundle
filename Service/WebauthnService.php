<?php

namespace Dayspring\LoginBundle\Service;

use Dayspring\LoginBundle\Model\User;
use Dayspring\LoginBundle\Model\UserWebauthn;
use Dayspring\LoginBundle\Model\UserWebauthnQuery;
use Dayspring\LoginBundle\Security\User\DayspringUserProvider;
use Exception;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\AttestationStatement\AttestationObjectLoader;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Cose\Algorithm\Manager;
use Cose\Algorithm\Signature\ECDSA\ES256;
use Cose\Algorithm\Signature\RSA\RS256;
use Webauthn\AuthenticationExtensions\ExtensionOutputCheckerHandler;
use Webauthn\PublicKeyCredentialLoader;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\PublicKeyCredentialDescriptor;
use Webauthn\PublicKeyCredentialRequestOptions;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\AuthenticatorAssertionResponse;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use \ParagonIE\ConstantTime\Base64;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Webauthn\AuthenticationExtensions\AuthenticationExtension;
use Webauthn\AuthenticationExtensions\AuthenticationExtensionsClientInputs;

class WebauthnService extends AbstractAuthenticator
{


    protected AuthenticatorAttestationResponseValidator $authenticatorAttestationResponseValidator;
    protected AuthenticatorAssertionResponseValidator $authenticatorAssertionResponseValidator;
    protected PublicKeyCredentialLoader $publicKeyCredentialLoader;

    public function __construct(
        protected LoggerInterface $logger,
        protected SessionInterface $session,
        protected UserProviderInterface $userProvider,
        protected RequestStack $requestStack,
        protected Security $security
    ) {
        // The manager will receive data to load and select the appropriate
        $attestationStatementSupportManager = AttestationStatementSupportManager::create();
        $attestationStatementSupportManager->add(NoneAttestationStatementSupport::create());

        $attestationObjectLoader = AttestationObjectLoader::create(
            $attestationStatementSupportManager
        );

        $this->publicKeyCredentialLoader = PublicKeyCredentialLoader::create(
            $attestationObjectLoader
        );
        $this->publicKeyCredentialLoader->setLogger($this->logger);

        $extensionOutputCheckerHandler = ExtensionOutputCheckerHandler::create();

        $this->authenticatorAttestationResponseValidator = AuthenticatorAttestationResponseValidator::create(
            $attestationStatementSupportManager,
            null, //Deprecated Public Key Credential Source Repository. Please set null.
            null, //Deprecated Token Binding Handler. Please set null.
            $extensionOutputCheckerHandler
        );

        $algorithmManager = Manager::create()
            ->add(
                ES256::create(),
                RS256::create()
            );

        $this->authenticatorAssertionResponseValidator = AuthenticatorAssertionResponseValidator::create(
            null,                           //Deprecated Public Key Credential Source Repository. Please set null.
            null,                           //Deprecated Token Binding Handler. Please set null.
            $extensionOutputCheckerHandler, // The extension output checker handler
            $algorithmManager               // The COSE Algorithm Manager
        );
    }

    public function generateRegistrationOptions($userHandle)
    {
        // RP Entity i.e. the application
        $rpEntity = PublicKeyCredentialRpEntity::create(
            'My Super Secured Application', //Name
        );

        // User Entity
        $userEntity = PublicKeyCredentialUserEntity::create(
            $userHandle,
            $userHandle,
            $userHandle,
            null                                    //Icon
        );

        // Challenge
        $challenge = random_bytes(16);

        $publicKeyCredentialCreationOptions = PublicKeyCredentialCreationOptions::create(
            $rpEntity,
            $userEntity,
            $challenge,
            attestation: PublicKeyCredentialCreationOptions::ATTESTATION_CONVEYANCE_PREFERENCE_NONE
        );

        return $publicKeyCredentialCreationOptions;
    }

    public function verifyRegistrationResponse($response, $publicKeyCredentialCreationOptions)
    {
        $publicKeyCredentialCreationOptions = PublicKeyCredentialCreationOptions::createFromArray($publicKeyCredentialCreationOptions);

        $publicKeyCredential = $this->publicKeyCredentialLoader->load($response);

        if (!$publicKeyCredential->response instanceof AuthenticatorAttestationResponse) {
            throw new Exception('The response is not an instance of AuthenticatorAttestationResponse');
        }

        $publicKeyCredentialSource = $this->authenticatorAttestationResponseValidator->check(
            $publicKeyCredential->response,
            $publicKeyCredentialCreationOptions,
            $this->requestStack->getMainRequest()->getHost(),
            ['localhost']
        );

        $this->logger->info('verifyRegistrationResponse success', ['publicKeyCredentialSource' => $publicKeyCredentialSource]);

        try {
            $user = $this->userProvider->loadUserByUsername($publicKeyCredentialSource->userHandle);
        } catch (UsernameNotFoundException $e) {
            // create a new user
            $user = new User();
            $user->setUsername($publicKeyCredentialSource->userHandle);
            $user->save();
        }

        // save the credential source
        $userWebauthn = new UserWebauthn();
        $userWebauthn
            ->setCredentialId(Base64UrlSafe::encodeUnpadded($publicKeyCredentialSource->publicKeyCredentialId))
            ->setCredentialData(json_encode($publicKeyCredentialSource))
            ->setUser($user)
            ->save();

        return true;
    }

    public function generateAuthenticationOptions($username = null)
    {
        try {
            $allowedCredentials = [];
            if ($username) {
                $this->logger->info(sprintf('generateAuthenticationOptions for %s', $username));
                $user = $this->userProvider->loadUserByUsername($username);
                $registeredAuthenticators = $user->getUserWebauthns()->getArrayCopy();
                $allowedCredentials = array_map(
                    static function (UserWebauthn $userWebauthn): PublicKeyCredentialDescriptor {
                        $credential = PublicKeyCredentialSource::createFromArray(json_decode($userWebauthn->getCredentialData(),
                            true));
                        return $credential->getPublicKeyCredentialDescriptor();
                    },
                    $registeredAuthenticators
                );
            }

            $publicKeyCredentialRequestOptions = PublicKeyCredentialRequestOptions::create(
                random_bytes(32), // Challenge
                allowCredentials: $allowedCredentials,
                userVerification: PublicKeyCredentialRequestOptions::USER_VERIFICATION_REQUIREMENT_PREFERRED,
            );

            return $publicKeyCredentialRequestOptions;
        } catch (UsernameNotFoundException $e) {
            return null;
        }
    }

    public function verifyAuthenticationResponse($response, $publicKeyCredentialRequestOptions)
    {
        $publicKeyCredentialRequestOptions = PublicKeyCredentialRequestOptions::createFromArray($publicKeyCredentialRequestOptions);

        /** @var PublicKeyCredential $publicKeyCredential */
        $publicKeyCredential = $this->publicKeyCredentialLoader->load($response);

        if (!$publicKeyCredential->response instanceof AuthenticatorAssertionResponse) {
            throw new AuthenticationException('The response is not an instance of AuthenticatorAssertionResponse');
        }

        $userWebauthn = UserWebauthnQuery::create()
            ->filterByCredentialId($publicKeyCredential->id)
            ->filterByIsActive(true)
            ->findOne();
        if (!$userWebauthn) {
            throw new AuthenticationException('No credential found for the given credential ID: '.$publicKeyCredential->id);
        }
        $publicKeyCredentialSource = PublicKeyCredentialSource::createFromArray(json_decode($userWebauthn->getCredentialData(), true));

        $publicKeyCredentialSource = $this->authenticatorAssertionResponseValidator->check(
            $publicKeyCredentialSource,
            $publicKeyCredential->response,
            $publicKeyCredentialRequestOptions,
            $this->requestStack->getMainRequest()->getHost(),
            $userWebauthn->getUser()->getUsername(),
            ['localhost']
        );

        $this->logger->info('verifyAuthenticationResponse success', ['publicKeyCredentialSource' => $publicKeyCredentialSource]);
        
        $userWebauthn
            ->setLastUsedAt(new \DateTime())
            ->save();

        return $userWebauthn->getUser();
    }

    public function disablePasskey($id)
    {
        $userWebauthn = UserWebauthnQuery::create()->findOneById($id);
        if ($userWebauthn) {
            $userWebauthn
                ->setIsActive(false)
                ->save();
        }
    }

    public function supports(Request $request): ?bool
    {
        try {
            $body = $request->getContent();
            $publicKeyCredential = $this->publicKeyCredentialLoader->load($body);
            $supports = $publicKeyCredential->response instanceof AuthenticatorAssertionResponse;
            $this->logger->debug('supports: '. ($supports ? 'true' : 'false'));
            return $supports;
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    public function authenticate(Request $request)
    {
        $body = $request->getContent();
        $authenticationOptions = json_decode($this->session->get('passkeys.authenticationOptions'), true);

        $user = $this->verifyAuthenticationResponse($body, $authenticationOptions);

        return new SelfValidatingPassport(new UserBadge($user->getUsername()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new JsonResponse(['verified' => true, 'redirect' => '/secure']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $this->logger->error('Authentication failed', ['exception' => $exception]);
        return new JsonResponse(['error' => 'Authentication with passkeys failed.'], Response::HTTP_UNAUTHORIZED);
    }


}
