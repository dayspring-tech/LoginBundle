<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/17/16
 * Time: 4:55 PM
 */

namespace Dayspring\SecurityBundle\Tests;

use Dayspring\UnitTestBundle\Framework\Test\DatabaseTestCase as BaseDatabaseTestCase;

class DatabaseTestCase extends BaseDatabaseTestCase
{

    protected static $application;

    protected static function getKernelClass()
    {
        return TestKernel::class;
    }

    protected function setUp()
    {
        parent::setUp();

        self::runCommand('propel:build --insert-sql');
    }

    protected static function getApplication()
    {
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
