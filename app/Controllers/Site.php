<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\APPException;
use App\Providers\PokerSessionProvider;
use App\Providers\PokerSiteProvider;
use Exception;

use function PHPUnit\Framework\throwException;

class Site extends BaseController
{
    public function index()
    {
    }

    public function salvaSitesPessoa()
    {
        try {
            $pokerSiteProvider = new PokerSiteProvider();
            $input = $this->getRequestInput($this->request);
            $update = $pokerSiteProvider->updateSitesUsuario(['sites' => $input['sites']]);

            if ($update)
                print(json_encode(['success' => true, 'message' => 'Atualização efetuada com sucesso']));
            else
                throw new Exception(json_encode($update), 1);
        } catch (Exception $e) {
            print(json_encode(['success' => false, 'message' => APPException::handleMessage($e->getMessage())]));
        }
    }

    public function lazyLoadSiteSelection()
    {
        try {
            $pokerSessionProvider = new PokerSessionProvider();
            $pokerSites = $pokerSessionProvider->getPokerSites();
            $this->dados['sites'] = $pokerSites['content'];
            $html = $this->view->render('Sites/selection.twig', $this->dados);
            print(json_encode(['success' => true, 'html' => $html]));
        } catch (Exception $e) {
            print(json_encode(['html' => APPException::handleMessage($e->getMessage())]));
        }
    }
}
