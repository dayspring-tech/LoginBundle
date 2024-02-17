<?php
namespace Dayspring\LoginBundle\Tests;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    public function getProjectDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return __DIR__.'/../var/cache';
    }

    public function getLogDir()
    {
        return __DIR__.'/../var/log';
    }

    public function registerBundles(): iterable
    {
        $bundles = [new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(), new \Symfony\Bundle\SecurityBundle\SecurityBundle(), new \Symfony\Bundle\TwigBundle\TwigBundle(), new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(), new \Propel\Bundle\PropelBundle\PropelBundle(), new \Dayspring\LoginBundle\DayspringLoginBundle()];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new \Symfony\Bundle\MonologBundle\MonologBundle();
        }

        return $bundles;
    }
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/Resources/config/config_test.yml');
        if (class_exists(\Symfony\Component\Asset\Package::class)) {
            $loader->load(function (ContainerBuilder $container) {
                $container->loadFromExtension('framework', ['assets' => []]);
            });
        }
    }
}
