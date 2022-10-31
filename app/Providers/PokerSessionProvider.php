<?php

namespace App\Providers;

use App\Libraries\APIFF;

class PokerSessionProvider extends APIFF
{
    public function getCurrentOpen()
    {
        return $this->consumeEndpoint('GET', "/poker_session/current_open_v2");
    }
    public function open($dados = [])
    {
        return $this->consumeEndpoint('POST', "/poker_session/create_v2", $dados);
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

    public function encerrar($dados)
    {
        return $this->consumeEndpoint('POST', "/poker_session/fecha_sessao_v2", $dados);
    }

    public function getResumoAnual($year)
    {
        return $this->consumeEndpoint('GET', "/poker_session/resumo_anual_v2/?year=$year");
    }
    public function getResumoAnualBySite($year)
    {
        return $this->consumeEndpoint('GET', "/poker_session/resumo_anual_by_site_v2/?year=$year");
    }

    public function getResumoMensal($month, $year)
    {
        return $this->consumeEndpoint('GET', "/poker_session/resumo_mensal_v2/?month=$month&year=$year");
    }
}