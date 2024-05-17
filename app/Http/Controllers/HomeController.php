<?php

namespace App\Http\Controllers;

use App\Models\Transferencia;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use stdClass;

class HomeController extends Controller
{
    public function __construct(protected Transferencia $transferencia_model)
    { }

    public function apuracao_pix(): View|JsonResponse
    {
        $dataRequisicao = request()->date;
    
        $dataFormatada = now()->format('Y-m-d');
        $dataInicio = $dataFormatada . ' 00:00:00';
        $dataFim = $dataFormatada . ' 23:59:59';
        $banco = 'pixiugu';
    
        if($dataRequisicao){
        
            $dataParametro = Carbon::createFromFormat('d/m/Y', $dataRequisicao);
            $dataFormatada = $dataParametro->format('Y-m-d');
            
            $dataInicio = $dataFormatada . ' 00:00:00';
            $dataFim = $dataFormatada . ' 23:59:59';
        
            return response()->json([
                'data' => [
                    'total'      => $this->transferencia_model->getTotal($banco, $dataInicio, $dataFim),
                    'deletados'  => $this->transferencia_model->getDeletados($banco, $dataInicio, $dataFim),
                    'pagos'      => $this->transferencia_model->getPagos($banco, $dataInicio, $dataFim),
                    'aguardando' => $this->transferencia_model->getAguardando($banco, $dataInicio, $dataFim)
                ]
            ]);
        }
    
        return view('layouts.home.apuracao_pix', [
            'total'      => $this->transferencia_model->getTotal($banco, $dataInicio, $dataFim),
            'deletados'  => $this->transferencia_model->getDeletados($banco, $dataInicio, $dataFim),
            'pagos'      => $this->transferencia_model->getPagos($banco, $dataInicio, $dataFim),
            'aguardando' => $this->transferencia_model->getAguardando($banco, $dataInicio, $dataFim)
        ]);
    }
    
}
