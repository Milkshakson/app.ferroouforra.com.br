<?php
namespace App\Traits;

use Exception;

trait Api
{

    private function searchKeyRecursive($array, string | array $searchKey, $default = null)
    {
        foreach ($array as $key => $value) {
            $foundKey = is_array($searchKey) ? in_array($key, $searchKey) : $key === $searchKey;

            if ($foundKey) {
                return $value; // Chave encontrada, retorna o valor
            } elseif (is_array($value)) {
                $result = $this->searchKeyRecursive($value, $searchKey);
                if ($result !== null) {
                    return $result; // Chave encontrada em um subarray, retorna o valor
                }
            }
        }
        return $default; // Chave não encontrada
    }
    /**
     * Lança uma Exception baseada no retorno
     */
    private function throwFromResponse($response, $default = null)
    {
        throw new Exception($this->handleResponse($response, $default), $response['statusCode']);
    }
    protected function handleResponse($response, $defaultMessage = null)
    {
        return $this->searchKeyRecursive($response, ['error', 'erros', 'erro'], $defaultMessage);
    }

    /**
     * Verifica o retorno da API e lança uma Exception em casa de status diferente do previsto
     */
    protected function checkResponse(array $response, array | string $validStatus, string $defaultMessage = null)
    {
        $validStatus = is_array($validStatus) ? $validStatus : [$validStatus];
        if (!in_array($response['statusCode'], $validStatus)) {
            $this->throwFromResponse($response, $defaultMessage);
        }

    }

    protected function isValidUpdateOrCreate(array $response, string $key = 'statusCode')
    {
        $status = $response[$key];
        return in_array($status, [200, 201]);
    }
}
