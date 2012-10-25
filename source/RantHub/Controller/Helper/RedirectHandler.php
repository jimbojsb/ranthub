<?php

namespace RantHub\Controller\Helper;

use P\Dispatcher;

class RedirectHandler implements Dispatcher\ReturnHandlerInterface
{

    public function getReturnType()
    {
        return array(
            __NAMESPACE__ . '\Redirect',
            __NAMESPACE__ . '\FlashRedirect'
        );
    }

    public function handle($redirect)
    {
        /** @var $redirect FlashRedirect */
        if ($redirect instanceof FlashRedirect) {
            $_SESSION['flash_message'] = $redirect->getFlashMessage();
        }

        // @see http://php.net/manual/en/function.header.php 302 will be set automatically
        header('Location: ' . $redirect->getRedirectUrl());
        exit;
    }

}
