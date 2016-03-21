<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/17/16
 * Time: 2:50 PM
 */

namespace Dayspring\LoginBundle\Tests\Controller;

use Dayspring\LoginBundle\Model\User;
use Dayspring\LoginBundle\Model\UserQuery;
use Dayspring\LoginBundle\Model\UserTotpToken;
use Dayspring\LoginBundle\Model\UserTotpTokenQuery;
use Dayspring\LoginBundle\Tests\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class UserAccountControllerTest extends WebTestCase
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var User
     */
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->client = self::createClient();
    }

    protected function createUserAndLogin()
    {
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');

        $this->user = new User();
        $this->user->setEmail(sprintf("test+%s@test.com", microtime()));
        $encoded = $encoder->encodePassword($this->user, 'password');
        $this->user->setPassword($encoded);
        $this->user->save();

        $crawler = $this->client->request("GET", "/login");

        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = $this->user->getUsername();
        $form['_password'] = 'password';

        $crawler = $this->client->submit($form);
    }

    public function testDashboard()
    {
        $this->createUserAndLogin();

        $crawler = $this->client->request("GET", "/account");

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Dashboard")')->count()
        );
    }


    public function testTwoFactorList()
    {
        $this->createUserAndLogin();

        $crawler = $this->client->request("GET", "/account/two-factor");

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Two-Factor")')->count()
        );
    }

    public function testTwoFactorEnroll()
    {
        $this->createUserAndLogin();

        $crawler = $this->client->request("GET", "/account/two-factor");

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Two-Factor")')->count()
        );

        $form = $crawler->selectButton('Add')->form();
        $form['form[name]'] = 'test device';

        $crawler = $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $crawler = $this->client->followRedirect();

        $newToken = UserTotpTokenQuery::create()
            ->filterByUser($this->user)
            ->findOne();

        $helper = self::createService('dayspring_login.totp_authenticator_helper');
        $code = $helper->generateCode($newToken);

        $form = $crawler->selectButton('Verify')->form();
        $form['form[code]'] = $code;

        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $crawler = $this->client->request("GET", "/account/two-factor");

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Active")')->count()
        );
    }

    public function testTwoFactorLogin()
    {
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');
        $totpHelper = static::$kernel->getContainer()->get('dayspring_login.totp_authenticator_helper');

        $this->user = new User();
        $this->user->setEmail(sprintf("test+%s@test.com", microtime()));
        $encoded = $encoder->encodePassword($this->user, 'password');
        $this->user->setPassword($encoded);
        $this->user->save();

        $token = new UserTotpToken();
        $token->setUser($this->user);
        $token->setName("My secret");
        $token->setSecret($totpHelper->generateSecret());
        $token->setActive(true);
        $token->save();

        $crawler = $this->client->request("GET", "/login");

        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = $this->user->getUsername();
        $form['_password'] = 'password';

        $crawler = $this->client->submit($form);

        // redirect to dashboard
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        // redirect to totp_login
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Enter MFA Token")')->count()
        );

        $form = $crawler->selectButton('Submit')->form();
        $form['_one_time'] = $totpHelper->generateCode($token);

        $crawler = $this->client->submit($form);

        // redirect to dashboard
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Dashboard")')->count()
        );
    }

    public function testTwoFactorLoginBadMFA()
    {
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');
        $totpHelper = static::$kernel->getContainer()->get('dayspring_login.totp_authenticator_helper');

        $this->user = new User();
        $this->user->setEmail(sprintf("test+%s@test.com", microtime()));
        $encoded = $encoder->encodePassword($this->user, 'password');
        $this->user->setPassword($encoded);
        $this->user->save();

        $token = new UserTotpToken();
        $token->setUser($this->user);
        $token->setName("My secret");
        $token->setSecret($totpHelper->generateSecret());
        $token->setActive(true);
        $token->save();

        $crawler = $this->client->request("GET", "/login");

        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = $this->user->getUsername();
        $form['_password'] = 'password';

        $crawler = $this->client->submit($form);

        // redirect to dashboard
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        // redirect to totp_login
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Enter MFA Token")')->count()
        );

        $form = $crawler->selectButton('Submit')->form();
        $form['_one_time'] = '123456';

        $crawler = $this->client->submit($form);

        // redirect to totp_login
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        // redirect to login
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Invalid token")')->count()
        );
    }
}
