<?php

namespace Komita\SiderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder
                    ->root('komita_sider')
                        ->children()->arrayNode('clients')->defaultValue(array())
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('alias')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->scalarNode('paginator_per_page')
                            ->defaultValue(20)
                        ->end()
                    ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
