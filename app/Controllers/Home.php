<?php

namespace App\Controllers;

use App\Libraries\APPException;
use App\Providers\PokerSessionProvider;

class Home extends BaseController
{
    public function index()
    {
        try{
        $this->dados['colorsRand']=$hex = array_merge(range(0, 9), range('A', 'F'));
        $this->dados['colorsRand']= sprintf('#06x', random_int(0, 0xFFFFFF));
        $year =date('Y');
        $pokerSessionProvider = new PokerSessionProvider();
        $yearlySumary = $pokerSessionProvider->getResumoAnual($year);
        if($yearlySumary['statusCode']==202){
        $this->dados['yearlySumary'] = $yearlySumary['content'];
        }
    }catch(APPException $e){
        $this->dados['erros'] = $e->getHandledMessage($e->getMessage());
    }
        $this->view->display('Home/index',$this->dados);
    }
}
