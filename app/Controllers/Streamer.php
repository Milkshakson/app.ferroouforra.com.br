<?php

namespace App\Controllers;

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
    $this->dados['yearlySumary'] = $yearlySumary['content'];
    $this->dados['profit'] = array_reduce($yearlySumary['content'], function ($carry, $item) {
      return $carry + $item['profit'];
    }, 0);
    $this->dados['countBuyIns'] = array_reduce($yearlySumary['content'], function ($carry, $item) {
      return $carry + $item['countBuyIns'];
    }, 0);
    $this->view->display("Overlay\yearly-summary-cards.twig", $this->dados);
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
      $this->dados['overlayError'] = 'Token inválido';
      $this->dados['overlayError'] = $e->getMessage() . ' ' . $existingToken;
    }
    $this->view->display('Overlay/tela1', $this->dados);
  }
}
