<?php

namespace Dayspring\LoginBundle\Controller;

use Dayspring\LoginBundle\Entity\ChangePasswordEntity;
use Dayspring\LoginBundle\Form\Type\ChangePasswordType;
use Dayspring\LoginBundle\Form\Type\ResetPasswordType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ForgotResetController extends AbstractController
{
    public function __construct(
        protected \Symfony\Bundle\SecurityBundle\Security $security,
        protected \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider,
        protected RequestStack $requestStack,
        protected \Symfony\Component\Mailer\MailerInterface $mailer,
        protected \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage,
        protected \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $userPasswordEncoder
    ) {
    }

    #[Route(path: '/forgot-password', name: 'forgot_password')]
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
            } catch (\Symfony\Component\Security\Core\Exception\UserNotFoundException) {
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

    #[Route(path: '/reset-password/{resetToken}', name: 'reset_password', defaults: ['resetToken' => null])]
    public function resetPasswordAction(Request $request, $resetToken)
    {
        $user = $this->userProvider->loadUserByResetToken($resetToken);
        if ($user) {
            $form = $this->createForm(ResetPasswordType::class, $user);
            if ($request->getMethod() == 'POST') {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $data = $form->getData();

                    $encoded = $this->userPasswordEncoder->hashPassword($user, $data->getPassword());
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

    #[Route(path: '/account/change-password', name: 'change_password')]
    public function changePasswordAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $currentUser = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, new ChangePasswordEntity());
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $encoded = $this->userPasswordEncoder->hashPassword($currentUser, $data->getNewPassword());
                $currentUser->setPassword($encoded);
                $currentUser->save();

                $this->security->login($currentUser);

                $this->requestStack->getSession()->getFlashBag()->add('success', 'New password has been saved.');

                return $this->redirect($this->generateUrl("account_dashboard"));
            }
        }
        return $this->render('@DayspringLogin/ForgotReset/changePassword.html.twig', ['form' => $form->createView()]);
    }
}
