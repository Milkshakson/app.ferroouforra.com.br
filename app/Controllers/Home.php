<?php

namespace App\Controllers;

use App\Providers\PokerSessionProvider;

class Home extends BaseController
{
    public function index($logout = false)
    {
        try {
            if ($logout) {
                $this->dados['avisos'] = 'Você saiu do sistema';
            }
            $pokerSessionProvider = new PokerSessionProvider();
            $this->session->set('openedSession', $pokerSessionProvider->getCurrentOpen()['content']);
        } catch (\Throwable $th) {
            //throw $th;
            $this->session->set('openedSession', null);
        }

        $this->view->display('Home/index', $this->dados);
    }
}
