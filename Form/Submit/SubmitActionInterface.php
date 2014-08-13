<?php

namespace Jb\Bundle\SimpleCrudBundle\Form\Submit;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata;

/**
 * SubmitActionInterface
 *
 * @author jobou
 */
interface SubmitActionInterface
{
    /**
     * Process a submitted form
     *
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata $metadata
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function submit(FormInterface $form, Request $request, CrudMetadata $metadata);
}
