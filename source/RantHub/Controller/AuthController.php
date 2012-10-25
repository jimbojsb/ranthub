<?php

namespace RantHub\Controller;

use MiniP\ServiceLocator;
use GitHubAPIv3\User\UserAPI;

class AuthController
{
    
    /**
     * @var ServiceLocator
     */
    protected $serviceLocator;

    public function __construct(ServiceLocator $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
    

    public function loginAction()
    {
        $_SESSION['state'] = $login_state = uniqid('state', true);

        $url = 'https://github.com/login/oauth/authorize?';
        $url .= 'client_id=fcd1b6e53e82fcc0000e';
        $url .= '&scope=repo';
        $url .= '&state=' . $login_state;
        header('Location: ' . $url);
        exit();
    }

    public function completeAction()
    {


        $code = $_GET['code'];
        $state = $_GET['state'];

        if ($code == '' || $state == '') {
            header('Location: /auth/login');
            exit();
        }


        if ($state !== $_SESSION['state']) {
            throw new \Exception('Invalid state.');
        }

        // set method and json header
        $httpOptions = array(
            'method' => 'POST',
            'header' => "Accept: application/json\r\nContent-type: application/json\r\n",
            'ignore_errors' => true,
            'content' => json_encode(array(
                'client_id' => 'fcd1b6e53e82fcc0000e',
                'client_secret' => '079f04d23d5fa07fd4d4915772b1308c01864439',
                'code' => $code,
                'state' => $state
            ))
        );

        // set context and get contents
        $context = stream_context_create(array('http' => $httpOptions));
        $fh = fopen('https://github.com/login/oauth/access_token', 'r', false, $context);
        $contents = stream_get_contents($fh);
        fclose($fh);

        unset($_SESSION['state']);

        $response = json_decode($contents, true);

        $_SESSION['access_token'] = $response['access_token'];

        $client = new UserAPI($response['access_token']);
        $_SESSION['user'] = $client->getAuthenticatedUser();

        header('Location: /');
        exit();
    }

    public function logout()
    {
//        /** @var $session \Zend\Session\Container */
//        $session = $this->getServiceLocator()->get('github_session');
//        unset($session['access_token']);
//        unset($session['user']);
//
//        return $this->redirect()->toUrl('/');
    }
}