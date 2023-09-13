<?php
namespace App\Controllers;

class Profile extends BaseController
{
    public function index()
    {
        $this->view->display('Profile/index.twig');
    }
}
