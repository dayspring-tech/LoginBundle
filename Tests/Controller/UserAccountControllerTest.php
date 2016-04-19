<?php
namespace Dayspring\LoginBundle\Tests\Controller;

use Dayspring\LoginBundle\Model\RoleQuery;
use Dayspring\LoginBundle\Model\RoleUserQuery;
use Dayspring\LoginBundle\Model\User;
use Dayspring\LoginBundle\Model\UserQuery;
use Dayspring\LoginBundle\Tests\WebTestCase;
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

    protected function createUserAndLogin($isAdmin = false)
    {
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');

        $user = new User();
        $user->setEmail(sprintf("test+%s@test.com", microtime()));
        $encoded = $encoder->encodePassword($user, 'password');
        $user->setPassword($encoded);
        if ($isAdmin) {
            $adminRole = RoleQuery::create()->filterByRoleName("ROLE_Admin")->findOneOrCreate();
            $user->addRole($adminRole);
        }
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

    public function testUsers()
    {
        $this->createUserAndLogin(true);

        $crawler = $this->client->request("GET", "/users");

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Users")')->count()
        );
    }

    public function testUsersNotAllowed()
    {
        $this->createUserAndLogin();

        $crawler = $this->client->request("GET", "/users");

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }
}
