<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/17/16
 * Time: 4:40 PM
 */

namespace Dayspring\SecurityBundle\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

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

    protected function setUp()
    {
        parent::setUp();

        self::runCommand('propel:build --insert-sql');
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

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new \Symfony\Component\Console\Input\StringInput($command));
    }
}
