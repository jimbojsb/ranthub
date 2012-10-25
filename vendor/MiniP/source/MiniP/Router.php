<?php

namespace MiniP;

use P\Router as BaseRouter;

class Router extends BaseRouter
{

    public function __construct($routes = array())
    {
        if (!$routes instanceof Route\RouteStack) {
            $routes = new Route\RouteStack($routes);
        }
        parent::__construct($routes);
    }

}
