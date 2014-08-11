<?php

namespace Jb\Bundle\SimpleCrudBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * CrudEntityConfiguration configuration structure.
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class CrudEntityConfiguration implements ConfigurationInterface
{
    /**
     * @var string
     */
    protected $entity;

    /**
     * Constructor
     *
     * @param string $entity
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root($this->entity);

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('controller')
                    ->defaultValue('jb_simple_crud.controller')
                ->end()
                ->scalarNode('page')
                    ->isRequired()
                ->end()
                ->append($this->addTemplatesNode())
                ->append($this->addColumnsNode())
                ->append($this->addFormNode())
            ->end();

        return $treeBuilder;
    }

    /**
     * Add templates tree node
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    protected function addTemplatesNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('templates');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('layout_ajax')->defaultValue('JbSimpleCrudBundle::layout_ajax.html.twig')->end()
                ->scalarNode('layout')->defaultValue('JbSimpleCrudBundle::layout.html.twig')->end()
                ->scalarNode('index')->defaultValue('JbSimpleCrudBundle:Crud:index.html.twig')->end()
                ->scalarNode('create')->defaultValue('JbSimpleCrudBundle:Crud:edit.html.twig')->end()
            ->end()
        ;

        return $node;
    }

    /**
     * Add columns tree node
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    protected function addColumnsNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('columns');

        $node
            ->prototype('scalar')
            ->end()
            ->defaultValue(array('id'))
        ;

        return $node;
    }

    /**
     * Add form tree node
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    protected function addFormNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('form');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('create')
                    ->beforeNormalization()
                    ->ifString()
                        ->then(function($value) { return array('type' => $value); })
                    ->end()
                    ->prototype('scalar')
                    ->end()
                    ->defaultValue(array())
                ->end()
                ->arrayNode('edit')
                    ->beforeNormalization()
                    ->ifString()
                        ->then(function($value) { return array('type' => $value); })
                    ->end()
                    ->prototype('scalar')
                    ->end()
                    ->defaultValue(array())
                ->end()
            ->end()
        ;

        return $node;
    }
}
