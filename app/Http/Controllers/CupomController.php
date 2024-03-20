<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CupomController extends Controller
{
  public function index()
  {
    return view('cupom.index');
  }
}
