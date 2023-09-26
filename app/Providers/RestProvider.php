<?php
namespace App\Providers;

use App\Traits\Curl;
use Exception;

class RestProvider
{
    use Curl;
    protected $apiURL = '';
    protected $clientId = '';
    protected $clientSecret = '';
    protected $accessToken;

    public function setAccessToken(string $accessToken)
    {
        $this->accessToken = $accessToken;
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
                'DELETE',
            ])) {
                throw new Exception("Não foi informado o tipo de requisição para a API.");
            }

            if (empty($route)) {
                throw new Exception("Não foi informado corretamente o endpoint da API.");
            }
            if (is_array($params) && count($params)) {
                $params = json_encode($params);
            }
            $this->setRequestType($requestType, $params);
            $this->curlExec($this->endPoint($route));
            return $this->getGenericResponse($params);
        } catch (Exception $e) {
            throw $e;
        }
    }

    protected function getGenericResponse($dataSent = null): array
    {
        $retorno = [
            'statusCode' => $this->getHttpStatus(),
            'content' => null,
            'raw' => $this->__tostring(),
            'method' => $this->_requestType,
            'url' => $this->_url,
        ];
        if ($dataSent) {
            $retorno['dataSent'] = $dataSent;
        }
        try {
            $jsonString = $this->__tostring();
            $content = json_decode($jsonString, 1);
            if (is_array($content)) {
                $retorno['content'] = $content;
                if (key_exists('error', $content)) {
                    throw new Exception($content['error']);
                }
                if (key_exists('rest_code', $content)) {
                    throw new Exception($content['rest_code']);
                }
            }
            return $retorno;
        } catch (Exception $e) {
            throw $e;
        }
    }

    protected function endPoint($route = '')
    {
        if (stripos($route, '/') === (int) 0) {
            return $this->apiURL . $route;
        } else {
            return $this->apiURL . '/' . $route;
        }

    }
    protected function curlExec($endpoint)
    {
        if (!empty($this->accessToken)) {
            $this->setHeader([
                "Authorization: Bearer $this->accessToken",
            ], true);
        }
        $this->createCurl($endpoint);
    }
}
