<?php

namespace Vend\Bundle\VacationBundle;

use Vend\Bundle\VacationBundle\DependencyInjection\Compiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class VacationBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new Compiler\ControllerPass());
        $container->addCompilerPass(new Compiler\ProcessorPass());
    }
}
