<?php

namespace Jb\Bundle\SimpleCrudBundle\Form\Submit;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Jb\Bundle\SimpleCrudBundle\Routing\CrudRouter;
use Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata;

/**
 * CreateSubmitAction
 *
 * @author jobou
 */
class CreateSubmitAction extends DefaultSubmitAction
{
    /**
     * @var \Jb\Bundle\SimpleCrudBundle\Routing\CrudRouter
     */
    protected $router;

    /**
     * Constructor
     *
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine, CrudRouter $router)
    {
        parent::__construct($doctrine);
        $this->router = $router;
    }

    /**
     * {@inheritDoc}
     */
    public function submit(FormInterface $form, Request $request, CrudMetadata $metadata)
    {
        parent::submit($form, $request, $metadata);

        $request->getSession()->getFlashBag()->add(
            'notice',
            'New entity created!'
        );

        return new RedirectResponse(
            $this->router->generateCrudUrl('update', $metadata, array('id' => $form->getData()->getId()))
        );
    }
}
