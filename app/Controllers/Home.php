<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $this->dados['colorsRand']=$hex = array_merge(range(0, 9), range('A', 'F'));
        $this->dados['colorsRand']= sprintf('#06x', random_int(0, 0xFFFFFF));
        $this->view->display('Home/index',$this->dados);
    }
}
