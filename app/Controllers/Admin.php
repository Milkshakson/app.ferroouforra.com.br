<?php

namespace App\Controllers;

use App\Providers\PokerSessionProvider;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Psr\Log\LoggerInterface;

class Admin extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $decodedToken = ($this->dados['decodedToken']);
        if ($decodedToken->nome != 'Milkshakson') {
            $this->exitSafe('Requisição inválida', '/');
        }
    }

    public function index()
    {
        try {
            $pokerSessionProvider = new PokerSessionProvider();
            $endpoint = 'admin/all_sessions_summary';
            $data = ($pokerSessionProvider->consumeEndpoint('get', $endpoint));
            $this->dados['sessoes'] = $data['content'];
        } catch (Exception $e) {
            $this->dados['erros'] = $e->getmessage();
        }
        $this->view->display('Admin/index', $this->dados);
    }
}