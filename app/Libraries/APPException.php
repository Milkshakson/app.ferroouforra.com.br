<?php

namespace App\Libraries;

use Exception;

class APPException extends Exception
{

    function __construct($message, $code = 500, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getHandledMessage(): String
    {
        return $this->handleMessage(parent::getMessage());
    }
    public static function handleMessage($message)
    {
        $genericMessages = array_array([
            'Erro ao recuperar a lista de sites',
            'Erro ao recuperar os tipos de buy in',
            'Erro ao recuperar a sessão aberta',
            'Id do jogo não encontrado'
        ]);
        $APIMessages = [
            'POKER_SESSIONS_OPEN_EXISTS' => 'Já existe uma sessão aberta',
        ];
        $messages = array_unique($APIMessages+$genericMessages);
        if (key_exists($message, $messages)) {
            return $messages[$message];
        } else {
            return 'Ocorreu um erro na operação.';
        }
    }
}
