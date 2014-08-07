<?php

namespace Jb\Bundle\SimpleCrudBundle\Config;

use Jb\Bundle\SimpleCrudBundle\Exception\MetadataNotFoundException;

/**
 * CrudMetadataList
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class CrudMetadataList
{
    /**
     * @var array
     */
    protected $configs = array();

    /**
     * Add configurations
     *
     * @param array $configs
     *
     * @return \Jb\Bundle\SimpleCrudBundle\Controller\CrudMetaData
     */
    public function addConfigs(array $configs)
    {
        foreach ($configs as $entity => $config) {
            $this->addConfig($entity, $config);
        }

        return $this;
    }

    /**
     * Add configuration
     *
     * @param string $entity
     * @param array $config
     *
     * @return \Jb\Bundle\SimpleCrudBundle\Controller\CrudMetaData
     */
    public function addConfig($entity, array $config)
    {
        $this->configs[$entity] = new CrudMetadata($entity, $config);

        return $this;
    }

    /**
     * Get metadata for entity
     *
     * @param string $entity
     *
     * @return \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata
     *
     * @throws \Jb\Bundle\SimpleCrudBundle\Exception\MetadataNotFoundException
     */
    public function getMetadata($entity)
    {
        if (!isset($this->configs[$entity])) {
            throw new MetadataNotFoundException('Crud metadata not found for '.$entity);
        }

        return $this->configs[$entity];
    }
}
