<?php

namespace Jb\Bundle\SimpleCrudBundle\Form\Factory;

use Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata;

/**
 *
 * @author jobou
 */
interface FormFactoryInterface
{
    /**
     * Create a form
     *
     * @param \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadata $metadata
     * @param string $formType
     * @param mixed $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(CrudMetadata $metadata, $formType, $data, $options = array());
}
