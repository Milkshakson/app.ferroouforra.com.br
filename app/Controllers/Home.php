<?php

namespace App\Controllers;

use App\Providers\PokerSessionProvider;

class Home extends BaseController
{
    public function index()
    {
        $this->dados['colorsRand']=$hex = array_merge(range(0, 9), range('A', 'F'));
        $this->dados['colorsRand']= sprintf('#06x', random_int(0, 0xFFFFFF));
        $year =date('Y');
        $pokerSessionProvider = new PokerSessionProvider();
        $yearlySumary = $pokerSessionProvider->getResumoAnual($year);
        if($yearlySumary['statusCode']==202){
        $this->dados['yearlySumary'] = $yearlySumary['content'];
        }
        $this->view->display('Home/index',$this->dados);
    }
}
