<?php

namespace Jb\Bundle\SimpleCrudBundle\Config;

/**
 * CrudMetadata
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class CrudMetadata
{
    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var string
     */
    protected $entity;

    /**
     * Constructor
     *
     * @param string $entity
     * @param array $config
     */
    public function __construct($entity, array $config)
    {
        $this->entity = $entity;
        $this->config = $config;
    }

    /**
     * Get entity
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Get a template
     *
     * @param string $key
     *
     * @return string
     */
    public function getTemplate($key)
    {
        return $this->config['templates'][$key];
    }

    /**
     * Get templates configuration
     *
     * @return array
     */
    public function getTemplates()
    {
        return $this->config['templates'];
    }

    /**
     * Get columns configuration
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->config['columns'];
    }

    /**
     * Get form.create configuration
     *
     * @return array
     */
    public function getFormCreate()
    {
        return $this->config['form']['create'];
    }

    /**
     * Get controller
     *
     * @return string
     */
    public function getController()
    {
        return $this->config['controller'];
    }

    /**
     * Get page (route prefix)
     *
     * @return string
     */
    public function getPage()
    {
        return $this->config['page'];
    }
}
