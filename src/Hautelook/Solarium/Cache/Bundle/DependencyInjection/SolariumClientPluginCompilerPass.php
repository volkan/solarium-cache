<?php

namespace Hautelook\Solarium\Cache\Bundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Adrien Brault <adrien.brault@gmail.com>
 */
class SolariumClientPluginCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('nelmio_solarium.plugin') as $id => $tags) {
            foreach ($tags as $tag) {
                $client = 'default';
                if (isset($tag['client'])) {
                    $client = $tag['client'];
                }
                $key = $tag['key'];

                $clientDefinition = $container->getDefinition(sprintf('solarium.client.%s', $client));
                $clientDefinition->addMethodCall(
                    'registerPlugin',
                    array(
                        $key,
                        new Reference($id)
                    )
                );
            }
        }
    }
}
