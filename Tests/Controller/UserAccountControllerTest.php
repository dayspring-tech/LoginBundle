<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/17/16
 * Time: 2:50 PM
 */

namespace Dayspring\SecurityBundle\Tests\Controller;

use Dayspring\SecurityBundle\Model\User;
use Dayspring\SecurityBundle\Model\UserQuery;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class UserAccountControllerTest extends WebTestCase
{

    /**
     * @var Client
     */
    protected $client;

    protected function setUp()
    {
        parent::setUp();

        $this->client = self::createClient();
    }

    protected function createUserAndLogin()
    {
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');

        $user = new User();
        $user->setEmail(sprintf("test+%s@test.com", microtime()));
        $encoded = $encoder->encodePassword($user, 'password');
        $user->setPassword($encoded);
        $user->save();

        $crawler = $this->client->request("GET", "/login");

        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = $user->getUsername();
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
}
