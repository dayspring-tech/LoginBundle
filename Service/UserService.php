<?php
namespace Dayspring\LoginBundle\Service;

use Dayspring\LoginBundle\Model\UserQuery;
use Dayspring\LoginBundle\Security\User\DayspringUserProvider;
use PropelObjectCollection;

class UserService extends DayspringUserProvider
{
    /**
     * @return PropelObjectCollection
     */
    public function getUsers()
    {
        return UserQuery::create()->orderByEmail()->find();
    }
}