<?php

namespace carlescliment\moco\DependencyInjection\CompilerPass;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ControllerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {

        $taggedServices = $container->findTaggedServiceIds(
            'moco.controller'
        );
        foreach ($taggedServices as $id => $attributes) {
            $definition = $container->getDefinition($id);
            $definition->addMethodCall(
                'setContainer',
                array($container)
            );
        }
    }
}