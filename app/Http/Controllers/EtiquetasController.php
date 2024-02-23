<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Envio;
use App\Models\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class EtiquetasController extends Controller
{
  public function index()
  {
    $envios = DB::table('coletas')
      ->join('envios','coletas.id','=','envios.coleta_id')
      ->select('coletas.id', DB::raw('Count(envios.id) as qte'), 
                             DB::raw('sum(envios.valor_total) as total'), 
                             DB::raw('sum(envios.valor_desconto) as desconto'),
                              'coletas.type'
                            )
      ->where("coletas.user_id","=",5)
      ->groupBy("coletas.id")
      ->paginate();

     /*  dd($envios); */
      
    return view('layouts.etiquetas',compact("envios"));
  }

  public function buscaDetalhesDasEtiquetas(int $idEtiqueta)
  {
    $etiquetas = Envio::select(
      'coleta_id',
      'AR',
      'CEP',
      'CEP_origem',
      'cpf_destinatario',
      'type',
      'logradouro',
      'numero',
      'complemento',
      'bairro',
      'cidade',
      'estado',
      'email',
      'nota_fiscal',
      'peso',
      'email',
      'date_postagem',
      'forma_envio',
      'destinatario',
      'seguro',
      'prazo',
      'nota_fiscal',
      'etiqueta_correios'
    )
    ->where('coleta_id', $idEtiqueta)
    ->get();

    if (! $etiquetas) {
      abort(204);
    }

    return response()->json(['data' => $etiquetas]);
  }


  public function edit(Request $request): View
  {
    return view('profile.edit', [
      'user' => $request->user(),
    ]);
  }

  public function show($id)
  {
    $envios = Envio::where("id",$id)->paginate();
    //return view('layouts.dashboard',compact('envios'));
    return response()->json(['html' =>view('layouts.coleta.detalhesColeta',compact('envios'))->render()]);
  }

  public function teste()
  {
    return true;
  }

  public function update(ProfileUpdateRequest $request): RedirectResponse
  {
    $request->user()->fill($request->validated());

    if ($request->user()->isDirty('email')) {
      $request->user()->email_verified_at = null;
    }

    $request->user()->save();
    return Redirect::route('profile.edit')->with('status', 'profile-updated');
  }

  public function destroy(Request $request): RedirectResponse
  {
    $request->validateWithBag('userDeletion', [
        'password' => ['required', 'current_password'],
    ]);

    $user = $request->user();

    Auth::logout();

    $user->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return Redirect::to('/');
  }
}
