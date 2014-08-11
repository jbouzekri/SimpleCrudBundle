<?php

namespace Jb\Bundle\SimpleCrudBundle\Form\Factory;

use Symfony\Component\Form\FormFactoryInterface as SfFormFactoryInterface;

/**
 * CrudFormFactory
 *
 * @author jobou
 */
class CrudFormFactory implements FormFactoryInterface
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * Constructor
     *
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     */
    public function __construct(SfFormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     *
     * @param array $configuration
     * @param mixed $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(array $configuration, $data, $options = array())
    {
        if (isset($configuration['type'])) {
            return $this->formFactory->create($configuration['type'], $data, $options);
        }

        $formBuilder = $this->formFactory->createBuilder('form', $data, $options);
        foreach ($configuration as $field) {
            $formBuilder->add($field);
        }

        // Add default submit button
        $formBuilder->add('submit', 'submit');

        return $formBuilder->getForm();
    }
}
