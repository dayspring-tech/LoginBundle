<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/17/16
 * Time: 4:55 PM
 */

namespace Dayspring\SecurityBundle\Tests;

use Dayspring\UnitTestBundle\Framework\Test\DatabaseTestCase;

class DayspringSecurityBundleDatabaseTestCase extends DatabaseTestCase
{
    protected static function getKernelClass()
    {
        return TestKernel::class;
    }
}
