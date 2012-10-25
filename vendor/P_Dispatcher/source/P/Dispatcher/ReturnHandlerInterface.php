<?php
/**
 * P Framework
 * @link http://github.com/pframework
 * @license UNLICENSE http://unlicense.org/UNLICENSE
 * @copyright Public Domain
 * @author Ralph Schindler <ralph@ralphschindler.com>
 */

namespace P\Dispatcher;

interface ReturnHandlerInterface
{
    public function getReturnType();
    public function handle($returnValue);
}
