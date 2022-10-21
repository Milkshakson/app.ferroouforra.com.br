<?php
if (!function_exists('breadcrumb')) {
    function breadcrumb($items = [], $header = null)
    {
        $html = '';
        if (!is_null($header)) {
            $html .= "<h1>$header</h1>";
        }
        $html .= "<nav>
			<ol class='breadcrumb'>";
        foreach ($items as $item) {
            $html .= "<li class='breadcrumb-item'>$item</li>";
        }
        $html .= "</ol>
		</nav>";
        return $html;
    }
}


function coalesce($var, $replace = '')
{
    if (is_null($var)) {
        return $replace;
    } else {
        return $var;
    }
}

function dolarFormat($money = 0)
{
    defined('MIL') or define('MIL', 1000);
    defined('MILHAO') or define('MILHAO', MIL * MIL);
    if (is_null($money))
        $money = 0;

    $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
    if ($money >= MILHAO) {
        return $fmt->formatCurrency($money / MILHAO, 'usd') . 'M';
    } else if ($money >= MIL) {
        return $fmt->formatCurrency($money / MIL, 'usd') . 'K';
    } else {
        return $fmt->formatCurrency($money, 'usd');
    }
}
function percentFormat($number = 0)
{
    return percent($number);
}
function percent($number = 0)
{
    $number = is_null($number) ? 0 : $number;
    return number_format($number, 2) . '%';
}
if (!function_exists('array_array')) {

    function array_array($array = [])
    {
        $retorno = [];
        foreach ($array as $value) {
            $retorno[$value] = $value;
        }
        return $retorno;
    }
}
if (!function_exists('box_summary_session')) {
    function box_summary_session($header, $valor)
    {
        $html = "
        <div class='d-flex flex-column'>
					<span class='text-center'>$header</span>
					<span class='text-center'>$valor</span>
				</div>        
        ";

        return $html;
    }
}


if (!function_exists('profitFormat')) {
    function profitFormat($valor = 0)
    {
        $class = '';
        if ($valor > 0) {
            $class = 'text-success';
        } elseif ($valor < 0) {
            $class = 'text-danger';
        } else {
            $class = 'text-primary';
        }
        $valor = dolarFormat($valor);
        return "<strong class='$class'>$valor</strong>";
    }
}