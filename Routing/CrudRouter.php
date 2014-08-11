<?php

namespace Jb\Bundle\SimpleCrudBundle\Routing;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata;

/**
 * Description of CrudRouter
 *
 * @author jobou
 */
class CrudRouter
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * Constructor
     *
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Generate a crud url
     *
     * @param string $route
     * @param \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata $metadata
     * @param array $parameters
     * @param bool $referenceType
     *
     * @return string
     */
    public function generateCrudUrl(
        $route,
        CrudMetadata $metadata,
        $parameters = array(),
        $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    ) {
        return $this->generateUrl($this->generateName($route, $metadata), $parameters, $referenceType);
    }

    /**
     * Generate a route name
     *
     * @param string $route
     * @param \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata $metadata
     *
     * @return string
     */
    public function generateName($route, CrudMetadata $metadata)
    {
        $page = $metadata->getPage();

        return 'jb_simple_crud_' . str_replace('-', '_', $page) . '_' . $route;
    }

    /**
     * Generate an url
     *
     * @param string $route
     * @param array $parameters
     * @param bool $referenceType
     *
     * @return string
     */
    public function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }
}
