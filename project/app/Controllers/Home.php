<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = ['title' => 'Dashboard'];
        return view('layout/main', $data);
    }
}
