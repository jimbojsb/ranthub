<?php

namespace RantHub\Controller;

use MiniP\ServiceLocator;
use RantHub\View;

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
        if ($_POST) {
            $data = $_POST;
            //https://raw.github.com/zendframework/zf2/master/library/Zend/Soap/AutoDiscover.php
            //https://github.com/zendframework/zf2/blob/master/library/Zend/Soap/AutoDiscover.php#L26-39

            $rawUrl = str_replace(array('github.com', 'blob/'), array('raw.github.com', ''), $data["url"]);

            $linesNums = str_replace('L', '', parse_url($data["url"], PHP_URL_FRAGMENT));
            $linesNums = explode('-', $linesNums);


            $lines = file($rawUrl);

            if (count($linesNums) == 1) {
                $linesToSave[] = $lines[$linesNums[0] + 1];
            } else {
                for ($c = $linesNums[0] + 1; $c < $linesNums[1]; $c++) {
                    $linesToSave[] = $lines[$c];
                }
            }
            $data["lines"] = $linesToSave;


            $mongo = $this->serviceLocator->get('MongoDb');


            $mongo->rants->insert($data);



            header("Location: /");
        } else {
            return new View\ViewScript('submit/submit.phtml');
        }
    }
}
