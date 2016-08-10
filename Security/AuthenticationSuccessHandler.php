<?php

namespace Dayspring\LoginBundle\Security;

use Dayspring\LoginBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;

class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();
        $user->setLastLoginDate(new \DateTime());
        $user->save();

        return parent::onAuthenticationSuccess($request, $token);
    }
}
