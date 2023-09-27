<?php

namespace App\Libraries;

use Exception;

class APPException extends Exception
{

    public function __construct($message, $code = 500, $previous = null)
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
            'Id do jogo não encontrado',
        ]);
        $APIMessages = [
            'INVALID_TOKEN' => 'Token de acesso inválido',
            'POKER_SESSIONS_OPEN_EXISTS' => 'Já existe uma sessão aberta',
            'POKER_SESSIONS_NOT_EXISTS' => 'Sessão não encontrada',
            "EMAIL_NOT_CONFIRMED" => "Seu email não foi confirmado.",
            "EMAIL_NOT_FOUND" => "Email não encontrado.",
            'EMAIL_EXISTS' => 'Este email já está cadastrado no sistema.',
            'OPENED_GAMES_IN_SESSION_CLOSE' => 'É preciso encerrar os torneios abertos antes de encerrar a sessão.',
            'TOO_MANY_RECOVERY' => 'Já existe uma recuperação em andamento.',
        ];
        $messages = array_unique($APIMessages + $genericMessages);
        if (key_exists($message, $messages)) {
            return $messages[$message];
        } else {
            return $message;
        }
    }
}
