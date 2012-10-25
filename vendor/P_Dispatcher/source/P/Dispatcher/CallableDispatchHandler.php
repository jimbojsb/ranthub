<?php
/**
 * P Framework
 * @link http://github.com/pframework
 * @license UNLICENSE http://unlicense.org/UNLICENSE
 * @copyright Public Domain
 * @author Ralph Schindler <ralph@ralphschindler.com>
 */

namespace P\Dispatcher;

class CallableDispatchHandler implements DispatchHandlerInterface
{
    protected $bindClosureTo = null;

    public function __construct($bindClosureTo = null)
    {
        $this->bindClosureTo = $bindClosureTo;
    }

    public function canDispatch($dispatchable)
    {
        return is_callable($dispatchable);
    }

    public function dispatch($dispatchable, array $arguments = array())
    {
//        if ($dispatchable instanceof \Closure && version_compare(PHP_VERSION, '5.4.0', '>=')) {
//            /** @var $dispatchable \Closure */
//            $dispatchable->bindTo($this->serviceLocator);
//        }

        return call_user_func_array($dispatchable, $arguments);
    }
}
