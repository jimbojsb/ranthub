<?php

namespace RantHub\View;

use P\Dispatcher;

class ViewScript
{
    protected $script;
    protected $variables;

    public function __construct($script, array $variables = array())
    {
        $this->script = $script;
        $this->variables = $variables;
    }

    public function getScript()
    {
        return $this->script;
    }

    public function getVariables()
    {
        return $this->variables;
    }

}