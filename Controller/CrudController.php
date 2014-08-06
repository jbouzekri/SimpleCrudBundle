<?php

namespace Jb\Bundle\SimpleCrudBundle\Controller;

use \Symfony\Bridge\Doctrine\RegistryInterface;

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
     * Constructor
     */
    public function __construct(
        RegistryInterface $doctrine
    ) {
        $this->doctrine = $doctrine;
    }

    /**
     * List entity
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($entity)
    {
        $repository = $this->doctrine->getManager()->getRepository($entity);

        
        return new \Symfony\Component\HttpFoundation\Response('test');
    }
}
