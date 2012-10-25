<?php

namespace MiniP;

class DefaultApplicationContext implements ApplicationContextInterface
{

    public function createServiceLocator()
    {
        return new ServiceLocator;
    }

    public function initialize(Application $application)
    {
        $mode = 'production';
        if (defined('APPLICATION_MODE')) {
            $mode = APPLICATION_MODE;
        } elseif (isset($_ENV['APPLICATION_MODE'])) {
            $mode = $_ENV['APPLICATION_MODE'];
        }
        $application->setVariable('mode', $mode);

        $path = getcwd();
        if (defined('APPLICATION_PATH')) {
            $path = APPLICATION_PATH;
        } elseif (isset($_ENV['APPLICATION_PATH'])) {
            $path = $_ENV['APPLICATION_PATH'];
        }
        $application->setVariable('path', $path);
    }

}
