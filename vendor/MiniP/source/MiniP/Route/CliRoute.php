<?php

namespace MiniP\Route;

use P\Router;

class CliRoute extends Router\CliRoute implements ApplicationRouteInterface
{

    protected $dispatchable = null;

    public function __construct($specification, $dispatchable, array $parameterDefaults = array(), array $parameterValidators = array())
    {
        $this->dispatchable = $dispatchable;
        parent::__construct($specification, $parameterDefaults, $parameterValidators);
    }

    public function getDispatchable()
    {
        return $this->dispatchable;
    }

}
