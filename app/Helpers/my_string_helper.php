<?php
if (!function_exists('containsAll')) {
    function containsAll($needle, $haystack)
    {
        $count = 0;
        $array = explode(' ', $needle);
        foreach ($array as $value) {
            if (false !== stripos($haystack, $value)) {
                ++$count;
            }
            ;
        }
        return $count == count($array);
    }
}

function upper($string)
{
    return mb_strtoupper($string);
}

function formatarValorMonetario($valor)
{
    // Remove qualquer vírgula do valor
    $valor = str_replace(',', '', $valor);

    // Formata o número com 2 casas decimais e usa o ponto como separador decimal
    return number_format($valor, 2, '.', '.');
}
