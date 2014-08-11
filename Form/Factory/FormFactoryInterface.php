<?php

namespace Jb\Bundle\SimpleCrudBundle\Form\Factory;

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
    public function createForm(array $configuration, $data, $options = array());
}
