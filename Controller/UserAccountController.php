<?php
namespace Dayspring\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserAccountController extends Controller
{

    /**
     * @Route("/account", name="account_dashboard")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function dashboardAction()
    {
        return $this->render('DayspringLoginBundle:UserAccount:dashboard.html.twig');
    }

    /**
     * @Route("/users", name="list_users")
     * @Security("is_granted('ROLE_Admin')")
     */
    public function usersAction()
    {
        $userService = $this->get('dayspring_login.user_service');
        $users = $userService->getUsers();

        return $this->render('DayspringLoginBundle:UserAccount:list.html.twig', array('users' => $users));
    }
}
