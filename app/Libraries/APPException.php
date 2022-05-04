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
    protected function handleMessage($message)
    {
        $messages = ['POKER_SESSIONS_OPEN_EXISTS' => 'Já existe uma sessão aberta'];
        if (key_exists($message, $messages)) {
            return $messages[$message];
        } else {
            return 'Ocorreu um erro na operação.';
        }
    }
}
