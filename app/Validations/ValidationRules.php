<?php

namespace App\Validations;

use CodeIgniter\Validation\Rules;

class ValidationRules extends Rules
{
    public function valid_CPF(string $str = null): bool
    {
        return valid_cpf($str);
    }
}
