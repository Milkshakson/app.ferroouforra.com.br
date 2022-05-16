<?php

namespace App\Controllers;

use App\Libraries\APPException;
use App\Providers\PokerSessionProvider;
use App\Providers\UsuarioProvider;

class Login extends BaseController
{
    public function index()
    {
        try {
            if ($this->request->getMethod() == 'post') {
                $input = $this->getRequestInput($this->request);
                if (!empty($input)) {
                    $rules = [
                        "email" => [
                            "label" => "Email",
                            "rules" => 'required'
                        ],
                        'senha' =>
                        [
                            'label' => "Senha",
                            'rules' => 'required'
                        ]
                    ];
                    if ($this->validate($rules)) {
                        $senha = $input['senha'];
                        $email = $input['email'];
                        $usuarioProvider = new UsuarioProvider();
                        $login = $usuarioProvider->login($email, $senha);
                        if ($login['statusCode'] == 202) {
                            $token = $login['content']['idToken'];
                            $this->session->set('tokenAcesso', $token);
                            $pokerSessionProvider = new PokerSessionProvider();
                            $openedSession = $pokerSessionProvider->getCurrentOpen();
                            if($openedSession['statusCode']==202){
                                $this->session->set('openedSession', $openedSession['content']);
                            }
                            
                            $this->response->redirect('/home/index');
                        } else {
                            $this->dados['erros'] = 'Falha ao efetuar Login';
                        }
                    } else {
                        $this->dados['erros'] = implode('<br />', $this->validator->getErrors());
                    }
                } else {
                    $this->dados['erros'] = 'Dados não enviados.';
                }
            }
        } catch (APPException $exception) {
            $this->dados['erro'] = $exception->getHandledMessage(); //'Erro ao efetuar Login.';
        }
        $this->view->display('Login/index', $this->dados);
    }

    public function logout(){
        $this->session->destroy();
        return $this->exitSafe('Você saiu do sistema.');
    }
}
