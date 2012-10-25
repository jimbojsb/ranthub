<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../source/autoload.php';

$app = new MiniP\Application(
    new RantHub\ApplicationContext(realpath(__DIR__ . '/../'), 'development')
);
$app->run();
