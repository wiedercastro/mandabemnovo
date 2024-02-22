<?php

namespace App\Http\Controllers;

use App\Http\Requests\GerarEnvioControllerRequest;
use App\Models\Envio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class GerarEnvioController extends Controller
{
  public function index()
  {
    $id = session('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');

    $envios = DB::table('envios')
      ->select(
        'id',
        'valor_total as total',
        'valor_desconto as desconto',
        'valor_balcao as balcao',
        'forma_envio as envio',
        'destinatario',
        'date_insert'
      )
      ->where("user_id", "=", $id)
      ->whereNull("coleta_id")
      ->paginate();

    // $envios = DB::table('envios')->where("user_id", "=", 35699)->groupBy("id")->paginate();

    // dd($envios);
    return view('layouts.gerar.gerar', compact("envios"));
  }
  public function saveEnvio(GerarEnvioControllerRequest $request)
  {

    try {

      $data = $request->all();

      $data['user_id'] = session('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
      $data['type'] = 'NORMAL'; //fazer verificação se é reversa
      //$data['nota_fiscal'] = '';
      // dd($data);

      return $envio = Envio::saveEnvio($data);
    } catch (\Exception $e) {
      // Aqui você lida com a exceção e pode retornar uma resposta de erro, redirecionar, etc.
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  public function buscarPeso()
  {
    try {

      return Envio::getPesos();
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  public function buscarEstado()
  {
    try {

      return Envio::get_lista_estados();
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  public function buscarDestinatiro(Request $request)
  {

    // dd($request->searchTerm);

    return "Wieder teste";
  }

  public function excluirEnvio($id)
  { 
    
    // Encontrar o modelo pelo ID
    $envio = Envio::where('id','=',$id)->delete();
    
    // Verificar se o modelo foi encontrado
    if ($envio) {
      // Excluir o modelo
      return $envio;

    } else {
      // O modelo não foi encontrado, você pode querer lidar com isso de alguma forma
      return "Envio não encontrado para o ID: $id";
    }
  } 

  public function excluirEnviosSelecionados(Request $request)
  { 
    // dd($request);
    // Encontrar o modelo pelo ID
    $envio = Envio::whereIn('id',$request->ids)->delete();
    
    // Verificar se o modelo foi encontrado
    if ($envio) {
      // Excluir o modelo
      return $envio;

    } else {
      // O modelo não foi encontrado, você pode querer lidar com isso de alguma forma
      return "Envio não encontrado.";
    }
  }
}
