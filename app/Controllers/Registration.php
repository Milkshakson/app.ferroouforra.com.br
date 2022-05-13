<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\APPException;
use App\Providers\UsuarioProvider;

class Registration extends BaseController
{
    public function new()
    {
        try {
            if ($this->request->getMethod() == 'post') {
                $input = $this->getRequestInput($this->request);
                if (!empty($input)) {
                    $rules = [
                        "email" => [
                            "label" => "Email",
                            "rules" => 'required|valid_email'
                        ],
                        'senha' =>
                        [
                            'label' => "Senha",
                            'rules' => 'required'
                        ],
                        'nome' =>
                        [
                            'label' => "Nome",
                            'rules' => 'required'
                        ],
                        'termoAceito' =>
                        [
                            'label' => 'Termo aceito',
                            'rules' => 'required',
                            'errors' => [
                                'required' => 'Você precisa aceitar os termos e condições.',
                            ],
                        ]
                    ];
                    if ($this->validate($rules)) {
                        $usuarioProvider = new UsuarioProvider();
                        $dados =  [
                            "nome" => ucfirst($input['nome']),
                            "email" => strtolower($input['email']),
                            "senha" => $input['senha'],
                            "inviteCode" => $input['inviteCode']
                        ];
                        $register = $usuarioProvider->register($dados);
                        if ($register['statusCode'] == 201) {
                            $this->session->setFlashdata('sucessos', 'Usuário cadastrado com sucesso!');
                            return $this->response->redirect('/login/index');
                        }
                    } else {
                        $this->dados['erros'] = implode('<br />', $this->validator->getErrors());
                    }
                } else {
                    $this->dados['erros'] = 'Dados não enviados.';
                }
            }
        } catch (APPException $exception) {
            $this->dados['erro'] = $exception->getHandledMessage();
        }
        $this->view->display('Registration/new', $this->dados);
    }

    public function confirmEmail()
    {
        try {
            if ($this->request->getMethod() == 'post') {
                $input = $this->getRequestInput($this->request);
                if (!empty($input)) {
                    $rules = [
                        "email" => [
                            "label" => "Email",
                            "rules" => 'required|valid_email'
                        ],
                        "token" => [
                            "label" => "Token",
                            "rules" => 'required'
                        ]
                    ];
                    if ($this->validate($rules)) {
                        $usuarioProvider = new UsuarioProvider();
                        $confirm = $usuarioProvider->confirmEmail($input['token'], strtolower($input['email']));
                        if ($confirm['statusCode'] == 202) {
                            $this->session->setFlashdata('sucessos', 'Email confirmado com sucesso!');
                            return $this->response->redirect('/login/index');
                        }else{
                            $this->dados['erros'] = 'Ocorreu um erro ao validar o token.';
                        }
                    } else {
                        $this->dados['erros'] = implode('<br />', $this->validator->getErrors());
                    }
                } else {
                    $this->dados['erros'] = 'Dados não enviados.';
                }
            }
        } catch (APPException $exception) {
            $this->dados['erro'] = $exception->getHandledMessage();
        }
        $this->view->display('Registration/confirmation', $this->dados);
    }
    public function resendConfirmationEmail()
    {
        try {
            if ($this->request->getMethod() == 'post') {
                $input = $this->getRequestInput($this->request);
                if (!empty($input)) {
                    $rules = [
                        "email" => [
                            "label" => "Email",
                            "rules" => 'required|valid_email'
                        ]
                    ];
                    if ($this->validate($rules)) {
                        $usuarioProvider = new UsuarioProvider();
                        $resend = $usuarioProvider->resendConfirmationEmail(strtolower($input['email']));
                        if ($resend['statusCode'] == 202) {
                            $this->session->setFlashdata('sucessos', 'Email enviado com sucesso. Caso não receba na caixa de entrada, verifique se o mesmo não foi parar no spam.');
                            return $this->response->redirect('/login/index');
                        }else{
                            $this->dados['erros'] = 'Ocorreu um erro ao reenviar o email.';
                        }
                    } else {
                        $this->dados['erros'] = implode('<br />', $this->validator->getErrors());
                    }
                } else {
                    $this->dados['erros'] = 'Dados não enviados.';
                }
            }
        } catch (APPException $exception) {
            $this->dados['erro'] = $exception->getHandledMessage();
        }
        $this->view->display('Registration/confirmation-resend', $this->dados);
    }
}
