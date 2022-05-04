<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the frameworks
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter4.github.io/CodeIgniter4/
 */
function pre($var, $exit = false)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
    if ($exit) {
        exit;
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
    $fmt = new NumberFormatter('usd', NumberFormatter::CURRENCY);
    return $fmt->formatCurrency($money, 'usd');
}
