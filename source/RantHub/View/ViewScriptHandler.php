<?php

namespace RantHub\View;

use P\Dispatcher;

class ViewScriptHandler implements Dispatcher\ReturnHandlerInterface
{
    protected $path = null;
    protected $lastIncludePath = null;
    protected $variables = array();

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getReturnType()
    {
        return __NAMESPACE__ . '\ViewScript';
    }

    public function handle($__viewScript)
    {
        /** @var $returnValue PhtmlScript */
        extract(array_merge($this->variables, $__viewScript->getVariables()));
        $this->prepare();
        try {
            include $__viewScript->getScript();
        } catch (\Exception $e) {
            $this->cleanup();
            throw $e;
        }

        $this->cleanup();
    }

    protected function prepare()
    {
        $this->lastIncludePath = get_include_path();
        set_include_path($this->path);
    }

    protected function cleanup()
    {
        set_include_path($this->lastIncludePath);
    }

    public function setVariable($name, $value)
    {
        $this->variables[$name] = $value;
    }

    public function getVariable($name, $value)
    {
        return $this->variables[$name];
    }

}
