<?php

namespace Jb\Bundle\SimpleCrudBundle\Controller;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Jb\Bundle\SimpleCrudBundle\Config\CrudMetadataList;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

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
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
     */
    protected $templating;

    /**
     * Constructor
     */
    public function __construct(
        RegistryInterface $doctrine,
        CrudMetadataList $configuration,
        EngineInterface $templating
    ) {
        $this->doctrine = $doctrine;
        $this->configuration = $configuration;
        $this->templating = $templating;
    }

    /**
     * List entity
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($entity)
    {
        $metadata = $this->configuration->getMetadata($entity);
        $manager = $this->doctrine->getManager();

        $entities = $manager->getRepository($entity)->findAll();

        $indexTemplate = $metadata->getTemplate('index');
        return $this->templating->renderResponse($indexTemplate, array(
            'entities' => $entities,
            'templates' => $metadata->getTemplates()
        ));
    }
}
