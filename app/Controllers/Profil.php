<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Profil extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Profil Saya'
        ];

        return view('profil', $data);
    }
}