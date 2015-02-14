<?php

namespace Vend\Bundle\VacationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ControllerPass implements CompilerPassInterface
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
        $registryDefinition = $container->findDefinition('vacation.controller_registry');

        $controllerDefinitions = $container->findTaggedServiceIds('vacation.controller');

        foreach ($controllerDefinitions as $id => $controllerDefinition) {
            $registryDefinition->addMethodCall('registerController', [new Reference($id)]);
        }
    }
}
