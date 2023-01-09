<?php

namespace App\Controllers;

use App\Libraries\APIFF;
use App\Libraries\View;
use CodeIgniter\Config\Services;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use CodeIgniter\Validation\Exceptions\ValidationException;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;
    protected $jwtToken;
    protected $session;
    protected $dados = [];

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];
    protected $twig;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        $this->dados['versionScripts'] = getenv('versionScripts') ?? date('dmYhis');
        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        $this->session = \Config\Services::session();
        $this->apiApp = new APIFF();
        $path = $this->request->getPath();
        helper(['url', 'form', 'date', 'template', 'my_string']);

        $excecao = [
            'home/index', 'home/index/1', '/',
            'streamer/showOverlay', 'streamer/showOverlay/',
            'login/index', 'login/login',
            'login/twitch',
            'login/logout', 'registration/new',
            'registration/email-confirmation-resend',
            'registration/password-recovery',
            'registration/email-confirm',
        ];
        $sitesLogo = [
            "gg poker" => "/assets/img/poker-sites/ggpoker.jpg",
            "poker stars" => "/assets/img/poker-sites/pokerstars.jpg",
            "natural 8" => "/assets/img/poker-sites/natural8.jpg",
            "party poker" => "/assets/img/poker-sites/partypoker.jpg",
            "winamax" => "/assets/img/poker-sites/winamax.jpg",
            "888 poker" => "/assets/img/poker-sites/888poker.jpg",
            "americas cardroom" => "/assets/img/poker-sites/americascardroom.png",
            "poker king" => "/assets/img/poker-sites/pokerking.png",
            "bet fair" => "/assets/img/poker-sites/betfair.png",
            "bodog" => "/assets/img/poker-sites/bodog.png",
            "ya poker" => "/assets/img/poker-sites/yapoker.jpg",
            "sportingbet" => "/assets/img/poker-sites/sportingbet.jpg",
            "tiger gaming" => "/assets/img/poker-sites/tigergaming.png",
            "poker stars.es" => "/assets/img/poker-sites/pokerstars.es.png",
        ];

        $this->dados['decodedToken'] = session('decodedTokenAcesso');
        $this->dados['title'] = 'Ferro ou Forra';
        $this->session->set('sitesLogo', $sitesLogo);
        //deve ser a última coisa a executar
        $this->view = new View($this->dados);
        if (!in_array($path, $excecao) && !str_starts_with($path, 'streamer/showOverlay')) {
            $this->checkToken();
        }
    }
    protected function getCurrentBi($buyInList, $idBuyIN)
    {
        $currentBI = [];
        $editBi = array_filter($buyInList, function ($bi) use ($idBuyIN) {
            return $bi['buyinId'] == $idBuyIN;
        });
        if (count($editBi) == 1) {
            $currentBI = end($editBi);
        }

        return $currentBI;
    }
    protected function checkToken()
    {
        try {
            // verifica se existe um token armazenado
            $stringTokenAcesso = trim($this->session->get('tokenAcesso'));
            if (empty($stringTokenAcesso)) {
                return $this->exitSafe('Esta área do sistema requer autenticação.');
            } else {
                // checa o payload
                $this->dados['is_valid_token'] = false;
                $payload = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $stringTokenAcesso)[1]))));
                if ($payload) {
                    $falhas = [];
                    $falhas = array_filter(['sub', 'localId', 'iat', 'exp_data', 'environment'], function ($key) use ($payload) {
                        return  !property_exists($payload, $key);
                    });
                    $expDate = Time::createFromTimestamp($payload->exp, app_timezone());
                    $payload->expDate = $expDate->format('d/m/Y H:i:s');
                    $nowDate = new Time('now');
                    if (!$expDate->isAfter($nowDate)) {
                        $falhas[] = 'isNotAfter';
                    }

                    if (count($falhas) > 0) {
                        $this->session->set('isValidTokenAcesso', false);
                        $this->session->set('usuarioTokenAcesso', null);

                        pre($falhas);

                        return $this->exitSafe('Token de acesso inválido.');
                    } else {
                        $this->session->set('isValidTokenAcesso', true);
                        $this->session->Set('usuarioTokenAcesso', $payload);
                    }
                }
            }
        } catch (Exception $e) {
            return $this->exitSafe('Esta área do sistema requer autenticação.');
        }
    }
    protected function exitSafe($msg = '', $route = 'login/index')
    {
        $this->session->setFlashdata('erros', $msg);
        $redirect = site_url($route);
        header("location: $redirect", 1);
        exit;
    }
    public function getResponse(
        array $responseBody,
        int $code = ResponseInterface::HTTP_OK
    ) {
        return $this
            ->response
            ->setStatusCode($code)
            ->setJSON($responseBody);
    }

    public function getRequestInput(IncomingRequest $request)
    {
        $input = $request->getPost();
        if (empty($input)) {
            //convert request body to associative array
            $input = $request->getBody() ? json_decode($request->getBody(), true) : null;
        }
        return $input;
    }
    public function validateRequest($input, array $rules, array $messages = [])
    {
        $this->validator = Services::Validation()->setRules($rules);
        // If you replace the $rules array with the name of the group
        if (is_string($rules)) {
            $validation = config('Validation');

            // If the rule wasn't found in the \Config\Validation, we
            // should throw an exception so the developer can find it.
            if (!isset($validation->$rules)) {
                throw ValidationException::forRuleNotFound($rules);
            }

            // If no error message is defined, use the error message in the Config\Validation file
            if (!$messages) {
                $errorName = $rules . '_errors';
                $messages = $validation->$errorName ?? [];
            }

            $rules = $validation->$rules;
        }
        return true; //$this->validator->setRules($rules, $messages)->run($input);
    }
}
