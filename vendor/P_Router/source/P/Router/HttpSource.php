<?php
/**
 * P Framework
 * @link http://github.com/pframework
 * @license UNLICENSE http://unlicense.org/UNLICENSE
 * @copyright Public Domain
 * @author Ralph Schindler <ralph@ralphschindler.com>
 */

namespace P\Router;

class HttpSource implements SourceInterface
{

    public function getCommand()
    {
        return array(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $_SERVER['SERVER_PROTOCOL']
        );
    }

    public function getContent()
    {
        return file_get_contents('php://input');
    }

}
