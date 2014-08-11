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
     * @var \Jb\Bundle\SimpleCrudBundle\Routing\CrudRouter
     */
    protected $router;

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
     * @param \Jb\Bundle\SimpleCrudBundle\Routing\CrudRouter $router
     * @param array $entities
     * @param \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadataList $metadataList
     */
    public function __construct(
        CrudRouter $router,
        array $entities,
        CrudMetadataList $metadataList
    ) {
        $this->router = $router;
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
                new Route(
                    $configuration[1],
                    $configuration[2],
                    $configuration[3],
                    array(),
                    '',
                    array(),
                    $configuration[4]
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
        $controller = $metadata->getController();
        $entity = $metadata->getEntity();

        $patternPrefix = '/' . urlencode($metadata->getPage());

        // Base crud routes
        $data = array(
            /**
             * route name,
             * path/pattern
             * defaults
             * requirements
             * methods
             */
            array(
                $this->router->generateName('index', $metadata),
                $patternPrefix,
                array('_controller' => $controller.':index', 'entity' => $entity),
                array(),
                array()
            ),
            array(
                $this->router->generateName('create', $metadata),
                $patternPrefix . '/create',
                array('_controller' => $controller.':create', 'entity' => $entity),
                array(),
                array('GET', 'POST')
            ),
            array(
                $this->router->generateName('update', $metadata),
                $patternPrefix . '/{id}/update',
                array('_controller' => $controller.':update', 'entity' => $entity),
                array('id' => '\d+'),
                array('GET', 'POST')
            ),
            array(
                $this->router->generateName('remove', $metadata),
                $patternPrefix . '/{id}/remove',
                array('_controller' => $controller.':remove', 'entity' => $entity),
                array('id' => '\d+'),
                array('POST')
            ),
        );

        // Controller is a service. Append Action to _controller route definition
        return array_map(function ($item) use ($controller) {
            if (strpos($controller, ':') === false) {
                $item[2]['_controller'] .= 'Action';
            }
            return $item;
        }, $data);
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
