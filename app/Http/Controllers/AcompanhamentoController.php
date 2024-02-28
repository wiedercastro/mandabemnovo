<?php

namespace App\Http\Controllers;

use App\Models\Acompanhamento;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AcompanhamentoController extends Controller
{
  public function index()
  {
    return view('acompanhamentos.index' , [
      'acompanhamento_email' => Acompanhamento::select('id', 'name')->get()
    ]);
  }

  public function busca_acomp_email(int $id)
  {
    $acompanhamentos = Acompanhamento::select('id', 'body', 'subject')->where('id', '=', $id)->first();
    if (! $acompanhamentos) {
      abort(404);
    }

    return response()->json(['data' => $acompanhamentos]);
  }

  public function atualiza_acomp_email(Request $req)
  {
    $id = (int) $req->id;

    if (! Acompanhamento::find($id) ) {
      abort(404);
    } 

    Acompanhamento::query()->where('id', '=', $id)->update([
      'subject'     => $req->assunto,
      'body'        => $req->corpo_email,
      'date_update' => Carbon::now()->format('Y-m-d H:i:s')
    ]);
    
    return response()->json('ok');
  }

  

  
}
