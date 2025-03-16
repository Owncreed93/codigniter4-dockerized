<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $scripts = [
                'assets/demo/chart-area-demo.js',
                'assets/demo/chart-bar-demo.js',
                'assets/js/datatables-simple-demo.js'
        ];
        $data = [
            'title' => 'Dashboard',
            'scripts' => $scripts,
        ];
        return view('dashboard', $data);
    }
}
