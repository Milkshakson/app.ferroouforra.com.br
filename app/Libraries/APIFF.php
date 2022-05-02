<?php

class APIFF extends curl
{
    protected $tokenAcesso = '';

    private $charset = 'UTF-8';

    private $APIURL;

    private $response;

    public function __construct($params = [])
    {
        switch (ENVIRONMENT) {
            case 'development':
            case 'testing':
                $this->APIURL = 'http://api-dev-ff.byworks.com.br';
                break;
            case 'production':
                $this->APIURL = 'http://api-prod-ff.byworks.com.br';
                break;
            default:
                $this->APIURL = '';
                break;
        }
        $header = [
            "Content-Type: application/json"
        ];
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

    /**
     * Retorna os dados basicos do protocolo baseado no seu numero
     *
     * @param string $protocolo
     * @return JsonSerializable
     */
    public function loginPorCPF($params = [])
    {
        $params = json_encode($params);
        $this->setRequestType('POST', $params);

        $this->curlExec($this->endPoint("api/v1/login"));
        return $this->getResponse();
    }

    /**
     * Retorna os dados basicos do protocolo baseado no seu numero
     *
     * @param string $protocolo
     * @return JsonSerializable
     */
    public function loginPorExpresso($params = [])
    {
        $params = json_encode($params);
        $this->setRequestType('POST', $params);

        $this->curlExec($this->endPoint("api/v1/login-ldap"));
        return $this->getResponse();
    }

    /**
     * Retorna os dados basicos do protocolo baseado no seu numero
     *
     * @param string $protocolo
     * @return JsonSerializable
     */
    public function loginPorLDAP($params = [])
    {
        $params = json_encode($params);
        $this->setRequestType('POST', $params);

        $this->curlExec($this->endPoint("api/v1/login-ldap"));
        return $this->getResponse();
    }

    public function getModuloByID($id = null)
    {
        $this->setRequestType('GET');
        $this->curlExec($this->endPoint("api/v1/modulo/$id"));
        return $this->getResponse();
    }

    public function findInstituicoesByToken()
    {
        $this->setRequestType('GET');
        $this->curlExec($this->endPoint("api/v1/instituicao/find-by-token"));
        return $this->getResponse();
    }

    public function searchInstituicoesByText($textSearch)
    {
        $params = json_encode([
            'textSearch' => $textSearch
        ]);
        $this->setRequestType('GET', $params);
        $this->curlExec($this->endPoint("api/v1/instituicao/find-by-text"));
        return $this->getResponse();
    }

    public function findInstituicaoByCodigo($A002_Codigo)
    {
        $params = json_encode([
            'A002_Codigo' => $A002_Codigo
        ]);
        $this->setRequestType('GET', $params);
        $this->curlExec($this->endPoint("api/v1/instituicao/find-by-codigo"));
        return $this->getResponse();
    }

    public function findDeclaracoesByInstituicao($A002_Codigo)
    {
        $params = json_encode([
            'A002_Codigo' => $A002_Codigo
        ]);
        $this->setRequestType('GET', $params);
        $this->curlExec($this->endPoint("api/v1/declaracao/find-by-instituicao"));
        return $this->getResponse();
    }

    public function consumeEndpoint($requestType = '', $route = '', $params = [])
    {
        try {
            $requestType = strtoupper($requestType);
            if (! in_array($requestType, [
                'GET',
                'POST',
                'PUT',
                'PATCH',
                'DELETE'
            ])) {
                throw new AppException("Não foi informado o tipo de requisição para a API.");
            }

            if (empty($route)) {
                throw new AppException("Não foi informado corretamente o endpoint da API.");
            }
            if (is_array($params) && count($params)) {
                $params = json_encode($params);
            }
            $this->setRequestType($requestType, $params);
            $this->curlExec($this->endPoint($route));
            return $this->getGenericResponse();
        } catch (Exception $e) {
            throw $e;
        }
    }

    protected function getGenericResponse()
    {
        try {
            $jsonString = $this->__tostring();
            $array = json_decode($jsonString,1);
            if (is_array($array) && key_exists('error', $array) && is_array($array['error'])) {
                $array['error'] = implode(chr(13) . chr(10), $array['error']);
            } elseif (is_array($array) && key_exists('message', $array) && is_array($array['message'])) {
                $array['error'] = implode(chr(13) . chr(10), $array['message']);
            } elseif (is_array($array) && key_exists('content', $array) && is_array($array['content']) && key_exists('message', $array['content']) && is_array($array['content']['message'])) {
                $array['error'] = implode(chr(13) . chr(10), $array['message']);
            } elseif (is_array($array) && key_exists('content', $array) && is_array($array['content']) && key_exists('message', $array['content']) && is_string($array['content']['message'])) {
                $array['error'] = $array['content']['message'];
            } elseif (is_array($array) && key_exists('message', $array) && is_string($array['message'])) {
                $array['error'] = $array['message'];
            }
            if (key_exists('error', $array)) {
                throw new AppException($array['error']);
            } else {
                return $array;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function getResponse()
    {
        try {
            $jsonString = $this->__tostring();
            $jsonString = iconv("UTF-8", "Windows-1252//IGNORE", $jsonString);
            $array = json_decode(utf8_encode($jsonString), 1);
            if (is_array($array) && key_exists('error', $array) && is_array($array['error'])) {
                $array['error'] = implode(chr(13) . chr(10), $array['error']);
            } elseif (is_array($array) && key_exists('message', $array) && is_array($array['message'])) {
                $array['error'] = implode(chr(13) . chr(10), $array['message']);
            } elseif (is_array($array) && key_exists('content', $array) && key_exists('message', $array['content']) && is_array($array['content']['message'])) {
                $array['error'] = implode(chr(13) . chr(10), $array['message']);
            } elseif (is_array($array) && key_exists('content', $array) && key_exists('message', $array['content']) && is_string($array['content']['message'])) {
                $array['error'] = $array['content']['message'];
            } elseif (is_array($array) && key_exists('message', $array) && is_string($array['message'])) {
                $array['error'] = $array['message'];
            }

            if ($this->charset == 'ISO-8859-1') {
                array_walk_recursive($array, 'utf8_to_iso_array');
            }
            return [
                "status_code" => $this->getHttpStatus(),
                "content" => $array
            ];
        } catch (Exception $e) {
            return [
                "status_code" => $this->getHttpStatus(),
                "content" => []
            ];
        }
    }

    protected function curlExec($endpoint)
    {
        if (! empty($this->tokenAcesso)) {
            $this->setHeader([
                "Authorization: Bearer $this->tokenAcesso"
            ], true);
        }
        $this->createCurl($endpoint);
    }
}