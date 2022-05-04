<?php

namespace App\Controllers;

use App\Libraries\APPException;
use App\Providers\PokerSessionProvider;

class Session extends BaseController
{
    public function current()
    {
        try {
            $pokerSessionProvider = new PokerSessionProvider();
            if ($this->request->getMethod() == 'post') {
                $input = $this->getRequestInput($this->request);
                if (!empty($input)) {
                    $rules = [
                        "descricao" => [
                            "label" => "Descrição",
                            "rules" => 'required'
                        ],
                        'data' =>
                        [
                            'label' => "Data de abertura",
                            'rules' => 'required'
                        ],
                        'hora' =>
                        [
                            'hora' => "Hora de abertura",
                            'rules' => 'required'
                        ]
                    ];
                    if ($this->validate($rules)) {
                        $dataPost = ['descricao' => $input['descricao'], 'data_inicio' => $input['data'] . ' ' . $input['hora']];
                        $open = $pokerSessionProvider->open($dataPost);
                        if ($open['statusCode'] == 201) {
                            $this->session->setFlashdata('sucessos', 'Sessão aberta com sucesso.');
                            $this->response->redirect('/session/current');
                        } else {
                            pre($open, 1);
                            $this->dados['erros'] = 'Falha ao criar a sessão.';
                        }
                    } else {
                        $this->dados['erros'] = implode('<br />', $this->validator->getErrors());
                    }
                } else {
                    $this->dados['erros'] = 'Dados não enviados.';
                }
            }
            $this->dados['openedSession'] = $pokerSessionProvider->getCurrentOpen()['content'];
        } catch (APPException $exception) {
            $this->dados['erro'] = $exception->getHandledMessage(); 
        }
        return view('session/current', $this->dados);
    }
}
