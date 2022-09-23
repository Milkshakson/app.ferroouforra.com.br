<?php

namespace App\Libraries;

use ReflectionFunction;
use Twig\Loader\FilesystemLoader;

class View
{
    private $template = 'twig';
    public function __construct()
    {
    }
    public function display($view, $dados = [])
    {
        switch ($this->template) {
            case 'twig':
                echo $this->render($view, $dados);
                break;
        }
    }
    public function render($view, $dados = [])
    {
        switch ($this->template) {
            case 'twig':
                $loader = new FilesystemLoader(APPPATH . 'Twig');
                $twig = new \Twig\Environment($loader, [
                    'cache' => false,
                    'autoescape' => false
                ]);

                // enable all php function on twig
                foreach (get_defined_functions() as $functions) {
                    foreach ($functions as $functionName) {
                        $details = new ReflectionFunction($functionName);
                        $function = new \Twig\TwigFunction($details->name, $details->name);
                        $twig->addFunction($function);
                    }
                }
                if (!str_ends_with($view, '.twig')) {
                    $view .= '.twig';
                }
                return $twig->render($view, $dados);
                break;
        }
    }
}