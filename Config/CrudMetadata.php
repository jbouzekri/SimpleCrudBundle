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
     * Get titles configuration
     *
     * @return array
     */
    public function getTitles()
    {
        return $this->config['titles'];
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
     * Get form
     *
     * @param string $type
     * @param string $default
     * @return \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadataForm
     */
    public function getForm($type, $default = 'create')
    {
        $config = $this->config['form'][$default];
        if (count($this->config['form'][$type]['fields']) > 0) {
            $config = $this->config['form'][$type];
        }

        return new CrudMetadataForm($config);
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

    /**
     * Get formated name
     *
     * @return string
     */
    public function getFormatedName()
    {
        return $this->config['formated_name'];
    }

    /**
     * Get line actions
     *
     * @return array
     */
    public function getLineActions()
    {
        return $this->config['line_actions'];
    }

    /**
     * Get main actions
     *
     * @return array
     */
    public function getMainActions()
    {
        return $this->config['main_actions'];
    }

    /**
     * Get translation domain
     *
     * @return array
     */
    public function getTranslationDomain()
    {
        return $this->config['translation_domain'];
    }
}
