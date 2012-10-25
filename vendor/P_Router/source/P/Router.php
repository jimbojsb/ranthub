<?php
/**
 * P Framework
 * @link http://github.com/pframework
 * @license UNLICENSE http://unlicense.org/UNLICENSE
 * @copyright Public Domain
 * @author Ralph Schindler <ralph@ralphschindler.com>
 */

namespace P;

/**
 * @property $routes Router\RouteStack
 */
class Router
{
    /**
     * @var Router\RouteStack
     */
    protected $routeStack = null;
    protected $source = null;
    protected $routeMatchPrototype = null;
    protected $matchedParameters = null;

    /**
     * @param array|Router\RouteStack $routes
     * @param Router\SourceInterface $source
     * @param Router\RouteMatch $routeMatchPrototype
     */
    public function __construct($routes = array(), Router\SourceInterface $source = null, Router\RouteMatch $routeMatchPrototype = null)
    {
        if ($routes instanceof Router\RouteStack) {
            $this->routeStack = $routes;
        } else {
            $this->routeStack = new Router\RouteStack($routes);
        }

        $this->setSource(($source) ?: (php_sapi_name() == 'cli') ? new Router\CliSource : new Router\HttpSource);
        $this->routeMatchPrototype = ($routeMatchPrototype) ?: new Router\RouteMatch;
    }

    public function getRouteStack()
    {
        return $this->routeStack;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource(Router\SourceInterface $source)
    {
        $this->source = $source;
        return $this;
    }
    
    public function getMatchedParameters()
    {
        return $this->matchedParameters;
    }

    public function setMatchedParameters($matchedParameters)
    {
        $this->matchedParameters = $matchedParameters;
        return $this;
    }
    
    public function route()
    {
        /** @var $route Router\RouteInterface */
        foreach ($this->routeStack as $name => $route) {
            $parameters = $route->match($this->source);
            if ($parameters !== false) {
                $routeMatch = clone $this->routeMatchPrototype;
                $routeMatch->setName($name);
                $routeMatch->setRoute($route);
                $routeMatch->setSource($this->source);
                $routeMatch->setParameters($parameters);
                return $routeMatch;
            }
        }

        return false;
    }

    public function match($routeName, Router\SourceInterface $source)
    {
        /** @var $route Router\RouteInterface */
        $route = $this->routeStack[$routeName];
        return $route->match($source);
    }

    public function assemble($routeName, array $parameters)
    {
        /** @var $route Router\RouteInterface */
        $route = $this->routeStack[$routeName];
        return $route->assemble($parameters);
    }

    public function __get($name)
    {
        switch (strtolower($name)) {
            case 'routes':
                return $this->routeStack;
            case 'routestack':
                return $this->routeStack;
        }
        throw new \InvalidArgumentException(
            $name . ' is not a valid magic property.'
        );
    }

}
