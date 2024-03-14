<?php

namespace App\Http\Controllers;

use App\Models\Declaracao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeclaracoesController extends Controller
{
  public function index($param = [])
  {

    $declaracoes = Declaracao::select('declaracao.id', 'declaracao.documento', 'declaracao.date_insert')
        ->join('declaracao_envio', 'declaracao.id', '=', 'declaracao_envio.declaracao_id')
        ->join('declaracao_envio_itens', 'declaracao_envio.id', '=', 'declaracao_envio_itens.declaracao_envio_id')
        ->select('declaracao.id', 'declaracao.documento', 'declaracao.date_insert', 'declaracao_envio_itens.descricao', 'declaracao_envio_itens.quantidade', 'declaracao_envio_itens.valor')
        ->paginate(10);

    return view('declaracoes.index', ['declaracoes' => $declaracoes]);
  }
}
 