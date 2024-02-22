<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use App\Libraries\DateUtils;


class Report extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
  
    public function getTotal($param = array())
    {
        $paymentModel = new Payment();
        $coletaModel = new Coleta();
        $diff = 0;

        $start = now()->subMonths($diff)->firstOfMonth();

        $last_day = $start->copy()->lastOfMonth()->day;
        $end = $start->copy()->lastOfMonth();

        $num_days = $last_day;

        $date_start = $start->startOfDay();
        $date_end = $end->endOfDay();

        $envios = DB::table('envios')
            ->selectRaw('envios.*, coletas.payment_id, coletas.date_insert as data_geracao, payment.value as total_paypal')
            ->leftJoin('coletas', 'coletas.id', '=', 'envios.coleta_id')
            ->leftJoin('payment', 'payment.id', '=', 'coletas.id_payment')
            ->where('envios.date_postagem', '!=', null)
            ->where('envios.valor_correios', '>', 0);

        if (isset($param['user_id'])) {
            $envios->where('envios.user_id', $param['user_id']);
        }

        if (auth()->user()->id != 5) {
            $envios->where('coletas.environment', 'production');
        }

        if (isset($param['filter_cliente']) && $param['filter_cliente']) {
            $envios->where('envios.user_id', $param['filter_cliente']);
        }

        if (isset($param['type']) && $param['type'] == 'parcial') {
            $date_start = now()->startOfDay();
            $date_end = now()->endOfDay();
        } else {
            if (isset($param['filter_periodo'])) {
                switch ($param['filter_periodo']) {
                    case 'current_month':
                        $date_start = now()->startOfMonth();
                        $date_end = now();
                        break;
                    case 'current_week':
                        $week_start = now()->startOfWeek();
                        $week_end = now()->endOfWeek();
                        $date_start = $week_start->startOfDay();
                        $date_end = $week_end->endOfDay();
                        break;
                    case 'current_year':
                        $date_start = now()->startOfYear();
                        $date_end = now()->endOfYear();
                        break;
                    case 'last_year':
                        $date_start = now()->subYear()->startOfYear();
                        $date_end = now()->subYear()->endOfYear();
                        break;
                    case 'last_month':
                        $date_start = now()->subMonth()->firstOfMonth();
                        $date_end = now()->subMonth()->lastOfMonth();
                        break;
                    case 'custom':
                        $date_start = Carbon::createFromFormat('Y-m-d', $param['data_inicial'])->startOfDay();
                        $date_end = Carbon::createFromFormat('Y-m-d', $param['data_final'])->endOfDay();
                        break;
                    default:
                        $date_start = now()->startOfMonth();
                        $date_end = now();
                        break;
                }
            } else {
                $date_start = now()->startOfMonth();
                $date_end = now();
            }
        }

        $envios->where('envios.date_postagem', '>=', $date_start);
        $envios->where('envios.date_postagem', '<=', $date_end);
        $envios->orderBy('coletas.id')->orderBy('envios.id');
        $envios = $envios->get();

        $coletas = [];
        foreach ($envios as $i) {
            $coletas[$i->coleta_id]['total_paypal'] = $i->total_paypal;
            $coletas[$i->coleta_id]['data_geracao'] = $i->data_geracao;
            $coletas[$i->coleta_id]['envios'][$i->id] = $i;
        }

        $resumo = [];
        $resumo['xenvios'] = [];
        $resumo['envios'] = [];
        $resumo['valor_envios'] = [];
        $report['num_coletas'] = 0;
        $report['num_envios'] = 0;
        $report['tmp_envios'] = [];
        $report['valor_total'] = 0;
        $report['valor_correios'] = 0;
        $report['total_economia'] = 0;
        $report['valor_taxa_paypal'] = 0;
        $report['valor_mandabem'] = 0;
        $report['real_valor_divergente'] = 0;
        $report['tmp_clientes'] = [];
        $report['tmp_clientes_cadastrados'] = [];
        $report['tmp_mandabem'] = [];

        // Contador de Coletas
        $tmp_count_col = [];
        foreach ($coletas as $coleta_id => $itens) {
            $creditos = $coletaModel->getCreditosPagos(['coleta_id' => $coleta_id]);
            $total_creditos = 0;

            foreach ($creditos as $c) {
                $total_creditos += $c->value;
            }

            $total_valor_envios = 0;
            $total_valor_correios = 0;
            $report['num_coletas']++;

            $taxa_paypal = $paymentModel->getPaypalTaxa(['value' => $itens['total_paypal'], 'date' => $itens['data_geracao']]);

            foreach ($itens['envios'] as $i) {
                $report['num_envios']++;
                $i->valor_total = ($i->valor_total + $i->valor_divergente);
                $total_valor_envios += $i->valor_total;
                $total_valor_correios += $i->valor_correios;
                $report['valor_total'] += $i->valor_total;
                $report['valor_correios'] += $i->valor_correios;
                $report['total_economia'] += $i->valor_balcao - $i->valor_total;

                if (request()->ip() == '177.185.220.149') {
                    $report['real_valor_divergente'] += ($i->real_valor_divergente - $i->valor_divergente);
                }

                // Grafico
                $xdate = Carbon::parse($i->date_postagem)->format('Y-m-d');
                if (!isset($report['tmp_envios'][$xdate]['valor'])) {
                    $report['tmp_envios'][$xdate]['economia'] = 0;
                    $report['tmp_envios'][$xdate]['valor'] = 0;
                    $report['tmp_envios'][$xdate]['num_envios'] = 0;
                    $report['tmp_envios'][$xdate]['valor_faturamento'] = 0;
                    $report['tmp_envios'][$xdate]['period'] = $xdate;
                    $report['tmp_clientes'][$xdate]['num_envios'] = 0;
                    $report['tmp_clientes'][$xdate]['num_clientes'] = 0;
                    $report['tmp_clientes'][$xdate]['period'] = $xdate;
                    $report['tmp_mandabem'][$xdate]['valor'] = 0;
                }

                if (!isset($tmp_count_col[$i->coleta_id])) {
                    $tmp_count_col[$i->coleta_id] = 1;

                    if (!isset($report['tmp_clientes'][$xdate]['num_coletas'])) {
                        $report['tmp_clientes'][$xdate]['num_coletas'] = 0;
                    }

                    $report['tmp_clientes'][$xdate]['num_coletas']++;
                }

                $report['tmp_clientes'][$xdate]['num_envios']++;
                $report['tmp_envios'][$xdate]['num_envios']++;
                $report['tmp_envios'][$xdate]['valor_faturamento'] += ($i->valor_total - $i->valor_correios);
                $report['tmp_envios'][$xdate]['valor'] += $i->valor_total;
                $report['tmp_envios'][$xdate]['economia'] += $i->valor_balcao - $i->valor_total;
                $report['tmp_mandabem'][$xdate]['valor'] += ($i->valor_total - ($i->valor_correios + number_format($taxa_paypal, 2, '.', '')));
            }

            $coletas[$coleta_id]['taxa_paypal'] = $taxa_paypal;
            $coletas[$coleta_id]['total_valor_envios'] = $total_valor_envios;
            $report['valor_taxa_paypal'] += $taxa_paypal;
            $report['valor_mandabem'] += $total_valor_envios - ($total_valor_correios + $taxa_paypal);
        }

        $report['coletas'] = $coletas;

        foreach ($report['tmp_envios'] as $en) {
            $report['envios'][] = $en;
        }

        // Clientes
        $tmp_resumo = [];
        $tmp_resumo['clientes'] = [];
        $tmp_resumo['clientes_cadastrados'] = [];
        $report['num_clientes'] = 0;
        $report['num_clientes_cadastrados'] = 0;

        // Clientes Cadastrados
        $clientesCadastrados = DB::table('user')
            ->where('status', 'ACTIVE')
            ->whereBetween('date_insert', [$date_start, $date_end])
            ->get();

        foreach ($clientesCadastrados as $i) {
            $xdate = Carbon::parse($i->date_insert)->format('Y-m-d');
            $report['num_clientes_cadastrados']++;

            if (!isset($report['tmp_clientes_cadastrados'][$xdate]['num_clientes'])) {
                $report['tmp_clientes_cadastrados'][$xdate]['num_clientes'] = 0;
                $report['tmp_clientes_cadastrados'][$xdate]['period'] = $xdate;
            }

            $report['tmp_clientes_cadastrados'][$xdate]['num_clientes']++;
        }

        // Cliente efetivos
        $clientesEfetivos = DB::table('user')
            ->where('status', 'ACTIVE')
            ->where(function ($query) {
                global $date_start, $date_end;
                $query->where('date_insert', '>=', $date_start)
                    ->where('date_insert', '<=', $date_end);
            })
            ->whereRaw('(SELECT count(id) FROM coletas WHERE coletas.user_id = user.id) > 0')
            ->get();

        foreach ($clientesEfetivos as $i) {
            $xdate = Carbon::parse($i->date_insert)->format('Y-m-d');
            $report['num_clientes']++;

            if (!isset($report['tmp_clientes'][$xdate]['num_clientes'])) {
                $report['tmp_clientes'][$xdate]['num_clientes'] = 0;
                $report['tmp_clientes'][$xdate]['period'] = $xdate;
            }

            $report['tmp_clientes'][$xdate]['num_clientes']++;
        }

        foreach ($resumo['envios'] as $en) {
            $resumo['xenvios'][] = $en;
        }

        foreach ($report['tmp_clientes'] as $e) {
            $report['clientes'][] = $e;
        }

        foreach ($report['tmp_clientes_cadastrados'] as $e) {
            $report['clientes_cadastrados'][] = $e;
        }

        $report['ranking'] = [];

        if (auth()->user()->group_code == 'mandabem') {
            $report['ranking'] = DB::select("SELECT COUNT(a.id) num_envios, SUM(a.valor_total) as valor_total, a.user_id, b.razao_social FROM envios a JOIN user b ON b.id = a.user_id group by user_id ORDER BY num_envios DESC limit 15");
        }

        return $report;
    }

    public function getTotalV3($param = [])
    {
        $start = now()->firstOfMonth()->toDateString();
        $dateUtils =  new DateUtils();
        $last_day = now()->lastOfMonth()->day;
        $end = now()->endOfMonth()->toDateString();  

        $date_start = "$start 00:00:00";
        $date_end = "$end 23:59:59";

        if (isset($param['type']) && $param['type'] == 'parcial') {
            $date_start = now()->toDateString() . ' 00:00:00';
            $date_end = now()->toDateString() . ' 23:59:59';
        } else {
            if (isset($param['filter_periodo'])) {
                if ($param['filter_periodo'] == 'current_month' && isset($param['report'])) {
                    $date_start = now()->firstOfMonth()->toDateString() . ' 00:00:00';
                    $date_end = now();
                } else if ($param['filter_periodo'] == 'current_week') {
                    $week_start = now()->startOfWeek()->toDateString();
                    $week_end = now()->endOfWeek()->toDateString();

                    $date_start = "$week_start 00:00:00";
                    $date_end = "$week_end 23:59:59";
                } else if ($param['filter_periodo'] == 'current_year') {
                    $date_start = now()->firstOfYear()->toDateString() . ' 00:00:00';
                    $date_end = now()->endOfYear()->toDateString() . ' 23:59:59';
                } else if ($param['filter_periodo'] == 'last_year') {
                    $date_start = now()->subYear()->firstOfYear()->toDateString() . ' 00:00:00';
                    $date_end = now()->subYear()->endOfYear()->toDateString() . ' 23:59:59';
                } else if ($param['filter_periodo'] == 'custom') {
                    $date_start = $dateUtils->toEn($param['data_inicial']) . ' 00:00:00';
                    $date_end = $dateUtils->toEn($param['data_final']) . ' 23:59:59';
                }
            } else {
                $date_start = now()->firstOfMonth()->toDateString() . ' 00:00:00';
                $date_end = now();
            }
        }

        $date_start = isset($param['date_start']) ? (strlen($param['date_start']) == 10 ? $param['date_start'] . ' 00:00:00' : $param['date_start']) : $date_start;
        $date_end = isset($param['date_end']) ? (strlen($param['date_end']) == 10 ? $param['date_end'] . ' 23:59:59' : $param['date_end']) : $date_end;

        $sql = "SELECT envios.id, envios.coleta_id, ";
        $sql .= " envios.valor_balcao, envios.valor_total, envios.valor_correios, envios.taxa_mandabem, ";
        $sql .= " envios.payment_divergente_id, envios.valor_divergente, envios.taxa_mandabem, envios.valor_contrato, ";
        $sql .= " envios.valor_devolvido, ";
        $sql .= " envios.date_postagem, envios.destinatario, envios.CEP, envios.forma_envio, envios.ref_id, ";
        $sql .= "CONCAT(envios.etiqueta_correios,'BR') as etiqueta, envios.integration, ";
        $sql .= "coletas.user_id, coletas.id_payment as payment_id, coletas.date_insert as date_coleta, ";
        $sql .= "(SELECT COUNT(*) FROM envios as e1 WHERE e1.coleta_id = coletas.id AND date_postagem IS NOT NULL) as num_envios, ";
        $sql .= "payment.value as gateway_payment, payment.fee as gateway_fee ";
        $sql .= "FROM envios ";
        $sql .= "JOIN coletas ON coletas.id = envios.coleta_id ";
        $sql .= "LEFT JOIN payment ON payment.id = coletas.id_payment ";
        $sql .= " WHERE valor_correios IS NOT NULL ";
        $sql .= " AND date_postagem IS NOT NULL ";

        $param_sql = array();

        if (!isset($param['coleta_id'])) {
            $sql .= ' AND envios.date_postagem >= ? ';
            array_push($param_sql, $date_start);

            $sql .= ' AND envios.date_postagem <= ? ';
            array_push($param_sql, $date_end);
        }

        if (isset($param['filter_cliente']) && $param['filter_cliente']) {
            $sql .= 'AND envios.user_id =  ? ';
            array_push($param_sql, $param['filter_cliente']);
        }
        if (isset($param['user_id'])) {
            $sql .= ' AND envios.user_id = ? ';
            array_push($param_sql, $param['user_id']);
        }
        if (isset($param['coleta_id'])) {
            $sql .= ' AND envios.coleta_id = ? ';
            array_push($param_sql, $param['coleta_id']);
        }

        if (isset($param['customer']) && strlen($param['customer'])) {
            $sql .= "AND envios.user_id = " . $param['customer'] . " ";
        }
        
        $sql .= " ORDER BY envios.date_postagem ASC ";
        $envios = DB::select($sql, $param_sql);

        $info = [];
        $info['total_mandabem'] = 0;
        $info['total_mandabem_apurado'] = 0;
        $info['total_paypal'] = 0;
        $info['total_divergen_aberto'] = 0;
        $info['total_correios'] = 0;
        $info['total_economia'] = 0;
        $info['total_devolvido'] = 0;

        // tmp pra manter dados de clientes
        $tmp_hold_customer = [];

        // Otimização para busca de clientes
        if (Auth::user()->group_code == 'mandabem' && count($envios) && ( isset($param['filter_periodo']) && $param['filter_periodo'] == 'current_month' )) {
            $str_get_customer = '';
            foreach ($envios as $k => $i) {
                if (!isset($tmp_hold_customer[$i->user_id])) {
                    $tmp_hold_customer[$i->user_id] = [];
                    $str_get_customer .= $i->user_id . ',';
                }
            }

            if ($str_get_customer != '') {
                $str_get_customer = rtrim($str_get_customer, ',');
            }

            $list_customers = DB::table('user')
                ->leftJoin('api_nuvem_shop as api', function ($join) {
                    $join->on('api.user_id', '=', 'user.id')
                        ->whereNotNull('api.status_generate_post');
                })
                ->whereIn('user.id', explode(',', $str_get_customer))
                ->select('user.id', 'user.name', 'user.razao_social', 'user.CEP', 'user.cidade', 'user.uf', 'user.tipo_cliente', 'user.cpf', 'user.cnpj', 'user.date_insert', 'api.date_insert as date_api')
                ->get();

            foreach ($list_customers as $lc) {
                $tmp_hold_customer[$lc->id] = (array) $lc;
            }
        }

        foreach ($envios as $k => $i) {
            // obtendo User
            if (!isset($tmp_hold_customer[$i->user_id])) {
                $tmp_hold_customer[$i->user_id] = DB::table('user')
                    ->leftJoin('api_nuvem_shop as api', function ($join) {
                        $join->on('api.user_id', '=', 'user.id')
                            ->whereNotNull('api.status_generate_post');
                    })
                    ->where('user.id', $i->user_id)
                    ->select('user.id', 'user.name', 'user.razao_social', 'user.CEP', 'user.cidade', 'user.uf', 'user.tipo_cliente', 'user.cpf', 'user.cnpj', 'user.date_insert', 'api.date_insert as date_api')
                    ->first();
            }

            if (!isset($tmp_hold_customer[$i->user_id]['id'])) {
                continue;
            }

            if (!isset($info['itens'][$i->coleta_id]['valor_total_itens'])) {
                $info['itens'][$i->coleta_id] = [
                    'valor_total_cobrado' => 0,
                    'valor_total_mandabem' => 0,
                    'valor_total_mandabem_apurado' => 0,
                    'valor_total_correios' => 0,
                    'valor_total_balcao' => 0,
                    'valor_total_itens' => 0,
                    'valor_total_divergente' => 0,
                    'valor_total_divergente_pago' => 0,
                    'valor_total_devolvido' => 0,
                    'pagemento_paypal' => 0,
                    'taxa_paypal' => 0,
                    'total_taxa_mandabem' => 0,
                    'all_envios' => (isset($param['type']) && $param['type'] == 'indiviual') ? DB::table('envios')->where('coleta_id', $i->coleta_id)->get() : null,
                ];
            }

            // colocar somente divergências pagas
            $valor_cobrado = $i->valor_total + ((int) $i->payment_divergente_id ? $i->valor_divergente : 0);
            $taxa_mandabem = (true) ? ($i->taxa_mandabem ? $i->taxa_mandabem : ($i->valor_total - $i->valor_contrato)) : $valor_cobrado - $i->valor_correios;

            $info['itens'][$i->coleta_id]['user_id'] = $i->user_id;
            $info['itens'][$i->coleta_id]['date_coleta'] = $i->date_coleta;
            $info['itens'][$i->coleta_id]['num_envios_canc'] = 0; //$i->num_envios_canc;
            $info['itens'][$i->coleta_id]['num_envios'] = $i->num_envios;
            $info['itens'][$i->coleta_id]['pagemento_paypal'] = $i->gateway_payment;
            $info['itens'][$i->coleta_id]['taxa_paypal'] = $i->gateway_fee;
            $info['itens'][$i->coleta_id]['valor_total_balcao'] += $i->valor_balcao;
            $info['itens'][$i->coleta_id]['valor_total_itens'] += $i->valor_total;
            $info['itens'][$i->coleta_id]['valor_total_divergente'] += $i->valor_divergente;
            $info['itens'][$i->coleta_id]['valor_total_correios'] += $i->valor_correios;
            $info['itens'][$i->coleta_id]['valor_total_devolvido'] += $i->valor_devolvido;
            $info['itens'][$i->coleta_id]['total_taxa_mandabem'] += $taxa_mandabem;

            if ((int) $i->payment_divergente_id) {
                $info['itens'][$i->coleta_id]['valor_total_divergente_pago'] += $i->valor_divergente;
            }

            $info['itens'][$i->coleta_id]['itens'][$i->id] = [
                'user_id' => $i->user_id,
                'destinatario' => $i->destinatario,
                'cep' => $i->CEP,
                'integration' => $i->integration,
                'date_postagem' => $i->date_postagem,
                'forma_envio' => $i->forma_envio,
                'valor_balcao' => $i->valor_balcao,
                'valor_correios' => $i->valor_correios,
                'valor_total' => $i->valor_total,
                'valor_divergente' => $i->valor_divergente,
                'payment_divergente_id' => $i->payment_divergente_id,
                'valor_cobrado' => $valor_cobrado,
                'taxa_mandabem' => $taxa_mandabem,
                'ref_id' => $i->ref_id,
                'date_postagem' => $i->date_postagem,
            ];
        }

        $info['stats']['num_coletas'] = 0;
        $info['stats']['num_envios'] = 0;
        $info['stats']['clientes_cadastrados'] = 0;
        $info['stats']['clientes_efetivos'] = 0;
        
        $info['stats']['num_envios_nuvem'] = 0;
        $info['stats']['valor_envios_nuvem'] = 0;
        
        $info['stats']['num_envios_loja_integrada'] = 0;
        $info['stats']['valor_envios_loja_integrada'] = 0;


        if (isset($info['itens'])) {
            foreach ($info['itens'] as $coleta_id => $i) {

                if ($info['itens'][$coleta_id]['num_envios'] != count($info['itens'][$coleta_id]['itens'])) {
                    $info['itens'][$coleta_id]['taxa_paypal'] = count($info['itens'][$coleta_id]['itens']) * (($info['itens'][$coleta_id]['taxa_paypal'] / $info['itens'][$coleta_id]['num_envios']));
                }

                $info['itens'][$coleta_id]['valor_total_diverg_aberto'] = ($i['valor_total_divergente'] - $i['valor_total_divergente_pago']);
                $info['itens'][$coleta_id]['valor_total_cobrado'] = ($i['valor_total_itens'] + $i['valor_total_divergente_pago']);

                // novo sem divergencias
                if (true) {

                    if ($info['itens'][$coleta_id]['valor_total_diverg_aberto'] > 0) {
                        $info['itens'][$coleta_id]['valor_total_mandabem'] = ($i['total_taxa_mandabem'] - $info['itens'][$coleta_id]['taxa_paypal']);
                    } else {
                        $info['itens'][$coleta_id]['valor_total_mandabem'] = ($i['valor_total_itens'] + $i['valor_total_divergente_pago']) - ($i['valor_total_correios'] + $info['itens'][$coleta_id]['taxa_paypal']);
                    }

                    $info['itens'][$coleta_id]['valor_total_mandabem_apurado'] = $info['itens'][$coleta_id]['valor_total_mandabem'] - $i['valor_total_devolvido'];
                } else {
                    $info['itens'][$coleta_id]['valor_total_mandabem'] = ($i['valor_total_itens'] + $i['valor_total_divergente_pago']) - ($i['valor_total_correios'] + $info['itens'][$coleta_id]['taxa_paypal']);
                }

                $info['itens'][$coleta_id]['valor_total_enconomia'] = $info['itens'][$coleta_id]['valor_total_balcao'] -
                        $info['itens'][$coleta_id]['valor_total_cobrado'];

                // grafico apenas para pag estatisticas
                $info['stats']['num_coletas']++;
                $info['stats']['num_envios'] += count($info['itens'][$coleta_id]['itens']);
                if (true) {
                    foreach ($i['itens'] as $envio) {
                        if (isset($tmp_hold_customer[$envio['user_id']]['date_api']) && strtotime($tmp_hold_customer[$envio['user_id']]['date_api']) <= strtotime($envio['date_postagem'])) {
                            if ($envio['integration'] == 'NuvemShop') {
                                $info['stats']['num_envios_nuvem']++;
                                $info['stats']['valor_envios_nuvem'] += 0.4;
                            }
                        }
                        
                        if ($envio['date_postagem'] >= '2021-04-01') {
                            if ($envio['date_postagem'] >= '2021-07-01') {
                                if ($envio['integration'] == 'LojaIntegrada' && $envio['ref_id']) {
                                    $info['stats']['num_envios_loja_integrada']++;
                                    $info['stats']['valor_envios_loja_integrada'] += 0.42;
                                }
                            } else {
                                if ($envio['integration'] == 'LojaIntegrada' && $envio['ref_id']) {
                                    $info['stats']['num_envios_loja_integrada']++;
                                    $info['stats']['valor_envios_loja_integrada'] += 0.3;
                                }
                            }
                        }

                        $date = substr($envio['date_postagem'], 0, 10);

                        if (!isset($info['stats']['grafico'][$date]['num_envios'])) {
                            $info['stats']['grafico'][$date]['num_envios'] = 0;
                            $info['stats']['grafico'][$date]['valor_envios'] = 0;
                        }

                        $info['stats']['grafico'][$date]['num_envios']++;
                        
                        if($envio['valor_divergente'] > 0 && (int) $envio['payment_divergente_id']) {
                            $envio['valor_total'] += $envio['valor_divergente'];
                        }
                        $info['stats']['grafico'][$date]['valor_envios'] += $envio['valor_total'];
                    }
                    if (!isset($info['stats']['grafico'][$date]['num_coletas'])) {
                        $info['stats']['grafico'][$date]['num_coletas'] = 0;
                    }

                    $info['stats']['grafico'][$date]['num_coletas']++;
                }
            }
            foreach ($info['itens'] as $coleta_id => $i) {
                $info['total_divergen_aberto'] += $i['valor_total_diverg_aberto'];
                $info['total_paypal'] += normalizePriceValue($i['taxa_paypal']);
                $info['total_mandabem'] += normalizePriceValue($i['valor_total_mandabem']);
                $info['total_mandabem_apurado'] += normalizePriceValue($i['valor_total_mandabem_apurado']); // descontando devolucao de valores
                $info['total_devolvido'] += normalizePriceValue($i['valor_total_devolvido']); // descontando devolucao de valores
                $info['total_correios'] += normalizePriceValue($i['valor_total_correios']);
                $info['total_economia'] += $i['valor_total_enconomia'];
            
                if (request()->server('REMOTE_ADDR') == '177.185.220.251') {
                    $num_envios = count($i['itens']);
                    $valor_por_envio = number_format($i['valor_total_mandabem'] / $num_envios, 2, '.', '');
            
                    $contra_prova = number_format($valor_por_envio * $num_envios, 2, '.', '');
            
                    $last_envio_id = null;
                    foreach ($i['itens'] as $envio_id => $tmp_envio) {
                        $info['itens'][$coleta_id]['itens'][$envio_id]['valor_por_envio'] = $valor_por_envio;
                        $last_envio_id = $envio_id;
                    }
                    if ($contra_prova != $i['valor_total_mandabem']) {
                        if ($contra_prova < $i['valor_total_mandabem']) {
                            $info['itens'][$coleta_id]['itens'][$last_envio_id]['valor_por_envio'] = number_format($valor_por_envio + ($i['valor_total_mandabem'] - $contra_prova), 2, '.', '');
                        }
                        if ($contra_prova > $i['valor_total_mandabem']) {
                            $info['itens'][$coleta_id]['itens'][$last_envio_id]['valor_por_envio'] = number_format($valor_por_envio - ($contra_prova - $i['valor_total_mandabem']), 2, '.', '');
                        }
                    }
            
                    $tmp_total_por_envio = 0;
                    foreach ($info['itens'][$coleta_id]['itens'] as $tmp_envio) {
                        $date = substr($tmp_envio['date_postagem'], 0, 10);
                        // taxa_mandabem
                        if (!isset($info['stats']['grafico'][$date]['total_mandabem'])) {
                            $info['stats']['grafico'][$date]['total_mandabem'] = 0;
                        }
                        if ($date == 'x2020-03-11') {
                            echo "Valor Mandabem: " . number_format($i['valor_total_mandabem'], 2, '.', '') . "\n";
                            echo "Item: " . number_format($tmp_envio['valor_por_envio'], 2, '.', '') . "\n";
                        }
                        $info['stats']['grafico'][$date]['total_mandabem'] += (float) number_format($tmp_envio['valor_por_envio'], 2, '.', '');
                        if ($date == 'x2020-03-11') {
                            echo "Data totalizando: $date " . $info['stats']['grafico'][$date]['total_mandabem'] . "\n";
                        }
                        $tmp_total_por_envio += $tmp_envio['valor_por_envio'];
                    }
                }
            }

            if (isset($param['report']) && ($param['report'] == 'customer_total' || $param['report'] == 'customer_envio')) {
                foreach ($info['itens'] as $coleta_id => $i) {
                    if (!isset($tmp_hold_customer[$i['user_id']])) {
                        $sql_user = "SELECT user.id, user.name, user.razao_social, user.CEP, user.cidade, user.uf, user.tipo_cliente, user.cpf, user.cnpj, user.date_insert, api.date_insert as date_api ";
                        $sql_user .= "FROM user ";
                        $sql_user .= "LEFT JOIN api_nuvem_shop as api ON api.user_id = user.id AND api.status_generate_post IS NOT NULL ";
                        $sql_user .= "WHERE user.id = ? ";
                        $tmp_hold_customer[$i['user_id']] = DB::select($sql_user, [$i['user_id']])[0];
                    }
                    if (!isset($info['report'][$i['user_id']]['num_envios'])) {
                        $info['report'][$i['user_id']]['num_envios'] = 0;
                        $info['report'][$i['user_id']]['total_mandabem'] = 0;
                        $info['report'][$i['user_id']]['total_mandabem_apurado'] = 0;
                        $info['report'][$i['user_id']]['total_correios'] = 0;
                        $info['report'][$i['user_id']]['total_paypal'] = 0;
                        $info['report'][$i['user_id']]['total_cobrado'] = 0;
                        $info['report'][$i['user_id']]['total_divergente'] = 0;
                        $info['report'][$i['user_id']]['total_divergente_pago'] = 0;
                        $info['report'][$i['user_id']]['total_devolvido'] = 0;
                    }
                    $info['report'][$i['user_id']]['num_envios'] += count($i['itens']);
                    $info['report'][$i['user_id']]['total_mandabem'] += normalizePriceValue($i['valor_total_mandabem']);
                    $info['report'][$i['user_id']]['total_mandabem_apurado'] += normalizePriceValue($i['valor_total_mandabem_apurado']);
                    $info['report'][$i['user_id']]['total_devolvido'] += $i['valor_total_devolvido'];
                    $info['report'][$i['user_id']]['total_correios'] += $i['valor_total_correios'];
                    $info['report'][$i['user_id']]['total_paypal'] += normalizePriceValue($i['taxa_paypal']);
                    $info['report'][$i['user_id']]['total_cobrado'] += $i['valor_total_cobrado'];
                    $info['report'][$i['user_id']]['total_divergente'] += $i['valor_total_divergente'];
                    $info['report'][$i['user_id']]['total_divergente_pago'] += $i['valor_total_divergente_pago'];
            
                    $info['report'][$i['user_id']]['customer_id'] = $tmp_hold_customer[$i['user_id']]->id;
                    $info['report'][$i['user_id']]['name'] = $tmp_hold_customer[$i['user_id']]->name;
                    $info['report'][$i['user_id']]['razao_social'] = $tmp_hold_customer[$i['user_id']]->razao_social;
                    $info['report'][$i['user_id']]['cep'] = $tmp_hold_customer[$i['user_id']]->CEP;
                    $info['report'][$i['user_id']]['cidade'] = $tmp_hold_customer[$i['user_id']]->cidade . '/' . $tmp_hold_customer[$i['user_id']]->uf;
                    $info['report'][$i['user_id']]['date_register'] = $dateUtils ->toBr($tmp_hold_customer[$i['user_id']]->date_insert, false);
                    $info['report'][$i['user_id']]['doc_emissao'] = ($tmp_hold_customer[$i['user_id']]->tipo_cliente == 'PF') ? $tmp_hold_customer[$i['user_id']]->cpf : $tmp_hold_customer[$i['user_id']]->cnpj;
                    $info['report'][$i['user_id']]['itens'][$coleta_id] = $i;
                }
            }
            
            // grafico para clientes
            if (isset($param['estatisticas'])) {
                // Clientes Cadastrados
                $sql_cliente_cad = 'SELECT * FROM user ';
                $sql_cliente_cad .= ' WHERE status = "ACTIVE" ';
                $sql_cliente_cad .= ' AND date_insert >= "' . $date_start . '" ';
                $sql_cliente_cad .= ' AND date_insert <= "' . $date_end . '" ';
                $clientes_cadastrados = DB::select($sql_cliente_cad);
            
                foreach ($clientes_cadastrados as $i) {
                    $date = substr($i->date_insert, 0, 10);
                    $info['stats']['clientes_cadastrados']++;
                    if (!isset($info['stats']['grafico'][$date]['clientes_cadastrados'])) {
                        $info['stats']['grafico'][$date]['clientes_cadastrados'] = 0;
                    }
                    $info['stats']['grafico'][$date]['clientes_cadastrados']++;
                }
            
                // Cliente efetivos
                $sql_cliente = 'SELECT * FROM user ';
                $sql_cliente .= ' WHERE status = "ACTIVE" ';
                $sql_cliente .= ' AND ( SELECT count(id) FROM coletas WHERE coletas.user_id = user.id ) > 0 ';
                $sql_cliente .= ' AND date_insert >= "' . $date_start . '" ';
                $sql_cliente .= ' AND date_insert <= "' . $date_end . '" ';
                $clientes = DB::select($sql_cliente);
            
                foreach ($clientes as $i) {
                    $date = substr($i->date_insert, 0, 10);
            
                    if (!isset($info['stats']['grafico'][$date]['num_envios'])) {
                        $info['stats']['grafico'][$date]['num_envios'] = 0;
                    }
            
                    $info['stats']['clientes_efetivos']++;
                    if (!isset($info['stats']['grafico'][$date]['clientes_efetivos'])) {
                        $info['stats']['grafico'][$date]['clientes_efetivos'] = 0;
                    }
                    $info['stats']['grafico'][$date]['clientes_efetivos']++;
                }
            }
            
            // ordenando datas
            if (isset($info['stats']['grafico'])) {
                ksort($info['stats']['grafico']);
            
                foreach ($info['stats']['grafico'] as $date => $i) {
                    if (isset($i['total_mandabem'])) {
                        $i['total_mandabem'] = number_format($i['total_mandabem'], 2, '.', '');
                    }
                    $i['period'] = $date;
                    $info['stats']['grafico_rows'][] = $i;
                }
            }
            
            if (isset($info['stats']['grafico'])) {
                ksort($info['stats']['grafico']);
            
                foreach ($info['stats']['grafico'] as $date => $i) {
                    if (isset($i['total_mandabem'])) {
                        $i['total_mandabem'] = number_format($i['total_mandabem'], 2, '.', '');
                    }
                    $i['period'] = $date;
                    $info['stats']['grafico_rows'][] = $i;
                }
            }
            
            if (auth()->user()->group_code == 'mandabem' && isset($param['estatisticas'])) {
                $info['stats']['ranking'] = DB::select("SELECT COUNT(a.id) num_envios, SUM(a.valor_total) as valor_total, a.user_id,b.razao_social, b.date_insert FROM envios a JOIN user b ON b.id = a.user_id group by user_id ORDER BY num_envios DESC limit 15");
            }
            
            if (isset($param['report'])) {
                usort($info['report'], 'sortByValorMandabem');
            }
            
            // Se for para armazenamento em cache soh retornar os totais
            if (isset($param['get_for_cache']) && $param['get_for_cache']) {
                unset($info['report']);
                unset($info['itens']);
            }
            
            return $info;
            
        }
        
    }

    public function getTotalV4($param = [])
    {
        $dateUtils = new DateUtils();
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        if (isset($param['type']) && $param['type'] == 'parcial') {
            $start = now()->startOfDay();
            $end = now()->endOfDay();
        } else {
            if (isset($param['filter_periodo'])) {
                if ($param['filter_periodo'] == 'current_month' && isset($param['report'])) {
                    $start = now()->startOfMonth();
                    $end = now();
                } else if ($param['filter_periodo'] == 'current_week') {
                    $start = now()->startOfWeek();
                    $end = now()->endOfWeek();
                } else if ($param['filter_periodo'] == 'current_year') {
                    $start = now()->startOfYear();
                    $end = now()->endOfYear();
                } else if ($param['filter_periodo'] == 'last_year') {
                    $start = now()->startOfYear()->subYear();
                    $end = now()->endOfYear()->subYear();
                } else if ($param['filter_periodo'] == 'custom') {
                    $start = now()->parse($param['data_inicial'])->startOfDay();
                    $end = now()->parse($param['data_final'])->endOfDay();
                }
            }
        }

        $start = isset($param['date_start']) ? (strlen($param['date_start']) == 10 ? now()->parse($param['date_start'])->startOfDay() : now()->parse($param['date_start'])) : $start;
        $end = isset($param['date_end']) ? (strlen($param['date_end']) == 10 ? now()->parse($param['date_end'])->endOfDay() : now()->parse($param['date_end'])) : $end;

        $sql = "SELECT envios.id, envios.coleta_id, ";
        $sql .= "envios.valor_balcao, envios.valor_total, envios.valor_correios, envios.taxa_mandabem, ";
        $sql .= "envios.payment_divergente_id, envios.valor_divergente, envios.taxa_mandabem, envios.valor_contrato, ";
        $sql .= "envios.valor_devolvido, ";
        $sql .= "envios.date_postagem, envios.destinatario, envios.CEP, envios.forma_envio, ";
        $sql .= "CONCAT(envios.etiqueta_correios,'BR') as etiqueta, envios.integration, ";
        $sql .= "coletas.user_id, coletas.id_payment as payment_id, coletas.date_insert as date_coleta, ";
        $sql .= "(SELECT COUNT(*) FROM envios as e1 WHERE e1.coleta_id = coletas.id AND date_postagem IS NOT NULL) as num_envios, ";
        $sql .= "(SELECT COUNT(*) FROM envios as e2 JOIN envios_cancelamento ec ON ec.envio_id = e2.id WHERE e2.coleta_id = coletas.id) as num_envios_canc, ";
        $sql .= "payment.value as gateway_payment, payment.fee as gateway_fee ";
        $sql .= "FROM envios ";
        $sql .= "JOIN coletas ON coletas.id = envios.coleta_id ";
        $sql .= "LEFT JOIN payment ON payment.id = coletas.id_payment ";
        $sql .= " WHERE valor_correios IS NOT NULL ";
        $sql .= " AND date_postagem IS NOT NULL ";

        $param_sql = [];

        if (!isset($param['coleta_id'])) {
            $sql .= ' AND envios.date_postagem >= ? ';
            array_push($param_sql, $start);

            $sql .= ' AND envios.date_postagem <= ? ';
            array_push($param_sql, $end);
        }

        if (isset($param['filter_cliente']) && $param['filter_cliente']) {
            $sql .= 'AND envios.user_id =  ? ';
            array_push($param_sql, $param['filter_cliente']);
        }
        if (isset($param['user_id'])) {
            $sql .= ' AND envios.user_id = ? ';
            array_push($param_sql, $param['user_id']);
        }
        if (isset($param['coleta_id'])) {
            $sql .= ' AND envios.coleta_id = ? ';
            array_push($param_sql, $param['coleta_id']);
        }

        if (isset($param['customer']) && strlen($param['customer'])) {
            $sql .= "AND envios.user_id = " . $param['customer'] . " ";
        }

        $sql .= " ORDER BY envios.date_postagem ASC ";

        $sql = 'SELECT envios.*, ';
        $sql .= 'coletas.date_insert as date_coleta, ';
        $sql .= 'payment.value as gateway_payment, payment.fee as gateway_fee, ';
        $sql .= "(SELECT COUNT(*) FROM envios as e1 WHERE e1.coleta_id = coletas.id AND date_postagem IS NOT NULL) as num_envios ";
        $sql .= 'FROM envios ';
        $sql .= 'JOIN coletas ON coletas.id = envios.coleta_id ';
        $sql .= 'LEFT JOIN payment ON payment.id = coletas.id_payment ';
        $sql .= 'WHERE envios.date_postagem >= ? AND envios.date_postagem <= ? ';
        $envios = DB::select($sql, $param_sql);

        $info = [];
        $info['total_mandabem'] = 0;
        $info['total_mandabem_apurado'] = 0;
        $info['total_paypal'] = 0;
        $info['total_divergen_aberto'] = 0;
        $info['total_correios'] = 0;
        $info['total_economia'] = 0;
        $info['total_devolvido'] = 0;

        // TOTALIZANDO PRIMEIRO
        foreach ($envios as $k => $i) {
            if (!isset($info['itens'][$i->coleta_id]['valor_total_itens'])) {
                $info['itens'][$i->coleta_id]['valor_total_cobrado'] = 0;
                $info['itens'][$i->coleta_id]['valor_total_mandabem'] = 0;
                $info['itens'][$i->coleta_id]['valor_total_mandabem_apurado'] = 0; // - menos descontos concedidos
                $info['itens'][$i->coleta_id]['valor_total_correios'] = 0;
                $info['itens'][$i->coleta_id]['valor_total_balcao'] = 0;
                $info['itens'][$i->coleta_id]['valor_total_itens'] = 0;
                $info['itens'][$i->coleta_id]['valor_total_divergente'] = 0;
                $info['itens'][$i->coleta_id]['valor_total_divergente_pago'] = 0;
                $info['itens'][$i->coleta_id]['valor_total_devolvido'] = 0;
                $info['itens'][$i->coleta_id]['pagemento_paypal'] = 0;
                $info['itens'][$i->coleta_id]['taxa_paypal'] = 0;
                $info['itens'][$i->coleta_id]['total_taxa_mandabem'] = 0;
            }
        
            // Só quando pegar coleta individualmente 
            if (isset($param['type']) && $param['type'] == 'indiviual') {
                $info['itens'][$i->coleta_id]['all_envios'] = DB::table('envios')->where('coleta_id', $i->coleta_id)->get();
            }
        
            // colocar somente divergencias pagas
            $valor_cobrado = $i->valor_total + ((int)$i->payment_divergente_id ? $i->valor_divergente : 0);
        
            $taxa_mandabem = $i->taxa_mandabem ? $i->taxa_mandabem : ($i->valor_total - $i->valor_contrato);
        
            $info['itens'][$i->coleta_id]['user_id'] = $i->user_id;
            $info['itens'][$i->coleta_id]['date_coleta'] = $i->date_coleta;
            $info['itens'][$i->coleta_id]['num_envios_canc'] = 0; // $i->num_envios_canc;
            $info['itens'][$i->coleta_id]['num_envios'] = $i->num_envios;
            $info['itens'][$i->coleta_id]['pagemento_paypal'] = $i->gateway_payment;
            $info['itens'][$i->coleta_id]['taxa_paypal'] = $i->gateway_fee;
            $info['itens'][$i->coleta_id]['valor_total_balcao'] += $i->valor_balcao;
            $info['itens'][$i->coleta_id]['valor_total_itens'] += $i->valor_total;
            $info['itens'][$i->coleta_id]['valor_total_divergente'] += $i->valor_divergente;
            $info['itens'][$i->coleta_id]['valor_total_correios'] += $i->valor_correios;
            $info['itens'][$i->coleta_id]['valor_total_devolvido'] += $i->valor_devolvido;
            $info['itens'][$i->coleta_id]['total_taxa_mandabem'] += $taxa_mandabem;
        
            if ((int)$i->payment_divergente_id) {
                $info['itens'][$i->coleta_id]['valor_total_divergente_pago'] += $i->valor_divergente;
            }
        
            $info['itens'][$i->coleta_id]['itens'][$i->id] = [
                'user_id' => $i->user_id,
                'destinatario' => $i->destinatario,
                'cep' => $i->CEP,
                'integration' => $i->integration,
                'date_postagem' => $i->date_postagem,
                'forma_envio' => $i->forma_envio,
                'valor_balcao' => $i->valor_balcao,
                'valor_correios' => $i->valor_correios,
                'valor_total' => $i->valor_total,
                'valor_divergente' => $i->valor_divergente,
                'valor_cobrado' => $valor_cobrado,
                'taxa_mandabem' => $taxa_mandabem,
                'date_postagem' => $i->date_postagem,
            ];
        }
        
        $info['stats']['num_coletas'] = 0;
        $info['stats']['num_envios'] = 0;
        $info['stats']['clientes_cadastrados'] = 0;
        $info['stats']['clientes_efetivos'] = 0;
        $info['stats']['num_envios_nuvem'] = 0;
        $info['stats']['valor_envios_nuvem'] = 0;
        
        if (isset($info['itens'])) {
            foreach ($info['itens'] as $coleta_id => $i) {
                if ($info['itens'][$coleta_id]['num_envios'] != count($info['itens'][$coleta_id]['itens'])) {
                    $info['itens'][$coleta_id]['taxa_paypal'] = count($info['itens'][$coleta_id]['itens']) * (($info['itens'][$coleta_id]['taxa_paypal'] / $info['itens'][$coleta_id]['num_envios']));
                }
        
                $info['itens'][$coleta_id]['valor_total_diverg_aberto'] = ($i['valor_total_divergente'] - $i['valor_total_divergente_pago']);
                $info['itens'][$coleta_id]['valor_total_cobrado'] = ($i['valor_total_itens'] + $i['valor_total_divergente_pago']);
        
                if ($info['itens'][$coleta_id]['valor_total_diverg_aberto'] > 0) {
                    $info['itens'][$coleta_id]['valor_total_mandabem'] = ($i['total_taxa_mandabem'] - $info['itens'][$coleta_id]['taxa_paypal']);
                } else {
                    $info['itens'][$coleta_id]['valor_total_mandabem'] = ($i['valor_total_itens'] + $i['valor_total_divergente_pago']) - ($i['valor_total_correios'] + $info['itens'][$coleta_id]['taxa_paypal']);
                }
        
                $info['itens'][$coleta_id]['valor_total_mandabem_apurado'] = $info['itens'][$coleta_id]['valor_total_mandabem'] - $i['valor_total_devolvido'];
            }
        }

        echo count($info) . "\n";
        exit;

        $envios = DB::select($sql, $param_sql);

        $info = [];
        $info['total_mandabem'] = 0;
        $info['total_mandabem_apurado'] = 0;
        $info['total_paypal'] = 0;
        $info['total_divergen_aberto'] = 0;
        $info['total_correios'] = 0;
        $info['total_economia'] = 0;
        $info['total_devolvido'] = 0;

        // tmp pra manter dados de clientes
        $tmp_hold_customer = [];

        foreach ($envios as $k => $i) {
            print_r($i);
            exit("OK111");

            if (!isset($info['itens'][$i->coleta_id]['valor_total_itens'])) {
                $info['itens'][$i->coleta_id]['valor_total_cobrado'] = 0;
                $info['itens'][$i->coleta_id]['valor_total_mandabem'] = 0;
                $info['itens'][$i->coleta_id]['valor_total_mandabem_apurado'] = 0; // - menos descontos concedidos
                $info['itens'][$i->coleta_id]['valor_total_correios'] = 0;
                $info['itens'][$i->coleta_id]['valor_total_balcao'] = 0;
                $info['itens'][$i->coleta_id]['valor_total_itens'] = 0;
                $info['itens'][$i->coleta_id]['valor_total_divergente'] = 0;
                $info['itens'][$i->coleta_id]['valor_total_divergente_pago'] = 0;
                $info['itens'][$i->coleta_id]['valor_total_devolvido'] = 0;
                $info['itens'][$i->coleta_id]['pagemento_paypal'] = 0;
                $info['itens'][$i->coleta_id]['taxa_paypal'] = 0;
                $info['itens'][$i->coleta_id]['total_taxa_mandabem'] = 0;
            }
            if (isset($param['type']) && $param['type'] == 'indiviual') {
                $info['itens'][$i->coleta_id]['all_envios'] = DB::table('envios')->where('coleta_id', $i->coleta_id)->get();
            }

            exit("OK");

            // colocar somente divergencias pagas
            $valor_cobrado = $i->valor_total + ((int)$i->payment_divergente_id ? $i->valor_divergente : 0);
            $taxa_mandabem = (true) ? ($i->taxa_mandabem ? $i->taxa_mandabem : ($i->valor_total - $i->valor_contrato)) : $valor_cobrado - $i->valor_correios;

            $info['itens'][$i->coleta_id]['user_id'] = $i->user_id;
            $info['itens'][$i->coleta_id]['date_coleta'] = $i->date_coleta;
            $info['itens'][$i->coleta_id]['num_envios_canc'] = $i->num_envios_canc;
            $info['itens'][$i->coleta_id]['num_envios'] = $i->num_envios;
            $info['itens'][$i->coleta_id]['pagemento_paypal'] = $i->gateway_payment;
            $info['itens'][$i->coleta_id]['taxa_paypal'] = $i->gateway_fee;
            $info['itens'][$i->coleta_id]['valor_total_balcao'] += $i->valor_balcao;
            $info['itens'][$i->coleta_id]['valor_total_itens'] += $i->valor_total;
            $info['itens'][$i->coleta_id]['valor_total_divergente'] += $i->valor_divergente;
            $info['itens'][$i->coleta_id]['valor_total_correios'] += $i->valor_correios;
            $info['itens'][$i->coleta_id]['valor_total_devolvido'] += $i->valor_devolvido;
            $info['itens'][$i->coleta_id]['total_taxa_mandabem'] += $taxa_mandabem;

            if ((int)$i->payment_divergente_id) {
                $info['itens'][$i->coleta_id]['valor_total_divergente_pago'] += $i->valor_divergente;
            }
            $info['itens'][$i->coleta_id]['itens'][$i->id] = [
                'user_id' => $i->user_id,
                'destinatario' => $i->destinatario,
                'cep' => $i->CEP,
                'integration' => $i->integration,
                'date_postagem' => $i->date_postagem,
                'forma_envio' => $i->forma_envio,
                'valor_balcao' => $i->valor_balcao,
                'valor_correios' => $i->valor_correios,
                'valor_total' => $i->valor_total,
                'valor_divergente' => $i->valor_divergente,
                'valor_cobrado' => $valor_cobrado,
                'taxa_mandabem' => $taxa_mandabem,
                'date_postagem' => $i->date_postagem,
            ];
        }
        $info['stats']['num_coletas'] = 0;
        $info['stats']['num_envios'] = 0;
        $info['stats']['clientes_cadastrados'] = 0;
        $info['stats']['clientes_efetivos'] = 0;
        $info['stats']['num_envios_nuvem'] = 0;
        $info['stats']['valor_envios_nuvem'] = 0;

        if (isset($info['itens'])) {
            foreach ($info['itens'] as $coleta_id => $i) {
        
                if ($info['itens'][$coleta_id]['num_envios'] != count($info['itens'][$coleta_id]['itens'])) {
                    $info['itens'][$coleta_id]['taxa_paypal'] = count($info['itens'][$coleta_id]['itens']) * (($info['itens'][$coleta_id]['taxa_paypal'] / $info['itens'][$coleta_id]['num_envios']));
                }
        
                $info['itens'][$coleta_id]['valor_total_diverg_aberto'] = ($i['valor_total_divergente'] - $i['valor_total_divergente_pago']);
                $info['itens'][$coleta_id]['valor_total_cobrado'] = ($i['valor_total_itens'] + $i['valor_total_divergente_pago']);
        
                // novo sem divergencias
                if (true) {
        
                    if ($info['itens'][$coleta_id]['valor_total_diverg_aberto'] > 0) {
                        $info['itens'][$coleta_id]['valor_total_mandabem'] = ($i['total_taxa_mandabem'] - $info['itens'][$coleta_id]['taxa_paypal']);
                    } else {
                        $info['itens'][$coleta_id]['valor_total_mandabem'] = ($i['valor_total_itens'] + $i['valor_total_divergente_pago']) - ($i['valor_total_correios'] + $info['itens'][$coleta_id]['taxa_paypal']);
                    }
        
                    $info['itens'][$coleta_id]['valor_total_mandabem_apurado'] = $info['itens'][$coleta_id]['valor_total_mandabem'] - $i['valor_total_devolvido'];
                } else {
                    $info['itens'][$coleta_id]['valor_total_mandabem'] = ($i['valor_total_itens'] + $i['valor_total_divergente_pago']) - ($i['valor_total_correios'] + $info['itens'][$coleta_id]['taxa_paypal']);
                }
        
                $info['itens'][$coleta_id]['valor_total_enconomia'] = $info['itens'][$coleta_id]['valor_total_balcao'] - $info['itens'][$coleta_id]['valor_total_cobrado'];
        
                // grafico apenas para pag estatisticas
                $info['stats']['num_coletas']++;
                $info['stats']['num_envios'] += count($info['itens'][$coleta_id]['itens']);
        
                foreach ($i['itens'] as $envio) {
                    if (isset($tmp_hold_customer[$envio['user_id']]['date_api']) && strtotime($tmp_hold_customer[$envio['user_id']]['date_api']) <= strtotime($envio['date_postagem'])) {
                        if ($envio['integration'] == 'NuvemShop') {
                            $info['stats']['num_envios_nuvem']++;
                            $info['stats']['valor_envios_nuvem'] += 0.4;
                        }
                    }
                    $date = substr($envio['date_postagem'], 0, 10);
                    if (!isset($info['stats']['grafico'][$date]['num_envios'])) {
                        $info['stats']['grafico'][$date]['num_envios'] = 0;
                    }
                    $info['stats']['grafico'][$date]['num_envios']++;
                }
                if (!isset($info['stats']['grafico'][$date]['num_coletas'])) {
                    $info['stats']['grafico'][$date]['num_coletas'] = 0;
                }
        
                $info['stats']['grafico'][$date]['num_coletas']++;
            }
        
            foreach ($info['itens'] as $coleta_id => $i) {
                $info['total_divergen_aberto'] += $i['valor_total_diverg_aberto'];
                $info['total_paypal'] += normalizePriceValue($i['taxa_paypal']);
                $info['total_mandabem'] += normalizePriceValue($i['valor_total_mandabem']);
                $info['total_mandabem_apurado'] += normalizePriceValue($i['valor_total_mandabem_apurado']); // descontando devolucao de valores
                $info['total_devolvido'] += normalizePriceValue($i['valor_total_devolvido']); // descontando devolucao de valores
                $info['total_correios'] += normalizePriceValue($i['valor_total_correios']);
                $info['total_economia'] += $i['valor_total_enconomia'];
        
                if ($this->input->server('REMOTE_ADDR') == '177.185.220.251') { //&& preg_match('/2020-03-09/', $date_start)
                    $num_envios = count($i['itens']);
                    $valor_por_envio = number_format($i['valor_total_mandabem'] / $num_envios, 2, '.', '');
                    $contra_prova = number_format($valor_por_envio * $num_envios, 2, '.', '');
                    $last_envio_id = null;
                    foreach ($i['itens'] as $envio_id => $tmp_envio) {
                        $info['itens'][$coleta_id]['itens'][$envio_id]['valor_por_envio'] = $valor_por_envio;
                        $last_envio_id = $envio_id;
                    }
                    if ($contra_prova != $i['valor_total_mandabem']) {
                        if ($contra_prova < $i['valor_total_mandabem']) {
                            $info['itens'][$coleta_id]['itens'][$last_envio_id]['valor_por_envio'] = number_format($valor_por_envio + ($i['valor_total_mandabem'] - $contra_prova), 2, '.', '');
                        }
                        if ($contra_prova > $i['valor_total_mandabem']) {
                            $info['itens'][$coleta_id]['itens'][$last_envio_id]['valor_por_envio'] = number_format($valor_por_envio - ($contra_prova - $i['valor_total_mandabem']), 2, '.', '');
                        }
                    }
        
                    $tmp_total_por_envio = 0;
                    foreach ($info['itens'][$coleta_id]['itens'] as $tmp_envio) {
                        $date = substr($tmp_envio['date_postagem'], 0, 10);
                        // taxa_mandabem
                        if (!isset($info['stats']['grafico'][$date]['total_mandabem'])) {
                            $info['stats']['grafico'][$date]['total_mandabem'] = 0;
                        }
                        if ($date == 'x2020-03-11') {
                            echo "Valor Mandabem: " . number_format($i['valor_total_mandabem'], 2, '.', '') . "\n";
                            echo "Item: " . number_format($tmp_envio['valor_por_envio'], 2, '.', '') . "\n";
                        }
                        $info['stats']['grafico'][$date]['total_mandabem'] += (float) number_format($tmp_envio['valor_por_envio'], 2, '.', '');
                        if ($date == 'x2020-03-11') {
                            echo "Data totalizando: $date " . $info['stats']['grafico'][$date]['total_mandabem'] . "\n";
                        }
                        $tmp_total_por_envio += $tmp_envio['valor_por_envio'];
                    } 
                }
            }
        
            // parte apenas para relatorio de totalizacao por cliente
            if (isset($param['report']) && ($param['report'] == 'customer_total' || $param['report'] == 'customer_envio')) {
                foreach ($info['itens'] as $coleta_id => $i) {
                    if (!isset($tmp_hold_customer[$i['user_id']])) {
                        $sql_user = "SELECT user.id, user.name, user.razao_social, user.cidade, user.uf, user.tipo_cliente, user.cpf, user.cnpj, user.date_insert, api.date_insert as date_api ";
                        $sql_user .= "FROM user ";
                        $sql_user .= "LEFT JOIN api_nuvem_shop as api ON api.user_id = user.id AND api.status_generate_post IS NOT NULL ";
                        $sql_user .= "WHERE user.id = ? ";
                        $tmp_hold_customer[$i['user_id']] = DB::select($sql_user, [$i['user_id']])[0];
                    }
                    if (!isset($info['report'][$i['user_id']]['num_envios'])) {
                        $info['report'][$i['user_id']]['num_envios'] = 0;
                        $info['report'][$i['user_id']]['total_mandabem'] = 0;
                        $info['report'][$i['user_id']]['total_mandabem_apurado'] = 0;
                        $info['report'][$i['user_id']]['total_correios'] = 0;
                        $info['report'][$i['user_id']]['total_paypal'] = 0;
                        $info['report'][$i['user_id']]['total_cobrado'] = 0;
                        $info['report'][$i['user_id']]['total_divergente'] = 0;
                        $info['report'][$i['user_id']]['total_divergente_pago'] = 0;
                        $info['report'][$i['user_id']]['total_devolvido'] = 0;
                    }
                    $info['report'][$i['user_id']]['num_envios'] += count($i['itens']);
                    $info['report'][$i['user_id']]['total_mandabem'] += normalizePriceValue($i['valor_total_mandabem']);
                    $info['report'][$i['user_id']]['total_mandabem_apurado'] += normalizePriceValue($i['valor_total_mandabem_apurado']);
                    $info['report'][$i['user_id']]['total_devolvido'] += $i['valor_total_devolvido'];
                    $info['report'][$i['user_id']]['total_correios'] += $i['valor_total_correios'];
                    $info['report'][$i['user_id']]['total_paypal'] += normalizePriceValue($i['taxa_paypal']);
                    $info['report'][$i['user_id']]['total_cobrado'] += $i['valor_total_cobrado'];
                    $info['report'][$i['user_id']]['total_divergente'] += $i['valor_total_divergente'];
                    $info['report'][$i['user_id']]['total_divergente_pago'] += $i['valor_total_divergente_pago'];
        
                    $info['report'][$i['user_id']]['customer_id'] = $tmp_hold_customer[$i['user_id']]->id;
                    $info['report'][$i['user_id']]['name'] = $tmp_hold_customer[$i['user_id']]->name;
                    $info['report'][$i['user_id']]['razao_social'] = $tmp_hold_customer[$i['user_id']]->razao_social;
                    $info['report'][$i['user_id']]['cidade'] = $tmp_hold_customer[$i['user_id']]->cidade . '/' . $tmp_hold_customer[$i['user_id']]->uf;
                    $info['report'][$i['user_id']]['date_register'] = $dateUtils->toBr($tmp_hold_customer[$i['user_id']]->date_insert, false);
                    $info['report'][$i['user_id']]['doc_emissao'] = ($tmp_hold_customer[$i['user_id']]->tipo_cliente == 'PF') ? $tmp_hold_customer[$i['user_id']]->cpf : $tmp_hold_customer[$i['user_id']]->cnpj;
                    $info['report'][$i['user_id']]['itens'][$coleta_id] = $i;
                }
            }
        }
        
        // grafico para clientes
        if (isset($param['estatisticas'])) {

            // Clientes Cadastrados
            $clientesCadastrados = DB::table('user')
                ->selectRaw('*')
                ->where('status', 'ACTIVE')
                ->whereBetween('date_insert', [$start, $end])
                ->get();

            foreach ($clientesCadastrados as $i) {
                $date = substr($i->date_insert, 0, 10);
                $info['stats']['clientes_cadastrados']++;
                if (!isset($info['stats']['grafico'][$date]['clientes_cadastrados'])) {
                    $info['stats']['grafico'][$date]['clientes_cadastrados'] = 0;
                }
                $info['stats']['grafico'][$date]['clientes_cadastrados']++;
            }
            // Cliente efetivos
            // $clientesEfetivos = DB::table('user')
            //     ->selectRaw('*')
            //     ->where('status', 'ACTIVE')
            //     ->where(function ($query) {
            //         $query->where('date_insert', '>=', $start)
            //             ->where('date_insert', '<=', $end);
            //     })
            //     ->whereRaw('(SELECT COUNT(id) FROM coletas WHERE coletas.user_id = user.id) > 0')
            //     ->get();

            // foreach ($clientesEfetivos as $i) {
            //     $date = substr($i->date_insert, 0, 10);

            //     if (!isset($info['stats']['grafico'][$date]['num_envios'])) {
            //         $info['stats']['grafico'][$date]['num_envios'] = 0;
            //     }

            //     $info['stats']['clientes_efetivos']++;
            //     if (!isset($info['stats']['grafico'][$date]['clientes_efetivos'])) {
            //         $info['stats']['grafico'][$date]['clientes_efetivos'] = 0;
            //     }
            //     $info['stats']['grafico'][$date]['clientes_efetivos']++;
            // }
        }

        // ordenando datas
        if (isset($info['stats']['grafico'])) {
            ksort($info['stats']['grafico']);

            foreach ($info['stats']['grafico'] as $date => $i) {
                if (isset($i['total_mandabem'])) {
                    $i['total_mandabem'] = number_format($i['total_mandabem'], 2, '.', '');
                }
                $i['period'] = $date;
                $info['stats']['grafico_rows'][] = $i;
            }
        }

        if (auth()->user()->group_code == 'mandabem' && isset($param['estatisticas'])) {
            $info['stats']['ranking'] = DB::select("SELECT COUNT(a.id) num_envios, SUM(a.valor_total) as valor_total, a.user_id,b.razao_social, b.date_insert FROM envios a JOIN user b ON b.id = a.user_id group by user_id ORDER BY num_envios DESC limit 15");
        }

        if (isset($param['report'])) {
            usort($info['report'], 'sortByValorMandabem');
        }

        // Se for para armazenamento em cache só retornar os totais
        if (isset($param['get_for_cache']) && $param['get_for_cache']) {
            unset($info['report']);
            unset($info['itens']);
        }

        return $info;

        
    }

    public function getTotalEnviosCliente($param = [])
    {
        $query = DB::table('envios')
            ->select('envios.*')
            ->whereNotNull('valor_correios')
            ->whereNotNull('date_postagem');

        if (isset($param['date_start'])) {
            $dateStart = strlen($param['date_start']) == 10 ? $param['date_start'] . ' 00:00:00' : $param['date_start'];
            $query->where('envios.date_postagem', '>=', $dateStart);
        }

        if (isset($param['date_end'])) {
            $dateEnd = strlen($param['date_end']) == 10 ? $param['date_end'] . ' 23:59:59' : $param['date_end'];
            $query->where('envios.date_postagem', '<=', $dateEnd);
        }

        if (isset($param['customer']) && strlen($param['customer'])) {
            $query->where('envios.user_id', '=', $param['customer']);
        }

        $envios = $query->orderBy('envios.date_postagem', 'ASC')->get();

        $info = [];

        foreach ($envios as $i) {
            // ... verificar
        }

        dd($info);
    }

    public function getTotalEconomia($data = [])
    {
        return DB::table('envios')
            ->where('user_id', '=', $data['user_id'])
            ->whereNotNull('date_postagem')
            ->whereNotNull('valor_correios')
            ->sum(DB::raw('(valor_balcao - valor_total)'));
    }

    public function getTotalDivergencia($data = [])
    {
        return DB::table('envios')
            ->where('user_id', '=', $data['user_id'])
            ->whereNull('payment_divergente_id')
            ->whereNotNull('date_postagem')
            ->where('valor_divergente', '>', 0)
            ->sum('valor_divergente');
    }

    public function getTotalClientesComEnvios($data = [])
    {
        if (!isset($data['type'])) {
            $dateStart = now()->subDays(30)->startOfDay();
            $dateEnd = now()->endOfDay();

            $query = DB::table('envios')
                ->join('coletas', 'coletas.id', '=', 'envios.coleta_id')
                ->where('envios.date_postagem', '>=', $dateStart)
                ->where('envios.date_postagem', '<=', $dateEnd);

            if (true) {
                return $query->distinct('envios.user_id')->count('envios.user_id');
            } else {
                return DB::table('user')
                    ->where(DB::raw('(SELECT COUNT(*) FROM envios JOIN coletas ON coletas.id = envios.coleta_id WHERE envios.user_id = user.id AND envios.date_postagem IS NOT NULL AND envios.valor_correios IS NOT NULL AND envios.date_postagem >= ? AND envios.date_postagem <= ?)'), '>', 0)
                    ->count();
            }
        }

        return 0;
    }

    public function getBoletoTransferencia($param = [])
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        if (isset($param['type']) && $param['type'] == 'parcial') {
            $start = now()->startOfDay();
            $end = now()->endOfDay();
        }

        $list = DB::table('payment')
            ->select('*')
            ->where('date', '>=', $start)
            ->where('date', '<=', $end)
            ->where(function ($query) {
                $query->whereIn('tipo', ['transferencia', 'boleto', 'mercado_pago'])
                    ->orWhere(function ($query) {
                        $query->where('tipo', 'cobranca')
                            ->where('status', 'completed');
                    });
            })
            ->get();

        $info = [
            'total' => ['transferencia' => 0, 'boleto' => 0, 'cobranca' => 0],
        ];

        $infoParcial = $info;

        foreach ($list as $i) {
            if ($i->tipo == 'mercado_pago') {
                $i->tipo = 'transferencia';
            }

            $info['total'][$i->tipo] += $i->tipo != 'cobranca' ? ($i->value * -1) : $i->value;

            if (substr($i->date, 0, 10) == now()->toDateString()) {
                $infoParcial['total'][$i->tipo] += $i->tipo != 'cobranca' ? ($i->value * -1) : $i->value;
            }
        }

        return [
            'all' => $info,
            'part' => $infoParcial,
        ];
    }

    public function getCreditoDesconto($data = [])
    {
        $result = [];

        $creditos = DB::table('payment')
            ->select('*')
            ->where('user_id', '=', $data['user_id'])
            ->where('value', '<', 0)
            ->whereNotIn('status', ['DEVOLVIDO', null])
            ->orderBy('id')
            ->get();

        foreach ($creditos as $c) {
            $result[$c->id]['description'] = $c->description;
            $result[$c->id]['value'] = $c->value * -1;
            $result[$c->id]['date'] = \Carbon\Carbon::parse($c->date)->format('d/m/Y');

            if (!isset($result[$c->id]['valor_descontado'])) {
                $result[$c->id]['valor_descontado'] = 0;
            }

            $itens = DB::table('payment_credit_discount')
                ->select('*')
                ->where('payment_id', '=', $c->id)
                ->orderByDesc('id')
                ->get();

            foreach ($itens as $i) {
                $result[$c->id]['itens'][] = (array)$i;
                $result[$c->id]['valor_descontado'] += $i->value * -1;
            }
        }

        return $result;
    }

    public function relCustomerEnvio($data)
    {
        $error = [];

        if (!isset($data['date_start']) || !strlen($data['date_start'])) {
            $error[] = "Informe a data Inicial";
        }
        if (!isset($data['date_end']) || !strlen($data['date_end'])) {
            $error[] = "Informe a data Final";
        }
        if ($error) {
            $this->error = implode('<br>', $error);
            return false;
        }

        $dateStart = \Carbon\Carbon::createFromFormat('Y-m-d', $data['date_start'])->startOfDay();
        $dateEnd = \Carbon\Carbon::createFromFormat('Y-m-d', $data['date_end'])->endOfDay();

        $query = DB::table('envios')
            ->join('user', 'user.id', '=', 'envios.user_id')
            ->select('envios.id', 'coleta_id', 'destinatario', 'envios.CEP', 'forma_envio', 'date_postagem', 'valor_total')
            ->addSelect(DB::raw('IFNULL(valor_divergente, valor_total) AS total'))
            ->addSelect('user.name', 'user.razao_social', 'user.id AS user_id')
            ->where('date_postagem', '>=', $dateStart)
            ->where('date_postagem', '<=', $dateEnd);

        if (isset($data['customer']) && (int) $data['customer']) {
            $query->where('user_id', '=', (int) $data['customer']);
        }

        $list = $query->orderBy('envios.date_postagem', 'ASC')->get();

        $result = [];

        foreach ($list as $i) {
            $result[$i->user_id]['name'] = $i->name;
            $result[$i->user_id]['razao_social'] = $i->razao_social;
            $result[$i->user_id]['envios'][$i->id] = (array)$i;
        }

        return $result;
    }

    public function relUsers($data)
    {
        $error = [];

        if (!isset($data['date_start']) || !strlen($data['date_start'])) {
            $error[] = "Informe a data Inicial";
        }
        if (!isset($data['date_end']) || !strlen($data['date_end'])) {
            $error[] = "Informe a data Final";
        }
        if ($error) {
            $this->error = implode('<br>', $error);
            return false;
        }

        $dateStart = \Carbon\Carbon::createFromFormat('Y-m-d', $data['date_start'])->startOfDay();
        $dateEnd = \Carbon\Carbon::createFromFormat('Y-m-d', $data['date_end'])->endOfDay();

        $query = DB::table('user')
            ->where('date_insert', '>=', $dateStart)
            ->where('date_insert', '<=', $dateEnd);

        if (isset($data['user_type_register']) && $data['user_type_register'] == 'NUVEM SHOP') {
            $query->whereRaw('(SELECT COUNT(id) FROM api_nuvem_shop ans WHERE ans.user_id = user.id AND ans.status_generate_post IS NOT NULL) > 0');
        }

        $result = $query->orderByDesc('date_insert')->get();

        return $result;
    }

    public function getReport($param = [])
    { 
        if ($param['report'] == 'customer_envio') {
            return $this->relCustomerEnvio($param);
        }

        if ($param['report'] == 'users') {
            return $this->relUsers($param);
        }

        if ($param['report'] == "customer_total" || $param['report'] == 'total_conferencia' || $param['report'] == 'total') {
            $dateStart = Carbon::parse($param['date_start'])->toDateString();
            $dateEnd = Carbon::parse($param['date_end'])->toDateString();
            $query = DB::table('user')
                ->select(
                    'user.id as customer_id',
                    'user.name',
                    'user.razao_social',
                    'user.date_insert',
                    'user.email',
                    DB::raw('CONCAT(user.cidade,"/",user.uf) as localidade'),
                    'envios.*',
                    'co.date_insert as date_insert_coleta',
                    'pa.value as valor_cobrado_paypal',
                    DB::raw('(SELECT SUM(pd.value) * -1 FROM payment_credit_discount pd WHERE pd.coleta_id = envios.coleta_id) as creditos')
                )
                ->leftJoin('envios', 'envios.user_id', '=', 'user.id')
                ->leftJoin('coletas as co', 'co.id', '=', 'envios.coleta_id')
                ->leftJoin('payment as pa', function ($join) {
                    $join->on('pa.id', '=', 'co.id_payment')
                        ->where('pa.status', '=', 'completed');
                })
                ->where('envios.valor_correios', '>', 0)
                ->where('envios.date_postagem', '>=', $dateStart . ' 00:00:00')
                ->where('envios.date_postagem', '<=', $dateEnd . ' 23:59:59');

            if (isset($param['cidade']) && strlen($param['cidade'])) {
                $query->where('user.cidade', $param['cidade']);
            }

            if (isset($param['customer']) && strlen($param['customer'])) {
                $query->where('user.id', $param['customer']);
            }

            $list = $query->orderBy('envios.coleta_id')
                ->orderBy('envios.id')
                ->get();

            $coletas = [];
            foreach ($list as $i) {
                $coletas[$i->coleta_id]['customer_id'] = $i->user_id;
                $coletas[$i->coleta_id]['customer_name'] = $i->name;
                $coletas[$i->coleta_id]['customer_razao_social'] = $i->razao_social;
                $coletas[$i->coleta_id]['customer_localidade'] = $i->localidade;
                $coletas[$i->coleta_id]['customer_date_insert'] = $i->date_insert;
                $coletas[$i->coleta_id]['data_coleta'] = $i->date_insert_coleta;
                $coletas[$i->coleta_id]['coleta_id'] = $i->coleta_id;
                $coletas[$i->coleta_id]['creditos'] = $i->creditos;
                $coletas[$i->coleta_id]['valor_cobrado_paypal'] = $i->valor_cobrado_paypal;
                $coletas[$i->coleta_id]['envios'][] = $i;
            }

            $totais = [];
            foreach ($coletas as $c) {
                $totais[] = $this->getTotalDetalhado($c);
            }

            $return = [];
            $return['totais']['valor_mandabem'] = 0;
            $return['totais']['valor_divergente'] = 0;
            $return['totais']['valor_total'] = 0;
            $return['totais']['total_geral'] = 0;
            $return['totais']['numero_envios'] = 0;
            $return['totais']['taxa_paypal'] = 0;
            $return['totais']['valor_correios'] = 0;

            foreach ($totais as $a) {
                foreach ($a as $i) {
                    if (!isset($return['rows'][$i['customer_id']]['valor_total'])) {
                        $return['rows'][$i['customer_id']]['valor_total'] = 0;
                    }
                    if (!isset($return['rows'][$i['customer_id']]['valor_divergente'])) {
                        $return['rows'][$i['customer_id']]['valor_divergente'] = 0;
                    }
                    if (!isset($return['rows'][$i['customer_id']]['total'])) {
                        $return['rows'][$i['customer_id']]['total'] = 0;
                    }
                    if (!isset($return['rows'][$i['customer_id']]['valor_correios'])) {
                        $return['rows'][$i['customer_id']]['valor_correios'] = 0;
                    }
                    if (!isset($return['rows'][$i['customer_id']]['valor_mandabem'])) {
                        $return['rows'][$i['customer_id']]['valor_mandabem'] = 0;
                    }
                    if (!isset($return['rows'][$i['customer_id']]['numero_envios'])) {
                        $return['rows'][$i['customer_id']]['numero_envios'] = 0;
                    }
                    if (!isset($return['rows'][$i['customer_id']]['taxa_paypal'])) {
                        $return['rows'][$i['customer_id']]['taxa_paypal'] = 0;
                    }

                    $return['rows'][$i['customer_id']]['customer_id'] = $i['customer_id'];
                    $return['rows'][$i['customer_id']]['customer_name'] = $i['customer_id'];
                    $return['rows'][$i['customer_id']]['customer_localidade'] = $i['customer_localidade'];
                    $return['rows'][$i['customer_id']]['customer_razao_social'] = $i['customer_razao_social'];
                    $return['rows'][$i['customer_id']]['customer_date_insert'] = $i['customer_date_insert'];
                    $return['rows'][$i['customer_id']]['valor_total'] += $i['valor_total'];
                    $return['rows'][$i['customer_id']]['valor_correios'] += $i['valor_correios'];
                    $return['rows'][$i['customer_id']]['valor_divergente'] += $i['valor_divergente'];
                    $return['rows'][$i['customer_id']]['valor_mandabem'] += $i['valor_mandabem'];
                    $return['rows'][$i['customer_id']]['total'] += $i['valor_total_final'];
                    $return['rows'][$i['customer_id']]['numero_envios'] += $i['numero_envios'];
                    $return['rows'][$i['customer_id']]['taxa_paypal'] += $i['taxa_paypal'];
                    $return['totais']['total_geral'] += $i['valor_total_final'];
                    $return['totais']['valor_mandabem'] += $i['valor_mandabem'];
                    $return['totais']['numero_envios'] += $i['numero_envios'];
                    $return['totais']['valor_divergente'] += $i['valor_divergente'];
                    $return['totais']['valor_total'] += $i['valor_total'];
                    $return['totais']['valor_correios'] += $i['valor_correios'];
                    $return['totais']['taxa_paypal'] += $i['taxa_paypal'];
                }
            }

            usort($return['rows'], 'sortByValor');

            if ($param['report'] == 'total_conferencia' || $param['report'] == 'total') {
                return ['resumo' => $totais, 'totais' => $return];
            }

            return $return;
        }

        if ($param['report'] == "customer_city" || $param['report'] == "customer_sem_coleta") {
            $dateStart = Carbon::parse($param['date_start'])->toDateString();
            $dateEnd = Carbon::parse($param['date_end'])->toDateString();
        
            $query = DB::table('user')
                ->where('date_insert', '>=', $dateStart)
                ->where('date_insert', '<=', $dateEnd);
        
            if ($param['report'] == "customer_sem_coleta") {
                $query->whereNotExists(function ($subquery) {
                    $subquery->select(DB::raw(1))
                        ->from('coletas')
                        ->whereColumn('coletas.user_id', 'user.id');
                });
            }
        
            if (isset($param['cidade']) && strlen($param['cidade'])) {
                $query->where('cidade', $param['cidade']);
            }
        
            $list = $query->orderBy('cidade')
                ->get();
        
            $return = [];
            foreach ($list as $i) {
                $return[$i->cidade][] = $i;
            }
        
            return $return;
        }
    }

    public function getTotalDetalhado($coletas) 
    {
        $info = [];
    
        $tmpTotalCreditoDiscont = $coletas['creditos'];
    
        $info[$coletas['coleta_id']]['customer_id'] = $coletas['customer_id'];
        $info[$coletas['coleta_id']]['data_coleta'] = $coletas['data_coleta'];
        $info[$coletas['coleta_id']]['customer_name'] = $coletas['customer_name'];
        $info[$coletas['coleta_id']]['customer_localidade'] = $coletas['customer_localidade'];
        $info[$coletas['coleta_id']]['customer_razao_social'] = $coletas['customer_razao_social'];
        $info[$coletas['coleta_id']]['customer_date_insert'] = $coletas['customer_date_insert'];
        $info[$coletas['coleta_id']]['creditos'] = $coletas['creditos'];
        $info[$coletas['coleta_id']]['numero_envios'] = count($coletas['envios']);
    
        foreach ($coletas['envios'] as $envio) {
            $coletaId = $envio->coleta_id;
    
            if (!isset($info[$coletaId]['valor_total'])) {
                $info[$coletaId]['valor_total'] = 0;
            }
            if (!isset($info[$coletaId]['valor_correios'])) {
                $info[$coletaId]['valor_correios'] = 0;
            }
            if (!isset($info[$coletaId]['valor_divergente'])) {
                $info[$coletaId]['valor_divergente'] = 0;
            }
            if (!isset($info[$coletaId]['valor_divergente_paga'])) {
                $info[$coletaId]['valor_divergente_paga'] = 0;
            }
            if (!isset($info[$coletaId]['valor_total_final'])) {
                $info[$coletaId]['valor_total_final'] = 0;
            }
    
            $info[$coletaId]['valor_total_final'] += ($envio->valor_total + (float) $envio->valor_divergente);
            $info[$coletaId]['valor_total'] += $envio->valor_total;
            $info[$coletaId]['valor_correios'] += $envio->valor_correios;
            $info[$coletaId]['valor_divergente'] += $envio->valor_divergente;
    
            $info[$coletaId]['envios'][$envio->id]['valor_total_final'] = ($envio->valor_total + (float) $envio->valor_divergente);
            if ($tmpTotalCreditoDiscont > 0 && $tmpTotalCreditoDiscont > $info[$coletaId]['envios'][$envio->id]['valor_total_final']) {
                $_creditos = $info[$coletaId]['envios'][$envio->id]['valor_total_final'];
                $tmpTotalCreditoDiscont -= $_creditos;
            } else {
                $_creditos = $tmpTotalCreditoDiscont;
                $tmpTotalCreditoDiscont -= $_creditos;
            }
    
            // Envios
            $info[$coletaId]['envios'][$envio->id]['valor_divergente'] = (float) $envio->valor_divergente;
    
            $info[$coletaId]['envios'][$envio->id]['valor_total'] = $envio->valor_total;
            $info[$coletaId]['envios'][$envio->id]['valor_correios'] = $envio->valor_correios;
            $info[$coletaId]['envios'][$envio->id]['creditos'] = $_creditos;
    
            if ($envio->payment_divergente_id) {
                $info[$coletas['coleta_id']]['valor_divergente_paga'] = (float) $envio->valor_divergente;
            }
        }
        $paymentModel = app(Payment::class);
        $realCobrado = $coletas['valor_cobrado_paypal'];
        $taxaPaypal = $paymentModel->getPaypalTaxa(['value' => $realCobrado, 'date' => $coletas['data_coleta']]);
        $info[$coletas['coleta_id']]['valor_mandabem'] = ($info[$coletas['coleta_id']]['valor_total'] + $info[$coletas['coleta_id']]['valor_divergente']) - ($info[$coletas['coleta_id']]['valor_correios'] + $taxaPaypal);
        $info[$coletas['coleta_id']]['taxa_paypal'] = $taxaPaypal;
    
        return $info;
    }

    public function getRateio($data = []) 
    {
        $data['valor'] = (float) $data['valor'];
        $difValor = 0;
        $valor = 0;
    
        if ($data['valor'] > 0) {
            $numItens = $data['num_itens'];
            $valor = (float) number_format($data['valor'] / $numItens, 2, '.', '');
            $tmpValor = $valor * $numItens;
            $difValor = (float) $data['valor'] - $tmpValor;
        }
    
        // if ($vl > 0) {
        //     if ($x == 1)
        //         $valor_item_frete = $vl_frete + $dif_vl_frete;
        //     else
        //         $valor_item_frete = $vl_frete;

        //     $xml .= '<vFrete>' . number_format($valor_item_frete, 2, '.', '') . '</vFrete>';
        // }
    
        return $difValor;
    }

    public function getColetas($param = []) 
    {
        $coletas = DB::table('coletas as a')
            ->select('a.*', 'b.valor_total', 'b.valor_correios', 'b.valor_balcao', 'b.valor_divergente', 'b.payment_divergente_id')
            ->when(isset($param['user_id']), function ($query) use ($param) {
                return $query->where('a.user_id', $param['user_id']);
            })
            ->when(session('user_id') != 5, function ($query) {
                return $query->where('a.environment', 'production');
            })
            ->whereNotNull('a.payment_id')
            ->whereNotNull('b.date_postagem')
            ->where('b.valor_correios', '>', 0)
            ->where('b.date_postagem', '>=', Carbon::now()->firstOfMonth())
            ->where('b.date_postagem', '<=', Carbon::now())
            ->join('envios as b', 'b.coleta_id', '=', 'a.id')
            ->get();
    
        $report['list'] = $coletas;
        $report['valor_total'] = 0;
        $report['valor_correios'] = 0;
        $report['valor_desconto'] = 0;
    
        foreach ($coletas as $coleta) {
            if (!$coleta->payment_divergente_id) {
                $coleta->valor_divergente = 0;
            }
    
            $report['valor_total'] += $coleta->valor_total + $coleta->valor_divergente;
            $report['valor_correios'] += $coleta->valor_correios;
            $report['valor_desconto'] += ($coleta->valor_balcao - ($coleta->valor_total + $coleta->valor_divergente));
        }
    
        return $report;
    }

    public function getEnvios($param = [])
    {
        $diff = 0;

        $start = date('Y') . '-' . (date('m') - $diff) . '-01';

        $last_day = date('t', strtotime($start));
        $end = date('Y') . '-' . (date('m') - $diff) . '-' . $last_day; //get end date of month

        $num_days = $last_day;

        $query = DB::table('envios as a')
            ->select('a.*', 'b.date_insert as date_coleta')
            ->join('coletas as b', 'b.id', '=', 'a.coleta_id');

        if (isset($param['filter_cliente']) && $param['filter_cliente']) {
            $query->where('a.user_id', $param['filter_cliente']);
        }

        if (isset($param['user_id']) && $param['user_id']) {
            $query->where('a.user_id', $param['user_id']);
        }

        if (session('user_id') != 5) {
            $query->where('b.environment', 'production');
        }

        if (isset($param['filter_periodo'])) {
            if ($param['filter_periodo'] == 'current_month') {
                $date_start = date('Y-m') . '-01 00:00:00';
                $date_end = now();
            } elseif ($param['filter_periodo'] == 'current_week') {
                $week_start = now()->startOfWeek();
                $week_end = now()->endOfWeek();

                $date_start = $week_start->toDateTimeString();
                $date_end = $week_end->toDateTimeString();
            } elseif ($param['filter_periodo'] == 'current_year') {
                $date_start = date('Y') . '-01-01 00:00:00';
                $date_end = date('Y') . '-12-31 23:59:59';
            } elseif ($param['filter_periodo'] == 'last_year') {
                $date_start = now()->subYear()->startOfYear()->toDateTimeString();
                $date_end = now()->subYear()->endOfYear()->toDateTimeString();
            } elseif ($param['filter_periodo'] == 'custom') {
                $date_start = Carbon::parse($param['data_inicial'])->startOfDay()->toDateTimeString();
                $date_end = Carbon::parse($param['data_final'])->endOfDay()->toDateTimeString();
            }

            $query->where('a.date_postagem', '>=', $date_start)
                ->where('a.date_postagem', '<=', $date_end);
        }

        $query->where('a.valor_correios', '>', 0)
            ->whereNotNull('a.coleta_id')
            ->whereNotNull('a.etiqueta_correios')
            ->whereNotNull('b.payment_id');

        $envios = $query->get();

        $resumo = [];
        $resumo['xenvios'] = [];
        $resumo['envios'] = [];
        $resumo['valor_envios'] = [];

        $resumo['num_coletas'] = 0;
        $resumo['num_envios'] = 0;
        $resumo['total_economia'] = 0;
        $resumo['valor_total'] = 0;
        $resumo['valor_correios'] = 0;

        $tmp_resumo = [];
        $tmp_resumo['clientes'] = [];
        $tmp_count_col = [];

        foreach ($envios as $i) {
            $xdate = substr($i->date_coleta, 0, 10);

            // Caso divergência ainda não paga
            if (!$i->payment_divergente_id) {
                $i->valor_divergente = 0;
            }

            $resumo['num_envios']++;
            $resumo['valor_total'] += ($i->valor_total + $i->valor_divergente);
            $resumo['valor_correios'] += $i->valor_correios;
            $resumo['total_economia'] += ($i->valor_balcao - ($i->valor_total + $i->valor_divergente));

            if (!isset($resumo['envios'][$xdate]['valor'])) {
                $resumo['envios'][$xdate]['economia'] = 0;
                $resumo['envios'][$xdate]['valor'] = 0;
                $resumo['envios'][$xdate]['num_envios'] = 0;
                $resumo['envios'][$xdate]['period'] = $xdate;

                $tmp_resumo['clientes'][$xdate]['num_envios'] = 0;
                $tmp_resumo['clientes'][$xdate]['num_clientes'] = 0;
                $tmp_resumo['clientes'][$xdate]['period'] = $xdate;
            }

            if (!isset($tmp_count_col[$i->coleta_id])) {
                $tmp_count_col[$i->coleta_id] = 1;

                if (!isset($tmp_resumo['clientes'][$xdate]['num_coletas'])) {
                    $tmp_resumo['clientes'][$xdate]['num_coletas'] = 0;
                }

                $resumo['num_coletas']++;
                $tmp_resumo['clientes'][$xdate]['num_coletas']++;
            }

            $resumo['envios'][$xdate]['num_envios']++;
            $resumo['envios'][$xdate]['valor'] += ($i->valor_total + $i->valor_divergente);
            $resumo['envios'][$xdate]['economia'] += ($i->valor_balcao - ($i->valor_total + $i->valor_divergente));

            $tmp_resumo['clientes'][$xdate]['num_envios']++;
        }

        $resumo['num_clientes'] = 0;
        $clientes = DB::table('user')
            ->where('date_insert', '>=', $date_start)
            ->where('date_insert', '<=', $date_end)
            ->get();

        foreach ($clientes as $i) {
            $xdate = substr($i->date_insert, 0, 10);
            $resumo['num_clientes']++;

            if (!isset($tmp_resumo['clientes'][$xdate]['num_clientes'])) {
                $tmp_resumo['clientes'][$xdate]['num_clientes'] = 0;
                $tmp_resumo['clientes'][$xdate]['period'] = $xdate;
            }

            $tmp_resumo['clientes'][$xdate]['num_clientes']++;
        }

        foreach ($resumo['envios'] as $en) {
            $resumo['xenvios'][] = $en;
        }

        foreach ($tmp_resumo['clientes'] as $e) {
            $resumo['clientes'][] = $e;
        }

        $resumo['ranking'] = [];

        if (session('group_code') == 'mandabem') {
            $resumo['ranking'] = DB::select("SELECT COUNT(a.id) num_envios, SUM(a.valor_total) as valor_total, a.user_id,b.razao_social FROM envios a JOIN user b ON b.id = a.user_id group by user_id ORDER BY num_envios DESC limit 5");
        }

        return $resumo;
    }

    public function getFromCache($type = null)
    {
        $dateUtils = new DateUtils();
        if (!$type) {
            $name_cache_total = 'cache_for_total_values';
        } elseif ($type == 'parcial') {
            $name_cache_total = 'cache_for_total_parcial_values';
        }

        $info = DB::table('system_settings')
            ->where('date_update', '>=', $dateUtils->getTimeFromNowToPast(600))
            ->where('name', $name_cache_total)
            ->first();

        return $info ?: false;
    }

    public function getInfoUserRegister($data = [])
    {
        $sql = '';
        $params = [];

        if ($data['type'] == 'loja_integrada') {

            $date_init = '2020-04-28';

            $sql = 'SELECT count(*) as total ';
            $sql .= 'FROM user ';
            $sql .= 'JOIN user_register_cache urc ON urc.email = user.email AND urc.status = "COMPLETE" ';
            $sql .= 'WHERE user.date_insert >= "2020-04-01" AND user.date_insert < ? ';
            $sql .= ' AND urc.plataforma = ? ';

            $params = [$date_init, 'Loja Integrada'];

            $total_a = DB::select($sql, $params)[0]->total;

            $sql = 'SELECT count(*) as total ';
            $sql .= 'FROM user ';
            $sql .= 'JOIN user_register_cache urc ON urc.email = user.email AND urc.status = "COMPLETE" ';
            $sql .= 'WHERE user.date_insert >= ? AND user.date_insert <= "2020-05-31" ';
            $sql .= ' AND urc.plataforma = ? ';

            $params = [$date_init, 'Loja Integrada'];

            $total_b = DB::select($sql, $params)[0]->total;

            $sql = '';
            $sql .= 'SELECT COUNT(*) as total FROM envios WHERE envios.date_postagem >= "2020-04-01" AND envios.date_postagem < ? AND envios.user_id IN ';
            $sql .= '( SELECT user.id ';
            $sql .= 'FROM user ';
            $sql .= 'JOIN user_register_cache urc ON urc.email = user.email AND urc.status = "COMPLETE" ';

            $sql .= 'WHERE user.date_insert >= "2020-04-01" AND user.date_insert < ? ';
            $sql .= ' AND urc.plataforma = "Loja Integrada" ) ';

            $envios_a = DB::select($sql, [$date_init, $date_init])[0]->total;

            $sql = '';
            $sql .= 'SELECT envios.* FROM envios WHERE envios.date_postagem >= "2020-04-01" AND envios.date_postagem < ? AND envios.user_id IN ';
            $sql .= '( SELECT user.id ';
            $sql .= 'FROM user ';
            $sql .= 'JOIN user_register_cache urc ON urc.email = user.email AND urc.status = "COMPLETE" ';

            $sql .= 'WHERE user.date_insert >= "2020-04-01" AND user.date_insert < ? ';
            $sql .= ' AND urc.plataforma = "Loja Integrada" ) ';
            $lista_envios_a = DB::select($sql, [$date_init, $date_init]);

            $clientes_com_envios_a = [];
            foreach ($lista_envios_a as $i) {
                $clientes_com_envios_a[$i->user_id] = 1;
            }

            $sql = '';
            $sql .= 'SELECT COUNT(*) as total FROM envios WHERE envios.date_postagem >= ? AND envios.date_postagem <= "2020-05-31" AND envios.user_id IN ';
            $sql .= '( SELECT user.id ';
            $sql .= 'FROM user ';
            $sql .= 'JOIN user_register_cache urc ON urc.email = user.email AND urc.status = "COMPLETE" ';

            $sql .= 'WHERE user.date_insert >= ? AND user.date_insert <= "2020-05-31" ';
            $sql .= ' AND urc.plataforma = "Loja Integrada" ) ';

            $envios_b = DB::select($sql, [$date_init, $date_init])[0]->total;

            $sql = '';
            $sql .= 'SELECT envios.* FROM envios WHERE envios.date_postagem >= ? AND envios.date_postagem <= "2020-05-31" AND envios.user_id IN ';
            $sql .= '( SELECT user.id ';
            $sql .= 'FROM user ';
            $sql .= 'JOIN user_register_cache urc ON urc.email = user.email AND urc.status = "COMPLETE" ';

            $sql .= 'WHERE user.date_insert >= ? AND user.date_insert <= "2020-05-31" ';
            $sql .= ' AND urc.plataforma = "Loja Integrada" ) ';
            $lista_envios_b = DB::select($sql, [$date_init, '2020-05-31']);

            $clientes_com_envios_b = [];
            foreach ($lista_envios_b as $i) {
                $clientes_com_envios_b[$i->user_id] = 1;
            }

            return [
                'A' => [
                    'cadastros' => $total_a,
                    'envios' => $envios_a,
                    'clientes_postagens' => count($clientes_com_envios_a),
                ],
                'B' => [
                    'cadastros' => $total_b,
                    'envios' => $envios_b,
                    'clientes_postagens' => count($clientes_com_envios_b),
                ],
            ];
        }

        return [];
    }

    public function getEnviosNaoPostados($data = []) 
    {
        print_r($data);
        exit;
    }

    public function metodosEnvios($data = [])
    {
        $sql = 'SELECT 
                    COUNT(envios.id) AS envios,
                    envios.forma_envio,
                    (COUNT(envios.id) / total_envios.total * 100) AS porcentagem
                FROM 
                    envios
                CROSS JOIN 
                    (SELECT COUNT(id) AS total FROM envios 
                    WHERE date_insert >= ? AND date_insert <= ? 
                    AND envios.date_postagem IS NOT NULL AND envios.coleta_id IS NOT NULL) AS total_envios
                WHERE 
                    envios.date_postagem IS NOT NULL 
                    AND envios.coleta_id IS NOT NULL 
                    AND envios.date_insert >= ? 
                    AND envios.date_insert <= ? 
                GROUP BY 
                    envios.forma_envio';

        $result = DB::select($sql, [
            $data['date_start'] . ' 00:00:00',
            $data['date_end'] . ' 23:59:59',
            $data['date_start'] . ' 00:00:00',
            $data['date_end'] . ' 23:59:59',
        ]);

        return $result;
    }

    public function newUserSemEnvios($data = [])
    {
        $sql = 'SELECT name, razao_social, email, telefone, date_insert, volume_medio
                FROM user
                WHERE (volume_medio LIKE "de 30 a 50 Envios" OR volume_medio LIKE "mais de 50 Envios")
                    AND date_insert >= ? 
                    AND date_insert <= ? 
                    AND id NOT IN (SELECT user_id FROM envios) 
                ORDER BY volume_medio DESC';

        $result = DB::select($sql, [
            $data['date_start'] . ' 00:00:00',
            $data['date_end'] . ' 23:59:59',
        ]);

        return $result;
    }

    public function indicacoes($data = [])
    {
        $indicador = explode(",", $data['indicacoes']);
        $indicadorConditions = [];

        foreach ($indicador as $i) {
            $indicadorConditions[] = 'urc.quem_indicou LIKE "%' . $i . '%"';
        }

        $sql = 'SELECT u.id, u.name, u.email, urc.quem_indicou, urc.date_insert, COUNT(e.id) AS envios_count ';
        $sql .= 'FROM user_register_cache urc ';
        $sql .= 'JOIN user u ON u.cpf = urc.cpf ';
        $sql .= 'LEFT JOIN envios e ON e.user_id = u.id ';
        $sql .= 'WHERE ' . implode(' OR ', $indicadorConditions) . ' ';
        $sql .= 'AND urc.date_insert >= ? ';
        $sql .= 'AND urc.date_insert <= ? ';
        $sql .= 'GROUP BY u.id, u.name, u.email, urc.quem_indicou, urc.date_insert ';
        $sql .= 'ORDER BY envios_count DESC';

        $result = DB::select($sql, [
            $data['date_start'] . ' 00:00:00',
            $data['date_end'] . ' 23:59:59',
        ]);

        return $result;
    }

    public function clienteDeposito($data = []) 
    {
        $sql = 'SELECT user_id, obs, value, date_insert, status, tipo
                FROM payment
                WHERE user_id = ? 
                    AND date_insert >= ? 
                    AND date_insert <= ?';
    
        return DB::select($sql, [
            $data['cliente_id'],
            $data['date_start'] . ' 00:00:00',
            $data['date_end'] . ' 23:59:59',
        ]);
    }

    public function clientePagamento($data = []) 
    {
        $sql = 'SELECT payment_credit_discount.*, payment.user_id 
                FROM payment_credit_discount 
                JOIN payment ON payment.id = payment_credit_discount.payment_id 
                WHERE payment.user_id = ? 
                    AND payment_credit_discount.date >= ? 
                    AND payment_credit_discount.date <= ?';
    
        return DB::select($sql, [
            $data['cliente_id'],
            $data['date_start'] . ' 00:00:00',
            $data['date_end'] . ' 23:59:59',
        ]);
    }

    public function plataformas() 
    {
        $sql = 'SELECT integration FROM envios GROUP BY integration';
        return DB::select($sql);
    }

    public function integracoes($data = []) 
    {
        $sql = 'SELECT COUNT(envios.id) as envios, envios.integration, user.id, user.name, user.email, user.razao_social, user.date_insert
                FROM envios
                JOIN user ON user.id = envios.user_id ';
    
        if ($data['integracoes'] == 'Externa') {
            $sql .= 'WHERE envios.integration IS NULL ';
        } else {
            $sql .= 'WHERE envios.integration = ? ';
        }
    
        $sql .= 'AND envios.date_postagem IS NOT NULL
                AND envios.date_insert >= ? 
                AND envios.date_insert <= ? 
                GROUP BY envios.user_id 
                ORDER BY envios DESC';
    
        if ($data['integracoes'] == 'Externa') {
            return DB::select($sql);
        } else {
            return DB::select($sql, [$data['integracoes'], $data['date_start'] . ' 00:00:00', $data['date_end'] . ' 23:59:59']);
        }
    }
}