<?php
namespace Dayspring\LoginBundle\Tests\Controller;

use Dayspring\LoginBundle\Model\SecurityRoleQuery;
use Dayspring\LoginBundle\Model\RoleUserQuery;
use Dayspring\LoginBundle\Model\User;
use Dayspring\LoginBundle\Model\UserQuery;
use Dayspring\LoginBundle\Tests\WebTestCase;
use Propel\Bundle\PropelBundle\Command\FixturesLoadCommand;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class UserAccountControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    protected function setUp()
    {
        parent::setUp();

        $application = new Application(static::$kernel);
        $application->add(new FixturesLoadCommand());

        $command = $application->find('propel:fixtures:load');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'bundle' => '@DayspringLoginBundle',
            '--sql'
        ]);

        $this->client = self::createClient();
    }

    protected function createUserAndLogin()
    {
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');

        $user = new User();
        $user->setEmail(sprintf("test+%s@test.com", microtime()));
        $encoded = $encoder->encodePassword($user, 'password');
        $user->setPassword($encoded);
        $user->addSecurityRole(SecurityRoleQuery::create()->filterByRoleName("ROLE_User")->findOneOrCreate());
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

    public function testInactiveUser()
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

        $crawler = $this->client->request("GET", "/login");

        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = $user->getUsername();
        $form['_password'] = 'password';
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();
        $this->assertCount(1, $crawler->filter("div.alert-danger:contains('User account is disabled')"));

        $user->delete();
    }

    public function testLastLoginDate()
    {
        $encoder = static::$kernel->getContainer()->get('security.password_encoder');

        $user = new User();
        $user->setEmail(sprintf("test+%s@test.com", microtime()));
        $encoded = $encoder->encodePassword($user, 'password');
        $user->setPassword($encoded);
        $user->addSecurityRole(SecurityRoleQuery::create()->filterByRoleName("ROLE_User")->findOneOrCreate());
        $user->save();

        $this->assertNull($user->getLastLoginDate());

        $crawler = $this->client->request("GET", "/login");

        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = $user->getUsername();
        $form['_password'] = 'password';

        $crawler = $this->client->submit($form);

        $user->reload();
        $this->assertNotNull($user->getLastLoginDate());
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

    public function testCreateUser()
    {
        $this->loginAdminUser();

        $crawler = $this->client->request("GET", "/user/new");

        $this->assertGreaterThan(0, $crawler->filter('input[name*=email]')->count());

        $form = $crawler->selectButton('Save')->form();
        $email = 'email_'.microtime(true).'@example.com';
        $form['user[email]'] = $email;
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect('/users'));
        $this->assertEquals(1, UserQuery::create()->filterByEmail($email)->count());
    }

    public function testEditUser()
    {
        $this->loginAdminUser();

        $crawler = $this->client->request("GET", "/user/edit/1");

        $this->assertGreaterThan(0, $crawler->filter('input[name*=email]')->count());

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

        $this->assertContains(
            'This value is not a valid email address',
            $crawler->html()
        );
    }
}
