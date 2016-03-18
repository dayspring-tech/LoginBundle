<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/17/16
 * Time: 11:35 AM
 */

namespace Dayspring\SecurityBundle\Tests\Model;

use Dayspring\SecurityBundle\Model\Role;
use Dayspring\SecurityBundle\Model\User;
use Dayspring\SecurityBundle\Tests\DayspringSecurityBundleDatabaseTestCase;
use Dayspring\UnitTestBundle\Framework\Test\DatabaseTestCase;

class UserTest extends DayspringSecurityBundleDatabaseTestCase
{

    public function testGetUsername()
    {
        $user = new User();
        $user->setEmail('helloworld');

        $this->assertEquals('helloworld', $user->getUsername());
    }

    public function testEraseCredentials()
    {
        $user = new User();

        $user->eraseCredentials();
    }

    public function testGetRoles()
    {
        $user = new User();
        $user->setEmail('helloworld');

        $r = new Role();
        $r->setRoleName("ROLE_TEST");
        $user->addRole($r);

        $this->assertEquals(1, count($user->getRoles()));
        $this->assertEquals(array("ROLE_TEST"), $user->getRoles());
    }

    public function testGetPassword()
    {
        $user = new User();
        $user->setPassword('mypassword');

        $this->assertEquals('mypassword', $user->getPassword());
    }

    public function testGenerateResetToken()
    {
        $user = new User();
        $user->setEmail(sprintf("user+%s@test.com", microtime()));

        $token = $user->generateResetToken();

        $this->assertNotNull($user->getResetTokenExpire());
        $this->assertNotNull($user->getResetToken());

        $this->assertEquals($token, $user->getResetToken());

        $user->delete();
    }

    public function testGenerateResetTokenExists()
    {
        $user = new User();
        $user->setEmail(sprintf("user+%s@test.com", microtime()));
        $user->setResetToken("thisismyresettoken");
        $exp = (new \DateTime())->add(new \DateInterval('PT1H'));
        $user->setResetTokenExpire($exp);

        $token = $user->generateResetToken();

        $this->assertNotNull($user->getResetTokenExpire());
        $this->assertNotNull($user->getResetToken());

        $this->assertEquals("thisismyresettoken", $token);
        $this->assertEquals("thisismyresettoken", $user->getResetToken());
        $this->assertEquals($exp, $user->getResetTokenExpire());

        $user->delete();
    }
}
