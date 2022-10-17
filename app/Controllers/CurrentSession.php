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
            $openedSession = session('openedSession');
            if (!$openedSession) {
            }
            $buyInList = key_exists('buyInList', session('openedSession')) ? session('openedSession')['buyInList'] : [];
            $this->dados['buyInList'] = $buyInList;
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
            $html = $this->view->render('Session/Current/summary.twig', $this->dados);
            print(json_encode(['html' => $html]));
        } catch (Exception $e) {
            $this->exitSafe(APPException::handleMessage($e->getMessage()));
        }
    }

    public function lazyLoadBuyInList()
    {
        try {
            $openedSession = session('openedSession');
            if (!$openedSession) {
            }
            $buyInList = key_exists('buyInList', session('openedSession')) ? session('openedSession')['buyInList'] : [];
            $this->dados['buyInList'] = $buyInList;
            $html = $this->view->render('Session/BuyIns/list-cards.twig', $this->dados);
            print(json_encode(['html' => $html]));
        } catch (Exception $e) {
            $this->exitSafe(APPException::handleMessage($e->getMessage()));
        }
    }

    public function lazyFormRegistration()
    {
        try {
            $openedSession = session('openedSession');
            if (!$openedSession) {
            }
            $buyInList = key_exists('buyInList', session('openedSession')) ? session('openedSession')['buyInList'] : [];
            $this->dados['buyInList'] = $buyInList;
            $html = $this->view->render('Session\BuyIns\form-cadastro.twig', $this->dados);
            print(json_encode(['html' => $html, 'title' => 'Adição de buy-in']));
        } catch (Exception $e) {
            $this->exitSafe(APPException::handleMessage($e->getMessage()));
        }
    }
}