<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Envio;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class EtiquetasController extends Controller
{
    public function __construct(
        protected Envio $envio,
        protected Payment $payment
    ){ }

  public function index()
  {
    $mesAtual = now()->format('m');

    $envios = DB::table('coletas')
      ->join('envios','coletas.id','=','envios.coleta_id')
      ->select('coletas.id', DB::raw('Count(envios.id) as qte'), 
                             DB::raw('sum(envios.valor_total) as total'), 
                             DB::raw('sum(envios.valor_desconto) as desconto'),
                              'coletas.type'
                            )
      ->where("coletas.user_id", "=", auth()->user()->id)
      ->groupBy("coletas.id")
      ->paginate();

    return view('layouts.etiquetas', [
        'envios'             => $envios,
        'mesAtual'           => getMeses($mesAtual),
        'anoAtual'           => now()->format('Y'),
        'totalEconomia'      => $this->envio->getTotalEconomia(),
        'totalEconomiaDoMes' => $this->envio->getTotalEconomiaDoMes(),
        'totalDivergencia'   => $this->envio->getTotalDivergencia(),
        'valorTotal'         => $this->envio->getTotal(),
        'totalSaldo'         => $this->payment->getCreditoSaldo($this->envio->getTotal())
    ]);
  }

  public function buscaDetalhesDasEtiquetas(int $idEtiqueta)
  {
    $etiquetas = $this->envio->select(
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
    $envios = $this->envio->where("id", $id)->paginate();
    return view('layouts.coleta.detalhesColeta', [
        'envios' => $envios
    ]);
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

  public function gerarEtiquetas(Request $request)
  {
    dd($request->all());
  }
}
