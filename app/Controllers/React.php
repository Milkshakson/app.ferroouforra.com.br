<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class React extends Controller
{
    public function __construct()
    {
    }
    public function index(){
        return view('react');
    }
}
