<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AfiliadosController extends Controller
{
    public function index()
    {
        return view('layouts.afiliados.index');
    }
}
