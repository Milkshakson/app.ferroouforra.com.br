<?php
namespace App\Controllers;

use App\Providers\DonationProvider;
use App\Providers\PagamentoProvider;
use Exception;
use Throwable;

class Donation extends BaseController
{
    protected function consultar(string $txId = null)
    {
        try {
            if ($txId) {
                $donationProvider = new DonationProvider();
                $pagamentoProvider = new PagamentoProvider();
                $cobranca = $pagamentoProvider->consultaCobrancaPIX($txId);
                $this->checkResponse($cobranca, 200);
                $payload = $cobranca['content'];
                $donationLocal = $donationProvider->findDonationByIdentificador($txId);
                $this->checkResponse($donationLocal, 200);
                $payload['recebido'] = $donationLocal['content']['recebido'];
                $this->dados['cobranca'] = $payload;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function confirmarPagamento($txId)
    {
        $message = '';
        $success = false;
        try {
            $pagamentoProvider = new PagamentoProvider();
            $cobranca = $pagamentoProvider->consultaCobrancaPIX($txId);
            if ($cobranca['statusCode'] != 200) {
                throw new Exception($cobranca['content']['erro'], 1);
            }
            if (
                key_exists('pix', $cobranca['content'])
            ) {
                $payload = $cobranca['content']['pix'][0];
                $donationProvider = new DonationProvider();
                $dataPagamento = dateUTCToLocale(ci_time($payload['horario']))->format('Y-m-d H:i');
                if ($dataPagamento) {
                    $donation = $donationProvider->findDonationByIdentificador($txId);
                    $this->checkResponse($donation, 200, "Donation $txId não encontrada");
                    $updateData = [
                        'id' => $donation['content']['id'],
                        'data_recebimento' => $dataPagamento,
                        'recebido' => 1,
                    ];
                    $update = $donationProvider->updateDonation($updateData);

                    $this->checkResponse($update, 200, 'Confirmação não pode ser efetuada');
                    $success = true;
                } else {
                    throw new Exception("Ainda não foi possível identificar o pagamento.", 1);
                }
            } else {
                throw new Exception("Ainda não foi possível identificar o pagamento", 1);
            }
        } catch (\Throwable $th) {
            $message = $th->getMessage();
        }
        print(json_encode(['success' => $success, 'message' => $message]));
    }
    public function create()
    {
        try {
            if ($this->request->getMethod() == 'post') {
                $mensagensAgradecimento = [
                    "Sua doação é como um raio de sol em nosso dia! Muito obrigado!",
                    "Com você ao nosso lado, podemos alcançar grandes feitos. Obrigado por sua generosidade!",
                    "Seu ato de bondade não passou despercebido. Agradecemos do fundo do coração.",
                    "Sua doação aqueceu nossos corações. Muito obrigado por fazer a diferença!",
                    "Com pessoas incríveis como você, nosso trabalho se torna mais significativo. Obrigado!",
                    "Sua doação é um presente precioso que nos inspira a continuar. Agradecemos muito!",
                    "Você é um herói para nós. Obrigado por sua doação incrível!",
                    "Seu apoio é a energia que nos impulsiona a avançar. Muito obrigado!",
                    "Agradecemos por ser uma parte valiosa de nossa comunidade de apoio.",
                    "Sua generosidade é um farol de esperança para nós. Obrigado!",
                    "Com doadores como você, podemos fazer a diferença no mundo. Agradecemos!",
                    "Seu ato de bondade é como uma luz brilhante em nossas vidas. Muito obrigado!",
                    "Agradecemos por acreditar em nossa causa e nos ajudar a crescer.",
                    "Sua doação é uma prova de que juntos podemos alcançar grandes coisas. Obrigado!",
                    "Com gratidão em nossos corações, agradecemos por seu apoio contínuo.",
                    "Sua contribuição é o que nos permite continuar nossa missão. Agradecemos muito!",
                    "Obrigado por ser uma fonte constante de inspiração e apoio.",
                    "Sua doação é um lembrete de que o mundo está cheio de bondade. Muito obrigado!",
                    "Com você ao nosso lado, estamos construindo um futuro melhor. Obrigado!",
                    "Agradecemos por ser um anjo em nossas vidas. Sua doação é incrível!",
                ];
                $indiceAleatorio = array_rand($mensagensAgradecimento);
                // Agora você pode escolher uma mensagem aleatoriamente quando precisar exibi-la no cabeçalho do formulário.

                $message = $mensagensAgradecimento[$indiceAleatorio];

                $input = $this->request->getPost();
                $rules =
                    [
                    'cpf' => 'required|valid_cpf',
                    'valor' => 'required|decimal',
                ];
                $validation = \Config\Services::validation();
                $validation->setRules($rules);
                if ($validation->run($input)) {
                    $input['valor'] = formatarValorMonetario($input['valor']);

                    $pagamentoProvider = new PagamentoProvider();
                    $inputPix = $input;
                    $decodedToken = $this->dados['decodedToken'];

                    $inputPix['nome'] = $decodedToken->nome;
                    $inputPix['titulo'] = 'Doação voluntária';

                    unset($inputPix['mensagem']);
                    $inputDonation = $input;
                    $inputDonation['pessoa_id'] = $decodedToken->localId;
                    unset($inputDonation['cpf']);

                    $pix = $pagamentoProvider->cobrarViaPIX($inputPix);
                    if ($pix['statusCode'] != 201) {
                        throw new Exception($pix['content']['erro'], 1);
                    }
                    $txId = $pix['content']['txid'];
                    $inputDonation['identificador'] = $txId;
                    $donationProvider = new DonationProvider();
                    $donation = $donationProvider->createDonation($inputDonation);
                    print(json_encode(['success' => true, 'message' => 'Sucesso', 'txId' => $txId]));
                    exit;
                } else {
                    throw new Exception(implode('<br>', $validation->getErrors()), 1);
                }
            } else {
                throw new Exception('Falha na requisição ao salvar', 1);
            }
        } catch (Throwable $th) {
            print(json_encode(['success' => false, 'message' => $th->getMessage()]));
            exit;
        }

        print(json_encode(['success' => false, 'message' => 'falha ao salvar']));

    }
    public function index($txId = null)
    {
        $message = '';
        $success = true;
        try {
            $this->consultar($txId);
        } catch (\Throwable $th) {
            $success = false;
            $this->dados['erro'] = $th->getMessage();
            $message = $th->getMessage();
        }
        $html = $this->view->render('Donation/index', $this->dados);
        print(json_encode(['success' => $success, 'message' => $message, 'html' => $html]));
    }
}
