<?php

namespace Hautelook\Solarium\Cache\Bundle;

use Hautelook\Solarium\Cache\Bundle\DependencyInjection\SolariumClientPluginCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Adrien Brault <adrien.brault@gmail.com>
 */
class HautelookSolariumCacheBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SolariumClientPluginCompilerPass());
    }
}
