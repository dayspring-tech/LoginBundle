<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/17/16
 * Time: 2:18 PM
 */

namespace Dayspring\SecurityBundle\Tests\DependencyInjection;

use Dayspring\SecurityBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

    public function testConfiguration()
    {
        $config = array();

        $processor = new Processor();
        $configuration = new Configuration(array());
        $config = $processor->processConfiguration($configuration, array($config));

        $this->assertEquals(array(), $config, 'The config is just an empty array');
    }
}
