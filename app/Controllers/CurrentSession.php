<?php

namespace App\Controllers;

use App\Libraries\APPException;
use App\Providers\PokerSessionProvider;
use Exception;
use Throwable;

class CurrentSession extends BaseController
{
    public function lazyLoadSummary()
    {
        try {
            $pokerSessionProvider = new PokerSessionProvider();
            $this->session->set('openedSession', $pokerSessionProvider->getCurrentOpen()['content']);
            $openedSession = session('openedSession');
            $buyInList = key_exists('buyInList', session('openedSession')) ? session('openedSession')['buyInList'] : [];
            $this->dados['buyInList'] = $buyInList;
            $this->dados['countAbertos'] = count(array_filter($buyInList, function ($bi) {
                return ci_time($bi['startDate'])->isBefore(ci_time('now')) && $bi['endDate'] == null;
            }));

            $this->dados['countFuturo'] = count(array_filter($buyInList, function ($bi) {
                return ci_time($bi['startDate'])->isAfter(ci_time('now')) && is_null($bi['endDate']);
            }));
            $this->dados['summary'] = $openedSession['summary'];
            $this->dados['countEncerrados'] = count(array_filter($buyInList, function ($bi) {
                return !is_null($bi['endDate']);
            }));
            $this->dados['sitesJogados'] = array_unique(array_column($buyInList, 'siteName'));
            $html = $this->view->render('Session/Current/summary.twig', $this->dados);
            print(json_encode(['html' => $html]));
        } catch (Exception $e) {
            print(json_encode(['html' => APPException::handleMessage($e->getMessage())]));
        }
    }

    public function updateBankRollSession()
    {
        try {
            $pokerSessionProvider = new PokerSessionProvider();
            $openedSession = session('openedSession');
            $dados = $this->getRequestInput($this->request);
            $pokerSessionProvider->updateBankrollSession($openedSession['id'], $dados);
            print(json_encode(['success' => true]));
        } catch (Exception $e) {
            print(json_encode(['success' => false, 'msg' => APPException::handleMessage($e->getMessage())]));
        }
    }

    public function lastClosedBankroll()
    {
        try {
            $pokerSessionProvider = new PokerSessionProvider();
            $lastSession = $pokerSessionProvider->consumeEndpoint('get', '/poker_session/last_closed')['content'];
            $bankroll = $pokerSessionProvider->getBankrollSession($lastSession['id'])['content'];
            print(json_encode(['success' => true, 'bankroll' => $bankroll]));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function lazyLoadBankroll()
    {
        try {
            $pokerSessionProvider = new PokerSessionProvider();
            $openedSession = session('openedSession');
            $saldos = $pokerSessionProvider->getBankrollSession($openedSession['id'])['content'];
            $pokerSites = $pokerSessionProvider->getPokerSites();
            $this->dados['saldos'] = $saldos;
            $this->dados['sites'] = $pokerSites['content'];
            $html = $this->view->render('Session/Current/bankroll-abertura.twig', $this->dados);
            print(json_encode(['html' => $html]));
        } catch (Exception $e) {
            print(json_encode(['html' => APPException::handleMessage($e->getMessage())]));
        }
    }
    public function lazyLoadBankrollEncerramento()
    {
        try {
            $pokerSessionProvider = new PokerSessionProvider();
            $openedSession = session('openedSession');
            $saldos = $pokerSessionProvider->getBankrollSession($openedSession['id'])['content'];
            $this->dados['saldos'] = $saldos;
            $html = $this->view->render('Session/Current/bankroll-encerramento.twig', $this->dados);
            print(json_encode(['html' => $html]));
        } catch (Exception $e) {
            print(json_encode(['html' => APPException::handleMessage($e->getMessage())]));
        }
    }
    public function lazyLoadBuyInList()
    {
        try {
            $pokerSessionProvider = new PokerSessionProvider();
            $this->session->set('openedSession', $pokerSessionProvider->getCurrentOpen()['content']);
            $openedSession = session('openedSession');
            $buyInList = key_exists('buyInList', session('openedSession')) ? session('openedSession')['buyInList'] : [];
            $this->dados['buyInList'] = $buyInList;
            $this->dados['openedSession'] = $openedSession;
            $html = $this->view->render('Session/BuyIns/list-cards-improved.twig', $this->dados);
            print(json_encode(['html' => $html]));
        } catch (Exception $e) {
            print(json_encode(['html' => APPException::handleMessage($e->getMessage())]));
        }
    }

    public function saveBuyIN()
    {
        if ($this->request->getMethod() == 'post') {
            $pokerSessionProvider = new PokerSessionProvider();
            $input = $this->getRequestInput($this->request);
            if (!empty($input)) {
                $rules = [
                    "buyinValue" => [
                        "label" => "Valor do buy in",
                        "rules" => 'required',
                    ],
                    "tipoBuyIn" => [
                        "label" => "Tipo do buy in",
                        "rules" => 'required',
                    ],
                    'sessionPokerid' =>
                    [
                        'label' => "Id da Sessão",
                        'rules' => 'required',
                    ],

                    'gameName' =>
                    [
                        'label' => "Nome do jogo",
                        'rules' => 'required',
                    ],
                    'pokerSiteId' =>
                    [
                        'label' => "Site",
                        'rules' => 'required',
                    ],
                    'startDate' =>
                    [
                        'label' => "Data de início",
                        'rules' => 'required',
                    ],
                    'startTime' =>
                    [
                        'label' => "Hora de início",
                        'rules' => 'required',
                    ],
                ];

                if ($this->validate($rules)) {
                    $input = array_map(function ($data) {
                        return (!is_null($data) && trim($data)) == '' ? null : mb_strtoupper($data);
                    }, $input);

                    if (empty($input['startDate'])) {
                        $startDate = null;
                    } else {
                        $startDate = ($input['startDate'] . ' ' . $input['startTime']) ?? null;
                    }
                    $buyInList = [];
                    $idBuyIN = null;
                    throw new Exception("Arrumar aqui", 1);

                    $currentBI = $this->getCurrentBi($buyInList, $idBuyIN);

                    $buyinValue = $input['buyinValue'] ?? null;
                    $reentryBuyIn = $input['reentryBuyIn'] ?? null;
                    $gameName = $input['gameName'] ?? null;
                    $tipoBuyIn = $input['tipoBuyIn'] ?? null;
                    $pokerSiteId = $input['pokerSiteId'] ?? null;

                    $buyinId = $input['buyinId'] ? $input['buyinId'] : null;
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
                    $adiciona = $pokerSessionProvider->salvaBuyIn($dataPost);
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
    }

    public function lazyFormRegistration($idBuyIN = null, $pokerSiteId = null)
    {

        try {
            $pokerSessionProvider = new PokerSessionProvider();
            $openedSession = $pokerSessionProvider->getCurrentOpen();
            if ($openedSession['statusCode'] != 202) {
                throw new APPException("Erro ao recuperar a sessão aberta");
            }
            $buyInList = $openedSession['content']['buyInList'];

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

            $this->dados['pokerSites'] = array_filter($pokerSites['content'], function ($site) {
                return !empty($site['id_pessoa']);
            });

            $html = $this->view->render('Session/BuyIns/form-cadastro.twig', $this->dados);
            print(json_encode(['success' => true, 'html' => $html, 'title' => 'Opaaaa']));

        } catch (Throwable $th) {
            print(json_encode(['success' => false, 'message' => $th->getMessage()]));
        }
    }
}
