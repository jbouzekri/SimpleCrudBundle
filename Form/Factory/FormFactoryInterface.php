<?php

namespace Jb\Bundle\SimpleCrudBundle\Form\Factory;

use Jb\Bundle\SimpleCrudBundle\Config\CrudMetadataForm;

/**
 *
 * @author jobou
 */
interface FormFactoryInterface
{
    /**
     * Create a form using metadata configuration
     *
     * @param array $configuration
     * @param mixed $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(CrudMetadataForm $configuration, $data, $options = array());
}
