<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/21/16
 * Time: 1:51 PM
 */

namespace Dayspring\LoginBundle\Security;

use Dayspring\LoginBundle\Model\User;
use Dayspring\LoginBundle\Model\UserTotpToken;
use Google\Authenticator\GoogleAuthenticator;

class TotpAuthenticatorHelper
{

    protected $googleAuthenticator;

    protected $issuer;

    /**
     * TotpAuthenticatorHelper constructor.
     */
    public function __construct()
    {
        $this->googleAuthenticator = new GoogleAuthenticator();
    }

    /**
     * Generate a new secret.
     * @return string
     */
    public function generateSecret()
    {
        return $this->googleAuthenticator->generateSecret();
    }

    /**
     * Check code against this secret
     * @param UserTotpToken $token
     * @param $code
     * @return bool
     */
    public function checkCode(UserTotpToken $token, $code)
    {
        return $this->googleAuthenticator->checkCode($token->getSecret(), $code);
    }

    /**
     * Check code against user's active secrets. Update the last used date of the matching secret.
     * @param User $user
     * @param $code
     * @return bool
     */
    public function checkUserCodes(User $user, $code)
    {
        $tokens = $user->getActiveTotpTokens();

        foreach ($tokens as $token) {
            if ($this->checkCode($token, $code)) {
                $token->setLastUsed(new \DateTime());
                $token->save();
                return true;
            }
        }

        return false;
    }

    /**
     * Get the QR code image URL for the secret.
     * @param UserTotpToken $token
     * @return string
     */
    public function getUrl(UserTotpToken $token)
    {
        $encoder = 'https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=';
        $userAndHost = rawurlencode($token->getUser()->getUsername());
        if ($this->issuer) {
            $encoderURL = sprintf(
                'otpauth://totp/%s:%s?secret=%s&issuer=%s',
                rawurlencode($this->issuer),
                $userAndHost,
                $token->getSecret(),
                rawurlencode($this->issuer)
            );
        } else {
            $encoderURL = sprintf(
                'otpauth://totp/%s?secret=%s',
                $userAndHost,
                $user->getGoogleAuthenticatorSecret()
            );
        }
        return $encoder.urlencode($encoderURL);
    }

}
