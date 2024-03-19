<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RelatorioEtiquetas extends Controller
{
  public function index()
  {
    return view('relatorio-etiquetas.index');
  }
}
