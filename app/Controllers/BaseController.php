<?php

namespace App\Controllers;

use App\Libraries\APIFF;
use App\Providers\PokerSessionProvider;
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

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        $this->session = \Config\Services::session();
        $this->apiApp = new APIFF();
        $path = $this->request->getPath();
        helper(['url', 'form']);
        $excecao = ['login/index', 'login/login', 'login/logout'];
        if (!in_array($path, $excecao)) {
            $this->checkToken();
        }
    }
    protected function checkToken()
    {

        $path = $this->request->getPath();
        try {
            // verifica se existe um token armazenado
            $stringTokenAcesso = trim($this->session->get('tokenAcesso'));
            if (empty($stringTokenAcesso)) {
                $this->exitSafe('Esta área do sistema requer autenticação.');
            } else {
                // checa o payload
                $this->dados['is_valid_token'] = false;
                $payload = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $stringTokenAcesso)[1]))));
                $payloadTeste = (array) $payload;
                if ($payloadTeste) {
                    $falhas = 0;
                    key_exists('sub', $payloadTeste) or $falhas++;
                    key_exists('localId', $payloadTeste) or $falhas++;
                    key_exists('iat', $payloadTeste) or $falhas++;
                    key_exists('exp_data', $payloadTeste) or $falhas++;
                    key_exists('environment', $payloadTeste) or $falhas++;
                    $expDate = Time::createFromTimestamp($payloadTeste['exp']);
                    $payload->expDate = $expDate->format('d/m/Y H:i:s');
                    $nowDate = new Time('now');
                    if (!$expDate->isAfter($nowDate)) {
                        $falhas++;
                    }
                    if ($falhas > 0) {
                        $this->session->set('isValidTokenAcesso', false);
                        $this->session->set('usuarioTokenAcesso', null);
                        $this->exitSafe('Token de acesso inválido.');
                    } else {
                        $this->session->set('isValidTokenAcesso', true);
                        $this->session->Set('usuarioTokenAcesso', $payload);
                    }
                }
            }
        } catch (Exception $e) {
            $this->exitSafe('Esta área do sistema requer autenticação.');
        }
    }
    protected function exitSafe($msg = '', $route = 'login/index')
    {
        $this->session->setFlashdata('erro', $msg);
        $this->response->redirect(base_url($route));
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
            $input = json_decode($request->getBody(), true);
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
