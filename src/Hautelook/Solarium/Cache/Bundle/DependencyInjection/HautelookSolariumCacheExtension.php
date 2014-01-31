<?php

namespace Hautelook\Solarium\Cache\Bundle\DependencyInjection;

use Hautelook\Solarium\Cache\CachePlugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Adrien Brault <adrien.brault@gmail.com>
 */
class HautelookSolariumCacheExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config['clients'] as $client => $cacheId) {
            $definition = new Definition(CachePlugin::CLASS);
            $definition->addMethodCall('setCache', array(new Reference($cacheId)));
            $definition->addTag('nelmio_solarium.plugin', array('client' => $client, 'key' => 'cache'));

            $container->setDefinition(
                sprintf('hautelook_solarium_cache.plugin.%s', $client),
                $definition
            );
        }
    }
}
