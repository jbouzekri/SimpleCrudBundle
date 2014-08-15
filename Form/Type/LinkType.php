<?php

namespace Jb\Bundle\SimpleCrudBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\SubmitButtonTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Jb\Bundle\SimpleCrudBundle\Routing\CrudRouter;

/**
 * LinkType
 *
 * @author jobou
 */
class LinkType extends AbstractType implements SubmitButtonTypeInterface
{
    /**
     * @var \Jb\Bundle\SimpleCrudBundle\Routing\CrudRouter
     */
    protected $router;

    /**
     * Constructor
     *
     * @param \Jb\Bundle\SimpleCrudBundle\Routing\CrudRouter $router
     */
    public function __construct(CrudRouter $router)
    {
        $this->router = $router;
    }

     /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['href'] = $this->router->generateCrudUrl($options['route'], $options['metadata'], $options['route_params']);
    }

     /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'route_params' => array(),
            'metadata' => false
        ));

        $resolver->setAllowedValues(array(
            'metadata' => null
        ));

        $resolver->setRequired(array(
            'route',
            'metadata'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'button';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'jb_crud_link';
    }
}
