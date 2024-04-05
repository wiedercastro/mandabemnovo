<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Payment;
use App\Models\Report;
use Illuminate\Contracts\View\View;

class PagamentoController extends Controller
{
    public function __construct(
        protected Envio $envio,
        protected Report $report,
        protected Payment $payment
    ){ }
    
    public function index(): View
    {
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

      return view('layouts.pagamentos.listar', [
        'cobrancas'          => $cobrancas,
        'mesAtual'           => getMeses($mesAtual),
        'anoAtual'           => now()->format('Y'),
        'totalEconomia'      => $this->report->getTotalEconomia(),
        'totalEconomiaDoMes' => $this->report->getTotalEconomiaDoMes(),
        'totalDivergencia'   => $this->report->getTotalDivergencia(),
        'valorTotal'         => $this->envio->getTotal(),
        'totalSaldo'         => $this->payment->getCreditoSaldo($this->envio->getTotal())
      ]);
    }
}
