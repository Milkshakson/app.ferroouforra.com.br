<?php

namespace App\Controllers;

use App\Libraries\APPException;
use App\Providers\PokerSessionProvider;
use App\Providers\UsuarioProvider;
use Exception;

class Home extends BaseController
{
    public function index($logout = false)
    {
        if ($logout) {
            $this->dados['avisos'] = 'Você saiu do sistema';
        }
        $pokerSessionProvider = new PokerSessionProvider();
        $this->session->set('openedSession', $pokerSessionProvider->getCurrentOpen()['content']);

        $this->view->display('Home/index', $this->dados);
    }
}
