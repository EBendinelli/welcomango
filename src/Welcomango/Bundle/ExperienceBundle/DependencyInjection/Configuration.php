<?php

namespace Welcomango\Bundle\ExperienceBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('welcomango_experience');

        $rootNode
            ->children()
            ->arrayNode('welcomango_reasons')
            ->prototype('scalar')->end()
            ->end()
            ->arrayNode('experience_refused')
            ->prototype('scalar')->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
