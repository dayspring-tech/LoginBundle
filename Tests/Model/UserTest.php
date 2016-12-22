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
    }

    public function testGetRoles()
    {
        $user = new User();
        $user->setEmail('helloworld');

        $r = new SecurityRole();
        $r->setRoleName("ROLE_TEST");
        $user->addSecurityRole($r);

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

    public function testCreateUser()
    {
        $user = new User();
        $user->setEmail(sprintf("user+%s@test.com", microtime()));
        $user->save();

        $user->reload(true);

        $this->assertNotNull($user->getCreatedDate());
        $this->assertNull($user->getLastLoginDate());
    }

    public function testJsonSerialize()
    {
        $email = sprintf("user+%s@test.com", microtime());

        $user = new User();
        $user->setEmail($email);

        $expectedJson = sprintf(
            '{"id":null,"email":"%s","createdDate":"%s","lastLoginDate":null}',
            $email,
            $user->getCreatedDate(\DateTime::ATOM)
        );
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($user));

        $obj = json_decode(json_encode($user));
        $this->assertNull($obj->id);
        $this->assertEquals($email, $obj->email);
    }

    public function testJsonSerializeSaved()
    {
        $email = sprintf("user+%s@test.com", microtime());

        $user = new User();
        $user->setEmail($email);
        $user->save();

        $expectedJson = sprintf(
            '{"id":%d,"email":"%s","createdDate":"%s","lastLoginDate":"%s"}',
            $user->getId(),
            $email,
            $user->getCreatedDate(\DateTime::ATOM),
            $user->getLastLoginDate(\DateTime::ATOM)
        );
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($user));

        $obj = json_decode(json_encode($user));
        $this->assertEquals($user->getId(), $obj->id);
        $this->assertEquals($email, $obj->email);
        $this->assertEquals($user->getCreatedDate(\DateTime::ATOM), $obj->createdDate);
        $this->assertEquals($user->getLastLoginDate(\DateTime::ATOM), $obj->lastLoginDate);

        $now = new \DateTime();
        $user->setLastLoginDate($now);

        $expectedJson = sprintf(
            '{"id":%d,"email":"%s","createdDate":"%s","lastLoginDate":"%s"}',
            $user->getId(),
            $email,
            $user->getCreatedDate(\DateTime::ATOM),
            $now->format(\DateTime::ATOM)
        );
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($user));
    }
}
