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
    $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
    return $fmt->formatCurrency($money, 'usd');
}
function percent($number = 0)
{
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

function getSiteSrcImage($siteName = '')
{
    $sitesLogo = session('sitesLogo');
    $siteName = strtolower($siteName);
    if (is_array($sitesLogo) && key_exists($siteName, $sitesLogo)) {
        return $sitesLogo[$siteName];
    } else {
        return '';
    }
}

if (!function_exists('boxBi')) {
    function boxBi($label, $value, $properties = '')
    {
        return "<div $properties>" . //
            "<div class='row bg-light  py-0'>$label</div>" . //
            "<div class='row py-0'>$value</div>" . //
            "</div>";
    }
}

if (!function_exists('arrayIncrement')) {
    function arrayIncrement($array, $chave, $valor = '')
    {
        $newArray = $array;
        if (key_exists($chave, $newArray)) {
            $newArray[$chave] += $valor;
        } else {
            $newArray[$chave] = $valor;
        }
         return $newArray;
    }
}
