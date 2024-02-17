<?php
namespace Dayspring\LoginBundle\Security\User;

use Dayspring\LoginBundle\Model\User;
use Dayspring\LoginBundle\Model\UserQuery;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class DayspringUserProvider implements UserProviderInterface
{

    /**
     * @param $username
     * @return User
     */
    public function loadUserByUsername($username)
    {
        $user = UserQuery::create()
            ->filterByEmail($username)
            ->findOne();

        if ($user == null) {
            throw new UsernameNotFoundException(
                sprintf('Username "%s" does not exist.', $username)
            );
        } else {
            $user->reload();
        }

        return $user;
    }

    /**
     * @param UserInterface $user
     * @return User
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === \Dayspring\LoginBundle\Model\User::class;
    }

    /**
     * @param $resetToken
     * @return User
     */
    public function loadUserByResetToken($resetToken)
    {
        $user = UserQuery::create()
            ->filterByResetToken($resetToken)
            ->findOne();

        return $user;
    }

    /**
     * @param $userId
     * @return User
     */
    public function loadUserById($userId)
    {
        return UserQuery::create()->findPk($userId);
    }

    /**
     * @return PropelObjectCollection
     */
    public function getUsers()
    {
        return UserQuery::create()->orderByEmail()->find();
    }
}
