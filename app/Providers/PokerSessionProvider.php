<?php

namespace App\Providers;

use App\Libraries\APIFF;

class PokerSessionProvider extends APIFF
{
    public function getCurrentOpen()
    {
        return $this->consumeEndpoint('GET', "/poker_session/current_open");
    }
    public function open($dados = [])
    {
        return $this->consumeEndpoint('POST', "/poker_session/create", $dados);
    }
    public function removeBuyIn($dados = [])
    {
        return $this->consumeEndpoint('POST', "/poker_session/remove_buyin", $dados);
    }

    public function salvaBuyIn($dados = [])
    {
        return $this->consumeEndpoint('POST', "/poker_session/save_buyin", $dados);
    }

    public function getTiposBuyIn()
    {
        return $this->consumeEndpoint('GET', "/poker_session/tipos_buyin", []);
    }

    public function getPokerSites()
    {
        return $this->consumeEndpoint('GET', "/poker_session/sites", []);
    }

    public function getMeusBuyIns()
    {
        return $this->consumeEndpoint('GET', "/poker_session/meus_buyins", []);
    }

    public function encerrar()
    {
        return $this->consumeEndpoint('POST', "/poker_session/fecha_sessao", []);
    }
}
