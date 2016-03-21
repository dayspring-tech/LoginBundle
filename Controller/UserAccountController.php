<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/17/16
 * Time: 2:37 PM
 */

namespace Dayspring\LoginBundle\Controller;

use Dayspring\LoginBundle\Model\User;
use Dayspring\LoginBundle\Model\UserTotpToken;
use Dayspring\LoginBundle\Model\UserTotpTokenQuery;
use Google\Authenticator\GoogleAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class UserAccountController extends Controller
{

    /**
     * @Route("/account", name="account_dashboard")
     * @Template
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function dashboardAction()
    {
        return array();
    }

    /**
     * @Route("/account/two-factor", name="account_two_factor")
     * @Template
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function twoFactorListAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $tokens = $user->getUserTotpTokens();

        $form = $this->createFormBuilder(array())
            ->add('name', TextType::class)
            ->getForm();

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            $data = $form->getData();
            $name = $data['name'];

            $totpHelper = $this->get('dayspring_login.totp_authenticator_helper');

            $token = new UserTotpToken();
            $token->setUser($user);
            $token->setName($name);
            $token->setSecret($totpHelper->generateSecret());
            $token->save();

            $this->get('session')->getFlashBag()->add('verify_totp_token_id', $token->getId());
            return $this->redirect($this->generateUrl('account_two_factor_verify'));
        }


        return array(
            'form' => $form->createView(),
            'tokens' => $tokens
        );
    }

    /**
     * @Route("/account/two-factor/verify", name="account_two_factor_verify")
     * @Template
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function twoFactorVerifyAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $totpHelper = $this->get('dayspring_login.totp_authenticator_helper');

        $form = $this->createFormBuilder(array())
            ->add('code', TextType::class)
            ->add('totp_token_id', HiddenType::class)
            ->getForm();

        $tokenId = $this->get('session')->getFlashBag()->get('verify_totp_token_id');

        $token = UserTotpTokenQuery::create()
            ->findOneById($tokenId);

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            $data = $form->getData();

            if ($totpHelper->checkCode($token, $data['code'])) {
                $token->setActive(true);
                $token->save();
                return $this->redirect($this->generateUrl('account_two_factor'));
            } else {
                $form->get('code')->addError(new FormError("Incorrect code"));
            }
        }

        $this->get('session')->getFlashBag()->add('verify_totp_token_id', $token->getId());

        return array(
            'form' => $form->createView(),
            'url' => $totpHelper->getUrl($token)
        );
    }


}
