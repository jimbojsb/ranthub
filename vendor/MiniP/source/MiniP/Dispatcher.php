<?php

namespace MiniP;

use P\Dispatcher as BaseDispatcher;

class Dispatcher extends BaseDispatcher
{
    public function __construct(ServiceLocator $serviceLocator)
    {
        $this->registerDispatchHandler(new BaseDispatcher\CallableDispatchHandler);
        $this->registerDispatchHandler(new BaseDispatcher\InstantiatorDispatchHandler(
            array($serviceLocator)
        ));
        $this->registerReturnHandler(new BaseDispatcher\EchoStringReturnHandler);
    }
}