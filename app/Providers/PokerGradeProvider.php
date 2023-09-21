<?php

namespace App\Providers;

use App\Libraries\APIFF;

class PokerGradeProvider extends APIFF
{
    public function findBYId($idGrade)
    {
        $idGrade = filter_var($idGrade, FILTER_SANITIZE_NUMBER_INT);
        return $this->consumeEndpoint('GET', "/poker_grade/read/$idGrade");
    }

    public function create($data)
    {
        return $this->consumeEndpoint('POST', "/poker_grade/create", $data);
    }

    public function update($data)
    {
        return $this->consumeEndpoint('POST', "/poker_grade/update", $data);
    }

    public function delete($idGrade)
    {
        $data = ['grade_id' => $idGrade];

        return $this->consumeEndpoint('POST', "/poker_grade/delete", $data);
    }

    public function findByToken()
    {
        return $this->consumeEndpoint('GET', "/poker_grade/findByToken");
    }

    public function loadTournaments($idGrade)
    {
        return $this->consumeEndpoint('GET', "/poker_grade/jogos/$idGrade");
    }

    public function removeTournaments($data)
    {
        return $this->consumeEndpoint('POST', "/poker_grade/remove_from_grade", $data);
    }

    public function addTournaments(int $idGrade, array $jogos)
    {
        $data = ['grade_id' => $idGrade, 'jogos' => $jogos];
        return $this->consumeEndpoint('POST', "/poker_grade/add_to_grade", $data);
    }
}
