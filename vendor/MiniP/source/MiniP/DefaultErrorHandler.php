<?php

namespace MiniP;

class DefaultErrorHandler
{
    public function __invoke($type, $exception = null)
    {

        switch ($type) {

            case \MiniP\Application::ERROR_NOT_DISPATCHABLE:
                if (php_sapi_name() != 'cli') {
                    header('HTTP/1.0 404 Not Found');
                    echo 'Not Found.' . PHP_EOL;
                } else {
                    echo 'Unknown command.' . PHP_EOL;
                }
                exit(-1);
            case \MiniP\Application::ERROR_EXCEPTION:
                if (php_sapi_name() != 'cli') {
                    header('HTTP/1.0 500 Application Error');
                }
                echo 'An application error has occrued: '
                    . $exception->getMessage() . "\n" . $exception->getTraceAsString() . PHP_EOL;
                exit(-1);
        }
    }

}
