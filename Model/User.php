<?php

namespace Dayspring\LoginBundle\Model;

use DateTime;
use Dayspring\LoginBundle\Model\om\BaseUser;
use PropelPDO;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

class User extends BaseUser implements UserInterface
{
    public function getUsername()
    {
        return $this->getEmail();
    }

    public function eraseCredentials()
    {
    }

    public function getRoles($criteria = null, PropelPDO $con = null)
    {
        $dbRoles = parent::getRoles($criteria, $con);

        $roles = [];
        foreach ($dbRoles as $r) {
            $roles[] = $r->getRoleName();
        }

        return $roles;
    }

    /**
     * @Assert\Length(
     *      min = 8,
     *      max = 50,
     *      minMessage = "Your password must be at least {{ limit }} characters long.",
     *      maxMessage = "Your password must be no longer than {{ limit }} characters.",
     *      groups={"password"}
     * )
     */
    public function getPassword()
    {
        return parent::getPassword();
    }

    public function generateResetToken()
    {
        $hours = 0;
        if ($this->getResetTokenExpire() !== null) {
            $diff = $this->getResetTokenExpire()->diff(new DateTime());
            $hours = $diff->h;
            $hours = $hours + ($diff->days * 24);
        }
        if ($this->getResetTokenExpire() === null || $hours >= 2) {
            // token was expired, generate a new one
            do {
                $token = md5(rand());
                $query = UserQuery::create()->filterByResetToken($token);
            } while ($query->count() > 0);

            $this->setResetToken($token);
            $this->setResetTokenExpire(new DateTime());
            $this->save();
        }
        return parent::getResetToken();
    }

    protected function getActiveTotpTokenQuery()
    {
        return UserTotpTokenQuery::create()
            ->filterByUser($this)
            ->filterByActive(true);
    }

    public function getActiveTotpTokens()
    {
        return $this->getActiveTotpTokenQuery()->find();
    }

    public function hasTotp()
    {
        $tokenCount = $this->getActiveTotpTokenQuery()
            ->count();

        return $tokenCount > 0;
    }
}
