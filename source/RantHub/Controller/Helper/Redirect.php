<?php

namespace RantHub\Controller\Helper;

class Redirect
{

    protected $redirectUrl = null;
    protected $code = 302;

    public function __construct($redirectUrl, $code = 302)
    {
        $this->redirectUrl = $redirectUrl;
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }
}