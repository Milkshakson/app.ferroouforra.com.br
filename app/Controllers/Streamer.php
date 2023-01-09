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
    if (session('isValidTokenAcesso')) {

      echo "<h2>Copie a url abaixo e cole no OBS como fonte de navegador</h2>";
      echo base_url('streamer/showOverlay/' . session('tokenAcesso'));
    } else {
      echo "<h2>Você precisa estar autenticado para usar a overlay.</h2>";
      echo '<a href="' . base_url() . '" >Entrar</a>';
    }
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

  public function showOverlay($existingTtoken)
  {
    $auth = new Auth();
    $usuarioProvider = new UsuarioProvider();
    $login = $usuarioProvider->loginByExistingToken($existingTtoken);
    $auth->loginToSession($login);
    $this->view->display('Overlay/tela1', $this->dados);
    try {
      $usuarioProvider = new UsuarioProvider();
      $login = $usuarioProvider->loginByExistingToken($existingTtoken);
      $auth->loginToSession($login);
      $this->view->display('Overlay/tela1', $this->dados);
    } catch (Exception $e) {
      // pre($e);
      $auth->unsetSession();
      echo 'Token inválido';
    }
  }
}
