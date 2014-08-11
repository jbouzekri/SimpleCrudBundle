<?php

namespace Jb\Bundle\SimpleCrudBundle\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Jb\Bundle\SimpleCrudBundle\Config\CrudMetadataList;
use Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata;

/**
 * Routing CrudLoader
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class CrudLoader implements LoaderInterface
{
    /**
     * @var bool
     */
    private $loaded = false;

    /**
     * @var array
     */
    protected $entities;

    /**
     * @var array
     */
    protected $metadataList;

    /**
     * Constructor
     *
     * @param array $entities
     * @param \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadataList $metadataList
     */
    public function __construct(
        array $entities,
        CrudMetadataList $metadataList
    ) {
        $this->entities = $entities;
        $this->metadataList = $metadataList;
    }

    /**
     * {@inheritDoc}
     */
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "extra" loader twice');
        }

        $routes = new RouteCollection();

        foreach ($this->entities as $entity) {
            $metadata = $this->metadataList->getMetadata($entity);
            $this->appendRoutes($routes, $metadata);
        }

        $this->loaded = true;

        return $routes;
    }

    /**
     * Append route for a page type
     *
     * @param \Symfony\Component\Routing\RouteCollection $routes
     * @param \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata $metadata
     *
     * @return void
     */
    protected function appendRoutes(RouteCollection $routes, CrudMetadata $metadata)
    {
        foreach ($this->getRoutesConfiguration($metadata) as $configuration) {
            $routes->add(
                $configuration[0],
                $this->createRoute(
                    $configuration[1],
                    $configuration[2],
                    $configuration[3]
                )
            );
        }
    }

    /**
     * Get route configuration data
     *
     * @param \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata $metadata
     *
     * @return array
     */
    protected function getRoutesConfiguration(CrudMetadata $metadata)
    {
        $page = $metadata->getPage();
        $controller = $metadata->getController();
        $entity = $metadata->getEntity();

        $routeNamePrefix = 'jb_simple_crud_'.str_replace('-', '_', $page);
        $patternPrefix = '/' . urlencode($page);

        // Base crud routes
        $data = array(
            array(
                $routeNamePrefix . "_index",
                $patternPrefix,
                array('_controller' => $controller.':index', 'entity' => $entity),
                false
            ),
            array(
                $routeNamePrefix . "_create",
                $patternPrefix . '/create',
                array('_controller' => $controller.':create', 'entity' => $entity),
                false
            ),
            array(
                $routeNamePrefix . "_update",
                $patternPrefix . '/{id}/update',
                array('_controller' => $controller.':update', 'entity' => $entity),
                true
            ),
            array(
                $routeNamePrefix . "_remove",
                $patternPrefix . '/{id}/remove',
                array('_controller' => $controller.':remove', 'entity' => $entity),
                true
            ),
        );

        // Controller is a service. Append Action to _controller route definition
        foreach ($data as $key => $configuration) {
            if (strpos($controller, ':') === false) {
                $data[$key][2]['_controller'] .= 'Action';
            }
        }

        return $data;
    }
    /**
     * Create a route
     *
     * @param string $pattern
     * @param array $defaults
     * @param bool $withId
     *
     * @return \Symfony\Component\Routing\Route
     */
    protected function createRoute($pattern, $defaults, $withId = false)
    {
        $requirements = array();
        if ($withId) {
            $requirements['parameter'] = '\d+';
        }

        return new Route($pattern, $defaults, $requirements);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($resource, $type = null)
    {
        return 'jb_simple_crud' === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getResolver()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
    }
}