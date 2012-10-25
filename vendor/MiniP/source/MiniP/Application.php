<?php

namespace MiniP;

/**
 * @proeprty ApplicationContextInterface $context
 * @proeprty ApplicationContextInterface $applicationContext
 * @property Dispatcher $dispatcher
 * @property Router $router
 * @property ServiceLocator $serviceLocator
 * @property ServiceLocator $services
 */
class Application implements \ArrayAccess
{
    const MODE_PRODUCTION = 'production';
    const MODE_DEVELOPMENT = 'development';

    const ERROR_NOT_DISPATCHABLE = 'undispatchable';
    const ERROR_EXCEPTION = 'exception';

    /** @var bool */
    protected $initialized = false;

    /** @var array */
    protected $variables = array();

    /** @var ServiceLocator */
    protected $serviceLocator;

    /** @var Router */
    protected $router;

    /** @var Dispatcher */
    protected $dispatcher;

    /**
     * @param ApplicationContextInterface|callable $applicationContext
     * @param ServiceLocator $serviceLocator
     */
    public function __construct(ApplicationContextInterface $applicationContext = null)
    {
        $this->applicationContext = ($applicationContext) ?: new DefaultApplicationContext;

        // if the app-context provides a service locator, get it
        $this->serviceLocator = $this->applicationContext->createServiceLocator();

        if (!$this->serviceLocator instanceof ServiceLocator) {
            throw new \RuntimeException('ApplicationContext must return an instance of MiniP\ServiceLocator from createServiceLocator()');
        }

        $this->router = $this->serviceLocator->get('router');
        $this->dispatcher = $this->serviceLocator->get('dispatcher');
    }

    /**
     * @param $name
     * @param $value
     */
    public function setVariable($name, $value)
    {
        $this->variables[$name] = $value;
    }

    /**
     * @param $name
     * @return null
     */
    public function getVariable($name)
    {
        return (isset($this->variables[$name])) ? $this->variables[$name] : null;
    }

    /**
     * @return void
     */
    public function initialize()
    {
        if ($this->initialized) {
            return;
        }

        $this->serviceLocator->set('application', $this);

        $this->applicationContext->initialize($this);

        // this will throw an exception if these are not in place
        $this->router = $this->serviceLocator->getValidated('router', 'MiniP\Router');
        $this->dispatcher = $this->serviceLocator->getValidated('dispatcher', 'MiniP\Dispatcher');

        $this->initialized = true;
    }

    /**
     * @return mixed|null
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function run()
    {
        $this->initialize();

        try {
            if (($routeMatch = $this->router->route()) === false) {
                return $this->triggerError(self::ERROR_NOT_DISPATCHABLE);
            }
        } catch (\Exception $e) {
            return $this->triggerError($e);
        }

        /** @var $route Route\ApplicationRouteInterface */
        $route = $routeMatch->getRoute();

        if (!$route instanceof Route\ApplicationRouteInterface) {
            throw new \RuntimeException('Matched route must implement MiniP\ApplicationRouteInterface');
        }

        /** @var $dispatcher Dispatcher */
        $dispatcher = $this->serviceLocator->get('dispatcher');

        $return = null;

        try {
            $return = $dispatcher->dispatch(
                $route->getDispatchable(),
                $routeMatch->getParameters()
            );
        } catch (\Exception $e) {
            if ($this->serviceLocator->has('errorhandler')) {
                return $this->triggerError($e);
            } else {
                throw $e;
            }
        }

        return $return;
    }

    /**
     * @param string|null $routeName
     * @param string|Route\ApplicationRouteInterface $routeSpecification
     * @return Application|void
     */
    public function offsetSet($routeName, $routeSpecification)
    {
        $this->router->routes[$routeName] = $routeSpecification;
        return $this;
    }

    /**
     * Get A Route
     * @param mixed $routeName
     * @return Route\ApplicationRouteInterface
     */
    public function offsetGet($routeName)
    {
        return $this->router->routes[$routeName];
    }

    /**
     * Does Route Exist?
     * @param mixed $routeName
     * @return bool
     */
    public function offsetExists($routeName)
    {
        return isset($this->router->routes[$routeName]);
    }

    /**
     * Remove a Route
     * @param mixed $routeName
     */
    public function offsetUnset($routeName)
    {
        unset($this->router->routes[$routeName]);
    }

    /**
     * @return ServiceLocator
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param string|\Exception $reason
     * @return mixed
     */
    public function triggerError($reason)
    {
        /** @var $errorHandler \Callable */
        $errorHandler = $this->serviceLocator->get('errorhandler');
        if ($reason instanceof \Exception) {
            return $errorHandler(self::ERROR_EXCEPTION, $reason);
        } else {
            return $errorHandler($reason);
        }
    }

    /**
     * @param $name
     * @return DefaultApplicationContext|ServiceLocator|mixed
     * @throws \InvalidArgumentException
     */
    public function __get($name)
    {
        switch (strtolower($name)) {
            case 'context':
            case 'applicationcontext':
                return $this->applicationContext;
            case 'services':
            case 'servicelocator':
                return $this->serviceLocator;
            case 'router':
            case 'dispatcher':
            default:
                if ($this->serviceLocator->has($name)) {
                    return $this->serviceLocator->get($name);
                }
        }

        throw new \InvalidArgumentException(
            $name . ' is not a valid property in the application object or a valid service in the ServiceLocator'
        );
    }

}