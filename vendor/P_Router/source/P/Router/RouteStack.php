<?php
/**
 * P Framework
 * @link http://github.com/pframework
 * @license UNLICENSE http://unlicense.org/UNLICENSE
 * @copyright Public Domain
 * @author Ralph Schindler <ralph@ralphschindler.com>
 */

namespace P\Router;

class RouteStack implements \ArrayAccess, \IteratorAggregate
{
    protected $routes = array();

    public function __construct(array $routes = array())
    {
        foreach ($routes as $name => $route) {
            $this->offsetSet($name, $route);
        }
    }

    /**
     * @param mixed $name
     * @param mixed $route
     * @throws \InvalidArgumentException
     */
    public function offsetSet($name, $route)
    {
        if (!$route instanceof RouteInterface) {
            throw new \InvalidArgumentException('A route must implement RouteInterface');
        }
        $this->routes[$name] = $route;
    }

    /**
     * @param string $name
     * @return RouteInterface
     */
    public function offsetGet($name)
    {
        return $this->routes[$name];
    }

    public function offsetUnset($name)
    {
        unset($this->routes[$name]);
    }

    public function offsetExists($name)
    {
        return isset($this->routes[$name]);
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->routes);
    }

}
