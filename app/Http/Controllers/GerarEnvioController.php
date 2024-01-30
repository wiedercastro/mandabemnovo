<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GerarEnvioController extends Controller
{
  public function index()
  {
    $envios = DB::table('coletas')
      ->join('envios','coletas.id','=','envios.coleta_id')
      ->select('coletas.id',DB::raw('Count(envios.id) as qte'),
                            DB::raw('sum(envios.valor_total) as total'),
                            DB::raw('sum(envios.valor_desconto) as desconto'),'coletas.type')
      ->where("coletas.user_id","=",5)
      ->groupBy("coletas.id")
      ->paginate();
    return view('layouts.gerar.gerar',compact("envios"));
  }
}
