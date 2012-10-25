<?php

// setup autoloader for source
spl_autoload_register(function ($class) {
    if (strpos($class, 'RantHub\\') === 0) {
        include __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    }
});