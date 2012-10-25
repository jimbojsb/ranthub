P_ServiceLocator
================

P_ServiceLocator is an implementation of a Service Locator for PHP 5.4.
This implementation will work on PHP 5.3, but will not bind closures
to the Service Locator instance.

Examples
--------

Example of a database service, with configuration:

```php
<?php
// In bootstrap
use P\ServiceLocator;
$sl = new ServiceLocator;
$sl->set('configuration', [
    'db' => [
        'dsn' => 'mysql:dbname=testdb;host=127.0.0.1',
        'username' => 'dev',
        'password' => 'dev',
    ]
]);
$sl->set('db', function () {
    $dbc = $this->get('configuration')['db'];
    return new \PDO($dbc['dsn'], $dbc['username'], $dbc['password']);
});

// elsewhere: get an instance of the db service (configured)
$db = $sl->get('db');

```


Contributors
------------

Ralph Schindler (@ralphschindler)