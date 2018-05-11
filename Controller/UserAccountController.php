<?php
namespace Dayspring\LoginBundle\Controller;

use Dayspring\LoginBundle\Form\Type\UserType;
use Dayspring\LoginBundle\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class UserAccountController extends Controller
{

    /**
     * @Route("/account", name="account_dashboard")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function dashboardAction()
    {
        return $this->render('@DayspringLogin/UserAccount/dashboard.html.twig');
    }

    /**
     * @Route("/users", name="list_users")
     * @Security("is_granted('ROLE_Admin')")
     */
    public function usersAction()
    {
        $userService = $this->get('dayspring_login.user_provider');
        $users = $userService->getUsers();

        return $this->render('@DayspringLogin/UserAccount/list.html.twig', array('users' => $users));
    }

    /**
     * @Route("/user/edit/{userId}", name="edit_user")
     * @Route("/user/new", name="new_user", defaults={"userId" = null})
     * @Security("is_granted('ROLE_Admin')")
     */
    public function editUserAction(Request $request, $userId)
    {
        $userService = $this->get('dayspring_login.user_provider');
        if ($userId) {
            $user = $userService->loadUserById($userId);
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
            array(
                'form' => $form->createView(),
                'title' => $userId ? 'Edit User' : 'Create New User'
            )
        );
    }
}
