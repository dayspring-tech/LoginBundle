<?php

namespace Dayspring\LoginBundle\Tests\Model;

use Dayspring\LoginBundle\Model\SecurityRole;

class SecurityRoleTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonSerialize()
    {
        $role = new SecurityRole();
        $role->setRoleName("ROLE_USER");

        $this->assertJsonStringEqualsJsonString('"ROLE_USER"', json_encode($role));
    }
}
