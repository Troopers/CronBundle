<?php

namespace Troopers\CronBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('troopers_cron');

        $rootNode
            ->children()
                ->arrayNode('reporting')
                    ->children()
                        ->arrayNode('api')
                            ->children()
                                ->scalarNode('url')
                                    ->isRequired()
                                ->end()
                                ->scalarNode('api_key')
                                    ->isRequired()
                                ->end()
                                ->enumNode('format')
                                    ->values(['form'])
                                    ->defaultValue('form')
                                    ->isRequired()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('tasks')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('command')
                                ->isRequired()
                            ->end()
                            ->scalarNode('schedule')
                                ->isRequired()
                            ->end()
                            ->variableNode('arguments')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}