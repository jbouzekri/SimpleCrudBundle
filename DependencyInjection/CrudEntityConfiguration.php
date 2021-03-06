<?php

namespace Jb\Bundle\SimpleCrudBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

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
                ->scalarNode('formated_name')
                    ->defaultValue('undefined')
                ->end()
                ->scalarNode('translation_domain')
                    ->defaultValue('messages')
                ->end()
                ->append($this->addTitleNode())
                ->append($this->addTemplatesNode())
                ->append($this->addColumnsNode())
                ->append($this->addFormNode())
                ->append($this->addMainActionsNode())
                ->append($this->addLineActionsNode())
            ->end();

        return $treeBuilder;
    }

    /**
     * Add titles tree node
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    protected function addTitleNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('titles');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('index')->defaultValue('List of %%entity_name%%')->end()
                ->scalarNode('create')->defaultValue('Create new %%entity_name%%')->end()
                ->scalarNode('edit')->defaultValue('Edit entity #%%id%%')->end()
            ->end()
        ;

        return $node;
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
                ->scalarNode('flashbag')->defaultValue('JbSimpleCrudBundle:Crud:_flashbag.html.twig')->end()
                ->scalarNode('index')->defaultValue('JbSimpleCrudBundle:Crud:index.html.twig')->end()
                ->scalarNode('main_actions')->defaultValue('JbSimpleCrudBundle:Crud:_main_actions.html.twig')->end()
                ->scalarNode('create')->defaultValue('JbSimpleCrudBundle:Crud:edit.html.twig')->end()
                ->scalarNode('edit')->defaultValue('JbSimpleCrudBundle:Crud:edit.html.twig')->end()
                ->scalarNode('table')->defaultValue('JbSimpleCrudBundle:Crud:_table.html.twig')->end()
                ->scalarNode('thead_line')->defaultValue('JbSimpleCrudBundle:Crud:_thead_line.html.twig')->end()
                ->scalarNode('tbody_line')->defaultValue('JbSimpleCrudBundle:Crud:_tbody_line.html.twig')->end()
                ->scalarNode('line_actions')->defaultValue('JbSimpleCrudBundle:Crud:_line_actions.html.twig')->end()
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
                ->append($this->addFormArray('create'))
                ->append($this->addFormArray('edit'))
            ->end();

        return $node;
    }

    /**
     * Add main actions tree node
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    protected function addMainActionsNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('main_actions');

        $this->addRoutesArray($node);

        $node
            ->defaultValue(
                array(
                    'create' => array(
                        'route' => 'create',
                        'label' => 'Create'
                    )
                )
            )
        ;

        return $node;
    }

    /**
     * Add line actions tree node
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    protected function addLineActionsNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('line_actions');

        $this->addRoutesArray($node);

        $node
            ->defaultValue(
                array(
                    'edit' => array(
                        'route' => 'update',
                        'label' => 'Edit'
                    ),
                    'Remove' => array(
                        'route' => 'remove',
                        'label' => 'Remove',
                        'method' => 'POST'
                    )
                )
            )
        ;

        return $node;
    }

    /**
     * Add routes array to a treebuilder
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    protected function addRoutesArray(ArrayNodeDefinition $node)
    {
        $node
            ->prototype('array')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('route')->isRequired()->end()
                    ->scalarNode('method')->defaultValue('GET')->end()
                    ->scalarNode('label')->isRequired()->end()
                ->end()
            ->end();
    }

    /**
     * Add form array to a nodebuilder
     *
     * @param string $type
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    protected function addFormArray($type)
    {
        $builder = new TreeBuilder();
        $node = $builder->root($type);

        $node
            ->children()
                ->arrayNode('fields')
                    ->beforeNormalization()
                    ->ifString()
                        ->then(function ($value) {
                            return array('type' => $value);
                        })
                    ->end()
                    ->prototype('scalar')
                    ->end()
                    ->defaultValue(array())
                ->end()
                ->arrayNode('actions')
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('type')->isRequired()->end()
                            ->arrayNode('options')
                                ->prototype('variable')
                                ->end()
                                ->defaultValue(array())
                            ->end()
                        ->end()
                    ->end()
                    ->defaultValue(array(
                        'default' => array(
                            'type' => 'submit',
                            'options' => array(
                                'label' => 'Save',
                                'attr' => array(
                                    'class' => 'btn btn-primary'
                                )
                            )
                        ),
                        'back' => array(
                            'type' => 'jb_crud_link',
                            'options' => array(
                                'route' => 'index'
                            )
                        )
                    ))
                ->end()
            ->end();

        return $node;
    }
}
