<?php

namespace MiniP\Route;

use P\Router\RouteStack as BaseRouteStack;

class RouteStack extends BaseRouteStack
{
    public function offsetSet($name, $route)
    {
        if (is_array($route)) {
            if (!is_string($route[0])) {
                throw new \InvalidArgumentException('The first parameter for a route must be a route specification string');
            }
            if (!is_string($route[1]) && !is_callable($route[1])) {
                throw new \InvalidArgumentException('The second parameter for a route must be a string dispatchable or something callable');
            }
            if (strpos($route[0], '$') === 0) {
                $route[0] = ltrim($route[0], '$ ');
                $route = $this->createCliRouteFromArraySpec($route);
            } else {
                $route = $this->createHttpRouteFromArraySpec($route);
            }

            if (!isset($route)) {
                throw new \InvalidArgumentException('A route could not be created from the given specification');
            }

        } elseif (!$route instanceof ApplicationRouteInterface) {
            throw new \InvalidArgumentException(
                'Needs to be an array or an instance of MiniP\Route\ApplicationRouteInterface'
            );
        }

        parent::offsetSet($name, $route);
    }

    protected function createCliRouteFromArraySpec(array $routeArgs)
    {
        $specification = null;
        $dispatchable = null;
        $parameterDefaults = array();
        $parameterValidators = array();
        $argKeys = array_keys($routeArgs);
        if (is_string($argKeys[0])) {
            extract($routeArgs);
        } else {
            // @todo Exception?
            $args = array_pad($routeArgs, 4, null);
            list($specification, $dispatchable, $parameterDefaults, $parameterValidators) = $args;
        }
        return new CliRoute($specification, $dispatchable, (array) $parameterDefaults, (array) $parameterValidators);
    }

    protected function createHttpRouteFromArraySpec(array $routeArgs)
    {
        $specification = null;
        $dispatchable = null;
        $parameterDefaults = array();
        $parameterValidators = array();
        $argKeys = array_keys($routeArgs);
        if (is_string($argKeys[0])) {
            extract($routeArgs);
        } else {
            // @todo Exception?
            $routeArgs = array_pad($routeArgs, 4, null);
            list($specification, $dispatchable, $parameterDefaults, $parameterValidators) = $routeArgs;
        }
        return new HttpRoute($specification, $dispatchable, (array) $parameterDefaults, (array) $parameterValidators);
    }

}
