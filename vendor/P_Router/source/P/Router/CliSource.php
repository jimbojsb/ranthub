<?php
/**
 * P Framework
 * @link http://github.com/pframework
 * @license UNLICENSE http://unlicense.org/UNLICENSE
 * @copyright Public Domain
 * @author Ralph Schindler <ralph@ralphschindler.com>
 */

namespace P\Router;

class CliSource implements SourceInterface
{

    protected $command;

    public function __construct()
    {
        $argv = $_SERVER['argv'];
        $this->command = array_splice($argv, 1);
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function getContent()
    {
        return file_get_contents('php://input');
    }

}
