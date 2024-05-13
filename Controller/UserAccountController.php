<?php
namespace Dayspring\LoginBundle\Controller;

use Dayspring\LoginBundle\Form\Type\UserType;
use Dayspring\LoginBundle\Model\User;
use Dayspring\LoginBundle\Service\WebauthnService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserAccountController extends AbstractController
{
    public function __construct(protected UserProviderInterface $userProvider, protected WebauthnService $webauthnService)
    {
    }

    #[Route(path: '/account', name: 'account_dashboard')]
    public function dashboardAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('@DayspringLogin/UserAccount/dashboard.html.twig');
    }

    #[Route(path: '/account/passkeys', name: 'account_passkeys')]
    public function passkeysAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        return $this->render('@DayspringLogin/UserAccount/passkeys.html.twig', [
            'user' => $user
        ]);
    }

    #[Route(path: '/account/passkeys/disable/{id}', name: 'account_passkeys_disable')]
    public function passkeysDeleteAction($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $this->webauthnService->disablePasskey($id);
        
        return $this->redirectToRoute('account_passkeys');
    }

    #[Route(path: '/users', name: 'list_users')]
    public function usersAction()
    {
        $this->denyAccessUnlessGranted('ROLE_Admin');

        $users = $this->userProvider->getUsers();

        return $this->render('@DayspringLogin/UserAccount/list.html.twig', ['users' => $users]);
    }

    #[Route(path: '/user/edit/{userId}', name: 'edit_user')]
    #[Route(path: '/user/new', name: 'new_user', defaults: ['userId' => null])]
    public function editUserAction(Request $request, $userId)
    {
        $this->denyAccessUnlessGranted('ROLE_Admin');

        if ($userId) {
            $user = $this->userProvider->loadUserById($userId);
        } else {
            $user = new User();
        }
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->save();
            $this->addFlash(
                'success',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('list_users');
        }

        return $this->render(
            '@DayspringLogin/UserAccount/edit.html.twig',
            ['form' => $form->createView(), 'title' => $userId ? 'Edit User' : 'Create New User']
        );
    }
}
