<?php

namespace Jb\Bundle\SimpleCrudBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Jb\Bundle\SimpleCrudBundle\DependencyInjection\Compiler\SubmitActionCompilerPass;

/**
 * JbSimpleCrudBundle bundle
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class JbSimpleCrudBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SubmitActionCompilerPass());
    }
}
