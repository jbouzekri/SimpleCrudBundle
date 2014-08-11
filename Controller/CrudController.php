<?php

namespace Jb\Bundle\SimpleCrudBundle\Controller;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Jb\Bundle\SimpleCrudBundle\Form\Factory\FormFactoryInterface;
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
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface
     */
    protected $templating;

    /**
     * @var \Jb\Bundle\SimpleCrudBundle\Form\Factory\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * Constructor
     */
    public function __construct(
        RegistryInterface $doctrine,
        CrudMetadataList $configuration,
        EngineInterface $templating,
        FormFactoryInterface $formFactory
    ) {
        $this->doctrine = $doctrine;
        $this->configuration = $configuration;
        $this->templating = $templating;
        $this->formFactory = $formFactory;
    }

    /**
     * List entity
     *
     * @param string $entity the entity namespace
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
            'templates' => $metadata->getTemplates(),
            'columns' => $metadata->getColumns()
        ));
    }

    /**
     * Create entity
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $entity the entity namespace
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request, $entity)
    {
        $metadata = $this->configuration->getMetadata($entity);

        $newEntity = new $entity();
        $form = $this->formFactory->createForm($metadata->getFormCreate(), $newEntity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($newEntity);
            $em->flush();

            //return $this->redirect(
            //    $this->generateUrl('jb_test_admin_show', array('id' => $entity->getId())));
        }

        $createTemplate = $metadata->getTemplate('create');
        return $this->templating->renderResponse($createTemplate, array(
            'form' => $form->createView(),
            'templates' => $metadata->getTemplates()
        ));
    }
}
