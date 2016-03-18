<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/17/16
 * Time: 4:40 PM
 */

namespace Dayspring\SecurityBundle\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DayspringSecurityBundleWebTestCase extends WebTestCase
{
    protected static function getKernelClass()
    {
        return TestKernel::class;
    }
}
