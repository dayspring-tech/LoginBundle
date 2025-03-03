<?php
namespace Dayspring\LoginBundle\Security\User;

use Dayspring\LoginBundle\Model\User;
use Dayspring\LoginBundle\Model\UserQuery;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class DayspringUserProvider implements UserProviderInterface
{

    public function loadUserByUsername($username)
    {
        return $this->loadUserByIdentifier($username);
    }

    /**
     * @param $username
     * @return User
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = UserQuery::create()
            ->filterByEmail($identifier)
            ->findOne();

        if ($user == null) {
            throw new UserNotFoundException(
                sprintf('Username "%s" does not exist.', $identifier)
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
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass($class): bool
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
