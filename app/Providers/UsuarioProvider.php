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
    public function loginTwitch($code)
    {
        return $this->consumeEndpoint('POST', "login-twitch", [
            'code' => $code,
        ]);
    }
    public function loginByExistingToken($token)
    {
        $this->setHeader(['Authorization' => $token], true);
        return $this->consumeEndpoint('POST', "auth/login_existing_token");
    }

    public function getStreamInfo()
    {
        return $this->consumeEndpoint('get', 'streamer/live_info');
    }

    public function getTwitchCredential($token, $email)
    {
        return $this->consumeEndpoint('get', "twitch/credential", ['token' => $token]);
    }

    public function register($dados = [])
    {
        return $this->consumeEndpoint('POST', "signup", $dados);
    }

    public function resendConfirmationEmail($email)
    {
        return $this->consumeEndpoint('POST', "auth/resend_confirmation_email", ['email' => $email]);
    }

    public function confirmEmail($token, $email)
    {
        return $this->consumeEndpoint('POST', "auth/confirm_email", ['token' => $token, 'email' => $email]);
    }

    public function changePassword($token, $email)
    {
        return $this->consumeEndpoint('POST', "auth/change_password", ['token' => $token, 'email' => $email]);
    }
    public function passwordRecovery($email)
    {
        return $this->consumeEndpoint('GET', "auth/password_recovery_v2?email=$email");
    }
}
