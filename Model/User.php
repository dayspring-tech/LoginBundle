<?php

namespace Dayspring\LoginBundle\Model;

use DateTime;
use JsonSerializable;
use Dayspring\LoginBundle\Model\om\BaseUser;
use PropelPDO;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

class User extends BaseUser implements AdvancedUserInterface, JsonSerializable
{
    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->setCreatedDate(new DateTime());
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->getIsActive();
    }

    public function getUsername()
    {
        return $this->getEmail();
    }

    public function eraseCredentials()
    {
    }

    public function getRoles($criteria = null, PropelPDO $con = null)
    {
        $dbRoles = parent::getSecurityRoles($criteria, $con);

        $roles = [];
        foreach ($dbRoles as $r) {
            $roles[] = $r->getRoleName();
        }

        return $roles;
    }

    /**
     * @Assert\Email()
     */
    public function getEmail()
    {
        return parent::getEmail();
    }

    /**
     * @Assert\NotBlank(
     *     message="You must enter a new password",
     *     groups={"password"}
     * )
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

    /**
     * Specify data which should be serialized to JSON.
     * If using concrete inheritance as described in the README, implement JsonSerializable on the child object to
     * override how User is serialized.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        if (method_exists($this, 'getChildObject') && $this->getChildObject() instanceof JsonSerializable) {
            return $this->getChildObject()->jsonSerialize();
        }

        return array(
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'createdDate' => $this->getCreatedDate(DateTime::ATOM),
            'lastLoginDate' => $this->getLastLoginDate(DateTime::ATOM),
            'securityRoles' => $this->getSecurityRoles(),
        );
    }
}
