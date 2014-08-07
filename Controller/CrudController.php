<?php

namespace Jb\Bundle\SimpleCrudBundle\Controller;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Jb\Bundle\SimpleCrudBundle\Config\CrudMetadataList;

/**
 * CrudController
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class CrudController
{
    /**
     * @var \Symfony\Bridge\Doctrine\RegistryInterface
     */
    protected $doctrine;

    /**
     * @var \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadataList
     */
    protected $configuration;

    /**
     * Constructor
     */
    public function __construct(
        RegistryInterface $doctrine,
        CrudMetadataList $configuration
    ) {
        $this->doctrine = $doctrine;
        $this->configuration = $configuration;
    }

    /**
     * List entity
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($entity)
    {
        $config = $this->configuration->getMetadata($entity);
        var_dump($config);
        $repository = $this->doctrine->getManager()->getRepository($entity);


        return new \Symfony\Component\HttpFoundation\Response('test');
    }
}
