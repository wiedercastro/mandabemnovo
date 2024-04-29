<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

class PagamentoController extends Controller
{
    public function __construct(
        protected Envio $envio,
        protected Payment $payment
    ){ }
    
    public function index(): View
    {
        $view = "";

        // verifica se o usuário tem permissão de admin
        if (Gate::allows('user_admin_mandabem')) {
            $view = 'layouts.pagamentos.admin.listar';
        } else {
            $view = 'layouts.pagamentos.cliente.listar';
        }
        
        $mesAtual = now()->format('m');

        $cobrancas = Payment::select(
            'date', 
            'tipo', 
            'description', 
            'payment_id', 
            'transferencia_id', 
            'value'
          )
        ->orderBy('id', 'DESC')
        ->paginate(15);

      return view($view, [
        'cobrancas'          => $cobrancas,
        'mesAtual'           => getMeses($mesAtual),
        'anoAtual'           => now()->format('Y'),
        'totalEconomia'      => $this->envio->getTotalEconomia(),
        'totalEconomiaDoMes' => $this->envio->getTotalEconomiaDoMes(),
        'totalDivergencia'   => $this->envio->getTotalDivergencia(),
        'valorTotal'         => $this->envio->getTotal(),
        'totalSaldo'         => $this->payment->getCreditoSaldo($this->envio->getTotal())
      ]);
    }

    public function transferencia(): View
    {
      return view('layouts.pagamentos.admin.transferencias');
    }

    public function afiliados(): View
    {
      return view('layouts.pagamentos.admin.afiliados');
    }

    public function boleto(): View
    {
      return view('layouts.pagamentos.admin.boleto');
    }

    public function creditos(): View
    {
      return view('layouts.pagamentos.admin.creditos');
    }

    public function cobranca(): View
    {
      return view('layouts.pagamentos.admin.cobranca');
    }

}
