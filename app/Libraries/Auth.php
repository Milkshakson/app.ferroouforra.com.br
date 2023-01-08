<?php

namespace App\Libraries;

class Auth
{
    private $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function unsetSession()
    {
        $this->session->remove('tokenAcesso');
        $this->session->remove('decodedTokenAcesso');
        $this->session->set('isValidTokenAcesso', false);
    }

    public function loginToSession($login)
    {
        $token = $login['content']['idToken'];
        $this->session->set('tokenAcesso', $token);
        $this->session->set('decodedTokenAcesso', (object) $login['content']);
        $this->session->set('isValidTokenAcesso', true);
    }
}
