<?php

namespace App\Controllers;

use App\Libraries\Auth;
use App\Libraries\APPException;
use App\Libraries\Dotenv;
use App\Libraries\Twitch;
use App\Providers\PokerSessionProvider;
use App\Providers\UsuarioProvider;
use Exception;

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
                            $this->session->set('isValidTokenAcesso', true);
                            $pokerSessionProvider = new PokerSessionProvider();
                            $openedSession = $pokerSessionProvider->getCurrentOpen();
                            if ($openedSession['statusCode'] == 202) {
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
        $this->view->display('Login/twitch', $this->dados);
    }
    public function twitch()
    {
        $auth = new Auth();
        try {
            $input = $_GET;
            $scope = urlencode('user:read:email user:read:subscriptions');
            $uri_return = is_null(getenv('uriRedirectTwitch')) ? '' : urlencode(getenv('uriRedirectTwitch'));
            $env = new Dotenv();
            $clientId = $env->get('clientIdTwitch');
            $this->dados['input'] = $input;
            if (!$input || count($input) == 0) {
                header("location: https://id.twitch.tv/oauth2/authorize?response_type=code&client_id=$clientId&redirect_uri=$uri_return&scope=$scope");
                exit;
            } else {
                if ($input['code']) {
                    $usuarioProvider = new UsuarioProvider();
                    $login = $usuarioProvider->loginTwitch($input['code']);
                    $auth->loginToSession($login);
                    $this->response->redirect('/home/index');
                } else {
                    $this->dados['erros'] = 'A autorização falhou.';
                }
            }
        } catch (Exception $e) {
            $auth->unsetSession();
            $this->dados['erros'] = 'Falha ao efetuar o seu login com a Twitch. Reclame com o Milk na próxima live em https://twitch.tv/milkshakson' . '<br>' . APPException::handleMessage($e->getMessage());
        }
        $this->view->display('Login/twitch', $this->dados);
    }
    public function logout()
    {
        $this->session->destroy();
        $redirect = site_url('home/index/1');
        header("location: $redirect");
        exit;
    }
}
