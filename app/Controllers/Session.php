<?php

namespace App\Controllers;

use App\Libraries\APPException;
use App\Providers\PokerSessionProvider;
use Exception;
use IntlCalendar;

class Session extends BaseController
{

    public function listClosed()
    {
        try {
            $endpoint = '/poker_session/my_sessions';
            $pokerSessionProvider = new PokerSessionProvider();
            $sessions = $pokerSessionProvider->consumeEndpoint('get', $endpoint);
            if (in_array($sessions['statusCode'], [200, 201])) {
                $closedSessions = array_filter($sessions['content'], function ($session) {
                    return !is_null($session['endDate']);
                });
            } else {
                $closedSessions  = [];
            }
            $this->dados['sessions'] = $closedSessions;
        } catch (APPException $exception) {
            $this->dados['erro'] = $exception->getHandledMessage();
            $this->exitSafe($exception->getHandledMessage());
        }
        $this->view->display('Session/Closed/index', $this->dados);
    }
    public function reopen($id)
    {
        try {
            $endpoint = '/poker_session/reabre_sessao';
            $pokerSessionProvider = new PokerSessionProvider();
            $reopen = $pokerSessionProvider->consumeEndpoint('post', $endpoint, ['id' => intval($id)]);
            $this->dados['reopen'] = $reopen;
            if ($reopen['statusCode'] == 200) {
                $this->session->setFlashdata('sucessos', 'Sessão reaberta com sucesso.');
                $this->response->redirect('/session/current');
            }
        } catch (APPException $exception) {
            $this->session->setFlashdata('erros', $exception->getHandledMessage());
            $this->response->redirect('/session/listClosed');
        }
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
            $this->session->set('openedSession', $pokerSessionProvider->getCurrentOpen()['content']);
        } catch (APPException $exception) {
            $this->dados['erro'] = $exception->getHandledMessage();
        }
        $buyInList = key_exists('buyInList', session('openedSession')) ? session('openedSession')['buyInList'] : [];
        $this->dados['countAbertos'] = count(array_filter($buyInList, function ($bi) {
            return ci_time($bi['startDate'])->isBefore(ci_time('now')) && $bi['endDate'] == null;
        }));

        $this->dados['countFuturo']  = count(array_filter($buyInList, function ($bi) {
            return ci_time($bi['startDate'])->isAfter(ci_time('now')) && is_null($bi['endDate']);
        }));


        $this->dados['countEncerrados']  = count(array_filter($buyInList, function ($bi) {
            return  !is_null($bi['endDate']);
        }));
        $this->dados['sitesJogados'] = array_unique(array_column($buyInList, 'siteName'));
        $this->view->display('Session/Current/index.twig', $this->dados);
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
    private function getCurrentBi($buyInList, $idBuyIN)
    {
        $currentBI = [];
        $editBi = array_filter($buyInList, function ($bi) use ($idBuyIN) {
            return $bi['buyinId'] == $idBuyIN;
        });
        if (count($editBi) == 1) {
            $currentBI = end($editBi);
        }

        return $currentBI;
    }
    public function endBuyIn($idBuyIN)
    {
        try {
            $pokerSessionProvider = new PokerSessionProvider();
            $openedSession = $pokerSessionProvider->getCurrentOpen();
            if ($openedSession['statusCode'] != 202) {
                throw new APPException("Erro ao recuperar a sessão aberta");
            }
            $this->dados['openedSession'] = $openedSession['content'];
            $buyInList = $openedSession['content']['buyInList'];
            $currentBI = $this->getCurrentBi($buyInList, $idBuyIN);

            $this->dados['bi'] = $this->getCurrentBi($buyInList, $idBuyIN);
            if ($this->request->getMethod() == 'post') {
                $input = $this->getRequestInput($this->request);
                if (!empty($input)) {
                    $idBuyIN = $input['buyinId'];
                    $currentBI = $this->getCurrentBi($buyInList, $idBuyIN);

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
                    if ($this->validate($rules)) {

                        if (empty($input['endDate'])) {
                            $endDate = null;
                        } else {
                            $endDate = ($input['endDate'] . ' ' . $input['endTime']) ?? null;
                        }
                        if ($currentBI) {
                            $currentBI['prizeIn'] = filter_var($input['prizeIn'], FILTER_VALIDATE_FLOAT);
                            $currentBI['prizeReentry'] = filter_var($input['prizeReentry'], FILTER_VALIDATE_FLOAT);
                            $currentBI['fieldSize'] = filter_var($input['fieldSize'], FILTER_VALIDATE_INT);
                            $currentBI['position'] = filter_var($input['position'], FILTER_VALIDATE_INT);
                            $currentBI['finalTable'] = key_exists('finalTable', $input) ? filter_var($input['finalTable'], FILTER_VALIDATE_INT) : false;
                            $currentBI['endDate'] = $endDate;
                            $adiciona =  $pokerSessionProvider->salvaBuyIn($currentBI);
                            if ($adiciona['statusCode'] == 201) {
                                $this->session->setFlashdata('sucessos', 'Buy in salvo com sucesso.');
                                $this->response->redirect('/session/current');
                            } else {
                                $this->dados['erros'] = APPException::handleMessage($adiciona['content']['erros']);
                            }
                        } else {
                            $this->dados['erros'] = 'Buy-in não encontrado na sua sessão.';
                        }
                    } else {
                        $this->dados['erros'] = implode('<br />', $this->validator->getErrors());
                    }
                } else {
                    $this->dados['erros'] = 'Dados não enviados.';
                }
            }
        } catch (APPException $exception) {
            return $this->exitSafe($exception->getHandledMessage(), 'home/index');
        }
        $this->dados['bi'] = $currentBI;
        $this->view->display('Session/BuyIns/Ending/index.twig', $this->dados);
    }

    public function stakingBuyIn($idBuyIN)
    {
        try {
            $pokerSessionProvider = new PokerSessionProvider();
            $openedSession = $pokerSessionProvider->getCurrentOpen();
            if ($openedSession['statusCode'] != 202) {
                throw new APPException("Erro ao recuperar a sessão aberta");
            }
            $this->dados['openedSession'] = $openedSession['content'];
            $buyInList = $openedSession['content']['buyInList'];
            $currentBI = $this->getCurrentBi($buyInList, $idBuyIN);

            $this->dados['bi'] = $this->getCurrentBi($buyInList, $idBuyIN);
            if ($this->request->getMethod() == 'post') {
                $input = $this->getRequestInput($this->request);
                if (!empty($input)) {
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

                    $idBuyIN = $input['buyinId'];
                    $currentBI = $this->getCurrentBi($buyInList, $idBuyIN);

                    if ($this->validate($rules)) {
                        if ($currentBI) {
                            $currentBI['stakingSelling'] = filter_var($input['stakingSelling'], FILTER_VALIDATE_FLOAT);
                            $currentBI['stakingSold'] = filter_var($input['stakingSold'], FILTER_VALIDATE_FLOAT);
                            $currentBI['markup'] = filter_var($input['markup'], FILTER_VALIDATE_FLOAT);
                            $adiciona =  $pokerSessionProvider->salvaBuyIn($currentBI);
                            if ($adiciona['statusCode'] == 201) {
                                $this->session->setFlashdata('sucessos', 'Buy in salvo com sucesso.');
                                $this->response->redirect('/session/current');
                            } else {
                                $this->dados['erros'] = APPException::handleMessage($adiciona['content']['erros']);
                            }
                        } else {
                            $this->dados['erros'] = 'Buy-in não encontrado na sua sessão.';
                        }
                    } else {
                        $this->dados['erros'] = implode('<br />', $this->validator->getErrors());
                    }
                } else {
                    $this->dados['erros'] = 'Dados não enviados.';
                }
            }
        } catch (APPException $exception) {
            return $this->exitSafe($exception->getHandledMessage(), 'home/index');
        }
        $this->dados['bi'] = $currentBI;
        $this->view->display('Session/BuyIns/Staking/index.twig', $this->dados);
    }
    public function createBuyIn($pokerSiteId = null)
    {
        $this->saveBuyIn(null, $pokerSiteId);
    }

    public function lazyloadMyBuyIns()
    {
        $pokerSessionProvider = new PokerSessionProvider();
        $myBuyIns = $pokerSessionProvider->getMeusBuyIns()['content'];
        $myBuyIns = array_filter($myBuyIns, function ($bi) {
            $bi = (object) $bi;
            // return ci_time($bi->startDate)->format('H:i') == '11:39';
            return $bi->pokerSiteName == 'Party Poker';
        });
        $this->session->set('meusBuyIns', $myBuyIns);
        $this->dados['myBuyIns'] = $myBuyIns;
        $this->view->display('Session/BuyIns/list-my-buyins', $this->dados);
    }
    public function saveBuyIn($idBuyIN = null, $pokerSiteId = null)
    {
        try {
            $pokerSessionProvider = new PokerSessionProvider();
            $openedSession = $pokerSessionProvider->getCurrentOpen();
            if ($openedSession['statusCode'] != 202) {
                throw new APPException("Erro ao recuperar a sessão aberta");
            }
            $buyInList = $openedSession['content']['buyInList'];
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

                    if ($this->validate($rules)) {
                        if (empty($input['startDate'])) {
                            $startDate = null;
                        } else {
                            $startDate = ($input['startDate'] . ' ' . $input['startTime']) ?? null;
                        }

                        $currentBI = $this->getCurrentBi($buyInList, $idBuyIN);

                        $buyinValue  = $input['buyinValue'] ?? null;
                        $reentryBuyIn  = $input['reentryBuyIn'] ?? null;
                        $gameName  = $input['gameName'] ?? null;
                        $tipoBuyIn  = $input['tipoBuyIn'] ?? null;
                        $pokerSiteId  = $input['pokerSiteId'] ?? null;

                        $buyinId  = $input['buyinId'] ? $input['buyinId'] : null;
                        $dataPost = [
                            'buyinId' => $buyinId,
                            'startDate' => $startDate,
                            'tipoBuyIn' => $tipoBuyIn,
                            'pokerSiteId' => $pokerSiteId,
                            'buyinValue' => $buyinValue,
                            'sessionPokerid' => $input['sessionPokerid'],
                            'reentryBuyIn' => $reentryBuyIn,
                            'currencyName' => 'dolar', //Somente dolar neste momento
                            'gameName' => $gameName,
                        ];
                        $dataPost = array_merge($currentBI, $dataPost);
                        unset($dataPost['gameId']);
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
            $this->dados['openedSession'] = $openedSession['content'];
            $currentBI = $this->getCurrentBi($buyInList, $idBuyIN);
            if ($currentBI == [] and !is_null($pokerSiteId)) {
                $currentBI['pokerSiteId'] = $pokerSiteId;
            }
            $this->dados['bi'] = $currentBI;
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
        $pokerSiteId =  intval($input['site']);

        if (is_array($meusBuyIns)) {
            $html .= '';

            if (intval($pokerSiteId) > 0) {
                $meusBuyIns = array_filter($meusBuyIns, function ($bi) use ($pokerSiteId) {
                    return $bi['pokerSiteId'] == $pokerSiteId;
                });
            }
            $exatos = array_filter($meusBuyIns, function ($bi) use ($busca) {
                return strtolower($bi['gameName']) == $busca;
            });
            $contem = array_filter($meusBuyIns, function ($bi) use ($busca, $exatos) {
                return !in_array($bi, $exatos) && str_contains(strtolower($bi['gameName']), $busca);
            });
            $mergedUnique = (array_merge($exatos, $contem));
            $lines = [];
            foreach ($mergedUnique as $bi) {
                $strLines = '';
                $buyInValue = $bi['buyinValue'];
                $gameName = $bi['gameName'];
                $pokerSiteId = $bi['pokerSiteId'];
                $tipoBuyIn = $bi['tipoBuyIn'];
                $gameInfo = box_bi('Buy in', dolarFormat($bi['buyinValue']), 'class="col-3 col-sm-3 col-md-3 col-lg-3"');
                $gameInfo .= box_bi('Site', ($bi['pokerSiteName']), 'class="col-4 col-sm-4 col-md-4 col-lg-4"');
                $gameInfo .= box_bi('&nbsp;', ($bi['tipoBuyInName']), 'class="col-4 col-sm-4 col-md-4 col-lg-4"');
                $strLines .= "<div class='border border-secondary '>";
                $strLines .= "<div class='row'>$gameInfo</div>";
                $strLines .= "<a href='#' class='seleciona-buy-in' data-buy-in='$buyInValue' data-game-name='$gameName' data-site='$pokerSiteId' data-tipo-buy-in='$tipoBuyIn'>
                <div class='row pb-3'>$gameName</div>
                </a>";
                $strLines .= '</div>';
                $lines[] = $strLines;
            }
            $html .= implode(array_unique($lines));
        } else {
            $html = '**s*';
        }
        return $html;
    }

    public function createBuyInBatch()
    {
        try {
            if ($this->request->getMethod() == 'post') {
                $input = $this->getRequestInput($this->request);
                $pokerSessionProvider = new PokerSessionProvider();
                $openedSession = $pokerSessionProvider->getCurrentOpen();
                if ($openedSession['statusCode'] != 202) {
                    throw new APPException("Erro ao recuperar a sessão aberta");
                }
                $idSession = intval($input['sessionId']);
                $sessionToImport =  $pokerSessionProvider->consumeEndpoint('get', "/poker_session/fetch_by_code/$idSession");
                $buyInListSession = $sessionToImport['content']['buyInList'];
                $buyInListPost = $input['buyInList'];
                $startDateSessionImport = ci_time($sessionToImport['content']['startDate']);
                $referenceDate = $startDateSessionImport;
                //muda o dia mês e ano para referência para a data de hoje
                $referenceDate = $referenceDate->setDay(date('d'));
                $referenceDate = $referenceDate->setMonth(date('m'));
                $referenceDate = $referenceDate->setYear(date('Y'));
                $buyInListToSave = array_map(
                    function ($bi) use ($referenceDate, $startDateSessionImport) {
                        /**
                         * É encessário ajustar para pegar os registros e setar as datas da forma correta
                         * Ex: A sessão importada é do dia 15/08.
                         * O primeiro registro foi 19:00, mas teve um registro depois da meia noite, 01:00
                         * Ao importar no dia 01/11 , o primeiro registro deve ser 01/11 19:00, e o registro depois da meia noite 02/11 01:00
                         */

                        $startDateBi = ci_time($bi['startDate']);
                        //Pega a diferença entre o inicio do torneio e o início da sessão que será importada
                        $diffBI = $startDateSessionImport->diff($startDateBi);
                        //Pega configura a data de referência como a data de importação
                        //define como início do registro para importação como o registro da sessão que está sendo importada
                        $newStartDateBi = $referenceDate;
                        //Agora aplica a diferença da importação também na sessão atual, mantendo as horas corretas
                        $newStartDateBi = $newStartDateBi->addDays($diffBI->d);
                        $newStartDateBi = $newStartDateBi->addHours($diffBI->h);
                        $newStartDateBi = $newStartDateBi->addMinutes($diffBI->i);
                        $newBi = [
                            "buyinValue" => $bi['buyinValue'],
                            "currencyName" => $bi['currencyName'],
                            "gameId" => $bi['gameId'],
                            "stakingSelling" => null,
                            "markup" => null,
                            "startDate" => $newStartDateBi->format('Y-m-d H:i:s'),
                            "pokerSiteId" => $bi['pokerSiteId'],
                            "fieldSize" => null,
                            "positiom" => null
                        ];

                        return $newBi;
                    },
                    array_filter($buyInListSession, function ($bi) use ($buyInListPost) {
                        return in_array($bi['buyinId'], $buyInListPost);
                    })
                );

                $data = [
                    'batch' => json_encode($buyInListToSave),
                    'idPokerSession' => $openedSession['content']['id']
                ];
                $create =  $pokerSessionProvider->consumeEndpoint('post', '/poker_session/create_buyin_batch', $data);
                $importados = $create['content']['countInsert'];
                $this->jsonRetorno['retorno'] = true;
                $this->jsonRetorno['msg'] = "Importação de $importados registro(s) realizada com sucesso.";
                echo json_encode($this->jsonRetorno);
            } else {
                throw new APPException("Dados não enviados");
            }
        } catch (APPException $exception) {
            $this->jsonRetorno['retorno'] = false;
            $this->jsonRetorno['msg'] = $exception->getMessage();
            echo json_encode($this->jsonRetorno);
        }
    }
    public function importarGrade()
    {
        $pokerSessionProvider = new PokerSessionProvider();
        $openedSession = $pokerSessionProvider->getCurrentOpen();
        if ($openedSession['statusCode'] != 202) {
            throw new APPException("Erro ao recuperar a sessão aberta");
        }
        $this->dados['openedSession'] = $openedSession['content'];
        $this->view->display('Session/Current/importarGrade.twig', $this->dados);
    }

    public function importaSessao($tipo = null)
    {
        try {
            $pokerSessionProvider = new PokerSessionProvider();
            if (strtolower($tipo) == 'last') {
                $last =  $pokerSessionProvider->consumeEndpoint('get', '/poker_session/last_closed');
                $this->dados['tituloImportacao'] = "Importação da última sessão";
            } else if (is_numeric($tipo)) {
                $tipo = intval($tipo);
                $dayName = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'][$tipo];
                $buyInList =  $pokerSessionProvider->consumeEndpoint('get', "/poker_session/last_by_day_week?dayWeek=$tipo");
                $idSession = $buyInList['content'][0]['sessionPokerid'];
                $last =  $pokerSessionProvider->consumeEndpoint('get', "/poker_session/fetch_by_code/$idSession");
                $last['content']['buyInList'] = $buyInList['content'];
                $this->dados['tituloImportacao'] = "Importação da última $dayName";
            }
            $this->dados['sessionToImport'] = $last['content'];
        } catch (APPException $exception) {
            $this->dados['erros'] = $exception->getHandledMessage();
        }
        $this->view->display('Session\Import\importador.twig', $this->dados);
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
                            $this->response->redirect('/Session/listClosed');
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
        $this->view->display('Session/Current/encerramento.twig', $this->dados);
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
                $retorno = $this->view->render('Home/Includes/graficos-sumary-home.twig', $this->dados);
                break;
        }
        return $retorno;
    }
}