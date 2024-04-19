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
use Illuminate\Support\Facades\Gate;

class EtiquetasController extends Controller
{
    public function __construct(
        protected Envio $envio,
        protected Payment $payment
    ){ }

  public function index()
  {
    $view = "";

    // verifica se o usuário tem permissão de admin
    if (Gate::allows('user_admin_mandabem')) {
        $view = 'layouts.etiquetas.index_admin';
    } else {
        $view = 'layouts.etiquetas.index_cliente';
    }

    $mesAtual = now()->format('m');

    $envios = DB::table('coletas')
        ->join('envios', 'coletas.id', '=', 'envios.coleta_id')
        ->join('user', 'coletas.user_id', '=', 'user.id') 
        ->leftJoin('payment', 'payment.id', '=', 'coletas.id_payment')
        ->select(
            'coletas.id',
            'coletas.payment_id',
            'payment.fee as total_paypal',
            'user.razao_social', 
            'user.name as user_name',
            'user.date_insert as data_cliente_cadastro', 
            'user.plataform_integration', 
            DB::raw('COUNT(envios.id) as qte'),
            DB::raw('SUM(envios.valor_total) as total'),
            DB::raw('SUM(envios.valor_desconto) as desconto'),
            DB::raw('SUM(envios.valor_correios) as total_correios'),
            'coletas.type',
            DB::raw('(SELECT COUNT(id) FROM api_nuvem_shop WHERE api_nuvem_shop.user_id = coletas.user_id) as is_nuvem_shop')
        )
    ->where("coletas.user_id", "=", auth()->user()->id)
    ->groupBy("coletas.id")
    ->paginate();


    return view($view, [
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
