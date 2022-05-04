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
}
