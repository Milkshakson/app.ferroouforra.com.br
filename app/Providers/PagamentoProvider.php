<?php
namespace App\Providers;

class PagamentoProvider extends RestProvider
{

    public function __construct(array $params = [])
    {

        $this->apiURL = getenv('apiPagamentos.url');
        $this->initialize($params);
        $this->setHeader(array(
            "Content-Type: application/json",
            "Authorization: " . getenv("apiPagamentos.authorizedKey"),
        ));
    }

    public function cobrarViaPIX(array $data = [])
    {
        try {
            return $this->consumeEndpoint('POST', '/pix/cobranca', $data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function listaCobrancasPIX(array $data = [])
    {
        try {
            return $this->consumeEndpoint('GET', "/pix/cobranca/listar?" . http_build_query($data));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function consultaCobrancaPIX($txID)
    {
        try {
            return $this->consumeEndpoint('GET', "/pix/cobranca/$txID");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getQrCodeLocation($id)
    {
        try {
            return $this->consumeEndpoint('GET', "/pix/qrcode/$id");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
