<?php

namespace RantHub;

use MiniP\ApplicationContextInterface;
use MiniP\Application;
use MiniP\ServiceLocator;
//use RantHub\Model;
//use RantHub\View;
use RantHub\Controller;

class ApplicationContext implements ApplicationContextInterface
{
    protected $path = null;
    protected $mode = null;

    /** @var Application */
    protected $application = null;

    public function __construct($path, $mode = 'production')
    {
        $this->path = $path;
        $this->mode = $mode;
        $this->serviceLocator = new ServiceLocator;
    }

    public function createServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function initialize(Application $application)
    {
        // if (!extension_loaded('aop')) {
        //     throw new \RuntimeException('This application requires aop extension');
        // }

        $this->application = $application;

        $application->setVariable('path', $this->path);
        $application->setVariable('mode', $this->mode);

        $this->initializeServices();
        $this->initializeRoutes();
        $this->initializeAspects();

        $config = $this->application->getServiceLocator()->get('configuration');

        // setup view script handling
        $application->dispatcher->registerReturnHandlers([
            $viewScriptHandler = new View\ViewScriptHandler($config['viewscript.path']),
            new Controller\Helper\RedirectHandler()
        ]);

        // setup php session
        session_cache_limiter(false);
        session_start();

        if (isset($_SESSION['flash_message'])) {
            $viewScriptHandler->setVariable('flash_message', $_SESSION['flash_message']);
            unset($_SESSION['flash_message']);
        }

        if (isset($_SESSION['user'])) {
            $viewScriptHandler->setVariable('user', $_SESSION['user']);
        }
    }

    protected function initializeServices()
    {
        $serviceLocator = $this->serviceLocator;

        $configuration = include $this->path . '/resource/configuration/application.php';
        $serviceLocator->set('configuration', $configuration[$this->mode]);

        $services = [

            'errorhandler' => function () {
                return new ErrorHandler($this);
            },

//            'MongoDb' => function () {
//                return (new \Mongo)->ppmphp;
//            },

//            'UserRepository' => function () {
//                return new Model\UserRepository(
//                    $this->get('MongoDb')->user
//                );
//            },

//            'PackageRepository' => function () {
//                return new Model\PackageRepository(
//                    $this->get('MongoDb')->package
//                );
//            },
//
//            'AuthenticationService' => function () {
//                return new Model\AuthenticationService(
//                    $this->get('UserRepository')
//                );
//            },

        ];

        foreach ($services as $name => $service) {
            $serviceLocator->set($name, $service);
        }
    }

    protected function initializeRoutes()
    {
        /** @var $router \MiniP\Router */
        $router = $this->application->router;

        $routes = array(
            'index' => array(
                '/',
                'RantHub\Controller\IndexController->indexAction'
            ),

            // user centric routes
//            'user:login' => [
//                '/login',
//                'PPMSite\Controller\UserController->loginAction'
//            ],
//            'user:logout' => [
//                '/logout',
//                'PPMSite\Controller\UserController->logoutAction'
//            ],
//            'user:profile' => [
//                '/profile',
//                'PPMSite\Controller\UserController->profileAction'
//            ],
//
//            // package centric routes
//            'package:get' => [
//                '/package/:packagename',
//                'PPMSite\Controller\PackageController->getAction'
//            ],
//
//            'package:user' => [
//                '/package/:username/:packagename',
//                'PPMSite\Controller\PackageController->userRestAction'
//            ]
        );

        // setup app routes
        $routeStack = $router->getRouteStack();
        foreach ($routes as $name => $specification) {
            $routeStack->offsetSet($name, $specification);
        }

    }

    protected function initializeAspects()
    {
//        // setup aspects
//        $this->aspects['access_control'] = new Controller\Aspect\AccessControlAspect($this->application->getServiceLocator());
//        $this->aspects['access_control']->registerAdvices();
    }

}