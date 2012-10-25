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
        $mongo = $this->serviceLocator->get('MongoDb');
        $rants = $mongo->rants->find()->limit(10);

        return new View\ViewScript('index/index.phtml', array('rants' => $rants));
    }
}
