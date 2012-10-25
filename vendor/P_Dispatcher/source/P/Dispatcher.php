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
 * P\Dispatcher
 */
class Dispatcher
{
    /** @var Dispatcher\DispatchHandlerInterface[] */
    protected $dispatchHandlers = array();

    /** @var Dispatcher\ReturnHandlerInterface[] */
    protected $returnHandlers = array();

    /**
     * @param Dispatcher\DispatchHandlerInterface[] $dispatchHandlers
     * @param Dispatcher\ReturnHandlerInterface[] $returnHandlers
     */
    public function __construct(array $dispatchHandlers = array(), array $returnHandlers = array())
    {
        if ($dispatchHandlers) {
            $this->registerDispatchHandlers($dispatchHandlers);
        }

        if ($returnHandlers) {
            $this->registerReturnHandlers($returnHandlers);
        }
    }

    /**
     * @return Dispatcher
     */
    public function resetDispatchHandlers()
    {
        $this->dispatchHandlers = array();
        return $this;
    }

    /**
     * @return Dispatcher
     */
    public function resetReturnHandlers()
    {
        $this->returnHandlers = array();
        return $this;
    }

    /**
     * @param Dispatcher\DispatchHandlerInterface[] $dispatchHandlers
     */
    public function registerDispatchHandlers(array $dispatchHandlers)
    {
        foreach ($dispatchHandlers as $dispatchHandler) {
            $this->registerDispatchHandler($dispatchHandler);
        }
    }

    /**
     * @param Dispatcher\DispatchHandlerInterface $dispatchHandler
     * @return Dispatcher
     */
    public function registerDispatchHandler(Dispatcher\DispatchHandlerInterface $dispatchHandler)
    {
        $this->dispatchHandlers[] = $dispatchHandler;
        return $this;
    }

    /**
     * @param Dispatcher\ReturnHandlerInterface[] $returnHandlers
     */
    public function registerReturnHandlers(array $returnHandlers)
    {
        foreach ($returnHandlers as $returnHandler) {
            $this->registerReturnHandler($returnHandler);
        }
    }

    /**
     * @param Dispatcher\ReturnHandlerInterface $returnHandler
     * @return Dispatcher
     * @throws \InvalidArgumentException
     */
    public function registerReturnHandler(Dispatcher\ReturnHandlerInterface $returnHandler)
    {
        $returnTypes = $returnHandler->getReturnType();
        if (is_string($returnTypes)) {
            $returnTypes = array($returnTypes);
        }
        foreach ($returnTypes as $returnType) {
            if (array_key_exists($returnType, $this->returnHandlers)) {
                throw new \InvalidArgumentException(
                    'A handler for the response type ' . $returnType . ' has already been registered'
                );
            }
            $this->returnHandlers[$returnType] = $returnHandler;
        }
        return $this;
    }

    /**
     * @param $dispatchable
     * @param mixed $arguments
     * @return mixed
     */
    public function dispatch($dispatchable, $arguments = null)
    {
        $returnValue = null;
        $dispatched = false;
        /** @var $dispatchHandler Dispatcher\DispatchHandlerInterface */
        foreach ($this->dispatchHandlers as $dispatchHandler) {
            if ($dispatchHandler->canDispatch($dispatchable)) {
                $dispatched = true;
                $returnValue = $dispatchHandler->dispatch($dispatchable, $arguments);
                break;
            }
        }

        if ($dispatched === false) {
            throw new \RuntimeException('Unable to dispatch dispatchable');
        }

        if (isset($returnValue)) {
            /** @var $returnHandler Dispatcher\ReturnHandlerInterface */
            foreach ($this->returnHandlers as $responseType => $returnHandler) {
                if (in_array($responseType, array('string', 'array', 'bool', 'null'))) {
                    if (gettype($returnValue) == $responseType) {
                        $returnHandler->handle($returnValue);
                    }
                } elseif ($returnValue instanceof $responseType) {
                    return $returnHandler->handle($returnValue);
                }
            }
        }

        return $returnValue;
    }
    
}
