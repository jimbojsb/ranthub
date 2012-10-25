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
 * P\ServiceLocator
 */
class ServiceLocator implements \ArrayAccess, \Countable
{
    /** @var mixed[] */
    protected $services = array();

    /** @var mixed[] */
    protected $initializers = array('+' => array());

    /** @var \Closure[] */
    protected $instantiated = array();

    /** @var bool[] */
    protected $modifiables = array();

    /**
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        $name = str_replace(array(' ', '_', '-'), '', strtolower($name));
        return (isset($this->services[$name]));
    }

    /**
     * @param $name
     * @param $service
     * @param bool $modifiable
     * @return ServiceLocator
     * @throws \InvalidArgumentException
     */
    public function set($name, $service, $modifiable = false)
    {
        if (!is_string($name) || $name == '') {
            throw new \InvalidArgumentException('$name must be a string in ServiceLocator::set()');
        }
        if (!is_object($service) && !is_callable($service) && !is_array($service)) {
            throw new \InvalidArgumentException('$service must be an array, object or callable in ServiceLocator::set()');
        }
        $name = str_replace(array(' ', '_', '-'), '', strtolower($name));
        if (isset($this->modifiables[$name]) && $this->modifiables[$name] === false) {
            throw new \InvalidArgumentException(
                'This service ' . $name . ' is already set and not modifiable.'
            );
        }
        // handle initializers
        if (substr($name, -1) == '+') {
            if ($name !== '+' && !$this->has(substr($name, 0, -1))) {
                throw new \InvalidArgumentException(
                    'An initializer cannot be set for a service that does not exist.'
                );
            } elseif ($name === '+' && !is_callable($service)) {
                throw new \InvalidArgumentException(
                    'Instances marked to be a global initializer must be Closures.'
                );
            }
            if ($name === '+') {
                $this->initializers['+'][] = $service;
                return $this;
            }
        }
        $this->services[$name] = $service;
        $this->modifiables[$name] = $modifiable;
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function get($name)
    {
        $name = str_replace(array(' ', '_', '-'), '', strtolower($name));
        static $depth = 0;
        static $allNames = array();
        if (!isset($this->services[$name])) {
            throw new \Exception('Service by name ' . $name . ' was not located in this ServiceLocator');
        }
        if ($depth > 99) {
            throw new \RuntimeException(
                'Recursion detected when trying to resolve these services: ' . implode(', ', $allNames)
            );
        }
        if ($this->services[$name] instanceof \Closure && !isset($this->instantiated[$name])) {
            $depth++;
            $allNames[] = $name;
            /** @var $factory \Closure */
            $factory = $this->services[$name];
            if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
                $factory = $factory->bindTo($this, __CLASS__);
            }
            $this->services[$name] = $factory($this);
            unset($factory);
            $this->instantiated[$name] = true; // allow closure wrapped in a closure
            $depth--;
        }
        if ($depth === 0) {
            while ($allNames) {
                $aName = array_pop($allNames);
                if (isset($this->initializers[$aName . '+'])) {
                    $depth++;
                    /** @var $initializer \Closure */
                    $initializer = $this->initializers[$aName . '+'];
                    if (version_compare(PHP_VERSION, '5.4.0')) {
                        $initializer = $initializer->bindTo($this, __CLASS__);
                    }
                    $initializer($this->services[$aName]);
                    $depth--;
                }
                if ($this->initializers['+']) {
                    foreach ($this->initializers['+'] as $initializer) {
                        if (version_compare(PHP_VERSION, '5.4.0')) {
                            $initializer = $initializer->bindTo($this, __CLASS__);
                        }
                        $initializer($this->services[$aName]);
                    }
                }
                unset($this->services[$aName . '+']);
            }
        }
        return $this->services[$name];
    }

    public function getValidated($name, $expectedType)
    {
        $service = $this->get($name);
        if (!$service instanceof $expectedType) {
            throw new \UnexpectedValueException($name . ' was found, but was not of type ' . $expectedType);
        }
        return $service;
    }

    /**
     * @param $name
     * @return ServiceLocator
     * @throws \InvalidArgumentException
     */
    public function remove($name)
    {
        $name = str_replace(array(' ', '_', '-'), '', strtolower($name));
        if (!isset($this->services[$name])) {
            throw new \InvalidArgumentException($name . ' is not a registered service.');
        }
        if (isset($this->modifiables[$name]) && $this->modifiables[$name] === false) {
            throw new \InvalidArgumentException(
                'This service ' . $name . ' is marked as unmodifiable and therefore cannot be removed.'
            );
        }
        unset($this->services[$name], $this->modifiables[$name]);
        return $this;
    }

    /**
     * @param mixed $name
     * @param mixed $service
     * @return ServiceLocator|void
     */
    public function offsetSet($name, $service)
    {
        return $this->set($name, $service);
    }

    /**
     * @param mixed $name
     * @return mixed
     */
    public function offsetGet($name)
    {
        return $this->get($name);
    }

    /**
     * @param mixed $name
     * @return bool
     */
    public function offsetExists($name)
    {
        return $this->has($name);
    }

    /**
     * @param mixed $name
     * @return ServiceLocator|void
     */
    public function offsetUnset($name)
    {
        return $this->remove($name);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->services);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }
}
