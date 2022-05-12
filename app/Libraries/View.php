<?php

namespace App\Libraries;

use Twig\Loader\FilesystemLoader;

class View
{
    private $template = 'twig';
    public function __construct()
    {
    }
    public function display($view, $dados)
    {
        switch ($this->template) {
            case 'twig':
                echo $this->render($view, $dados);
                break;
        }
    }
    public function render($view, $dados)
    {
        switch ($this->template) {
            case 'twig':
                $loader = new FilesystemLoader(APPPATH . 'Twig');
                $twig = new \Twig\Environment($loader, [
                    'cache' => false,
                    'autoescape'=>false
                ]);
                /*
                helper('array');
                pre(get_defined_functions(),1);
                foreach (element('user', get_defined_functions()) as $func_name) {
                $function = new \Twig\TwigFunction($func_name, function () {
                    // ...
                });
                $twig->addFunction($function);
            }
            */
                $twig->registerUndefinedFunctionCallback(function ($name) {
                    if (function_exists($name)) {
                        return new \Twig\TwigFunction($name, $name);
                    }

                    return false;
                });
                $twig->registerUndefinedFilterCallback(function ($name) {
                    if (function_exists($name)) {
                        return new \Twig\TwigFunction($name, $name);
                    }

                    return false;
                });
                if (!str_ends_with($view, '.twig')) {
                    $view .= '.twig';
                }
                return $twig->render($view, $dados);
                break;
        }
    }
}
