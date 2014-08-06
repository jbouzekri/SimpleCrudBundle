<?php

namespace Jb\Bundle\SimpleCrudBundle\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

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
    protected $pages;

    /**
     * Constructor
     *
     * @param array $pages {
     *     An array of arrays each representing an entity and its url prefix
     *
     *     @type array $page {
     *         @type string $type
     *         @type string $entity
     *     }
     * }
     */
    public function __construct(array $pages)
    {
        $this->pages = $pages;
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

        foreach ($this->pages as $page) {
            $this->appendRoutes($routes, $page['type'], $page['entity']);
        }

        $this->loaded = true;

        return $routes;
    }

    /**
     * Append route for a page type
     *
     * @param \Symfony\Component\Routing\RouteCollection $routes
     * @param string $page
     * @param string $entity
     *
     * @return void
     */
    protected function appendRoutes(RouteCollection $routes, $page, $entity)
    {
        $routeNamePrefix = 'jb_simple_crud_'.str_replace('-', '_', $page);
        $patternPrefix = '/'.  urlencode($page);

        $routesConfigurations = array(
            array(
                $routeNamePrefix . "_index",
                $patternPrefix,
                array('_controller' => 'jb_simple_crud.controller:indexAction', 'entity' => $entity),
                false
            ),
            array(
                $routeNamePrefix . "_create",
                $patternPrefix . '/create',
                array('_controller' => 'jb_simple_crud.controller:createAction', 'entity' => $entity),
                false
            ),
            array(
                $routeNamePrefix . "_update",
                $patternPrefix . '/{id}/update',
                array('_controller' => 'jb_simple_crud.controller:updateAction', 'entity' => $entity),
                true
            ),
            array(
                $routeNamePrefix . "_remove",
                $patternPrefix . '/{id}/remove',
                array('_controller' => 'jb_simple_crud.controller:removeAction', 'entity' => $entity),
                true
            ),
        );

        foreach ($routesConfigurations as $configuration) {
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