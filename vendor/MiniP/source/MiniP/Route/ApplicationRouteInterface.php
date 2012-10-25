<?php

namespace MiniP\Route;

use P\Router\RouteInterface;

interface ApplicationRouteInterface extends RouteInterface {
    public function getDispatchable();
}
