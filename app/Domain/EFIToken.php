<?php
namespace App\Domain;

use CodeIgniter\I18n\Time;

class EFIToken
{
    private $accessToken;
    private $tokenType;
    private $expiresIn;
    private $scope;
    private $expirationDate = null;

    public function __construct(array $data)
    {
        $this->accessToken = $data['access_token'];
        $this->tokenType = $data['token_type'];
        $this->expiresIn = $data['expires_in'];
        $this->scope = $data['scope'];
        $this->expirationDate = Time::now()->addSeconds($this->expiresIn);
    }
    public function isValid()
    {
        $timeLimit = Time::now()->addSeconds(60);
        return $this->accessToken && $this->expirationDate->isAfter($timeLimit);
    }
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getTokenType()
    {
        return $this->tokenType;
    }

    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    public function getScope()
    {
        return $this->scope;
    }
}
