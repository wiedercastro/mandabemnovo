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

        //dd($request->cliente);

        if ($usuarioMandaBem != 1) {
            $params['user_id'] = $usuarioMandaBem;
        } else {

            if ($request->cliente) {
                $params['user_id'] = $request->cliente;
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

    public function afiliados(Request $request)
    {
        dd($request->all());
    }

    public function get_boletos(): JsonResponse|View
    {
        $caminhoUrl = request()->path();
        $limit = true;

        if ($caminhoUrl === "todos-boletos") {
            $limit = false;
            return view('layouts.boletos.index', [
                'boletos'         => $this->boleto->getBoletoList($limit),
                'boletosPago'     => $this->boleto->getInfoTotal(),
                'boletosPendente' => $this->boleto->getInfoTotal('PENDING'),
                
            ]);
        }
        return response()->json(['boletos' => $this->boleto->getBoletoList($limit)]);
    }

    public function creditos(Request $request)
    {
        dd($request->all());
    }

    public function cobranca(Request $request)
    {
        dd($request->all());
    }

}
