<?php

namespace RantHub\Controller;

use MiniP\ServiceLocator;
use RantHub\View;
use RantHub\Form;

class SubmitController
{
    
    /**
     * @var ServiceLocator
     */
    protected $serviceLocator;

    public function __construct(ServiceLocator $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
    
    public function indexAction()
    {
        $form = new Form\SubmitForm;

        return new View\ViewScript('submit/submit.phtml', array('form' => $form));
    }
}
