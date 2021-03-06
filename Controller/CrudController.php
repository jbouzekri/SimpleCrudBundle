<?php

namespace Jb\Bundle\SimpleCrudBundle\Controller;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Jb\Bundle\SimpleCrudBundle\Form\Factory\FormFactoryInterface;
use Jb\Bundle\SimpleCrudBundle\Config\CrudMetadataList;
use Jb\Bundle\SimpleCrudBundle\Routing\CrudRouter;
use Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata;
use Jb\Bundle\SimpleCrudBundle\Form\Submit\SubmitActionChain;

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
     * @var \Jb\Bundle\SimpleCrudBundle\Routing\CrudRouter
     */
    protected $router;

    /**
     * @var \Jb\Bundle\SimpleCrudBundle\Form\Submit\SubmitActionChain
     */
    protected $submitActions;

    /**
     * Constructor
     */
    public function __construct(
        RegistryInterface $doctrine,
        CrudMetadataList $configuration,
        EngineInterface $templating,
        FormFactoryInterface $formFactory,
        CrudRouter $router,
        SubmitActionChain $submitActions
    ) {
        $this->doctrine = $doctrine;
        $this->configuration = $configuration;
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->submitActions = $submitActions;
    }

    /**
     * List entity
     *
     * @param \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata $metadata
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(CrudMetadata $metadata)
    {
        $manager = $this->doctrine->getManager();

        $entities = $manager->getRepository($metadata->getEntity())->findAll();

        $indexTemplate = $metadata->getTemplate('index');
        return $this->templating->renderResponse($indexTemplate, array(
            'entities' => $entities,
            'templates' => $metadata->getTemplates(),
            'columns' => $metadata->getColumns(),
            'metadata' => $metadata
        ));
    }

    /**
     * Create entity
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata $metadata
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request, CrudMetadata $metadata)
    {
        $entity = $metadata->getEntity();

        $newEntity = new $entity();
        $form = $this->formFactory->createForm(
            $metadata,
            'create',
            $newEntity,
            array('data_class' => $entity, 'translation_domain' => $metadata->getTranslationDomain())
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            // Find the submit action
            $submitName = $form->getClickedButton()->getName();
            $submitAction = $this->submitActions->getSubmitAction($submitName);

            // Process the form
            return $submitAction->submit($form, $request, $metadata);
        }

        $createTemplate = $metadata->getTemplate('create');
        return $this->templating->renderResponse($createTemplate, array(
            'form' => $form->createView(),
            'metadata' => $metadata
        ));
    }

    /**
     * Update entity
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata $metadata
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, CrudMetadata $metadata, $id)
    {
        $entity = $this->doctrine->getRepository($metadata->getEntity())->find($id);

        $form = $this->formFactory->createForm(
            $metadata,
            'edit',
            $entity,
            array('data_class' => $metadata->getEntity(), 'translation_domain' => $metadata->getTranslationDomain())
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($entity);
            $em->flush();

            $request->getSession()->getFlashBag()->add(
                'notice',
                'Your changes were saved!'
            );
        }

        $createTemplate = $metadata->getTemplate('edit');
        return $this->templating->renderResponse($createTemplate, array(
            'form' => $form->createView(),
            'metadata' => $metadata
        ));
    }

    /**
     * Remove action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata $metadata
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeAction(Request $request, CrudMetadata $metadata, $id)
    {
        $em = $this->doctrine->getManager();

        $entity = $em->getRepository($metadata->getEntity())->find($id);

        $em->remove($entity);
        $em->flush();

        $request->getSession()->getFlashBag()->add(
            'notice',
            'Entity removed!'
        );

        return new RedirectResponse($this->router->generateCrudUrl('index', $metadata));
    }
}
