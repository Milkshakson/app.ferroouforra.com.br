<?php

namespace App\Controllers;

use App\Libraries\APPException;
use App\Providers\PokerSessionProvider;

class Home extends BaseController
{
    public function index($logout = false)
    {
        if ($logout) {
            $this->dados['avisos'] = 'Você saiu do sistema';
        }
        $this->view->display('Home/index', $this->dados);
    }
}
