<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class Coleta extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'coleta_id',
        'user_id',
        'date_postagem',
    ];

    private $error;
    protected $table = "coletas";

    public function getError()
    {
        return $this->error;
    }

    public function search($param = [])
    {
        $sql = "SELECT * FROM coletas WHERE id = ? OR plp = ?";
        return DB::selectOne($sql, [$param['numero'], $param['numero']]);
    }

    public function get($param = [])
    {
        $query = DB::table('coletas')->where('id', $param['id']);

        if (isset($param['user_id'])) {
            $query->where('user_id', $param['user_id']);
        }

        return $query->first();
    }
    public function getList($param = []) 
    {
        if (true) {
            if (!isset($param['user_id']) && ($this->session->group_code != 'mandabem' && $this->session->group_code != 'franquia')) {
                $this->error = "ID user nao fornecido";
                return false;
            }
    
            if ($this->session->group_code == 'franquia' && !isset($param['franquia_id'])) {
                $this->error = "Franquia ID user nao fornecido";
                return false;
            }
    
            $query = DB::table('coletas')
            ->join('user', 'user.id', '=', 'coletas.user_id')
            ->leftJoin('payment', 'payment.id', '=', 'coletas.id_payment')
            ->where('coletas.plp', 'IS NOT', null);
        
            if (isset($param['get_total'])) {
                $query->selectRaw('count(*) as total');
            } else {
                $query->select('coletas.*', 'user.razao_social', 'user.date_insert as data_cliente_cadastro', 'user.plataform_integration', 'payment.fee as total_paypal');
            
                if (isset($param['user_id'])) {
                    $query->addSelect(DB::raw('(select sum(envios.valor_divergente) from envios where envios.coleta_id = coletas.id) as total_divergente'));
                    $query->addSelect(DB::raw('(select sum(envios.valor_balcao) from envios where envios.coleta_id = coletas.id) as total_balcao'));
                    $query->addSelect(DB::raw('(select sum(envios.valor_correios) from envios where envios.coleta_id = coletas.id) as total_correios'));
                    $query->addSelect(DB::raw('(select sum(envios.valor_total) from envios where envios.coleta_id = coletas.id) as total_cobrado'));
                }
            }
            
            $query->where(function ($query) {
                $query->where('coletas.status', '!=', 'ERROR')
                    ->orWhereNull('coletas.status');
            });
            
            $result = $query->get();

            if ($this->session->group_code == 'mandabem') {
                $query->where('coletas.user_id', '<>', 5);
            }
    
            $param_sql = [];
            if (isset($param['user_id'])) {
                $query->where('coletas.user_id', $param['user_id']);
            }
    
            if (isset($param['filter_status']) && strlen($param['filter_status'])) {
                if ($param['filter_status'] == 'postado') {
                    $query->whereIn('coletas.id', function ($subquery) {
                        $subquery->select('coleta_id')
                            ->from('envios')
                            ->whereRaw('envios.coleta_id = coletas.id AND envios.date_postagem IS NOT NULL');
                    });
                }
                if ($param['filter_status'] == 'pendente') {
                    $query->whereIn('coletas.id', function ($subquery) {
                        $subquery->select('coleta_id')
                            ->from('envios')
                            ->whereRaw('envios.coleta_id = coletas.id AND envios.date_postagem IS NULL');
                    });
                }
            }
    
            if (!isset($param['user_id']) && $this->session->group_code != 'mandabem') {
                exit("Nao permitido");
            }
    
            if (isset($param['filter_date_postagem']) && strlen($param['filter_date_postagem'])) {
                $_date_postagem = $this->dateUtils->toEn($param['filter_date_postagem']);
                $query->whereIn('coletas.id', function ($subquery) use ($_date_postagem) {
                    $subquery->select('coleta_id')
                        ->from('envios')
                        ->whereRaw('envios.coleta_id = coletas.id AND envios.date_postagem >= "' . $_date_postagem . ' 00:00:00" AND envios.date_postagem <= "' . $_date_postagem . ' 23:59:59"');
                });
            }
    
            if (isset($param['filter_date_create']) && strlen($param['filter_date_create'])) {
                $_date_create = $this->date_utils->to_en($param['filter_date_create']);
                $query->whereBetween('coletas.date_insert', [$_date_create . ' 00:00:00', $_date_create . ' 23:59:59']);
            }

            if (isset($param['txt_search']) && strlen($param['txt_search'])) {
                if (is_numeric($param['txt_search'])) {
                    $query->where('coletas.id', (int) $param['txt_search']);
                } else {
                    if (preg_match('/([a-z]){2}([0-9]){9}BR/i', $param['txt_search'])) {
                        $subQuery = DB::table('coletas as cB')
                            ->join('envios as eB', 'eB.coleta_id', '=', 'cB.id')
                            ->where('eB.etiqueta_correios', 'LIKE', substr($param['txt_search'], 0, -2));

                        $query->whereIn('coletas.id', $subQuery->select('cB.id'));
                    } else {
                        $query->where(function ($query) use ($param) {
                            $query->orWhere(function ($query) use ($param) {
                                $query->whereIn('coletas.id', function ($query) use ($param) {
                                    $query->from('coletas as cB')
                                        ->join('envios as eB', 'eB.coleta_id', '=', 'cB.id')
                                        ->where('eB.destinatario', 'LIKE', '%' . preg_replace("/[^0-9a-z]|\n/i", '%', $param['txt_search']) . '%')
                                        ->select('cB.id');
                                });
                            })
                            ->orWhere(function ($query) use ($param) {
                                $query->whereIn('coletas.id', function ($query) use ($param) {
                                    $query->from('coletas as cB')
                                        ->join('envios as eB', 'eB.coleta_id', '=', 'cB.id')
                                        ->where('eB.etiqueta_correios', 'LIKE', '%' . substr($param['txt_search'], 0, -2) . '%')
                                        ->select('cB.id');
                                });
                            })
                            ->orWhere('user.razao_social', 'LIKE', '%' . addslashes($param['txt_search']) . '%');
                        });
                    }
                }
            }
    
            $query->where(function ($query) {
                $query->where('coletas.status', '!=', 'ERROR')
                    ->orWhereNull('coletas.status');
            });
    
            if (isset($param['get_total']) && $param['get_total']) {
                return $query->count();
            }
    
            if ($this->session->group_code == 'mandabem') {
                $query->where('coletas.date_insert', '>=', '2021-06-01');
            }
    
            $limit = isset($param['per_page']) ? $param['per_page'] : 10;
            $start = isset($param['page_start']) ? $param['page_start'] : 0;
    
            $result = $query->select('coletas.*', 'user.razao_social', 'user.date_insert as data_cliente_cadastro', 'user.plataform_integration', 'payment.fee as total_paypal')
                ->orderByDesc('coletas.id')
                ->offset($start)
                ->limit($limit)
                ->get();
    
            return $result;
        }
        $user = null;
        $userModel = app(User::class);
        if (isset($param['user_id'])) {
            $user = $userModel->get($param['user_id']);
        }

        if (!isset($param['user_id']) && ($this->session->group_code != 'mandabem' && $this->session->group_code != 'franquia')) {
            $this->error = "ID user nao fornecido";
            return false;
        }

        if ($this->session->group_code == 'franquia' && !isset($param['franquia_id'])) {
            $this->error = "Franquia ID user nao fornecido";
            return false;
        }

        $query = DB::table('coletas')
            ->select('coletas.*')
            ->select(DB::raw('(SELECT count(id) FROM api_nuvem_shop WHERE api_nuvem_shop.user_id = coletas.user_id) as is_nuvem_shop'))
            ->select('user.name as user_name', 'user.razao_social', 'user.date_insert as data_cliente_cadastro', 'user.plataform_integration')
            ->select(DB::raw('(SELECT sum(envios.valor_balcao) FROM envios WHERE envios.coleta_id = coletas.id) as total_balcao'))
            ->select(DB::raw('(SELECT sum(envios.valor_correios) FROM envios WHERE envios.coleta_id = coletas.id) as total_correios'))
            ->select(DB::raw('(SELECT sum(envios.valor_total) FROM envios WHERE envios.coleta_id = coletas.id) as total_cobrado'))
            ->select(DB::raw('(SELECT sum(envios.valor_divergente) FROM envios WHERE envios.coleta_id = coletas.id) as total_divergente'))
            ->select('payment.fee as total_paypal')
            ->join('user', 'user.id', '=', 'coletas.user_id')
            ->leftJoin('payment', 'payment.id', '=', 'coletas.id_payment');

        if (isset($param['user_id'])) {
            $query->where('coletas.user_id', $param['user_id']);
        }

        if (isset($param['franquia_id'])) {
            $query->where('user.franquia_responsavel', $param['franquia_id'])
                ->whereNull('coletas.status');
        }

        if (isset($param['type'])) {
            $query->where('coletas.type', $param['type']);
        }

        $query->where(function ($query) {
            $query->where('coletas.status', '!=', 'ERROR')
                ->orWhereNull('coletas.status');
        });

        $query->whereNotNull('coletas.plp')
            ->where(DB::raw('(SELECT count(id) FROM envios WHERE envios.coleta_id = coletas.id)'), '>', 0);

        if ($this->session->user_id != 5 && $this->input->server('REMOTE_ADDR') != '177.185.220.191') {
            $query->where('coletas.environment', 'production');
        }

        if ($this->session->user_id == 5) {
            $query->where('coletas.id', '>=', 3550);
        }
        $user = null;
        if (isset($param['user_id'])) {
            $user = $userModel->get($param['user_id']);
        }

        $query = DB::table('coletas')
            ->select('coletas.*')
            ->select(DB::raw('(SELECT count(id) FROM api_nuvem_shop WHERE api_nuvem_shop.user_id = coletas.user_id) as is_nuvem_shop'))
            ->select('user.name as user_name', 'user.razao_social', 'user.date_insert as data_cliente_cadastro', 'user.plataform_integration')
            ->select(DB::raw('(SELECT sum(envios.valor_balcao) FROM envios WHERE envios.coleta_id = coletas.id) as total_balcao'))
            ->select(DB::raw('(SELECT sum(envios.valor_correios) FROM envios WHERE envios.coleta_id = coletas.id) as total_correios'))
            ->select(DB::raw('(SELECT sum(envios.valor_total) FROM envios WHERE envios.coleta_id = coletas.id) as total_cobrado'))
            ->select(DB::raw('(SELECT sum(envios.valor_divergente) FROM envios WHERE envios.coleta_id = coletas.id) as total_divergente'))
            ->select('payment.fee as total_paypal')
            ->join('user', 'user.id', '=', 'coletas.user_id')
            ->leftJoin('payment', 'payment.id', '=', 'coletas.id_payment');

        if (isset($param['filter_status']) && strlen($param['filter_status'])) {
            $query->where(function ($query) use ($param) {
                if ($param['filter_status'] == 'postado') {
                    $query->orWhereRaw('coletas.id IN (SELECT coleta_id FROM envios WHERE envios.coleta_id = coletas.id AND envios.date_postagem IS NOT NULL)');
                }
                if ($param['filter_status'] == 'pendente') {
                    $query->orWhereRaw('coletas.id IN (SELECT coleta_id FROM envios WHERE envios.coleta_id = coletas.id AND envios.date_postagem IS NULL)');
                }
                if ($param['filter_status'] == 'entregue') {
                    $query->orWhereRaw('coletas.id IN (SELECT coleta_id FROM envios WHERE envios.coleta_id = coletas.id AND envios.date_entregue IS NOT NULL)');
                }
                if ($param['filter_status'] == 'nao_entregue') {
                    $query->orWhereRaw('coletas.id IN (SELECT coleta_id FROM envios WHERE envios.coleta_id = coletas.id AND envios.date_entregue IS NULL)');
                }
            });
        }

        if (isset($param['filter_date_postagem']) && strlen($param['filter_date_postagem'])) {
            $_date_postagem = $this->date_utils->to_en($param['filter_date_postagem']);
            $query->orWhereRaw('coletas.id IN (SELECT coleta_id FROM envios WHERE envios.coleta_id = coletas.id AND envios.date_postagem >= ? AND envios.date_postagem <= ?)', [$_date_postagem . ' 00:00:00', $_date_postagem . ' 23:59:59']);
        }

        if (isset($param['filter_date_create']) && strlen($param['filter_date_create'])) {
            $_date_create = $this->date_utils->to_en($param['filter_date_create']);
            $query->orWhereRaw('coletas.date_insert >= ? AND coletas.date_insert <= ?', [$_date_create . ' 00:00:00', $_date_create . ' 23:59:59']);
        }

        if (isset($param['search_num_order']) && (int)($param['search_num_order'])) {
            $query->orWhereRaw('coletas.id IN (SELECT cC.id FROM coletas cC JOIN envios eC ON eC.coleta_id = cC.id WHERE eC.ref_id LIKE ?)', [(int)$param['search_num_order']]);
        }

        if (isset($param['txt_search']) && strlen($param['txt_search'])) {
            if (is_numeric($param['txt_search'])) {
                $query->orWhere('coletas.id', (int)$param['txt_search']);
            } else {
                $query->orWhere(function ($query) use ($param) {
                    $query->orWhereRaw('coletas.id IN (SELECT cB.id FROM coletas cB JOIN envios eB ON eB.coleta_id = cB.id WHERE eB.destinatario LIKE ?)', ['%' . preg_replace("/[^0-9a-z]|\n/i", '%', $param['txt_search']) . '%'])
                        ->orWhereRaw('coletas.id IN (SELECT cB.id FROM coletas cB JOIN envios eB ON eB.coleta_id = cB.id WHERE eB.etiqueta_correios LIKE ?)', ['%' . substr($param['txt_search'], 0, -2) . '%'])
                        ->orWhere('user.razao_social', 'like', '%' . $param['txt_search'] . '%');
                });
            }
        }

        if (isset($param['get_total']) && $param['get_total']) {
            return $query->count();
        } else {
            $limit = isset($param['per_page']) ? $param['per_page'] : 10;
            $start = isset($param['page_start']) ? $param['page_start'] : 0;

            $query->offset($start)->limit($limit);

            $query->orderBy('coletas.id', 'DESC');

            if ($this->input->server('REMOTE_ADDR') == 'xx177.185.220.19') {
                echo $query->toSql();
                exit;
            }

            $coletas = $query->get();

            foreach ($coletas as $coleta) {
                $coleta->status_desc = 'Envios Pendentes';
                if ($coleta->status == '55') {
                    $coleta->status_desc = 'Aguardando Envio';
                }
            }

            return $coletas;
        }

    }

    public function getDivergenciasPagas($param = [])
    {
        $query = DB::table('payment AS a')
            ->when(!isset($param['get_sum_total']), function ($query) {
                return $query->select('b.valor_divergente', 'b.coleta_id', 'b.CEP', DB::raw('CONCAT(b.etiqueta_correios, "BR") as etiqueta'), 'c.date_insert');
            })
            ->when(isset($param['get_sum_total']), function ($query) {
                return $query->select(DB::raw('SUM(b.valor_divergente) as total'));
            })
            ->where('a.id', $param['id_payment'])
            ->join('envios AS b', 'b.payment_divergente_id', '=', 'a.id')
            ->join('coletas AS c', 'c.id', '=', 'b.coleta_id');

        if (isset($param['get_sum_total'])) {
            return $query->first()->total;
        }

        return $query->get();
    }

    public function getDivergenciaPorCredito($param = [])
    {
        return DB::table('payment_credit_discount')
            ->select('pcd.*', DB::raw('(pcd.value * -1) as valor'), 'pcd.coleta_id')
            ->where('ref_coleta_id', $param['coleta_id'])
            ->get();
    }
    public function getCreditosPagos($param = [])
    {
        $query = DB::table('payment_credit_discount AS a')
            ->select(DB::raw('(a.value * -1) as value'), 'a.date', 'a.payment_id', DB::raw('(SELECT description FROM payment WHERE id = a.payment_id) as description'))
            ->where('a.coleta_id', $param['coleta_id']);

        $cred = $query->get();

        foreach ($cred as $c) {
            $c->divergencias_envios = '';

            $envios = DB::table('envios')
                ->where('payment_divergente_id', $c->payment_id)
                ->where('coleta_id', $param['coleta_id'])
                ->get();

            if ($envios->isNotEmpty()) {
                foreach ($envios as $e) {
                    if (!$c->divergencias_envios && $e->valor_divergente == $c->value) {
                        $c->description = 'Cobrança Divergente: ';
                        $c->divergencias_envios = "CEP " . $e->CEP . " - " . $this->date_utils->to_br($e->date_insert, false);
                    }
                }
            }
        }

        if (isset($param['totalizar']) && $param['totalizar']) {
            $totalCreditos = $cred->sum('value');
            return $totalCreditos;
        }

        return $cred;
    }

    public function getEnvios($coletaId, $type = 'NORMAL')
    {
        $query = DB::table('envios')
            ->select('envios.*', 'ec.id as cancelamento_id', 'ec.status as cancelamento_status');

        $query->leftJoin('envios_cancelamento AS ec', 'ec.envio_id', '=', 'envios.id');

        $query->where('coleta_id', $coletaId);

        $envios = $query->get();

        foreach ($envios as $i) {
            $i->etiqueta_status = "Aguardando Postagem";
            $i->etiqueta_status_tipo = "";
            $i->etiqueta_status_numero = "";

            $sqlEtiqueta = 'SELECT ev.descricao as etiqueta_status, ev.tipos as status_tipo, ev.status as status_number ';
            $sqlEtiqueta .= 'FROM etiqueta_status es ';
            $sqlEtiqueta .= 'LEFT JOIN etiqueta_events ev ON ev.id = es.etiqueta_event_id ';
            $sqlEtiqueta .= 'WHERE es.envio_id = ?';

            $et = DB::select($sqlEtiqueta, [$i->id])[0] ?? null;

            if ($et) {
                $i->etiqueta_status = $et->etiqueta_status;
                $i->etiqueta_status_tipo = $et->status_tipo;
                $i->etiqueta_status_numero = $et->status_number;
            }
        }

        return $envios;
    }

    public function saveColeta($data)
    {
        $data['date_insert'] = now();

        if (!DB::table('coletas')->insert($data)) {
            $this->error = "Falha ao criar Coleta, tente novamente mais tarde.";
            return false;
        }

        return DB::getPdo()->lastInsertId();
    }

    public function updatePlp($param = [])
    {
        $coletaId = $param['coleta_id'];
        $plp = $param['plp'];
        $userId = $param['user']['id'];

        $updateData = ['plp' => $plp];
        
        if (!isset($param['is_industrial'])) {
            $teste = Coleta::where('id', '=',$coletaId)
                ->where('user_id','=', $userId)
                ->update($updateData);
            // dd($teste);
        } else {
            
            $agenciaIdFecha = auth()->user()->id; // Supondo que a agência está associada ao usuário autenticado
            $updateData['agencia_id_fecha'] = $agenciaIdFecha;
            
            Coleta::where('id', $coletaId)
                ->update($updateData);
        }

        // SEDEX
        $this->updateEnvios($param['dados_sedex'], $coletaId, $param['etiquetas']['sedex']);
        
        // SEDEX HOJE
        $this->updateEnvios($param['dados_sedex_hj'], $coletaId, $param['etiquetas']['sedex_hj']);

        // SEDEX 12
        $this->updateEnvios($param['dados_sedex_12'], $coletaId, $param['etiquetas']['sedex_12']);

        // PAC
        $this->updateEnvios($param['dados_pac'], $coletaId, $param['etiquetas']['pac']);

        // PAC MINI
        $this->updateEnvios($param['dados_pacmini'], $coletaId, $param['etiquetas']['pacmini']);
       
        return true;
    }

    protected function updateEnvios($dadosEnvios, $coletaId, $etiquetas)
    {
        foreach ($dadosEnvios as $envio) {
            $envioId = $envio->id;
           
            $updateData = [
                'coleta_id' => $coletaId,
                'etiqueta_correios' => $etiquetas[$envio->index],
                'date_update' => date('Y-m-d H:i:s'),//$this->date_utils->get_now(),
            ];
            
            $teste = DB::table('envios')->where('id', '=' ,$envioId)->where('user_id', '=' ,$envio->user_id)->update($updateData);

            $etiq = DB::table('etiqueta_status')->where('envio_id', '=' ,$envioId)->first();
            if (!$etiq) {
                DB::table('etiqueta_status')->insert([
                    'envio_id' => $envioId,
                    'date_insert' => date('Y-m-d H:i:s'),//$this->date_utils->get_now(),
                ]);
            }
        }
    }

    public function updateEtiqueta($coleta, $info)
    {
        $envio = DB::table('envios')->where('etiqueta_correios', '=' ,$info['etiqueta'])->first();

        if ($envio) {
            $rowStatus = DB::table('etiqueta_status')->where('envio_id', '=' ,$envio->id)->first();

            if (!$rowStatus) {
                // Se etiqueta_status não existir, inserir e enviar email
                $this->load->library('email_maker');
                $this->email_maker->msg([
                    'to' => 'regygom@gmail.com',
                    'subject' => 'Atualizando TB etiqueta_status',
                    'msg' => "INFO:<br><pre>" . print_r($info, true) . '</pre>'
                ]);

                DB::table('etiqueta_status')->insert([
                    'envio_id' => $envio->id,
                    'date_insert' => $this->date_utils->getNow()
                ]);
            }
        }

        if ($coleta->type == 'REVERSA') {
            $envioPendente = DB::table('envios')
                ->where('valor_correios', null)
                ->where('coleta_id', $coleta->id)
                ->first();

            if ($envioPendente) {
                $paramUpdateEnvio = [
                    'etiqueta_correios' => substr($info['etiqueta'], 0, -2),
                    'valor_correios' => $info['valor']
                ];

                $user = DB::table('users')->where('id', $envioPendente->user_id)->first();

                // Verificando Divergencia de Valores
                $taxaMandabem = $this->envio_model->getTaxaEnvio([
                    'valor_envio' => $info['valor'],
                    'forma_envio' => $envioPendente->forma_envio,
                    'grupo_taxa_pacmini' => $user->grupo_taxa_pacmini
                ]);

                $newValorTotal = number_format($info['valor'] + $taxaMandabem, 2, '.', '');

                if ($newValorTotal > $envioPendente->valor_total) {
                    $paramUpdateEnvio['valor_divergente'] = number_format(($newValorTotal - $envioPendente->valor_total), 2, '.', '');
                }

                DB::table('envios')
                    ->where('id', $envioPendente->id)
                    ->where('coleta_id', $envioPendente->coleta_id)
                    ->update($paramUpdateEnvio);
            }
        }

        return true;
    }

    public function updateStatus($param)
    {
        $query = DB::table('coletas')->where('type', $param['type'])->where('id', $param['id']);

        if (isset($param['environment'])) {
            $query->where('environment', $param['environment']);
        }

        $paramSql = ['status' => $param['status']];

        if (isset($param['last_search'])) {
            $paramSql['last_search'] = $param['last_search'];
        }

        return $query->update($paramSql);
    }

    public function updateCodPostagem($param)
    {
        return DB::table('coletas')
            ->where('user_id', $param['user_id'])
            ->where('id', $param['id'])
            ->where('plp', null)
            ->where('type', 'REVERSA')
            ->update(['plp' => $param['codigo_postagem']]);
    }

    public function getPendentesConsulta($param)
    {
        if ($param['type'] == 'REVERSA') {
            if (false) {
                // TODO: Adicione a lógica se necessário
            } else {
                $query = DB::table('coletas')
                    ->select('coletas.*')
                    ->join('envios', 'envios.coleta_id', '=', 'coletas.id')
                    ->leftJoin('envios_cancelamento ec', 'ec.envio_id', '=', 'envios.id')
                    ->where('coletas.environment', 'production')
                    ->where('coletas.date_insert', '>=', '2020-06-01')
                    ->where(function ($query) use ($param) {
                        $query->where('coletas.last_search', '<=', now()->subSeconds(3600)->toDateTimeString())
                            ->orWhereNull('coletas.last_search');
                    })
                    ->where('coletas.plp', '!=', null)
                    ->where('coletas.type', 'REVERSA')
                    ->whereNull('ec.id')
                    ->where(function ($query) {
                        $query->whereNotLike('coletas.status', '%Expirado%')
                            ->whereNotLike('coletas.status', '%ERROR%')
                            ->whereNotLike('coletas.status', '%Coletado%')
                            ->whereNotLike('coletas.status', '%Desistencia%')
                            ->orWhereNull('coletas.status')
                            ->orWhere('coletas.status', '55')
                            ->orWhere('coletas.status', '');
                    })
                    ->orderBy('coletas.last_search', 'asc')
                    ->orderBy('coletas.id', 'asc');

                if (isset($param['limit'])) {
                    $query->limit($param['limit']);
                }

                return $query->get();
            }
        }
    }

    public function getPendenteCobranca($param)
    {
        return DB::table('coletas')
            ->where('user_id', $param['user_id'])
            ->whereNull('payment_id')
            ->where('payment_retry', '<', 2)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('type', 'NORMAL')
                        ->where('date_insert', '>', '2019-01-16');
                })->orWhere(function ($query) {
                    $query->where('type', '!=', 'NORMAL')
                        ->where('date_insert', '>', '2018-01-01');
                });
            })
            ->where(DB::raw('(SELECT COUNT(id) FROM envios WHERE envios.coleta_id = coletas.id)'), '>', 0)
            ->get();
    }

    public function updateInfoPayment($info)
    {
        return DB::table('coletas')
            ->where('id', $info['coleta_id'])
            ->where('user_id', $info['user_id'])
            ->whereNull('payment_id')
            ->update([
                'id_payment' => $info['id_payment'],
                'payment_id' => $info['payment_id'],
                'payment_status' => $info['payment_status']
            ]);
    }

    public function getCacheColetas($user_id)
    {
        $coletas = [];

        $_coletas = DB::table('coletas')->where('status', 'PENDENTE_GERAR')->where('user_id', $user_id)->get();

        foreach ($_coletas as $c) {
            $valor_total = 0;
            $envios = $this->getEnvios($c->id); // Certifique-se de que a função getEnvios esteja disponível
            foreach ($envios as $e) {
                $valor_total += $e->valor_total;
            }
            $c->valor_total = $valor_total;
            $coletas[] = [
                'info' => $c,
                'envios' => $envios
            ];
        }

        return $coletas;
    }

    public function updateStatusConsulta($conjunto, $status = null)
    {
        if ($status == 'COMPLETE') {
            DB::table('coletas')
                ->where('id', $conjunto->id)
                ->update(['status' => 'COMPLETE', 'last_search' => now()]);
            return;
        }

        $updateData = ['last_search' => now()];

        if ((now()->timestamp - strtotime($conjunto->date_insert)) > (86400 * 15)) {
            $updateData['status'] = 'COMPLETEX';
        }

        DB::table('coletas')
            ->where('id', $conjunto->id)
            ->update($updateData);
    }

    public function getReversaDestino($id)
    {
        return DB::table('envio_origem eo')
            ->join('envios', 'envios.id', '=', 'eo.envio_id')
            ->where('envios.coleta_id', $id)
            ->first();
    }

    public function supports(): HasMany
    {
        return $this->hasMany(Support::class);
    }
}
