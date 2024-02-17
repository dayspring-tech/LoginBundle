<?php
namespace Dayspring\LoginBundle\Controller;

use Dayspring\LoginBundle\Form\Type\UserType;
use Dayspring\LoginBundle\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserAccountController extends AbstractController
{
    protected $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

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
        $users = $this->userProvider->getUsers();

        return $this->render('@DayspringLogin/UserAccount/list.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/user/edit/{userId}", name="edit_user")
     * @Route("/user/new", name="new_user", defaults={"userId" = null})
     * @Security("is_granted('ROLE_Admin')")
     */
    public function editUserAction(Request $request, $userId)
    {
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
