<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EstatisticaController extends Controller
{
  public function index()
  {
    return view('estatisticas.index');
  }
}
