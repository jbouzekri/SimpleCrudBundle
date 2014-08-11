<?php

namespace Jb\Bundle\SimpleCrudBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Yaml\Yaml;

/**
 * JbSimpleCrud extension
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class JbSimpleCrudExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Classic bundle configuration loader
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        // Add available pages to container
        $container->setParameter('jb_simple_crud.entities', $config['entities']);

        // Load crud configuration across bundles
        $crudConfigs = $this->getCrudYamlMappingConfiguration($container);
        foreach ($config['entities'] as $entity) {
            // Normalize configuration
            $crudConfig = isset($crudConfigs[$entity]) ? $crudConfigs[$entity] : array();
            $crudConfigs[$entity] = $this->processConfiguration(
                new CrudEntityConfiguration($entity),
                array($crudConfig)
            );
        }

        $crudEntityMetadata = $container->getDefinition('jb_simple_crud.metadata_list');
        $crudEntityMetadata->addMethodCall('addConfigs', array($crudConfigs));
    }

    /**
     * Get crud entities configuration
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return array
     */
    protected function getCrudYamlMappingConfiguration(ContainerBuilder $container)
    {
        $configs = array();

        // Load crud_entities.yml across enabled bundles
        foreach ($container->getParameter('kernel.bundles') as $bundle) {
            $reflection = new \ReflectionClass($bundle);
            if (is_file($file = dirname($reflection->getFilename()).'/Resources/config/crud_entities.yml')) {
                $configs = array_merge($configs, $this->parseYamlFile($file));
                $container->addResource(new FileResource($file));
            }
        }

        return $configs;
    }

    /**
     * Parse YAML file
     *
     * @param string $file
     *
     * @return array
     */
    protected function parseYamlFile($file)
    {
        $parsedConfig = Yaml::parse($file);
        if (!is_array($parsedConfig)) {
            $parsedConfig = array();
        }

        return $parsedConfig;
    }
}
