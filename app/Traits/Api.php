<?php
namespace App\Traits;

use Exception;

trait Api
{

    private function searchKeyRecursive($array, string | array $searchKey, $default = null)
    {
        try {
            $message = $default;
            foreach ($array as $key => $value) {
                $foundKey = is_array($searchKey) ? in_array($key, $searchKey) : $key === $searchKey;

                if ($foundKey) {
                    return $value; // Chave encontrada, retorna o valor
                } elseif (is_object($value)) {
                    $result = $this->searchKeyRecursive((array) $value, $searchKey);
                    if ($result !== null) {
                        return $result; // Chave encontrada em um subarray, retorna o valor
                    }
                } elseif (is_array($value)) {
                    $result = $this->searchKeyRecursive($value, $searchKey);
                    if ($result !== null) {
                        return $result; // Chave encontrada em um subarray, retorna o valor
                    }
                }
            }
            return $message; // Chave não encontrada
            throw new Exception("Error Processing Request" . $message, 1);

            return $message; // Chave não encontrada
        } catch (\Throwable $th) {
            throw $th;
        }

    }
    /**
     * Lança uma Exception baseada no retorno
     */
    protected function throwFromResponse($response, $default = null)
    {
        throw new Exception($this->handleResponse($response, $default), $response['statusCode']);
    }

    protected function handleResponse($response, $defaultMessage = null)
    {
        try {
            return $this->searchKeyRecursive($response, ['error', 'erros', 'erro'], $defaultMessage);
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function dataToString(array $data, $separator = '<br/>', bool $withKey = false)
    {
        try {
            $finalData = [];
            if ($separator == 'json') {
                return (json_encode($data));
            }
            if ($withKey) {
                foreach ($data as $key => $value) {
                    $finalData[] = "$key: $value";
                }
            } else {
                $finalData = $data;
            }

            return implode($separator, $finalData);
        } catch (\Throwable $th) {
            throw $th;
        }

    }
    /**
     * Verifica o retorno da API e lança uma Exception em casa de status diferente do previsto
     */
    protected function checkResponse(array $response, array | string $validStatus, string $defaultMessage = null)
    {
        try {
            $validStatus = is_array($validStatus) ? $validStatus : [$validStatus];
            if (!in_array($response['statusCode'], $validStatus)) {
                $this->throwFromResponse($response, $defaultMessage);
            }
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    protected function isValidUpdateOrCreate(array $response, string $key = 'statusCode')
    {
        try {
            $status = $response[$key];
            return in_array($status, [200, 201]);
        } catch (\Throwable $th) {
            throw $th;
        }

    }
}
