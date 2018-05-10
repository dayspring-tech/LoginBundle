<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/17/16
 * Time: 2:18 PM
 */

namespace Dayspring\LoginBundle\Tests\DependencyInjection;

use Dayspring\LoginBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{

    public function testConfiguration()
    {
        $config = array();

        $processor = new Processor();
        $configuration = new Configuration(array());
        $config = $processor->processConfiguration($configuration, array($config));

        $this->assertEquals(array(
            'from_address' => 'nobody@dayspring-tech.com',
            'from_display_name' => 'Test Application'
        ), $config, 'Config should have from_address and from_display_name');
    }
}
