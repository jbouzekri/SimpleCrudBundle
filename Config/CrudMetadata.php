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
}
