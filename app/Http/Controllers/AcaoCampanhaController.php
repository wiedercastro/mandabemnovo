<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use stdClass;

class AcaoCampanhaController extends Controller
{
    public function index(Request $request): View
    {
        $data = new \stdClass();
        $data->users_campanha = [];
        $data->users_normal = [];
        $data->user_campanha_com_envio = 0;
    
        $filter_month = $request->filter_month;
    
        if ($filter_month) {
            // Extrai mês e ano do filtro
            list($mes, $ano) = explode('_', $filter_month);
            $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
    
            $start_date = Carbon::createFromDate("20$ano", $mes, 1)->startOfMonth();
            $end_date = Carbon::createFromDate("20$ano", $mes, 1)->endOfMonth();
        } else {
            $start_date = Carbon::createFromDate(2021, 8, 30);
            $end_date = Carbon::now();
        }
    
        // Consulta dos cadastros por mês
        $cadastros_envios_mes = User::join('envios', 'envios.user_id', '=', 'user.id')
            ->whereNotNull('user.url_referer')
            ->whereNotNull('envios.date_postagem')
            ->where('envios.valor_correios', '>', 0)
            ->whereBetween('user.date_insert', [$start_date, $end_date])
            ->groupBy('envios.user_id')
            ->get(['user.id']);
        
        // Total de envios
        $envios = Envio::join('user', 'user.id', '=', 'envios.user_id')
            ->whereNotNull('envios.date_postagem')
            ->where('envios.valor_correios', '>', 0)
            ->whereNotNull('user.url_referer')
            ->whereBetween('envios.date_insert', [$start_date, $end_date])
            ->count();
    
        // Consulta para contar usuários com url_referer não nula
        $cont_cadastros = User::whereNotNull('url_referer')
            ->whereBetween('date_insert', [$start_date, $end_date])
            ->count();
    
        // Consulta para os cadastros detalhados
        $cadastros = Envio::selectRaw('user.id, user.razao_social, user.name, user.date_insert, user.url_referer, COUNT(envios.user_id) as num_envios')
                    ->join('user', 'user.id', '=', 'envios.user_id')
                    ->whereNotNull('envios.date_postagem')
                    ->where('envios.valor_correios', '>', 0)
                    ->whereBetween('user.date_insert', [$start_date, $end_date])
                    ->groupBy('user.id')
                    ->orderByRaw('COUNT(envios.user_id) DESC')
                    ->get();
    
        // Atualiza as informações no objeto de dados
        $data->cadastros_envios_mes = count($cadastros_envios_mes);
        $data->envios_user = $envios;
        $data->cont_cadastros = $cont_cadastros;
    
        $array_user = $cadastros_envios_mes->pluck('id')->toArray();
    
        $quant_envios = Envio::whereIn('user_id', $array_user)
            ->whereNotNull('date_postagem')
            ->where('valor_correios', '>', 0)
            ->count();
    
        $data->quant_envios_mes = $quant_envios;
    
        foreach ($cadastros as $i) {
            if ($i->num_envios) {
                $i->primeiro_envio = Envio::where('user_id', $i->id)
                    ->whereNotNull('date_postagem')
                    ->where('valor_correios', '>', 0)
                    ->orderBy('id')
                    ->first()->date_postagem ?? '--';
            } else {
                $i->primeiro_envio = '--';
            }
    
            if (strlen($i->url_referer)) {
                $data->users_campanha[] = $i;
                if ($i->num_envios >= 1) {
                    $data->user_campanha_com_envio++;
                }
            } else {
                if ($i->num_envios <= 0) {
                    continue;
                }
                $data->users_normal[] = $i;
            }
        }
    
        // Adiciona apenas os primeiros 20 registros para cada lista
        $data->users_normal = array_slice($data->users_normal, 0, 20);
        $data->users_campanha = array_slice($data->users_campanha, 0, 20);
    
        // Atualiza as estatísticas para o gráfico
        $camp_env = [];
    
        foreach ($data->users_campanha as $u) {
            if (!isset($camp_env[$u->url_referer])) {
                $camp_env[$u->url_referer] = 0;
            }
            $camp_env[$u->url_referer] += $u->num_envios;
        }
    
        $data->envios_camp = $camp_env;
    
        $tmp = [];
        $tmp_b = [];
        $tmp_c = [];
        
        foreach ($cadastros_envios_mes as $c) {
            $date = substr($c->data_cadastro, 0, 10);
    
            if (!isset($tmp[$date])) {
                $tmp[$date] = 0;
            }
    
            if (!isset($tmp_b[$c->url_referer])) {
                $tmp_b[$c->url_referer] = 0;
            }
    
            $tmp[$date]++;
            $tmp_b[$c->url_referer]++;
    
            if (!isset($tmp_c[$date][$c->url_referer])) {
                $tmp_c[$date][$c->url_referer] = 0;
            }
    
            $tmp_c[$date][$c->url_referer]++;
        }
    
        $grafico_stats = [];
    
        $x = 0;
    
        foreach ($tmp as $date => $total) {
            $grafico_stats['stats'][$x] = [
                'data_cadastro' => $date,
                'total' => $total,
            ];
            $x++;
        }

        $list_month = [
            '8_21' => 'Agosto/21',
            '9_21' => 'Setembro/21',
            '10_21' => 'Outubro/21',
            '11_21' => 'Novembro/21',
            '12_21' => 'Dezembro/21',
            '1_22' => 'Janeiro/22',
            '2_22' => 'Fevereiro/22',
            '3_22' => 'Março/22',
            '4_22' => 'Abril/22',
            '5_22' => 'Maio/22',
            '6_22' => 'Junho/22',
            '7_22' => 'Julho/22',
            '8_22' => 'Agosto/22',
            '9_22' => 'Setembro/22',
            '10_22' => 'Outubro/22',
            '11_22' => 'Novembro/22',
            '12_22' => 'Dezembro/22',
        ];
    
      /*   dd($data);

        $data->grafico_stats = [
            'stats' => $grafico_stats['stats'],
            'resumo' => $tmp_b,
        ]; */

        return view('layouts.acao-campanhas.index', [
            'list_month' => $list_month
        ]);
    }
}
