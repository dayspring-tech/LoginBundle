<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/17/16
 * Time: 4:40 PM
 */

namespace Dayspring\LoginBundle\Tests;


use Propel\Bundle\PropelBundle\Command\BuildCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class WebTestCase extends BaseWebTestCase
{

    protected static $application;

    protected static function getKernelClass()
    {
        return TestKernel::class;
    }

    /**
     * Creates a Client.
     *
     * @param array   $options An array of options to pass to the createKernel class
     * @param array   $server  An array of server parameters
     *
     * @return Client A Client instance
     */
    static protected function createService($id)
    {
        $service = static::$kernel->getContainer()->get($id);

        return $service;
    }

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $application = new Application(static::$kernel);
        $application->add(new BuildCommand());

        $command = $application->find('propel:build');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--insert-sql' => null
        ]);
    }

    protected static function getApplication()
    {
        if (null === static::$kernel) {
            static::bootKernel();
        }

        if (null === self::$application) {
            self::$application = new \Symfony\Bundle\FrameworkBundle\Console\Application(static::$kernel);
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }
}
