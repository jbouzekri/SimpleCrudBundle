<?php

namespace Jb\Bundle\SimpleCrudBundle\Form\Submit;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata;

/**
 * CoreSubmitAction
 *
 * @author jobou
 */
class CoreSubmitAction implements SubmitActionInterface
{
    /**
     * @var \Symfony\Bridge\Doctrine\RegistryInterface
     */
    protected $doctrine;

    /**
     * Constructor
     *
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * {@inheritDoc}
     */
    public function submit(FormInterface $form, Request $request, CrudMetadata $metadata)
    {
        $em = $this->doctrine->getManager();
        $em->persist($form->getData());
        $em->flush();

        return null;
    }
}
