<?php
namespace Dayspring\SecurityBundle\Tests;

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
        return array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
//            new \Symfony\Bundle\MonologBundle\MonologBundle(),
//            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
//            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
//            new \Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new \Propel\PropelBundle\PropelBundle(),
//            new \Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
            new \Dayspring\SecurityBundle\DayspringSecurityBundle(),

//            new JMS\AopBundle\JMSAopBundle(),
//            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
        );
    }
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/Resources/config/config.yml');
        if (class_exists('Symfony\Component\Asset\Package')) {
            $loader->load(function (ContainerBuilder $container) {
                $container->loadFromExtension('framework', array('assets' => array()));
            });
        }
    }
}
