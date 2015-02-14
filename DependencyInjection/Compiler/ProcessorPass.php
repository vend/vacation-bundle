<?php

namespace Vend\Bundle\VacationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ProcessorPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $executorDefinition = $container->findDefinition('vacation.executor');

        $processorDefinitions = $container->findTaggedServiceIds('vacation.processor');

        foreach ($processorDefinitions as $id => $tags) {
            $name = empty($tags[0]['alias']) ? $id : $tags[0]['alias'];
            $executorDefinition->addMethodCall('registerProcessor', [$name, new Reference($id)]);
        }
    }
}
