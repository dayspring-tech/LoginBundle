<?php
/**
 * Created by PhpStorm.
 * User: jwong
 * Date: 3/17/16
 * Time: 2:19 PM
 */

namespace Dayspring\LoginBundle\Tests\DependencyInjection;

use Dayspring\LoginBundle\DependencyInjection\DayspringLoginExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use PHPUnit\Framework\TestCase;

class ExtensionTest extends TestCase
{

    public function testLoadEmptyConfiguration()
    {
        $container = $this->createContainer();
        $extension = new DayspringLoginExtension();
        $extension->load(array(), $container);
        $container->registerExtension($extension);

        $this->compileContainer($container);

        $this->assertEquals(5, count($container->getParameterBag()->all()), '->load() loads the services.xml file');

        $this->assertEquals(7, count($container->getDefinitions()));
    }

    private function createContainer()
    {
        $container = new ContainerBuilder(new ParameterBag(array(
            'kernel.cache_dir' => __DIR__,
            'kernel.charset'   => 'UTF-8',
            'kernel.debug'     => false,
        )));

        return $container;
    }

    private function compileContainer(ContainerBuilder $container)
    {
        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();
    }
}
