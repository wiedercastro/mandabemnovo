<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DOMDocument;
use App\Models\Log;

class Manifestacao extends Model
{
    protected $table = 'envios_manifestacao';
    protected $error;
    protected $resp_http;
    protected $info_request;

    public function __construct()
    {
        parent::__construct();
        $this->error = null;
        $this->resp_http = null;
        $this->info_request = null;
    }

    public function getError()
    {
        return $this->error;
    }

    public function get($id, $args = [])
    {
        $query = $this->select('envios_manifestacao.*');

        if (isset($args['return_user'])) {
            $query->selectRaw('CONCAT(user.id,"-",user.razao_social) as user_name')
                ->join('envios', 'envios.id', '=', 'envios_manifestacao.envio_id')
                ->join('user', 'user.id', '=', 'envios.user_id');
        }

        $query->where('envios_manifestacao.numero_pi', '<>', 'f')
            ->where('envios_manifestacao.id', $id);

        return $query->first();
    }

    public function getByParams($data)
    {
        if (!$data || !count($data)) {
            return;
        }

        $query = $this->select('envios_manifestacao.*');

        if (isset($data['id'])) {
            $query->where('envios_manifestacao.id', $data['id']);
        }
        
        if (isset($data['user_id'])) {
            $query->join('envios', 'envios.id', '=', 'envios_manifestacao.envio_id')
                ->where('envios.user_id', $data['user_id']);
        }

        if (isset($data['numero_pi'])) {
            $query->where('numero_pi', $data['numero_pi']);
        }

        if (isset($data['objeto'])) {
            $query->where('objeto', $data['objeto']);
        }

        if (!isset($data['is_cron']) && !isset($data['id']) && (!isset($data['negada']) || !$data['negada'])) {
            $query->where('indica_procedencia', 'S');
        }

        $query->orderBy('envios_manifestacao.id', 'desc');

        return $query->first();
    }

    public function getList($param = [])
    {
        $query = DB::table('envios_manifestacao as ma');

        if (isset($param['get_total'])) {
            $query->select(DB::raw('count(*) as total'));
        } else if (!isset($param['apuracao'])) {
            $query->select('ma.*', DB::raw('(SELECT count(*) FROM envios_manifestacao_comments WHERE envio_manifestacao_id = ma.id) as num_comments'))
                ->select('envios.destinatario', 'envios.CEP', 'envios.coleta_id', 'envios.valor_correios', DB::raw('if(envios.payment_divergente_id IS NOT NULL, (envios.valor_total + envios.valor_divergente), envios.valor_total) as valor_total'), 'envios.seguro', 'envios.valor_devolvido', 'envios.etiqueta_correios', 'envios.date_postagem')
                ->select('user.razao_social')
                ->select('ev.descricao as ultimo_status');
        }

        if (!isset($param['apuracao'])) {
            $query->join('envios', 'envios.id', '=', 'ma.envio_id')
                ->join('user', 'user.id', '=', 'envios.user_id')
                ->leftJoin('etiqueta_status as es', 'es.envio_id', '=', 'envios.id')
                ->leftJoin('etiqueta_events as ev', 'ev.id', '=', 'es.etiqueta_event_id')
                ->whereRaw('if(ma.flag_automatic != 1 AND ma.status != "Interno" AND ma.status != "Prioritario",codigo_motivo NOT LIKE 0, (codigo_motivo IS NULL OR codigo_motivo IS NOT NULL) )')
                ->where('numero_pi', 'NOT LIKE', 'f');
        }

        if (!isset($param['apuracao'])) {
            if ($param['user_id'] != 'mandabem') {
                $query->where('envios.user_id', $param['user_id']);
                if (session('group_code') != 'mandabem') {
                    $query->where(function ($q) {
                        $q->whereNull('ma.flag_automatic')
                            ->orWhere('ma.flag_automatic', 2);
                    });
                }
            }
        }

        if (!isset($param['apuracao'])) {
            if (isset($param['status']) && $param['status']) {
                if ($param['status'] == 'aceito') {
                    $query->where('ma.indica_procedencia', 'S');
                } else if ($param['status'] == 'pending') {
                    // $query->where('ma.indica_procedencia', 'S');
                } else if ($param['status'] == 'pago') {
                    $query->where('ma.status_pagamento', 'PAGO');
                } else if ($param['status'] == 'pending_sem_obj_finalizado') {
                    $query->whereNull('envios.is_finalizado');
                } else if ($param['status'] == 'pago_sem_repasse') {
                    $query->where('ma.status_pagamento', 'PAGO')
                        ->whereNull('ma.date_pgto_cliente');
                } else {
                    $query->where('ma.status', $param['status']);
                }
            }
        }

        if (!isset($param['date_end'])) {
            $param['date_end'] = now()->toDateString();
        }

        if ($param['filter_periodo'] == 'custom') {
            if (isset($param['date_start']) && $param['date_start']) {
                $date_start = now()->modify($param['date_start'])->startOfDay();
            }
            if (isset($param['date_end']) && $param['date_end']) {
                $date_end = now()->modify($param['date_end'])->endOfDay();
            }
        }

        if (!isset($param['filter_periodo']) || $param['filter_periodo'] == 'current_month') {
            $date_start = now()->startOfMonth();
            $date_end = now();
        }

        if ($param['filter_periodo'] == 'current_week') {
            $date_start = now()->startOfWeek();
            $date_end = now()->endOfWeek();
        }

        if ($param['filter_periodo'] == 'current_year') {
            $date_start = now()->startOfYear();
            $date_end = now()->endOfYear();
        }

        if ($param['filter_periodo'] == 'last_year') {
            $date_start = now()->subYear()->startOfYear();
            $date_end = now()->subYear()->endOfYear();
        }

        if (!isset($param['apuracao']) && session('group_code') == 'mandabem') {
            $query->where('ma.date_insert', '>=', $date_start)
                ->where('ma.date_insert', '<=', $date_end);
        }

        if (!isset($param['apuracao']) && session('group_code') != 'mandabem') {
            $query->whereNull('ma.type_view')
                ->whereNotNull('ma.numero_pi')
                ->where('ma.numero_pi', '<>', 'f');
        }

        if (!isset($param['apuracao']) && isset($param['filter_only_auditor']) && $param['filter_only_auditor']) {
            $query->whereNotNull('ma.sent_auditor');
        }

        if (isset($param['etiqueta']) && $param['etiqueta']) {
            if (strtoupper(substr($param['etiqueta'], -2)) == 'BR') {
                $param['etiqueta'] = substr($param['etiqueta'], 0, -2);
            }
            if (!isset($param['apuracao'])) {
                $query->where('envios.etiqueta_correios', 'LIKE', '%' . $param['etiqueta'] . '%');
            }
        }

        if (isset($param['filter_numero_pi']) && $param['filter_numero_pi']) {
            if (!isset($param['apuracao'])) {
                $query->where('ma.numero_pi', $param['filter_numero_pi']);
            }
        }

        if (isset($param['get_total']) && $param['get_total']) {
            return $query->get()->count();
        } else {
            if (isset($param['apuracao']) && $param['apuracao']) {
                $query_base = '';

                $query_base .= ' AND ma.date_insert >= "' . $date_start . '" AND ma.date_insert <= "' . $date_end . '" ';
                $query_base .= ' AND if(flag_automatic != 1,codigo_motivo NOT LIKE 0, (codigo_motivo IS NULL OR codigo_motivo IS NOT NULL) ) ';

                $apuracao = new \stdClass();

                $query_init1 = "SELECT count(*) as total FROM envios_manifestacao ma ";
                if ($param['user_id'] != 'mandabem') {
                    $query_init1 .= " JOIN envios ON envios.id = ma.envio_id ";
                }
                $query_init1 .= " WHERE 1 ";
                $query_init2 = "SELECT SUM(valor_indenizado_pago) as valor_pago, SUM(valor_seguro_pago) as seguro_pago FROM envios_manifestacao ma ";

                if ($param['user_id'] != 'mandabem') {
                    $query_init2 .= " JOIN envios ON envios.id = ma.envio_id ";
                }
                $query_init2 .= ' WHERE 1 ';

                if ($param['user_id'] != 'mandabem') {
                    $query_base .= ' AND envios.user_id = ' . $param['user_id'] . ' ';
                }

                if (isset($param['status']) && $param['status'] == 'pago_sem_repasse') {
                    $query_base .= ' AND ma.status_pagamento = "PAGO" AND ma.date_pgto_cliente IS NULL ';
                }

                $apuracao->aceitas = 0;
                $apuracao->criadas = DB::select($query_init1 . $query_base)[0]->total;
                $apuracao->aceitas = DB::select($query_init1 . $query_base . ' AND indica_procedencia = "S"  ')[0]->total;
                $sum = DB::select($query_init2 . $query_base . ' AND indica_procedencia = "S" AND fatura_pagamento IS NOT NULL AND fatura_pagamento != ""  ')[0];

                $apuracao->valor_pago = $sum->valor_pago;
                $apuracao->seguro_pago = $sum->seguro_pago;

                return $apuracao;
            }

            $limit = isset($param['per_page']) ? $param['per_page'] : 10;
            $start = isset($param['page_start']) ? $param['page_start'] : 0;

            $list = $query->limit($limit)->offset($start)
                ->orderBy('ma.id', 'DESC')
                ->get();

            foreach ($list as $k => $v) {
                $sql = "SELECT * FROM envios_manifestacao_ch_status WHERE envio_manifestacao_id = ? ORDER BY id DESC ";
                $list[$k]->change_status = DB::select($sql, [$v->id]);
            }

            return $list;
        }
    }

    public function updateManifestacao($id, $param)
    {
        DB::table('envios_manifestacao')
            ->where('id', $id)
            ->update($param);
    }

    public function saveManifestacao($data)
    {
        return DB::table('envios_manifestacao')
            ->insertGetId([
                'envio_id' => $data['envio_id'],
                'numero_pi' => $data['numero_pi'],
                'numero_lote' => $data['numero_lote'],
                'objeto' => $data['objeto'],
                'codigo_motivo' => $data['codigo_motivo'],
                'str_motivo' => $data['str_motivo'],
                'flag_automatic' => isset($data['flag_automatic']) ? $data['flag_automatic'] : null,
                'date_insert' => now(),
                'date_update' => now(),
            ]);
    }

    public function saveComment($data)
    {
        if (isset($data['msg_unique_id'])) {
            $ins = DB::table('envios_manifestacao_comments')->insert($data);
            return $ins ? DB::getPdo()->lastInsertId() : false;
        } else {
            return DB::table('envios_manifestacao_comments')->insertGetId([
                'user_creator_id' => $data['user_id'],
                'envio_manifestacao_id' => $data['manifestacao_id'],
                'comment' => $data['comment'],
                'date_insert' => now(),
            ]);
        }
    }

    public function getNumComments($id)
    {
        return DB::table('envios_manifestacao_comments')
            ->where('envio_manifestacao_id', $id)
            ->count();
    }

    public function getComments($id, $type = 'manifestacao')
    {
        if (!$type) {
            return [];
        }

        $sql = "SELECT emc.*, emc.msg_from as name_creator FROM ";
        $sql .= "envios_manifestacao_comments emc ";

        if ($type == 'manifestacao') {
            $sql .= "WHERE emc.envio_manifestacao_id = ? ";
        }
        if ($type == 'envio') {
            $sql .= "WHERE emc.envio_id = ? ";
        }

        $sql .= "ORDER BY emc.date_msg ";

        return DB::select($sql, [$id]);
    }

    public function saveStatusAuditor($data)
    {
        if (isset($data['comment']) && strlen($data['comment'])) {
            $this->save_comment($data);
        }

        DB::table('envios_manifestacao_ch_status')->insert([
            'user_creator_id' => $data['user_id'],
            'envio_manifestacao_id' => $data['manifestacao_id'],
            'status' => $data['status_auditor'],
            'date_insert' => now(),
        ]);
    }

    public function getStatusAuditor($id)
    {
        $row = DB::table('envios_manifestacao_ch_status')
            ->where('envio_manifestacao_id', $id)
            ->orderBy('id', 'desc')
            ->first();

        return $row ? $row->status : '';
    }

    public function baixaEtiqueta($data)
    {
        $envio = $data['envio'];
        $str_error = $data['error'] ?? null;
        $flag_automatic = $data['flag_automatic'] ?? 1;
        $status = $data['status'] ?? null;

        if (preg_match('/Falha: Ol(.*?), o objeto (.*?) mensurado pelo n(.*?)vel de serviço, portanto n(.*?)o (.*?) previsto o registro de manifesta(.*?)(.*?)o por este motivo./i', $str_error)) {
            return DB::table('envios_manifestacao')->insert([
                'envio_id' => $envio->id,
                'objeto' => $envio->etiqueta_correios . 'BR',
                'codigo_motivo' => 0,
                'status' => "Interno",
                'numero_pi' => "MB" . $envio->id,
                'str_motivo' => $str_error,
                'flag_automatic' => $flag_automatic ? $flag_automatic : null,
                'date_insert' => now(),
                'date_update' => now(),
            ]);
        } else {
            return DB::table('envios_manifestacao')->insert([
                'envio_id' => $envio->id,
                'objeto' => $envio->etiqueta_correios . 'BR',
                'codigo_motivo' => 0,
                'status' => $status,
                'str_motivo' => ($str_error ? $str_error : 'Não aceito (abertura atraso/nao entregue)'),
                'flag_automatic' => $flag_automatic ? $flag_automatic : null,
                'date_insert' => now(),
                'date_update' => now(),
            ]);
        }
    }

    public function getListAtraso()
    {
        $sql = "SELECT envios.* FROM envios ";
        $sql .= "LEFT JOIN envios_manifestacao em ON em.envio_id = envios.id AND em.flag_automatic = 1 ";
        $sql .= "WHERE em.id IS NULL AND envios.dias_uteis_entrega > envios.prazo ";
        $sql .= "AND envios.date_entregue >= ? ";
        $sql .= "LIMIT 100";

        return DB::select($sql, ['2020-02-04']);
    }

    public function isEntregaAtrasada($envio)
    {
        if (!$envio->date_postagem || $envio->date_entregue) {
            return false;
        }

        $dataInicial = now()->modify($this->dateUtils->toBr($envio->date_postagem, false))->startOfDay();
        $dataFinal = now()->endOfDay();

        $diasUteis = $this->dateUtils->diasUteis($dataInicial, $dataFinal);

        return $diasUteis > $envio->prazo;
    }

    public function setPgtoCliente($id)
    {
        $exist = DB::table('envios_manifestacao')
            ->where('id', $id)
            ->whereNotNull('date_pgto_cliente')
            ->first();

        if ($exist) {
            return false;
        }

        return DB::table('envios_manifestacao')
            ->where('id', $id)
            ->whereNull('date_pgto_cliente')
            ->update(['date_pgto_cliente' => now()]);
    }

    public function sendAuditor($data)
    {
        return DB::table('envios_manifestacao')
            ->where('id', $data['id'])
            ->whereNull('sent_auditor')
            ->update([
                'sent_auditor' => now(),
                'msg_to_auditor' => $data['msg_to_auditor']
            ]);
    }

    public function getReaberturas($manifestacao_id)
    {
        return DB::table('envios_manifestacao_reopen')
            ->where('manifestacao_id', $manifestacao_id)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function reabrir($data)
    {
        $manifestacao = $this->get($data['manifestacao_id']);

        if (!$manifestacao) {
            $this->error = "Manifestação não encontrada.";
            return false;
        }

        $reabertura = $this->getReaberturas($manifestacao->id);

        if ($reabertura && auth()->id() != '3748') {
            $this->error = "Reabertura já solicitada.";
            return false;
        }

        $initPage = $this->connectionFaleConosco([
            'url' => 'http://www2.correios.com.br/sistemas/falecomoscorreios/reativar.cfm?e_mail=marcos@mandabem.com.br&codigo=' . $manifestacao->numero_pi
        ]);

        if (preg_match('/N(.*?)o foi encontrada manifesta(.*?)o com as informa(.*?)fornecidas|ou a manifesta(.*?)o já se encontra reativada ou em tratamento/', $initPage)) {
            $this->error = 'Não foi encontrada manifestação com as informações fornecidas, ou a manifestação já se encontra reativada ou em tratamento';
            return false;
        }

        $urlPost2 = 'http://www2.correios.com.br/sistemas/falecomoscorreios/gravaForms.cfm?forms=5';

        $motivo = $data['resumo'];

        $dataPost = [
            'tipoManifestacaoAnterior' => 'I',
            'objeto' => $manifestacao->objeto,
            'email' => 'marcos@mandabem.com.br',
            'Codigo' => $manifestacao->numero_pi,
            'destino' => 'N',
            'motivoReativacao' => isset($data['new_motivo']) ? 'outro_motivo' : 'mesmo_motivo',
            'tipoManifestacao' => 'I',
            'formaPagamento' => 'F',
            'motivo' => $motivo,
            'formOrigem' => '4',
            'botaoEnvia' => 'Reativar'
        ];

        if (isset($data['new_motivo'])) {
            $dataPost['motivoreclamacao'] = $data['new_motivo'];
        }

        $reat = $this->connectionFaleConosco([
            'url' => $urlPost2,
            'post_data' => $dataPost
        ]);

        if (!preg_match('/Prezado Cliente(.*?)a manifesta(.*?)o n(.*?)mero(.*?)foi reativada com sucesso/', $reat)) {
            // Handle failure
            $this->error = 'Falha ao reabrir manifestação. Contate suporte.';
            return false;
        }

        $inserted = DB::table('envios_manifestacao_reopen')->insert([
            'manifestacao_id' => $data['manifestacao_id'],
            'motivo' => $data['motivo'],
            'resumo' => $data['resumo'],
            'date' => now(),
        ]);

        if ($inserted) {
            DB::table('envios_manifestacao')->where('id', $data['manifestacao_id'])->update([
                'status' => 'Aberto'
            ]);
        }

        return true;
    }

    public function getMotivosAbertura($key = null, $type = 'normal')
    {
        if ($type == 'normal') {
            $motivos = [
                "39" => "Atrasos na distribuição",
                "134" => "Remessa/Objeto postal avariada/danificada",
                "136" => "Remessa/Objeto postal devolvida indevidamente",
                "132" => "Remessa/Objeto postal entregue em local divergente",
                "211" => "Remessa/Objeto postal não entregue",
                "133" => "Remessa/Objeto postal violada",
            ];
        }

        if ($type == 'reabertura') {
            $motivos = [
                "834" => 'AR Digital - Imagem não disponível',
                "123" => 'Remessa/Objeto postal avariada/danificada',
                "125" => 'Remessa/Objeto postal devolvida indevidamente',
                "124" => 'Remessa/Objeto postal entregue com atraso',
                "774" => 'Remessa/Objeto postal entregue em local divergente',
                "122" => 'Remessa/Objeto postal violada',
                "132" => 'Remetente não recebeu o AR',
            ];
        }

        if ($key && isset($motivos[$key])) {
            return $motivos[$key];
        }

        return $motivos;
    }

    public function updateIndenizacao()
    {
        $this->load->library('validation');

        if (false) {
            $mes = 'marco_2021';

            $xml = file_get_contents('/home/sysuser/files/xml_conferencia/xml_plp_' . $mes . '.xml');
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->loadXML($xml);

            $creditos = $dom->getElementsByTagName('credito');

            $dataCreditos = [];
            $totalCredito = 0;

            foreach ($creditos as $i) {
                if (!preg_match('/CREDITO/', $i->getElementsByTagName('descricaoCredito')->item(0)->nodeValue)) {
                    continue;
                }

                $totalCredito += $i->getElementsByTagName('valorCredito')->item(0)->nodeValue;
                $dataCreditos[] = $i;
            }

            print_r($dataCreditos);
            print_r($totalCredito);

            return;
        }

        if (true) {
            $file = '/home/sysuser/files/indenizacoes/fatura-601735.csv';

            $this->load->library('CsvImporter', ['filename' => $file, 'delimiter' => ';'], 'csv');
            $lines = $this->csv->get();

            $numeroFatura = null;
            $datePgtoFatura = null;

            foreach ($lines as $i) {
                $desc = $i[0];
                $numeroFatura = '601735'; // fixando fatura, arquivo diferente
                $datePgtoFatura = '2022-04-06';

                if ($desc == '27347642000118') {
                    $numeroFatura = $i[8];
                    $datePgtoFatura = $this->date_utils->to_en($i[10]);
                }

                if (!preg_match('/CREDITO - IND/', $desc)) {
                    continue;
                }

                echo $desc . "\n";

                if (!$numeroFatura) {
                    echo "Numero da Fatura Nao encotrado.\n";
                    return;
                }

                if (!$datePgtoFatura || !$this->validation->val_date($datePgtoFatura, 'en')) {
                    echo "Data de Pagto Obtido da Fatura invalida ($datePgtoFatura).\n";
                    return;
                }

                $valor = preg_replace('/,/', '.', preg_replace('/[^0-9,]/', '', $i[1]));

                $tmp = [];
                preg_match('/PI ([0-9]){1,}/', $desc, $tmp);
                $numeroPi = preg_replace('/[^0-9]/', '', $tmp[0]);

                preg_match('/([A-Z]){2}([0-9]){9}BR/', $desc, $tmp);

                if (!isset($tmp[0])) {
                    echo "Falha ao obter numero de etiqueta\n";
                    exit();
                }

                $objetoMani = $tmp[0];

                $manifestacao = $this->getByParams(['numero_pi' => $numeroPi, 'is_cron' => true]);

                if (!$manifestacao) {
                    $manifestacao = $this->getByParams(['objeto' => $objetoMani, 'is_cron' => true]);

                    if (!$manifestacao) {
                        echo "Falha em busca de manifestacao: " . print_r($i, true) . "\n";

                        $sqlStatus = " SELECT es.*, ee.* FROM envios  ";
                        $sqlStatus .= " LEFT JOIN etiqueta_status es ON es.envio_id = envios.id ";
                        $sqlStatus .= " LEFT JOIN etiqueta_events ee ON ee.id = es.etiqueta_event_id ";
                        $sqlStatus .= " WHERE envios.etiqueta_correios = ? ";

                        echo $sqlStatus . "\n";

                        $etiqueta = DB::select($sqlStatus, [substr($objetoMani, 0, 11)])[0];

                        echo "ETIQUETA\n";
                        print_r($etiqueta);
                        echo "\n";

                        echo "#######################################################################\n";
                        echo "#######################################################################\n";
                        continue;
                    }
                }

                echo "Objeto: $manifestacao->objeto \n";

                $paramUpdate = [
                    'status_pagamento' => 'PAGO',
                    'fatura_pagamento' => $numeroFatura,
                    'date_pgto_fatura' => $datePgtoFatura,
                    'valor_indenizado_pago' => $valor,
                ];

                if ($manifestacao->valor_seguro_pago > 0) {
                    $paramUpdate['valor_seguro_pago'] = 0;
                }

                $this->update($manifestacao->id, $paramUpdate);
            }
        }
    }

    public function connectionFaleConosco($options = [])
    {
        $url = $options['url'];
        $cookie = storage_path('app/tmp/cookie_correios_mani_fale_conosco.data');

        if (isset($options['clear_cookie']) && $options['clear_cookie']) {
            @unlink($cookie);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);

        if (isset($options['show_header']) && $options['show_header']) {
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        }

        if (isset($options['post_data'])) {
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($options['post_data']));
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36');

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($info['http_code'] != '200') {
            $this->error = "FALHA CONEXAO:\n";
            $this->error .= print_r($info, true);
            $this->error .= print_r($error, true);
            $this->error .= print_r($response, true);
            $this->error .= "\n";

            return false;
        }

        $this->resp_http = "FALHA CONEXAO:\n";
        $this->resp_http .= print_r($info, true);
        $this->resp_http .= print_r($error, true);
        $this->resp_http .= print_r($response, true);
        $this->resp_http .= "\n";

        $debug = "RESP CONEXAO:\n";
        $debug .= "Info:\n" . print_r($info, true);
        $debug .= "Error:\n" . print_r($error, true);
        $debug .= "Response:\n" . print_r($response, true);
        $debug .= "\n";

        Log::debug($debug);

        $this->infoRequest = $info;

        return $response;
    }

    private function resolveRecaptcha($param)
    {
        require_once base_path('path/to/deathbycaptcha.php');

        // Use the 'storage' disk to store captcha images and responses
        $path = storage_path('captcha');
        $this->load->library('email_maker');

        // Put your DBC username & password here.
        $username = "marcosgazza";
        $password = "Mandabem2018";

        $client = new DeathByCaptcha_HttpClient($username, $password);
        $client->isVerbose = true;

        echo "DeathBYCaptcha: your balance is {$client->balance} US cents<br>\n";

        if ($client->balance < 20) {
            $this->email_maker->msg([
                'to' => 'regygom@gmail.com',
                'subject' => 'Saldo - Recaptcha DeathByCaptcha',
                'msg' => 'DeathByCaptcha saldo abaixo de 20 Centavos: ' . $client->balance . " US Cents"
            ]);

            return false;
        }

        $data = [
            'proxy' => $param['proxy'],
            'proxytype' => 'HTTP',
            'googlekey' => '6LesvAUTAAAAABaj2URTBfe8v4xqRN9xbtt_VbP6',
            'pageurl' => $param['url']
        ];

        // Create a JSON string
        $json = json_encode($data);

        // Put the type and the JSON payload
        $extra = [
            'type' => 4,
            'token_params' => $json, # banner img
        ];

        // Put null the first parameter and add the extra payload
        $captcha = $client->decode(null, $extra);

        if ($captcha) {
            sleep(DeathByCaptcha_Client::DEFAULT_TIMEOUT);

            // Poll for CAPTCHA indexes:
            $text = $client->getText($captcha['captcha']);

            if ($text) {
                echo "CAPTCHA {$captcha['captcha']} solved: {$text}\n";
                return $text;
            } else {
                echo "Falha Recaptcha<br>\n";
                $client->report($captcha['captcha']);
                return false;
            }
        } else {
            echo "Falha decode by Captcha\n";
            return false;
        }
    }

    public function getStatsAberturaAutomatica($data = [])
    {
        $dateInit = '2020-06-01 00:00:00';

        $whereBase = ' em.flag_automatic = 2 AND codigo_motivo NOT LIKE 0 AND numero_pi IS NOT NULL AND numero_pi NOT LIKE "f" AND em.date_insert >= ? ';

        $criadas = DB::select('SELECT count(*) as total FROM envios_manifestacao em WHERE ' . $whereBase, [$dateInit])[0]->total;

        $respondidas = DB::select('SELECT count(*) as total FROM envios_manifestacao em JOIN envios ON envios.id = em.envio_id WHERE ' . $whereBase . ' AND status = "Fechado" AND envios.date_entregue IS NOT NULL ', [$dateInit])[0]->total;

        $aceitas = DB::select('SELECT count(*) as total FROM envios_manifestacao em WHERE ' . $whereBase . ' AND status = "Fechado" AND indica_procedencia = "S" ', [$dateInit])[0]->total;

        $negadas = DB::select('SELECT count(*) as total FROM envios_manifestacao em JOIN envios ON envios.id = em.envio_id WHERE ' . $whereBase . ' AND status = "Fechado" AND indica_procedencia = "N" AND envios.date_entregue IS NULL ', [$dateInit])[0]->total;

        return [
            'criadas' => $criadas,
            'respondidas' => $respondidas,
            'aceitas' => $aceitas,
            'negadas' => $negadas,
        ];
    }

    public function getNegadasTransito()
    {
        return DB::select('SELECT envios.id, envios.date_postagem, envios.date_entregue, em.status, em.objeto, em.str_motivo, ev.descricao as status_etiqueta
        FROM `envios_manifestacao` em 
        join envios on envios.id = em.envio_id
        JOIN etiqueta_status es on es.envio_id = envios.id
        join etiqueta_events ev on ev.id = es.etiqueta_event_id
        WHERE em.status LIKE "Reopen_negada" AND em.date_insert >= "2021-01-01"');
    }

    public function getComment($param)
    {
        $query = DB::table('envios_manifestacao_comments');

        if (isset($param['msg_from'])) {
            $query->where('msg_from', $param['msg_from']);
        }
        if (isset($param['msg_unique_id'])) {
            $query->where('msg_unique_id', $param['msg_unique_id']);
        }
        if (isset($param['id'])) {
            $query->where('id', $param['id']);
        }
        if (isset($param['envio_manifestacao_id'])) {
            $query->where('envio_manifestacao_id', $param['envio_manifestacao_id']);
        }

        return $query->first();
    }

    public function getLastRespMsgId($maniId)
    {
        $query = DB::table('envios_manifestacao_comments')
            ->where('envio_manifestacao_id', $maniId)
            ->where(function ($query) {
                $query->orWhere('msg_from', 'like', '%rjgecomsupinfinite@correios.com.br%')
                    ->orWhere('msg_from', 'like', '%claudiasil@correios.com.br%')
                    ->orWhere('msg_from', 'like', '%franciscoricardo@correios.com.br%')
                    ->orWhere('msg_from', 'like', '%gracieledasilva@correios.com.br%')
                    ->orWhere('msg_from', 'like', '%claraines@correios.com.br%')
                    ->orWhere('msg_from', 'like', '%chrissousa@correios.com.br%');
            })
            ->orderByDesc('id')
            ->first();

        if ($query) {
            return $query->msg_unique_id;
        }

        return false;
    }

    public function getManifestacoes()
    {
        return DB::select('SELECT em.status, em.date_insert, em.numero_pi, em.str_motivo, envios.date_postagem, envios.prazo, envios.seguro, '
                . 'IF( envios.forma_envio = "PACMINI", "ENVIO MINI", envios.forma_envio ) forma_envio , '
                . 'CONCAT(envios.etiqueta_correios, "BR") etiqueta, '
                . 'CONCAT("Dest.: ", envios.destinatario, " - Logradouro: ", envios.logradouro, ", N ", envios.numero, ", Compl: ", '
                . 'IF( envios.complemento IS NOT NULL, envios.complemento , "" ), ", Bairro: ", envios.bairro, " - ", envios.cidade, "/", envios.estado, " CEP: ", envios.CEP ) destinatario, em.str_motivo, em.ultima_resposta, '
                . 'IF( em.date_ultima_resposta = "0000-00-00 00:00:00", "",em.date_ultima_resposta ) as data_ultima_resposta, '
                . 'IF(em.sent_auditor = "S", "Enviado para gerencia", "Aguardando resposta" ) ultima_resposta FROM `envios_manifestacao` em '
                . 'JOIN envios ON envios.id = em.envio_id where (em.status = "Interno" OR em.status = "Prioritario") AND em.date_insert >= "2022-03-21" ORDER BY em.date_insert ASC');
    }
    
}
