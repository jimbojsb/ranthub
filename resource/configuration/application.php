<?php

$config = array(
    'production' => array(
        'viewscript.path' => realpath(__DIR__ . '/../view-script')
        /*
        'log.enable' => true,
        'log.path' => '../logs',
        'debug' => false,
        'db' => array(
            'database' => 'production',
        ),
        'package_directory' => __DIR__ . '/../../data/package/',
        'tmp_directory' => __DIR__ . '/../../data/tmp'
        */
    ),
    'development' => array(
        /*
        'log.enable' => true,
        'debug' => true,
        'db' => array(
            'driver' => 'Pdo_Sqlite',
            'database' => __DIR__ . '/../../data/development.db'
        ),
        */
    )
);

$config['development'] = array_merge($config['production'], $config['development']);
return $config;