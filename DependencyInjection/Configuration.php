<?php

namespace Dayspring\LoginBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('dayspring_login');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->scalarNode('from_address')->defaultValue('nobody@dayspring-tech.com')->end()
            ->scalarNode('from_display_name')->defaultValue('Test Application')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
