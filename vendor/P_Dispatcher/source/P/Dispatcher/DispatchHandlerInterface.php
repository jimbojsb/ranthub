<?php
/**
 * P Framework
 * @link http://github.com/pframework
 * @license UNLICENSE http://unlicense.org/UNLICENSE
 * @copyright Public Domain
 * @author Ralph Schindler <ralph@ralphschindler.com>
 */

namespace P\Dispatcher;

interface DispatchHandlerInterface
{
    public function canDispatch($dispatchable);
    public function dispatch($dispatchable, array $arguments = array());
}
