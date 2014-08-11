<?php

namespace Jb\Bundle\SimpleCrudBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Jb\Bundle\SimpleCrudBundle\Routing\CrudRouter;
use Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata;

/**
 * Description of RouterExtension
 *
 * @author jobou
 */
class RouterExtension extends \Twig_Extension
{
    /**
     * @var \Jb\Bundle\SimpleCrudBundle\Routing\CrudRouter
     */
    protected $router;

    /**
     * Constructor
     *
     * @param \Jb\Bundle\SimpleCrudBundle\Routing\CrudRouter $router
     */
    public function __construct(CrudRouter $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'crud_path',
                array($this, 'getCrudPath')
            )
        );
    }

    /**
     * Get crud path
     *
     * @param string $router
     * @param \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata $metadata
     * @param array $parameters
     * @param bool $absolute
     *
     * @return string
     */
    public function getCrudPath(
        $router,
        CrudMetadata $metadata,
        $parameters = array(),
        $absolute = UrlGeneratorInterface::ABSOLUTE_PATH
    ) {
        return $this->router->generateCrudUrl($router, $metadata, $parameters, $absolute);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'jb_simple_crud_router_extension';
    }
}
