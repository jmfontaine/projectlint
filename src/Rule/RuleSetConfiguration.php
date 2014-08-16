<?php
namespace ProjectLint\Rule;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class RuleSetConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ruleset');

        $rootNode
            ->children()
                ->scalarNode('name')->cannotBeEmpty()->defaultValue('Unnamed ruleset')->end()
                ->scalarNode('author')->cannotBeEmpty()->end()
                ->arrayNode('rules')
                    ->prototype('array')
                        ->beforeNormalization()
                            ->ifString()
                            ->then(function ($value) {
                                return array('expression' => $value);
                            })
                        ->end()
                        ->children()
                            ->scalarNode('expression')->isRequired()->cannotBeEmpty()->end()
                            ->arrayNode('include')
                                ->prototype('scalar')->cannotBeEmpty()->end()
                            ->end()
                            ->arrayNode('exclude')
                                ->prototype('scalar')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
