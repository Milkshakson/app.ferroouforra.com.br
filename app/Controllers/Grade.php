<?php

namespace App\Controllers;

use App\Libraries\APPException;
use App\Providers\PokerGradeProvider;
use Exception;

class Grade extends BaseController
{
    public function lazyLoad()
    {
        try {
            $pokerGradeProvider = new PokerGradeProvider();
            $pokergrades = $pokerGradeProvider->findByToken();
            $this->dados['grades'] = $pokergrades['content'];
            $html = $this->view->render('Schedule/list.twig', $this->dados);
            print(json_encode(['success' => true, 'html' => $html]));
        } catch (Exception $e) {
            print(json_encode(['html' => APPException::handleMessage($e->getMessage())]));
        }
    }

    public function salvar($id = null)
    {
        try {
            $pokerGradeProvider = new PokerGradeProvider();
            if ($this->request->getMethod() == 'post') {
                $input = $this->getRequestInput($this->request);
                // $input['nome_grade'] = ucfirst(trim($input['nome_grade']));
                // $input['descricao_grade'] = trim($input['descricao_grade']);
                if (key_exists('id', $input)) {
                    $save = $pokerGradeProvider->update($input);
                } else {
                    $save = $pokerGradeProvider->create($input);
                }

                if ($this->isValidUpdateOrCreate($save)) {
                    print(json_encode(['success' => true]));
                } else {
                    throw new Exception($this->handleResponse($save, 'Falha ao salvar a grade'), $save['statusCode']);
                }

            } else {
                $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
                $grade = $pokerGradeProvider->findBYId($id);
                $this->dados['grade'] = $grade['content'];
                $html = $this->view->render('Schedule/store.twig', $this->dados);
                print(json_encode(['success' => true, 'html' => $html]));
            }

        } catch (Exception $e) {
            print(json_encode(['message' => APPException::handleMessage($e->getMessage())]));
        }
    }

    public function lazyLoadByID($idGrade = null)
    {
        try {
            $pokerGradeProvider = new PokerGradeProvider();

            $idGrade = filter_var($idGrade, FILTER_SANITIZE_NUMBER_INT);
            $pokergrades = $pokerGradeProvider->findBYId($idGrade);
            $this->dados['grade'] = $pokergrades['content'];
            $html = $this->view->render('Schedule/detail.twig', $this->dados);
            print(json_encode(['success' => true, 'html' => $html]));
        } catch (Exception $e) {
            print(json_encode(['html' => APPException::handleMessage($e->getMessage())]));
        }
    }

    public function loadTournaments($idGrade)
    {
        try {
            $pokerGradeProvider = new PokerGradeProvider();
            $tournaments = $pokerGradeProvider->loadTournaments($idGrade);
            $this->dados['tournaments'] = $tournaments['content'];
            $html = $this->view->render('Schedule/tournaments.twig', $this->dados);
            print(json_encode(['success' => true, 'html' => $html]));
        } catch (Exception $e) {
            print(json_encode(['html' => APPException::handleMessage($e->getMessage())]));
        }
    }
    public function removerTorneio($idTorneioGrade)
    {
        try {
            $idTorneioGrade = filter_var($idTorneioGrade, FILTER_SANITIZE_NUMBER_INT);
            $pokerGradeProvider = new PokerGradeProvider();
            $jogos = [$idTorneioGrade];
            $remover = $pokerGradeProvider->removeTournaments(['jogos' => $jogos]);
            if ($remover['statusCode'] == 204) {
                print(json_encode(['success' => true]));
            } else {
                throw new Exception($this->handleResponse($remover, 'Falha ao remover o torneio'), $remover['statusCode']);
            }

        } catch (Exception $e) {
            print(json_encode(['message' => APPException::handleMessage($e->getMessage())]));
        }
    }
    public function remover($idGrade)
    {
        try {
            $idGrade = filter_var($idGrade, FILTER_SANITIZE_NUMBER_INT);
            $pokerGradeProvider = new PokerGradeProvider();
            $remover = $pokerGradeProvider->delete($idGrade);
            if ($remover['statusCode'] == 204) {
                print(json_encode(['success' => true]));
            } else {
                throw new Exception($remover['content']['erros'], $remover['statusCode']);
            }

        } catch (Exception $e) {
            print(json_encode(['message' => APPException::handleMessage($e->getMessage())]));
        }
    }

    public function registrar()
    {
        try {
            if ($this->request->getMethod() == 'post') {
                $input = $this->getRequestInput($this->request);
                throw new Exception(json_encode($input));
            } else {
                throw new Exception("Requisição inválida");
            }

        } catch (Exception $e) {
            print(json_encode(['message' => APPException::handleMessage($e->getMessage())]));
        }
    }
}
