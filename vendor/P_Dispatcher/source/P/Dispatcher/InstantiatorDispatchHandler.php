<?php
/**
 * P Framework
 * @link http://github.com/pframework
 * @license UNLICENSE http://unlicense.org/UNLICENSE
 * @copyright Public Domain
 * @author Ralph Schindler <ralph@ralphschindler.com>
 */

namespace P\Dispatcher;

class InstantiatorDispatchHandler implements DispatchHandlerInterface
{
    protected $constructorArguments = null;
    protected $extractMethodArguments = true;

    public function __construct(array $constructorArguments = array(), $extractMethodArguments = true)
    {
        $this->constructorArguments = $constructorArguments;
        $this->extractMethodArguments = (bool) $extractMethodArguments;
    }

    public function setConstructorArguments(array $constructorArguments)
    {
        $this->constructorArguments = $constructorArguments;
        return $this;
    }

    public function setExtractMethodArguments($extractMethodArguments)
    {
        $this->extractMethodArguments = (bool) $extractMethodArguments;
        return $this;
    }

    public function canDispatch($specification)
    {
        if (!is_string($specification)) {
            return false;
        }
        return preg_match('#([\\\\\w]+)->(\w+)#', $specification);
    }

    public function dispatch($specification, array $arguments = array())
    {
        $matches = null;
        preg_match('#([\\\\\w]+)->(\w+)#', $specification, $matches);
        $class = $matches[1];
        $method = $matches[2];

        // switch to avoid Reflection in most common use cases
        switch (count($this->constructorArguments)) {
            case 0:
                $obj = new $class();
                break;
            case 1:
                $obj = new $class($this->constructorArguments[0]);
                break;
            case 2:
                $obj = new $class($this->constructorArguments[0], $this->constructorArguments[1]);
                break;
            case 3:
                $obj = new $class($this->constructorArguments[0], $this->constructorArguments[1], $this->constructorArguments[2]);
                break;
            default:
                $r = new \ReflectionClass($class);
                $obj = $r->newInstanceArgs($this->constructorArguments);
                break;
        }


        if ($this->extractMethodArguments) {
            $arguments = array_values($arguments);

            // switch to be nice to stack traces to avoid call_user_func_array item
            switch (count($arguments)) {
                case 0: return $obj->{$method}();
                case 1: return $obj->{$method}($arguments[0]);
                case 2: return $obj->{$method}($arguments[0], $arguments[1]);
                case 3: return $obj->{$method}($arguments[0], $arguments[1], $arguments[2]);
                default: return call_user_func_array(array($obj, $method), $arguments);
            }

        } else {
            return $obj->{$method}($arguments);
        }

    }
}
