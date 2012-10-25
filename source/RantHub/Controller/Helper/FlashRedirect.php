<?php

namespace PPMSite\Controller\Helper;

class FlashRedirect extends Redirect
{

    protected $flashMessage = null;

    public function __construct($flashMessage, $redirectUrl, $code = 302)
    {
        $this->flashMessage = $flashMessage;
        parent::__construct($redirectUrl, $code);
    }

    public function getFlashMessage()
    {
        return $this->flashMessage;
    }

}
