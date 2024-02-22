<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Envio;
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
                              'coletas.type',
                              'envios.email',
                              'envios.forma_envio',
                              'envios.logradouro',
                              'envios.cep',
                              'envios.numero',
                              'envios.complemento',
                              'envios.destinatario',
                              'envios.cidade',
                              'envios.estado',
                              'envios.peso',
                              'envios.seguro',
                              'envios.ar',
                              'envios.prazo',
                              'envios.etiqueta_correios',
                              'envios.date_postagem'
                            )
      ->where("coletas.user_id","=",5)
      ->groupBy("coletas.id")
      ->paginate();

     /*  dd($envios); */
      
    return view('layouts.etiquetas',compact("envios"));
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
