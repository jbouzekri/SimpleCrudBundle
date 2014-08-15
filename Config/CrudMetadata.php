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
     * Get form.create configuration
     *
     * @return \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadataForm
     */
    public function getFormCreate()
    {
        return new CrudMetadataForm($this->config['form']['create']);
    }

    /**
     * Get form.edit configuration
     *
     * @return array
     */
    public function getFormEdit()
    {
        if (count($this->config['form']['edit']['fields']) === 0) {
            return $this->getFormCreate();
        }

        return new CrudMetadataForm($this->config['form']['edit']);
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
