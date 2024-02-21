<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    public function index(): View
    {
      return view('layouts.pagamentos.listar', [
        'cobrancas' => Payment::select(
          'date', 
          'tipo', 
          'description', 
          'payment_id', 
          'transferencia_id', 
          'value'
        )
        ->orderBy('id', 'DESC')
        ->paginate(15)
      ]);
    }
}
