<?php

namespace Jb\Bundle\SimpleCrudBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * FormResourceCompilerPass
 *
 * @author jobou
 */
class FormResourceCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $formResources = $container->getParameter('templating.helper.form.resources');
        $formResources[] = 'JbSimpleCrudBundle:Form';
        $container->setParameter('templating.helper.form.resources', $formResources);

        $formResources = $container->getParameter('twig.form.resources');
        $formResources[] = 'JbSimpleCrudBundle:Form:fields.html.twig';
        $container->setParameter('twig.form.resources', $formResources);
    }
}