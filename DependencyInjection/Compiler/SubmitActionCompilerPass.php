<?php

namespace Jb\Bundle\SimpleCrudBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * SubmitActionCompilerPass
 *
 * @author jobou
 */
class SubmitActionCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('jb_simple_crud.submit_action.chain')) {
            return;
        }

        $definition = $container->getDefinition(
            'jb_simple_crud.submit_action.chain'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'jb_simple_crud.submit_action'
        );
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
                    'addSubmitAction',
                    array(new Reference($id), $attributes["alias"])
                );
            }
        }
    }
}
