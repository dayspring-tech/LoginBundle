<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/15/16
 * Time: 1:30 PM
 */

namespace Dayspring\LoginBundle\Tests\Security\User;

use Dayspring\LoginBundle\Model\User;
use Dayspring\LoginBundle\Security\User\DayspringUserProvider;
use Dayspring\LoginBundle\Tests\WebTestCase;
use Propel\Bundle\PropelBundle\Command\FixturesLoadCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Security\Core\User\UserInterface;

class DayspringUserProviderTest extends WebTestCase
{

    /**
     * @var DayspringUserProvider $userProvider
     */
    protected $userProvider;

    protected function setUp()
    {
        parent::setUp();

        $application = new Application(static::$kernel);
        $application->add(new FixturesLoadCommand());

        $command = $application->find('propel:fixtures:load');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'bundle' => '@DayspringLoginBundle',
            '--sql' => null
        ]);

        $this->userProvider = new DayspringUserProvider();
    }


    public function testLoadUserByUsername()
    {
        $user = $this->userProvider->loadUserByUsername('testuser@example.com');

        $this->assertEquals(1, $user->getId());
    }

    public function testRefreshUser()
    {
        $user = new User();
        $user->setEmail('testuser@example.com');

        $refreshedUser = $this->userProvider->refreshUser($user);
        $this->assertEquals(1, $refreshedUser->getId());
        $this->assertEquals('testuser@example.com', $refreshedUser->getUsername());
    }

    public function testSupportsClass()
    {
        $user = new User();

        $this->assertTrue($this->userProvider->supportsClass(get_class($user)));
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function testLoadUserByUsernameFailure()
    {
        $this->userProvider->loadUserByUsername('foobar@doesnotexist.com');
    }

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function testRefreshUserFailure()
    {
        $user = new SomeUser();

        $this->userProvider->refreshUser($user);
    }

    public function testSupportsClassFailure()
    {
        $user = new SomeUser();

        $this->assertFalse($this->userProvider->supportsClass(get_class($user)));
    }
}

class SomeUser implements UserInterface
{
    public function getRoles()
    {
    }

    public function getPassword()
    {
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
    }

    public function eraseCredentials()
    {
    }
}
