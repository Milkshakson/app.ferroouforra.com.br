<?php

namespace App\Controllers;

use App\Libraries\APPException;
use App\Libraries\Auth;
use App\Providers\PokerSessionProvider;
use App\Providers\UsuarioProvider;
use Exception;

class Streamer extends BaseController
{

  public function overlay()
  {
    $this->view->display("Streamer/overlay.twig");
  }
  public function liveInfo()
  {
    try {
      $usuarioProvider = new UsuarioProvider();
      $info = $usuarioProvider->getStreamInfo();
      echo $info['content']['statusLiveText'];
    } catch (Exception $e) {
      echo 'Sem dados';
    }
  }

  public function summary($year = null)
  {
    $year = $year ?? $year = date('Y');
    $this->dados['year'] = $year;
    $pokerSessionProvider = new PokerSessionProvider();
    $yearlySumary = $pokerSessionProvider->getResumoAnual($year);
    $dataSummary  =  $yearlySumary['content'];
    $this->dados['profit'] = array_reduce($dataSummary, function ($carry, $item) {
      return $carry + $item['profit'];
    }, 0);
    $this->dados['countBuyIns'] = array_reduce($dataSummary, function ($carry, $item) {
      return $carry + $item['countBuyIns'];
    }, 0);

    $this->dados['abi'] = array_reduce($dataSummary, function ($carry, $item) {
      return $carry + $item['avgBuyIn'];
    }, 0) / (count($dataSummary) > 0 ? count($dataSummary) : 1);


    $this->view->display("Overlay\yearly-summary-cards.twig", $this->dados);
  }

  public function tournamentList()
  {
    try {
      $pokerSessionProvider = new PokerSessionProvider();
      $this->session->set('openedSession', $pokerSessionProvider->getCurrentOpen()['content']);
      $openedSession = session('openedSession');
      $buyInList = key_exists('buyInList', session('openedSession')) ? session('openedSession')['buyInList'] : [];
      $this->dados['openedSession'] = $openedSession;
      $this->dados['torneios'] = $buyInList;
      $this->dados['now'] = $now = ci_time();
      // $html = "<h2>Esta sessão</h2>";
      // //abertos
      // $html .= "<h3>Jogando</h3>";
      // $this->dados['now'] = $now = ci_time();
      // $this->dados['torneios'] = array_filter($buyInList, function ($torneio) use ($now) {
      //   $startDate = ci_time($torneio['startDate']);
      //   return !$torneio['isClosed'] && $startDate <= $now;
      // });
      // $html .= $this->view->render('Overlay/session-info.twig', $this->dados);
      // //encerrados
      // $html .= "<h3>Encerrados</h3>";
      // $this->dados['torneios'] = array_filter($buyInList, function ($torneio) {
      //   return $torneio['isClosed'];
      // });
      // $html .= $this->view->render('Overlay/session-info.twig', $this->dados);
      // //em breve
      // $html .= "<h3>Em Breve</h3>";
      // $this->dados['torneios'] = array_filter($buyInList, function ($torneio) use ($now) {
      //   $startDate = ci_time($torneio['startDate']);
      //   return $startDate > $now;
      // });
      // $html .= $this->view->render('Overlay/session-info.twig', $this->dados);

      $html = $this->view->render('Overlay/session-info.twig', $this->dados);
      print(json_encode(['html' => $html]));
    } catch (Exception $e) {
      print(json_encode(['html' => APPException::handleMessage($e->getMessage())]));
    }
  }

  public function showOverlay($existingToken)
  {
    $this->session->set('tokenAcesso', $existingToken);
    $auth = new Auth();
    try {
      $usuarioProvider = new UsuarioProvider();
      $login = $usuarioProvider->loginByExistingToken($existingToken);
      $auth->loginToSession($login);
    } catch (Exception $e) {
      $this->dados['overlayError'] = $e->getMessage() . ' ' . $existingToken;
    }
    $this->view->display('Overlay/tela1', $this->dados);
  }
}
