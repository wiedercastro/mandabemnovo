<?php

namespace App\Http\Controllers;

use App\Models\Coleta;
use App\Models\Envio;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EstatisticasAdminController extends Controller
{
    public function __construct(protected Report $report)
    {
        
    }

    public function index(Request $request) : View 
    {
        $now = Carbon::now();
        $users = 0;
        $coletas = 0;
        $envios = 0;

        $anoAtual = now()->format('Y');
        $mesAtual = now()->format('m');
        $primeiroDiaMesAtual = now()->startOfMonth();
        $finalDiaMesAtual = now()->endOfWeek();
        $anoAnterior = now()->subYear();

        if (! empty($request->periodo)) {
            switch ($request->periodo) {
                case 'semana_atual':
                    $startOfWeek = $now->startOfWeek();
                    $endOfWeek = $now->endOfWeek();
    
                    $users = User::whereBetween('date_insert', [$startOfWeek, $endOfWeek])->count();
                    $coletas = Coleta::whereBetween('date_insert', [$startOfWeek, $endOfWeek])->count();
                    $envios = Envio::whereBetween('date_insert', [$startOfWeek, $endOfWeek])->count();
                    break;
    
                    case 'mes_atual':
                    $dataInicioFormatada = "{$anoAtual}-{$mesAtual}-{$primeiroDiaMesAtual->format('d')}";
                    $dataFinalFormatada = "{$anoAtual}-{$mesAtual}-{$finalDiaMesAtual->format('d')}";
    
                    $users = User::whereBetween('date_insert', [$dataInicioFormatada, $dataFinalFormatada])->count();
                    $coletas = Coleta::whereBetween('date_insert', [$dataInicioFormatada, $dataFinalFormatada])->count();
                    $envios = Envio::whereBetween('date_insert', [$dataInicioFormatada, $dataFinalFormatada])->count();
                    break;
    
                case 'ano_atual':
                    $dataInicioFormatada = "{$anoAtual}-01-01";
                    $dataFinalFormatada = "{$anoAtual}-12-31";
    
                    $users = User::whereBetween('date_insert', [$dataInicioFormatada, $dataFinalFormatada])->count();
                    $coletas = Coleta::whereBetween('date_insert', [$dataInicioFormatada, $dataFinalFormatada])->count();
                    $envios = Envio::whereBetween('date_insert', [$dataInicioFormatada, $dataFinalFormatada])->count();
                    break;
    
                case 'ano_anterior':
                    $dataInicioFormatada = "{$anoAnterior->format('Y')}-01-01";
                    $dataFinalFormatada = "{$anoAnterior->format('Y')}-12-31";
                    
                    $users = User::whereBetween('date_insert', [$dataInicioFormatada, $dataFinalFormatada])->count();
                    $coletas = Coleta::whereBetween('date_insert', [$dataInicioFormatada, $dataFinalFormatada])->count();
                    $envios = Envio::whereBetween('date_insert', [$dataInicioFormatada, $dataFinalFormatada])->count();
                    break;

                case 'customizado':
                    $dataInicial = Carbon::createFromFormat('Y-m-d', $request->data_inicial);
                    $dataFinal = Carbon::createFromFormat('Y-m-d', $request->data_final);

                    $users = User::whereBetween('date_insert', [$dataInicial->format('Y-m-d'), $dataFinal->format('Y-m-d')])->count();
                    $coletas = Coleta::whereBetween('date_insert', [$dataInicial->format('Y-m-d'), $dataFinal->format('Y-m-d')])->count();
                    $envios = Envio::whereBetween('date_insert', [$dataInicial->format('Y-m-d'), $dataFinal->format('Y-m-d')])->count();
                    break;
    
                default:
                    throw new \InvalidArgumentException("Filtro inválido: $request->periodo");
            }
        }

     /*    $teste = $this->report->getTotalV3();
        dd($teste); */

        return view('layouts.estatisticas.admin_index', [
            'users'   => $users,
            'coletas' => $coletas,
            'envios'  => $envios
        ]);
    }


    public function getDadosEstatisticas(Request $request) : JsonResponse 
    {
        $anoAtual = now()->format('Y');
        $mesAtual = now()->format('m');
        
        $periodo = $request->periodo ?? 'default';
        $cacheKey = "estatisticas_{$periodo}_{$anoAtual}_{$mesAtual}";
    
        // Verifica se os dados já estão em cache
        $cachedData = Cache::get($cacheKey);
    
        if ($cachedData) {
            return response()->json($cachedData);
        }
    
        // Caso contrário, recupera os dados do banco de dados
        $users = [];
        $coletas = [];
        $envios = [];
        
        switch ($periodo) {
            case 'semana_atual':
                $dataInicio = Carbon::now()->startOfWeek(); // Primeiro dia da semana
                $dataFinal = Carbon::now()->endOfWeek();   // Último dia da semana
            

                $users   = $this->countPerDay(User::class, $dataInicio, $dataFinal);
                $coletas = $this->countPerDay(Coleta::class, $dataInicio, $dataFinal);
                $envios  = $this->countPerDay(Envio::class, $dataInicio, $dataFinal);
                break;
    
            case 'mes_atual':
                $dataInicio = Carbon::create($anoAtual, $mesAtual, 1); // Primeiro dia do mês
                $dataFinal = Carbon::create($anoAtual, $mesAtual, $dataInicio->daysInMonth);
    
                $users   = $this->countPerDay(User::class, $dataInicio, $dataFinal);
                $coletas = $this->countPerDay(Coleta::class, $dataInicio, $dataFinal);
                $envios  = $this->countPerDay(Envio::class, $dataInicio, $dataFinal);
                break;
    
            case 'ano_atual':
                $dataInicio = Carbon::create($anoAtual, 1, 1); 
                $dataFinal = Carbon::create($anoAtual, 12, 31);
    
                $users   = $this->countPerDay(User::class, $dataInicio, $dataFinal);
                $coletas = $this->countPerDay(Coleta::class, $dataInicio, $dataFinal);
                $envios  = $this->countPerDay(Envio::class, $dataInicio, $dataFinal);
                break;
    
            case 'ano_anterior':
                $anoAnterior = now()->subYear();
                $dataInicio = Carbon::create($anoAnterior->format('Y'), 1, 1);
                $dataFinal = Carbon::create($anoAnterior->format('Y'), 12, 31);
    
                $users   = $this->countPerDay(User::class, $dataInicio, $dataFinal);
                $coletas = $this->countPerDay(Coleta::class, $dataInicio, $dataFinal);
                $envios  = $this->countPerDay(Envio::class, $dataInicio, $dataFinal);
                break;

            case 'customizado':
                $dataInicial = Carbon::createFromFormat('Y-m-d', $request->data_inicial);
                $dataFinal = Carbon::createFromFormat('Y-m-d', $request->data_final);

                $users   = $this->countPerDay(User::class, $dataInicial, $dataFinal);
                $coletas = $this->countPerDay(Coleta::class, $dataInicial, $dataFinal);
                $envios  = $this->countPerDay(Envio::class, $dataInicial, $dataFinal);
                break;
        }
        
        $data = [
            'users' => $users,
            'coletas' => $coletas,
            'envios' => $envios,
        ];
    
        // Armazenar os dados no cache com tempo indefinido
        Cache::forever($cacheKey, $data);
    
        return response()->json($data);
    }

    public function countPerDay($model, $startDate, $endDate) {
        $counts = [];
        
        // Percorrer cada dia do período
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $count = $model::whereDate('date_insert', $date->toDateString())->count();
            $counts[] = [
                'dia' => $date->format('d/m/Y'),
                'count' => $count,
            ];
        }
        
        return $counts;
    }
}

