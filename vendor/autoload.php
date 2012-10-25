<?php

$vendor_packages = array(
    'MiniP' => __DIR__ . '/MiniP/source/',
    'P\Dispatcher' => __DIR__ . '/P_Dispatcher/source/',
    'P\Router' => __DIR__ . '/P_Router/source/',
    'P\ServiceLocator' => __DIR__ . '/P_ServiceLocator/source/',
);

spl_autoload_register(function ($class) use ($vendor_packages) {
    foreach ($vendor_packages as $ns => $path) {
        if (strpos($class, $ns) === 0) {
            include $path . str_replace('\\', '/', $class) . '.php';
            return;
        }
    }
});