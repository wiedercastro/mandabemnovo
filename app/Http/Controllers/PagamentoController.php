<?php

namespace App\Http\Controllers;

use App\Models\Boleto;
use App\Models\Envio;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use stdClass;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    public function __construct(
        protected Envio $envio,
        protected Payment $payment,
        protected Boleto $boleto,
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

    public function get_transferencia(Request $request): JsonResponse
    {

        $usuarioMandaBem = auth()->user()->user_group_id;
        $params = ['type' => 'pendentes'];

        if ($usuarioMandaBem != 3) {
            $params['user_id'] = $usuarioMandaBem;
        } else {
            if ($request->filter_trans_cliente) {
                $params['user_id'] = $request->filter_trans_cliente;
            }
        }

        $list = $this->payment->getTransf($params);

        if (!count($list)) {
            if ($usuarioMandaBem == 1) {
                return response()->json(['msg' => 'Sem transferências pendentes']);
            }
        }

        return response()->json(['data' => $list]);
    }

    public function afiliados(): View
    {
      return view('layouts.pagamentos.admin.afiliados');
    }

    public function get_boletos(): JsonResponse
    {
        return response()->json(['boletos' => $this->boleto->getBoletoList()]);
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
