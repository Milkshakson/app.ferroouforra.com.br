<?php

namespace App\Providers;

use App\Libraries\APIFF;

class UsuarioProvider extends APIFF
{
    public function login($email, $senha)
    {
        return $this->consumeEndpoint('POST', "login", [
            'email' => $email,
            'senha' => $senha
        ]);
    }
}
