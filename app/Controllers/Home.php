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
        pre(session('decodedTokenAcesso'));
        $this->view->display('Home/index', $this->dados);
    }

    public function overlay($existingTtoken)
    {
        try {
            $usuarioProvider = new UsuarioProvider();
            $login = $usuarioProvider->loginByExistingToken($existingTtoken);
            $token = $login['content']['idToken'];
            $this->session->set('tokenAcesso', $token);
            $this->session->set('decodedTokenAcesso', (object) $login['content']);
            $this->session->set('isValidTokenAcesso', true);
            $this->view->display('Overlay/tela1', $this->dados);
        } catch (Exception $e) {
            echo 'Token inválido';
        }
    }
}
