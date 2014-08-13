<?php

namespace Jb\Bundle\SimpleCrudBundle\Form\Submit;

use Jb\Bundle\SimpleCrudBundle\Exception\SubmitActionNotFoundException;

/**
 * SubmitActionChain
 *
 * @author jobou
 */
class SubmitActionChain
{
    /**
     * @var array
     */
    protected $actions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->actions = array();
    }

    /**
     * Add a submit action
     *
     * @param type $action
     */
    public function addSubmitAction(SubmitActionInterface $action, $alias)
    {
        $this->actions[$alias] = $action;
    }

    /**
     * Get a submit action
     *
     * @param string $type
     *
     * @return \Jb\Bundle\SimpleCrudBundle\Form\Submit\SubmitActionInterface
     *
     * @throws SubmitActionNotFoundException
     */
    public function getSubmitAction($type)
    {
        if (!isset($this->actions[$type])) {
            throw new SubmitActionNotFoundException('Action '.$type.' does not exists.');
        }

        return $this->actions[$type];
    }
}