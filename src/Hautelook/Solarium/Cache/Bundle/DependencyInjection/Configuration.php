<?php

namespace Hautelook\Solarium\Cache\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Adrien Brault <adrien.brault@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hautelook_solarium_cache');

        $rootNode
            ->children()
                ->arrayNode('clients')
                    ->defaultValue(array())
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
