<?php
/**
 * Created by PhpStorm.
 * User: jeffreywong
 * Date: 3/20/16
 * Time: 9:37 PM
 */

namespace Dayspring\LoginBundle\Security;

use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

/**
 * TotpToken identifies authentication tokens where MFA token was provided.
 * @package Dayspring\LoginBundle\Security
 */
class TotpToken extends PostAuthenticationGuardToken
{

}