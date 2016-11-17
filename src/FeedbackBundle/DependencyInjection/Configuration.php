<?php

namespace FeedbackBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {
    
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        
        $rootNode = $treeBuilder->root('feedback');
        $rootNode
            ->children()
                ->arrayNode('commenting')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('class')->end()
                        ->scalarNode('route')->end()
                    ->end()
                    ->end()
                ->end()
            ->end();
            
        return $treeBuilder;
    }

}
