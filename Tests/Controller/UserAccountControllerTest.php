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

        self::runCommand('propel:fixtures:load @DayspringLoginBundle --sql');

        $this->client = self::createClient();
    }

    protected function createUserAndLogin()
    {
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');

        $user = new User();
        $user->setEmail(sprintf("test+%s@test.com", microtime()));
        $encoded = $encoder->encodePassword($user, 'password');
        $user->setPassword($encoded);
        $user->addRole(RoleQuery::create()->filterByRoleName("ROLE_User")->findOneOrCreate());
        $user->save();

        $crawler = $this->client->request("GET", "/login");

        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = $user->getUsername();
        $form['_password'] = 'password';

        $crawler = $this->client->submit($form);
    }

    protected function loginAdminUser()
    {
        $crawler = $this->client->request("GET", "/login");

        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = 'admin@example.com';
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
        $this->loginAdminUser();

        $crawler = $this->client->request("GET", "/users");

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Users")')->count());
    }

    public function testUsersNotAllowed()
    {
        $this->createUserAndLogin();

        $crawler = $this->client->request("GET", "/users");

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testEditUser()
    {
        $this->loginAdminUser();

        $crawler = $this->client->request("GET", "/user/edit/1");

        $this->assertGreaterThan(0, $crawler->filter('input[name*=email][value*=testuser]')->count());

        $form = $crawler->selectButton('Save')->form();
        $form['user[email]'] = 'newemail@example.com';
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect('/users'));
        $this->assertEquals('newemail@example.com', UserQuery::create()->findPk(1)->getEmail());
    }

    public function testUserValidation()
    {
        $this->loginAdminUser();

        $crawler = $this->client->request("GET", "/user/edit/1");

        $form = $crawler->selectButton('Save')->form();
        $form['user[email]'] = 'not-email';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('.has-error input[name*=email]')->count());
    }
}
