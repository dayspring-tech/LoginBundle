<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/17/16
 * Time: 11:35 AM
 */

namespace Dayspring\LoginBundle\Tests\Model;

use Dayspring\LoginBundle\Model\SecurityRole;
use Dayspring\LoginBundle\Model\User;
use Dayspring\LoginBundle\Tests\WebTestCase;

class UserTest extends WebTestCase
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

        $this->assertEquals(true, true);
    }

    public function testGetRoles()
    {
        $user = new User();
        $user->setEmail('helloworld');

        $r = new SecurityRole();
        $r->setRoleName("ROLE_TEST");
        $user->addSecurityRole($r);

        $this->assertEquals(1, count($user->getRoles()));
        $this->assertEquals(["ROLE_TEST"], $user->getRoles());
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
        $this->assertEquals($exp->format('Y-m-d H:i:s'), $user->getResetTokenExpire()->format('Y-m-d H:i:s'));

        $user->delete();
    }

    public function testCreateUser()
    {
        $user = new User();
        $user->setEmail(sprintf("user+%s@test.com", microtime()));
        $user->save();

        $user->reload(true);

        $this->assertNotNull($user->getCreatedDate());
        $this->assertNull($user->getLastLoginDate());
    }
}
