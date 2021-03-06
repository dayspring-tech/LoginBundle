<?php
namespace Dayspring\LoginBundle\Tests;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    public function getRootDir()
    {
        return __DIR__.'/Resources';
    }
    public function registerBundles()
    {
        $bundles = array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
//            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
//            new \Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new \Propel\Bundle\PropelBundle\PropelBundle(),
//            new \Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
            new \Dayspring\LoginBundle\DayspringLoginBundle(),

//            new JMS\AopBundle\JMSAopBundle(),
//            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new \Symfony\Bundle\MonologBundle\MonologBundle();
        }

        return $bundles;
    }
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/Resources/config/config_test.yml');
        if (class_exists('Symfony\Component\Asset\Package')) {
            $loader->load(function (ContainerBuilder $container) {
                $container->loadFromExtension('framework', array('assets' => array()));
            });
        }
    }
}
