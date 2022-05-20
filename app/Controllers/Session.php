<?php

namespace App\Controllers;

use App\Libraries\APPException;
use App\Providers\PokerSessionProvider;

class Session extends BaseController
{
    public function __construct()
    {
        helper('date');
    }
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
                            "rules" => 'required|max_length[120]'
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
                        pre($dataPost);
                        $open = $pokerSessionProvider->open($dataPost);
                        if ($open['statusCode'] == 201) {
                            $this->session->setFlashdata('sucessos', 'Sessão aberta com sucesso.');
                            $this->response->redirect('/session/current');
                        } else {
                            pre($open ,1);
                            $this->dados['erros'] = 'Falha ao criar a sessão.';
                        }
                    } else {
                        $this->dados['erros'] = implode('<br />', $this->validator->getErrors());
                    }
                } else {
                    $this->dados['erros'] = 'Dados não enviados.';
                }
            }
            $this->session->set('openedSession', $pokerSessionProvider->getCurrentOpen()['content']);
        } catch (APPException $exception) {
            $this->dados['erro'] = $exception->getHandledMessage();
        }
        $this->view->display('Session/index.twig', $this->dados);
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


    public function salvaBuyIn($idBuyIN = null)
    {
        try {
            $pokerSessionProvider = new PokerSessionProvider();
            if ($this->request->getMethod() == 'post') {
                $input = $this->getRequestInput($this->request);
                if (!empty($input)) {
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
                    ];
                    if (key_exists('isClosed', $input) && $input['isClosed']) {
                        $rules['endDate'] =
                            [
                                'label' => "Data fim",
                                'rules' => 'required'
                            ];
                        $rules['endTime'] =
                            [
                                'label' => "Hora fim",
                                'rules' => 'required'
                            ];
                        $rules['prizeIn'] =
                            [
                                'label' => "Premiação",
                                'rules' => 'required'
                            ];
                        if (key_exists('finalTable', $input) && $input['finalTable']) {
                            $rules['position'] =
                                [
                                    'label' => "Posição",
                                    'rules' => 'required|greater_than[0]|less_than_equal_to[9]'
                                ];
                        }
                        $rules['fieldSize'] =
                            [
                                'label' => "Tamanho do field",
                                'rules' => 'required'
                            ];
                    }

                    if (key_exists('stakingSellingCheck', $input) && $input['stakingSellingCheck'] > 0) {

                        $rules['stakingSelling'] =
                            [
                                'label' => "Cota à venda",
                                'rules' => 'required'
                            ];
                        $rules['markup'] = [
                            'label' => "Markup",
                            'rules' => 'required|greater_than[0]|less_than_equal_to[2]'
                        ];
                        $rules['stakingSold'] =
                            [
                                'label' => "Cota vendida",
                                'rules' => 'required'
                            ];
                    }

                    if ($this->validate($rules)) {
                        if (empty($input['startDate'])) {
                            $startDate = null;
                        } else {
                            $startDate = ($input['startDate'] . ' ' . $input['startTime']) ?? null;
                        }
                        if (empty($input['endDate'])) {
                            $endDate = null;
                        } else {
                            $endDate = ($input['endDate'] . ' ' . $input['endTime']) ?? null;
                        }
                        $stakingSelling  = $input['stakingSelling'] ?? null;
                        $stakingSold  = $input['stakingSold'] ?? null;
                        $prizeIn  = $input['prizeIn'] ?? null;
                        $buyinValue  = $input['buyinValue'] ?? null;
                        $prizeReentry  = $input['prizeReentry'] ?? null;
                        $reentryBuyIn  = $input['reentryBuyIn'] ?? null;
                        $markup  = $input['markup'] ?? null;
                        $fieldSize  = $input['fieldSize'] ?? null;
                        $gameName  = $input['gameName'] ?? null;
                        $tipoBuyIn  = $input['tipoBuyIn'] ?? null;
                        $pokerSiteId  = $input['pokerSiteId'] ?? null;
                        $buyinId  = $input['buyinId'] ? $input['buyinId'] : null;
                        $position  = $input['position'] ?? null;
                        $finalTable  = $input['finalTable']  ?? null;
                        $dataPost = [
                            'buyinId' => $buyinId,
                            'buyinValue' => $buyinValue,
                            'sessionPokerid' => $input['sessionPokerid'],
                            'stakingSelling' => $stakingSelling,
                            'stakingSold' => $stakingSold,
                            'prizeIn' => $prizeIn,
                            'prizeReentry' => $prizeReentry,
                            'reentryBuyIn' => $reentryBuyIn,
                            'markup' => $markup,
                            'currencyName' => 'dolar', //Somente dolar neste momento
                            'startDate' => $startDate,
                            'endDate' => $endDate,
                            'fieldSize' => $fieldSize,
                            'gameName' => $gameName,
                            'tipoBuyIn' => $tipoBuyIn,
                            'pokerSiteId' => $pokerSiteId,
                            'position' => $position,
                            'finalTable' => $finalTable,
                        ];
                        $adiciona =  $pokerSessionProvider->salvaBuyIn($dataPost);
                        if ($adiciona['statusCode'] == 201) {
                            $this->session->setFlashdata('sucessos', 'Buy in salvo com sucesso.');
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
            if ($openedSession['statusCode'] != 202) {
                throw new APPException("Erro ao recuperar a sessão aberta");
            }
            $this->dados['openedSession'] = $openedSession['content'];
            $buyInList = $openedSession['content']['buyInList'];
            foreach ($buyInList as $bi) {
                if ($bi['buyinId'] == $idBuyIN) {
                    $this->dados['bi'] = $bi;
                    break;
                }
            }
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
        $this->view->display('Session/BuyIns/index.twig', $this->dados);
    }
    public function meusBuyIns()
    {
        $meusBuyIns = session('meusBuyIns');
        if (!$meusBuyIns) {
            $pokerSessionProvider = new PokerSessionProvider();
            $meusBuyIns = $pokerSessionProvider->getMeusBuyIns()['content'];
            $this->session->set('meusBuyIns', $meusBuyIns);
        }
        $html = '';
        $input = $this->getRequestInput($this->request);

        $busca =  strtolower($input['busca']);

        if (is_array($meusBuyIns)) {
            $html .= '';
            foreach ($meusBuyIns as $bi) {
                $adicionar = false;
                if (strtolower($bi['gameName']) == $busca) {
                    $adicionar = true;
                } else
                if (str_contains(strtolower($bi['gameName']), $busca)) {
                    $adicionar = true;
                }
                if ($adicionar) {
                    $buyInValue = $bi['buyinValue'];
                    $gameName = $bi['gameName'];
                    $pokerSiteId = $bi['pokerSiteId'];
                    $tipoBuyIn = $bi['tipoBuyIn'];
                    $game = boxBi('Jogo', $gameName, 'class="col-4 col-sm-4 col-md-4 col-lg-4"');
                    $game .= boxBi('Buy in', dolarFormat($bi['buyinValue']), 'class="col-4 col-sm-4 col-md-4 col-lg-4"');
                    $game .= boxBi('Site', ($bi['pokerSiteName']), 'class="col-4 col-sm-4 col-md-4 col-lg-4"');
                    $html .= "<a href='#' class='seleciona-buy-in' data-buy-in='$buyInValue' data-game-name='$gameName' data-site='$pokerSiteId' data-tipo-buy-in='$tipoBuyIn'><div class='row pb-3'>$game</div></a>";
                }
            }
        }
        return $html;
    }

    public function encerrar()
    {
        try {
            $pokerSessionProvider = new PokerSessionProvider();
            if ($this->request->getMethod() == 'post') {
                $input = $this->getRequestInput($this->request);
                if (!empty($input)) {
                    $rules = [
                        "endTime" => [
                            "label" => "Hora do encerramento",
                            "rules" => 'required'
                        ],
                        "endDate" => [
                            "label" => "Data do encerramento",
                            "rules" => 'required|valid_date'
                        ],
                        "sessionId" => [
                            "label" => "Id da sessão",
                            "rules" => 'required|greater_than[0]'
                        ],
                        "descriptionEnd" => [
                            "label" => "Descrição encerramento",
                            "rules" => 'required|max_length[120]'
                        ],
                    ];
                    if ($this->validate($rules)) {
                        $dataPost = ['id' => $input['sessionId'], 'descriptionEnd' => $input['descriptionEnd'], 'endDate' => $input['endDate'] . ' ' . $input['endTime']];
                        $fechamento =  $pokerSessionProvider->encerrar($dataPost);
                        if ($fechamento['statusCode'] == 202) {
                            $this->session->remove("openedSession");
                            $this->session->setFlashdata('sucessos', 'Sessão encerrada com sucesso.');
                            $this->response->redirect('/home/index');
                        } else {
                            $this->dados['erros'] = APPException::handleMessage($fechamento['content']['erros']);
                        }
                    } else {
                        $this->dados['erros'] = implode('<br />', $this->validator->getErrors());
                    }
                } else {
                    $this->dados['erros'] = "Dados não enviados.";
                }
            }
        } catch (APPException $exception) {
            $this->dados['erros'] = $exception->getHandledMessage();
        }
        $this->view->display('Session/encerramento', $this->dados);
    }
    public function component($componente = 'cards')
    {
        $retorno = '';
        switch ($componente) {
            case 'cards':
                $retorno = $this->view->render('Home/Includes/cards-sumary-home.twig', $this->dados);
                break;
            case 'graficos-pizza':
                $retorno = $this->view->render('Home/Includes/charts.twig');
                break;
            case 'graficos-barra':
                try {
                    $this->dados['colorsRand'] = $hex = array_merge(range(0, 9), range('A', 'F'));
                    $this->dados['colorsRand'] = sprintf('#06x', random_int(0, 0xFFFFFF));
                    $year = date('Y');
                    $pokerSessionProvider = new PokerSessionProvider();
                    $yearlySumary = $pokerSessionProvider->getResumoAnual($year);
                    if ($yearlySumary['statusCode'] == 202) {
                        $this->dados['yearlySumary'] = $yearlySumary['content'];
                    }
                } catch (APPException $e) {
                    $this->dados['erros'] = $e->getHandledMessage($e->getMessage());
                }
                $retorno = $this->view->render('Home/Includes/graficos-sumary-home.twig',$this->dados);
                break;
        }
        return $retorno;
    }
}
