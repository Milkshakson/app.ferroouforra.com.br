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

    public function removeBuyIn($idBuyIN = 0)
    {
        $pokerSessionProvider = new PokerSessionProvider();
        $dataPost = ['id' => $idBuyIN];
        $remove = $pokerSessionProvider->removeBuyIn($dataPost);
        if ($remove['statusCode'] == 204) {
            $this->session->setFlashdata('sucessos', 'Registro removido com sucesso.');
            $this->response->redirect('/session/current');
        } else {
            $this->dados['erros'] = 'Falha ao remover o buyin.';
        }
    }


    public function salvaBuyIn()
    {
        try {
            $pokerSessionProvider = new PokerSessionProvider();
            if ($this->request->getMethod() == 'post') {
                $input = $this->getRequestInput($this->request);
                if (!empty($input)) {
                    /*
                    [buyinValue] => 0.00
                    [buyinId] => 3723
                    [currencyName] => dolar
                    [gameId] => 1113
                    [sessionPokerid] => 429
                    [stakingSelling] => 
                    [stakingSold] => 
                    [stakingReturn] => 0.0000000000
                    [profit] => 7.6000000000
                    [markup] => 
                    [prizeIn] => 7.60
                    [prizeReentry] => 
                    [reentryBuyIn] => 
                    [totalBuyIn] => 0.00
                    [totalPrize] => 7.60
                    [totalPrizeStakers] => 0.00000000
                    [endDate] => 2021-12-02 23:41:00
                    [startDate] => 2021-12-02 21:00:00
                    [startWeekDay] => Quinta
                    [fieldSize] => 
                    [gameName] => Freeroll RodriguesSF
                    [pokerSiteName] => Party Poker
                    [pokerSiteShortName] => Party
                    [pokerSiteId] => 3
                    [tipoBuyInName] => Torneio MTT
                    [tipoBuyIn] => 1
                    [isClosed] => 1
                    */
                    $rules = [
                        "buyinValue" => [
                            "label" => "Valor do buy in",
                            "rules" => 'required'
                        ],
                        "tipoBuyIn" => [
                            "label" => "Tipo do buy in",
                            "rules" => 'required'
                        ],
                        'sessionPokerid' =>
                        [
                            'label' => "Id da Sessão",
                            'rules' => 'required'
                        ],

                        'gameName' =>
                        [
                            'label' => "Nome do jogo",
                            'rules' => 'required'
                        ],
                        'pokerSiteId' =>
                        [
                            'label' => "Site",
                            'rules' => 'required'
                        ],
                        'startDate' =>
                        [
                            'label' => "Data de início",
                            'rules' => 'required'
                        ],
                        'startTime' =>
                        [
                            'label' => "Hora de início",
                            'rules' => 'required'
                        ],
                        'stakingSelling' =>
                        [
                            'label' => "Cota à venda",
                            'rules' => 'required'
                        ],
                        'stakingSold' =>
                        [
                            'label' => "Cota vendida",
                            'rules' => 'required'
                        ],
                        'prizeIn' =>
                        [
                            'label' => "Premiação",
                            'rules' => 'required'
                        ],
                        'prizeReentry' =>
                        [
                            'label' => "Premiação na reentrada",
                            'rules' => 'required'
                        ],
                        'markup' =>
                        [
                            'label' => "Markup",
                            'rules' => 'required'
                        ],
                        'fieldSize' =>
                        [
                            'label' => "Tamanho do field",
                            'rules' => 'required'
                        ]
                    ];

                    if ($this->validate($rules)) {
                        if(empty($input['startDate'])){
                            $startDate =null;
                        }else{
                            $startDate = ($input['startDate'] . ' ' . $input['startDate']) ?? null;
                        }
                        if(empty($input['endDate'])){
                            $endDate =null;
                        }else{
                            $endDate = ($input['endDate'] . ' ' . $input['endTime']) ?? null;
                        }
                        $stakingSelling  = $input['stakingSelling'] ?? null;
                        $stakingSold  = $input['stakingSold'] ?? null;
                        $prizeIn  = $input['prizeIn'] ?? null;
                        $buyinValue  = $input['buyinValue'] ?? null;
                        $prizeReentry  = $input['prizeReentry'] ?? null;
                        $markup  = $input['markup'] ?? null;
                        $fieldSize  = $input['fieldSize'] ?? null;
                        $gameName  = $input['gameName'] ?? null;
                        $tipoBuyIn  = $input['tipoBuyIn'] ?? null;
                        $pokerSiteId  = $input['pokerSiteId'] ?? null;
                        $dataPost = [
                            'buyinValue' => $buyinValue,
                            'sessionPokerid' => $input['sessionPokerid'],
                            'stakingSelling' => $stakingSelling,
                            'stakingSold' => $stakingSold,
                            'prizeIn' => $prizeIn,
                            'prizeReentry' => $prizeReentry,
                            'markup' => $markup,
                            'currencyName' => 'dolar', //Somente dolar neste momento
                            'startDate' => $startDate,
                            'endDate' => $endDate,
                            'fieldSize' => $fieldSize,
                            'gameName' => $gameName,
                            'tipoBuyIn' => $tipoBuyIn,
                            'pokerSiteId' => $pokerSiteId,
                        ];
                        $adiciona =  $pokerSessionProvider->salvaBuyIn($dataPost);
                        if ($adiciona['statusCode'] == 201) {
                            $this->session->setFlashdata('sucessos', 'Buy in adicionado com sucesso.');
                            $this->response->redirect('/session/current');
                        } else {
                            $this->dados['erros'] = APPException::handleMessage($adiciona['content']['erros']);
                        }
                    } else {
                        $this->dados['erros'] = implode('<br />', $this->validator->getErrors());
                    }
                } else {
                    $this->dados['erros'] = 'Dados não enviados.';
                }
            }
            $openedSession = $pokerSessionProvider->getCurrentOpen();
            if ($openedSession['statusCode'] != 200) {
                throw new APPException("Erro ao recuperar a sessão aberta");
            }
            $this->dados['openedSession'] = $openedSession['content'];

            $tiposBuyIn = $pokerSessionProvider->getTiposBuyIn();
            if ($tiposBuyIn['statusCode'] != 200) {
                throw new APPException("Erro ao recuperar os tipos de buy in");
            }
            $this->dados['tiposBuyIn'] = $tiposBuyIn['content'];

            $pokerSites = $pokerSessionProvider->getPokerSites();
            if ($pokerSites['statusCode'] != 200) {
                throw new APPException("Erro ao recuperar a lista de sites");
            }
            $this->dados['pokerSites'] = $pokerSites['content'];
        } catch (APPException $exception) {
            return $this->exitSafe($exception->getHandledMessage(), 'home/index');
        }
        return view('session/buyins/index', $this->dados);
    }
}
