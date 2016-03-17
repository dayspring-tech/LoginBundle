<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/15/16
 * Time: 2:46 PM
 */

namespace Dayspring\SecurityBundle\Entity;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordEntity
{

    protected $password;

    protected $newPassword;

    /**
     * @SecurityAssert\UserPassword(
     *     message = "Wrong value for your current password"
     * )
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @Assert\Length(
     *      min = 8,
     *      max = 50,
     *      minMessage = "Your password must be at least {{ limit }} characters long.",
     *      maxMessage = "Your password must be no longer than {{ limit }} characters."
     * )
     * @return mixed
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param mixed $newPassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }
}
