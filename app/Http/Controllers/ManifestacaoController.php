<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class ManifestacaoController extends Controller
{
    public function index(): View
    {
        return view('layouts.manifestacoes.index');
    }
}

