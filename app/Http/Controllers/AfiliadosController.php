<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class AfiliadosController extends Controller
{
    public function index(): View
    {
        return view('layouts.afiliados.index');
    }
}
