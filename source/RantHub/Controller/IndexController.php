<?php

namespace RantHub\Controller;

use MiniP\ServiceLocator;
use RantHub\Form;
use RantHub\View;

class IndexController
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
        return new View\ViewScript('index/index.phtml', array('messages' => 'hello, from the IndexController'));
    }
}
