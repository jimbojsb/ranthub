<?php

namespace MiniP;

use P\ServiceLocator as BaseServiceLocator;

class ServiceLocator extends BaseServiceLocator
{
    public function __construct(Router $router = null, Dispatcher $dispatcher = null, callable $errorHandler = null)
    {
        // router
        $this->set('router', $router ?: new Router());

        // dispatcher
        $this->set('dispatcher', $dispatcher ?: new Dispatcher($this));

        // error handler
        $this->set('errorhandler', $errorHandler ?: function () { return new DefaultErrorHandler(); }, true);
    }

}
