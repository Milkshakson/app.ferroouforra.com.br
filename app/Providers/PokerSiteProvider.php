<?php

namespace App\Providers;

use App\Libraries\APIFF;
use Exception;

class PokerSiteProvider extends APIFF
{
    public function updateSitesUsuario($dados = [])
    {
        try {
            $update = $this->consumeEndpoint('POST', "/poker_session/sites_usuario", $dados);
            if (in_array($update['statusCode'], [200, 201]))
                return true;
            else
            if (key_exists('msg', $update['content']))
                throw new Exception($update['content']['msg'], 1);
            else
                throw new Exception("Erro ao salvar os sites", 1);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
