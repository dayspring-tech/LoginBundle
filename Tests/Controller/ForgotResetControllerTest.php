<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/17/16
 * Time: 12:25 PM
 */

namespace Dayspring\LoginBundle\Tests\Controller;

use Dayspring\LoginBundle\Model\SecurityRole;
use Dayspring\LoginBundle\Model\User;
use Dayspring\LoginBundle\Model\UserQuery;
use Dayspring\LoginBundle\Tests\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class ForgotResetControllerTest extends WebTestCase
{

    /**
     * @var Client
     */
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
    }

    public function testForgotPassword()
    {
        $user = new User();
        $user->setEmail(sprintf("test+%f@test.com", microtime(true)));
        $user->save();

        $crawler = $this->client->request("GET", "/forgot-password");

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Forgot Password")')->count()
        );

        $form = $crawler->selectButton("Submit")->form();
        $form['form[email]'] = $user->getEmail();
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Your request has been sent")')->count()
        );
    }

    public function testForgotPasswordDeactiveUser()
    {
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');

        $user = new User();
        $user
            ->setEmail("test-inactive-user@example.com")
            ->setPassword("password")
            ->setIsActive(false);

        $encoded = $encoder->encodePassword($user, 'password');
        $user
            ->setPassword($encoded)
            ->save();

        $crawler = $this->client->request("GET", "/forgot-password");

        $form = $crawler->selectButton("Submit")->form();
        $form['form[email]'] = $user->getEmail();
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();
        $this->assertCount(1, $crawler->filter("div.alert-danger:contains('Your request has been sent')"));

        $user->delete();
    }

    public function testForgotPasswordUnknownUser()
    {
        $crawler = $this->client->request("GET", "/forgot-password");

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Forgot Password")')->count()
        );

        $form = $crawler->selectButton("Submit")->form();
        $form['form[email]'] = 'foobar@doesnotexist.com';
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Your request has been sent")')->count()
        );
    }

    public function testResetPasswordBadToken()
    {
        $this->client->request("GET", "/reset-password/badtoken");

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testResetPassword()
    {
        $user = new User();
        $user->setEmail(sprintf("test+%s@test.com", microtime()));
        $user->setPassword("myoldpassword");
        $token = $user->generateResetToken();

        $crawler = $this->client->request("GET", "/reset-password/".$token);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Reset Password")')->count()
        );

        $form = $crawler->selectButton("Save")->form();
        $form['reset_password[password][first]'] = '';
        $form['reset_password[password][second]'] = '';
        $crawler = $this->client->submit($form);
        $this->assertFalse($this->client->getResponse()->isRedirect());

        $form['reset_password[password][first]'] = 'Welcome1';
        $form['reset_password[password][second]'] = 'Welcome1';
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("New password has been saved")')->count()
        );

        $user->reload();
        $this->assertNotEquals("myoldpassword", $user->getPassword());
    }

    public function testResetPasswordValidationError()
    {
        $user = new User();
        $user->setEmail(sprintf("test+%s@test.com", microtime()));
        $user->setPassword("myoldpassword");
        $token = $user->generateResetToken();

        $crawler = $this->client->request("GET", "/reset-password/".$token);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Reset Password")')->count()
        );

        $form = $crawler->selectButton("Save")->form();
        $form['reset_password[password][first]'] = 'Welcome1';
        $form['reset_password[password][second]'] = 'doesnotmatch';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("The password fields must match")')->count()
        );

        $user->reload();
        $this->assertEquals("myoldpassword", $user->getPassword());
    }

    public function testChangePasswordNotLoggedIn()
    {
        $crawler = $this->client->request("GET", "/account/change-password");

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Log In")')->count()
        );
    }

    public function testChangePassword()
    {
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');

        $securityRole = new SecurityRole();
        $securityRole->setRoleName('ROLE');

        $user = new User();
        $encoded = $encoder->encodePassword($user, 'password');

        $user
            ->addSecurityRole($securityRole)
            ->setEmail(sprintf("test+%s@test.com", microtime()))
            ->setPassword($encoded)
            ->save();

        $crawler = $this->client->request("GET", "/login");

        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = $user->getUsername();
        $form['_password'] = 'password';

        $crawler = $this->client->submit($form);

        $crawler = $this->client->request("GET", "/account/change-password");
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Change Password")')->count()
        );

        $form = $crawler->selectButton('Save')->form();
        $form['change_password[password]'] = 'password';
        $form['change_password[newPassword][first]'] = 'Welcome1';
        $form['change_password[newPassword][second]'] = 'Welcome1';
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("New password has been saved")')->count()
        );

        $user->reload();
        $this->assertNotEquals($encoded, $user->getPassword());
    }

    public function testChangePasswordNoMatch()
    {
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');

        $securityRole = new SecurityRole();
        $securityRole->setRoleName('ROLE');

        $user = new User();
        $encoded = $encoder->encodePassword($user, 'password');

        $user
            ->addSecurityRole($securityRole)
            ->setEmail(sprintf("test+%s@test.com", microtime()))
            ->setPassword($encoded)
            ->save();

        $crawler = $this->client->request("GET", "/login");

        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = $user->getUsername();
        $form['_password'] = 'password';

        $crawler = $this->client->submit($form);

        $crawler = $this->client->request("GET", "/account/change-password");
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Change Password")')->count()
        );

        $form = $crawler->selectButton('Save')->form();
        $form['change_password[password]'] = 'password';
        $form['change_password[newPassword][first]'] = 'Welcome1';
        $form['change_password[newPassword][second]'] = 'doesnotmatch';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("The password fields must match")')->count()
        );

        $user->reload();
        $this->assertEquals($encoded, $user->getPassword());
    }
}
