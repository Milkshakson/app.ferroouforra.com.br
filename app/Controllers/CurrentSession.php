<?php

namespace App\Controllers;

use App\Libraries\APPException;
use App\Providers\PokerSessionProvider;
use Exception;

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

            $this->dados['countFuturo']  = count(array_filter($buyInList, function ($bi) {
                return ci_time($bi['startDate'])->isAfter(ci_time('now')) && is_null($bi['endDate']);
            }));
            $this->dados['sumary'] = $openedSession['sumary'];
            $this->dados['countEncerrados']  = count(array_filter($buyInList, function ($bi) {
                return  !is_null($bi['endDate']);
            }));
            $this->dados['sitesJogados'] = array_unique(array_column($buyInList, 'siteName'));
            $html = $this->view->render('Session/Current/summary.twig', $this->dados);
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

    public function lazyFormRegistration()
    {
        try {
            $idBuyIN = null;
            $pokerSessionProvider = new PokerSessionProvider();
            $openedSession = session('openedSession');
            if (!$openedSession) {
            }
            $buyInList = key_exists('buyInList', $openedSession) ? $openedSession['buyInList'] : [];
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
            $currentBI = $this->getCurrentBi($buyInList, $idBuyIN);
            if ($currentBI == []) {
                $currentBI['sessionPokerid'] = $openedSession['id'];
            }
            $this->dados['bi'] = $currentBI;


            $html = $this->view->render('Session\BuyIns\lazy-form.twig', $this->dados);
            print(json_encode(['html' => $html, 'title' => 'Adição de buy-in']));
        } catch (Exception $e) {
            print(json_encode(['html' => APPException::handleMessage($e->getMessage())]));
        }
    }
}