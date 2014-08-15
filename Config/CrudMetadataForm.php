<?php

namespace Jb\Bundle\SimpleCrudBundle\Config;

/**
 * CrudMetadataForm
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class CrudMetadataForm
{
    /**
     * @var array
     */
    protected $config = array();

    /**
     * Constructor
     *
     * @param string $entity
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get fields or form service name
     *
     * @return array
     */
    public function getFields()
    {
        return $this->config['fields'];
    }

    /**
     * Get actions
     *
     * @return array
     */
    public function getActions()
    {
        return $this->config['actions'];
    }
}
