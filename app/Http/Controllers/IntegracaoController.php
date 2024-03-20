<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IntegracaoController extends Controller
{
  public function index()
  {
    return view('integracoes.index');
  }
}
