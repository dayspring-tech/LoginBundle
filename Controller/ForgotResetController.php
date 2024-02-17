<?php

namespace Dayspring\LoginBundle\Controller;

use Dayspring\LoginBundle\Entity\ChangePasswordEntity;
use Dayspring\LoginBundle\Form\Type\ChangePasswordType;
use Dayspring\LoginBundle\Form\Type\ResetPasswordType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ForgotResetController extends AbstractController
{
    protected $userProvider;
    protected $authenticationManager;
    protected $session;
    protected $tokenStorage;
    protected $userPasswordEncoder;
    protected $mailer;

    public function __construct(
        AuthenticationManagerInterface $authenticationManager,
        UserProviderInterface $userProvider,
        SessionInterface $session,
        MailerInterface $mailer,
        TokenStorageInterface $tokenStorage,
        UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->authenticationManager = $authenticationManager;
        $this->mailer = $mailer;
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->userProvider = $userProvider;
    }

    /**
     * @Route("/forgot-password", name="forgot_password")
     */
    public function forgotPasswordAction(Request $request)
    {
        $genericMsg = 'Your request has been sent. If an account was found, an email has been sent. Please check your email for further instructions.';

        $form = $this->createFormBuilder([])
            ->add('email', EmailType::class)
            ->getForm();
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            $data = $form->getData();
            $email = $data['email'];

            try {
                $user = $this->userProvider->loadUserByUsername($email);

                if ($user->getIsActive()) {
                    $user->generateResetToken();

                    $subject = "Reset Password";
                    $data = ['user' => $user];
                    $fromAddress = $this->getParameter('login_bundle.from_address');
                    $fromDisplayName = $this->getParameter('login_bundle.from_display_name');

                    $message = (new Email())
                        ->subject($subject)
                        ->to($user->getEmail())
                        ->html($this->renderView(
                            '@DayspringLogin/Emails/reset_password.html.twig',
                            $data
                        ));

                    if (is_array($fromAddress)) {
                        foreach($fromAddress as $from) {
                            $message ->from($from);
                        }
                    } else {
                        $message->from($fromAddress);
                    }

                    $this->mailer->send($message);
                }
            } catch (UsernameNotFoundException $e) {
                // do not throw an error for UsernameNotFoundException
            }

            $request->getSession()->getFlashBag()->add(
                "success",
                $genericMsg
            );
            return $this->redirect($this->generateUrl('_login'));

        }

        return $this->render('@DayspringLogin/ForgotReset/forgotPassword.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/reset-password/{resetToken}", name="reset_password", defaults={"resetToken"=null})
     */
    public function resetPasswordAction(Request $request, $resetToken)
    {
        $user = $this->userProvider->loadUserByResetToken($resetToken);
        if ($user) {
            $form = $this->createForm(ResetPasswordType::class, $user);
            if ($request->getMethod() == 'POST') {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $data = $form->getData();

                    $encoded = $this->userPasswordEncoder->encodePassword($user, $data->getPassword());
//                    $encoded = $this->userPasswordEncoder->hashPassword($user, $data->getPassword());
                    $user->setPassword($encoded);
                    $user->save();

                    $data->setResetToken(null);
                    $data->setResetTokenExpire(null);
                    $data->save();
                    $request->getSession()->getFlashBag()->add(
                        'success',
                        'New password has been saved, please login with new password.'
                    );
                    return $this->redirect($this->generateUrl('_login'));
                }
            }
            return $this->render('@DayspringLogin/ForgotReset/resetPassword.html.twig', ['form' => $form->createView()]);
        } else {
            throw new AccessDeniedHttpException("No User found with this reset token.");
        }
    }

    /**
     * @Route("/account/change-password", name="change_password")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function changePasswordAction(Request $request)
    {
        $currentUser = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, new ChangePasswordEntity());
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $encoded = $this->userPasswordEncoder->encodePassword($currentUser, $data->getNewPassword());
//                $encoded = $this->userPasswordEncoder->hashPassword($currentUser, $data->getNewPassword());
                $currentUser->setPassword($encoded);
                $currentUser->save();

                $token = new UsernamePasswordToken(
                    $currentUser,
                    $data->getNewPassword(),
                    "secured_area",
                    $currentUser->getRoles()
                );
                $token = $this->authenticationManager->authenticate($token);
                $this->tokenStorage->setToken($token);

                $this->session->getFlashBag()->add('success', 'New password has been saved.');

                return $this->redirect($this->generateUrl("account_dashboard"));
            }
        }
        return $this->render('@DayspringLogin/ForgotReset/changePassword.html.twig', ['form' => $form->createView()]);
    }
}
