<?php

namespace App\Libraries;

class APIFF extends Curl
{
    protected $tokenAcesso = '';

    private $APIURL;

    public function __construct($params = [])
    {
        switch (ENVIRONMENT) {
            case 'development':
            case 'testing':
                $this->APIURL = 'https://api-dev.ferroouforra.com.br';
                break;
            case 'production':
                $this->APIURL = 'https://api.ferroouforra.com.br';
                break;
            default:
                $this->APIURL = '';
                break;
        }
        $header = [
            "Content-Type: application/json",
            "App-Version: " . config('App')->appVersion
        ];
        $this->setTokenAcesso(session('tokenAcesso'));
        $this->setHeader($header, 1);
    }

    public function setTokenAcesso($tokenAcesso)
    {
        $this->tokenAcesso = $tokenAcesso;
    }

    public function getTokenAcesso()
    {
        return $this->tokenAcesso;
    }

    public function endPoint($route = '')
    {
        if (stripos($route, '/') === (int) 0)
            return $this->APIURL . $route;
        else
            return $this->APIURL . '/' . $route;
    }

    public function consumeEndpoint($requestType = '', $route = '', $params = [])
    {
        try {
            $requestType = strtoupper($requestType);
            if (!in_array($requestType, [
                'GET',
                'POST',
                'PUT',
                'PATCH',
                'DELETE'
            ])) {
                throw new APPException("Não foi informado o tipo de requisição para a API.");
            }

            if (empty($route)) {
                throw new APPException("Não foi informado corretamente o endpoint da API.");
            }
            if (is_array($params) && count($params)) {
                $params = json_encode($params);
            }
            $this->setRequestType($requestType, $params);
            $this->curlExec($this->endPoint($route));
            return $this->getGenericResponse();
        } catch (AppException $e) {
            throw $e;
        }
    }

    protected function getGenericResponse()
    {
        $retorno = ['statusCode' => $this->getHttpStatus(), 'content' => null, 'raw' => $this->__tostring()];
        try {
            $jsonString = $this->__tostring();
            $content = json_decode($jsonString, 1);
            if (is_array($content)) {
                $retorno['content'] = $content;
                if (key_exists('error', $content)) {
                    throw new APPException($content['error']);
                }
                if (key_exists('rest_code', $content)) {
                    throw new APPException($content['rest_code']);
                }
            }
            return $retorno;
        } catch (APPException $e) {
            throw $e;
        }
    }

    protected function curlExec($endpoint)
    {
        if (!empty($this->tokenAcesso)) {
            $this->setHeader([
                "Authorization: Bearer $this->tokenAcesso"
            ], true);
        }
        $this->createCurl($endpoint);
    }
}
