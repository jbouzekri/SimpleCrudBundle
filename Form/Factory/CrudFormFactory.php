<?php

namespace Jb\Bundle\SimpleCrudBundle\Form\Factory;

use Symfony\Component\Form\FormFactoryInterface as SfFormFactoryInterface;
use Jb\Bundle\SimpleCrudBundle\Config\CrudMetadataForm;

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
     * Create a form
     *
     * @param \Jb\Bundle\SimpleCrudBundle\Config\CrudMetadataForm $configuration
     * @param mixed $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(CrudMetadataForm $configuration, $data, $options = array())
    {
        $fields = $configuration->getFields();
        if (isset($fields['type'])) {
            return $this->formFactory->create($fields['type'], $data, $options);
        }

        $formBuilder = $this->formFactory->createBuilder('form', $data, $options);
        foreach ($fields as $field) {
            $formBuilder->add($field);
        }

        // Add submit button or back link
        foreach ($configuration->getActions() as $name => $action) {
            $formBuilder->add($name, $action['type']);
        }

        return $formBuilder->getForm();
    }
}
