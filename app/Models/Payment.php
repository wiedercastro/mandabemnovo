<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use App\Models\Log;
use App\Models\User;
use App\Models\Envio;
use App\Models\Boleto;
use Carbon\Carbon;
use App\Libraries\FormBuilder;
use App\Libraries\Validation;
use App\Libraries\DateUtils;

class Payment extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'date_insert',
    ];
    protected $table = "payment";
    protected $fields_cobranca;
    protected $fields_credito;

    public function getError()
    {
        return $this->error;
    }

    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }


    public function getFieldCustomer()
    {
        $field = $this->fields_cobranca['cliente'];
        $field['class'] = '';
        unset($field['no_cols']);
        $field['cols'] = [3, 9];
        return $field;
    }

    public function getFieldsCobranca()
    {
        $lista_users = User::all();

        // Tipo
        $tipos = [
            'boleto' => 'Boleto',
            'transferencia' => 'Transferência',
            'mercado_pago' => 'Transferência Mercado Pago',
            'outros' => 'Outros'
        ];

        $list_tipos = collect($tipos)->map(function ($label, $value) {
            return (object)['id' => $value, 'name' => $label];
        })->toArray();

        $this->fields_cobranca = [
            'cliente' => [
                'element_name' => 'user_id',
                'place_holder_default' => 'Todos',
                'class' => 'filter',
                'type' => 'select',
                'opts' => $lista_users,
                'label' => 'Empresa',
                'cols' => [4, 8],
            ],
            'user_id' => [
                'element_id' => 'form-input-add-cobranca',
                'type' => 'select',
                'opts' => $lista_users,
                'label' => 'Cliente',
                'required' => false,
                'cols' => [4, 8],
            ],
            'forma_cobranca' => [
                'label' => 'Forma de Cobrança',
                'required' => true,
                'type' => 'select',
                'opts' => ['paypal' => 'PayPal', 'creditos' => 'Desconto em Créditos'],
            ],
            'value' => ['label' => 'Valor', 'required' => true, 'class' => 'input-money'],
            'description' => ['type' => 'textarea', 'label' => 'Descrição', 'required' => true, 'cols' => [4, 6]],
            'obs' => ['type' => 'textarea', 'label' => 'Observação', 'required' => false, 'cols' => [4, 8]],
        ];

        $this->fields_cobranca['cliente'] = [
            'label' => 'Cliente',
            'type' => 'autocomplete',
            'element_name' => 'cliente',
            'element_keyup_id' => 'cliente_pgto',
            'element_id' => 'cliente_pgto',
            'default_value' => '',
            'description' => '',
            'required' => false,
        ];

        return $this->fields_cobranca;
    }

    public function getFieldsCredito()
    {
        $lista_users = array();

        // Tipo
        $tipos = [
            'boleto' => 'Boleto',
            'transferencia' => 'Transferência',
            'mercado_pago' => 'Transferência Mercado Pago',
            'credito_antecipado' => 'Crédito Antecipado',
            'devolucao' => 'Devolução',
            'outros' => 'Outros',
        ];

        $list_tipos = collect($tipos)->map(function ($label, $value) {
            return (object)['id' => $value, 'name' => $label];
        })->toArray();

        $this->fields_credito = [
            'cliente' => [
                'place_holder_default' => 'Todos',
                'class' => 'filter',
                'no_cols' => true,
                'type' => 'select',
                'opts' => $lista_users,
                'label' => 'Empresa',
                'cols' => [4, 8],
            ],
            'user_id' => [
                'element_id' => 'user_id_credido',
                'type' => 'select',
                'opts' => $lista_users,
                'label' => 'Cliente',
                'required' => true,
                'cols' => [4, 8],
            ],
            'value' => [
                'element_id' => 'value_credito',
                'label' => 'Valor',
                'required' => true,
                'class' => 'input-money',
            ],
            'is_agendamento' => ['type' => 'checkbox', 'label' => 'Transferência agendada?'],
            'description_tipo' => [
                'type' => 'select',
                'opts' => $list_tipos,
                'label' => 'Tipo',
                'required' => true,
            ],
            'description' => [
                'element_id' => 'description_credito',
                'type' => 'textarea',
                'label' => 'Descrição',
                'cols' => [4, 6],
            ],
            'obs' => [
                'element_id' => 'obs_credito',
                'type' => 'textarea',
                'label' => 'Observação',
                'required' => false,
                'cols' => [4, 8],
            ],
        ];

        $this->fields_credito['user_id'] = [
            'label' => 'Cliente',
            'type' => 'autocomplete',
            'element_id' => 'user_id',
            'default_value' => '',
            'description' => '',
        ];

        return $this->fields_credito;
    }

    public function getAuthorization($param = [])
    {
        $sqlParam = [
            'user_id' => $param['user_id'],
            'status' => 'ACTIVE',
        ];

        return self::where($sqlParam)->first();
    }

    public function getList($param = [])
    {
        return $this->getPayments($param);
    }
    //corrigir erro foreach linha 372
    public function getPayments($param = [])
    {
        if (!isset($param['user_id']) && auth()->user()->group_code != 'mandabem') {
            $this->error = "ID user nao fornecido";
            return false;
        }

        $query = self::query();

        if (auth()->user()->user_id == 'x3748') {
            if (isset($param['get_total']) && $param['get_total']) {
                $query->selectRaw('SUM(value) as total');
            } else {
                $query->select([
                    'payment.*',
                    'payment.id as row_id',
                    DB::raw('CONCAT(c.razao_social, "<br><small>\(",c.name,"\)</small>") as ecommerce'),
                    'user_creator.name as user_name_creator',
                    DB::raw('(SELECT id FROM payment_credit_discount WHERE payment_credit_discount.payment_id = payment.id LIMIT 1) as register_used_id'),
                ]);
            }

            $query->leftJoin('user as user_creator', 'payment.user_id_creator', '=', 'user_creator.id')
                ->leftJoin('user', 'user.id', '=', 'payment.user_id')
                ->where(function ($query) {
                    $query->where('payment.registry_type', 'N')
                        ->where('payment.status', 'completed');
                })
                ->orWhere(function ($query) {
                    $query->where('payment.registry_type', 'C')
                        ->where('payment.value', '<', 0)
                        ->where(function ($query) {
                            $query->whereNull('payment.status')
                                ->orWhere('payment.status', 'FINALIZED');
                        });
                })
                ->where('payment.environment', 'production')
                ->where('payment.date', '>=', '2021-02-01 00:00:00')
                ->orderByDesc('payment.date')
                ->limit(20);

            if (isset($param['get_total']) && $param['get_total']) {
                return $query->first()->total;
            }

            $rows = $query->get();
        } else {
            $query->select([
                'payment.*',
                'payment.id as row_id',
                DB::raw('CONCAT(c.razao_social, "<br><small>\(",c.name,"\)</small>") as ecommerce'),
                'user_creator.name as user_name_creator',
                DB::raw('(SELECT id FROM payment_credit_discount WHERE payment_credit_discount.payment_id = payment.id LIMIT 1) as register_used_id'),
            ]);
        }
        $query->leftJoin('user as user_creator', 'payment.user_id_creator', '=', 'user_creator.id')
            ->leftJoin('user as c', 'c.id', '=', 'payment.user_id')
            ->where('if(payment.tipo = "credito_antecipado",payment_assoc_id IS NULL,payment.id IS NOT NULL)');

        $query->where(function ($query) {
            $query->where(function ($query) {
                $query->where('payment.registry_type', 'N')
                    ->where('payment.status', 'completed');
            })->orWhere(function ($query) {
                $query->where('payment.registry_type', 'C')
                    ->where('payment.value', '<', 0)
                    ->where(function ($query) {
                        $query->whereNull('payment.status')
                            ->orWhere('payment.status', 'FINALIZED');
                    });
            });
        });

        if (true) {
            $query->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('payment.registry_type', 'N')
                        ->where('payment.status', 'completed');
                })
                ->orWhere(function ($query) {
                    $query->where('payment.registry_type', 'C')
                        ->where('payment.value', '<', 0)
                        ->where(function ($query) {
                            $query->whereNull('payment.status')
                                ->orWhere('payment.status', 'FINALIZED');
                        });
                });
            });
        } else {
            $query->where(function ($query) {
                $query->whereNotNull('payment.status')
                    ->where('payment.status', 'NOT LIKE', 'xcompleted')
                    ->where('payment.status', 'NOT LIKE', 'Incoming JSON request does n%');
            });
        }
        
        if (request()->ip() != '177.185.220.131') {
            if (auth()->user()->user_id != 5) {
                $query->where('payment.environment', 'production');
            }
        }
        
        if (!isset($param['filter_periodo']) || $param['filter_periodo'] == 'custom') {
            if (isset($param['filter_date_start']) && $param['filter_date_start']) {
                $date_start = now()->parse($param['filter_date_start'])->startOfDay();
                $query->where('payment.date', '>=', $date_start);
            }
            if (isset($param['filter_date_end']) && $param['filter_date_end']) {
                $date_end = now()->parse($param['filter_date_end'])->endOfDay();
                $query->where('payment.date', '<=', $date_end);
            }
        }
        
        if (isset($param['user_id']) && $param['user_id'] != 'mandabem') {
            $query->where('payment.user_id', $param['user_id']);
        }
        
        if (isset($param['filter_cliente']) && (int) $param['filter_cliente']) {
            $query->where('payment.user_id', $param['filter_cliente']);
        }
        if (isset($param['filter_periodo'])) {
            if ($param['filter_periodo'] == 'current_month') {
                $query->where('payment.date', '>=', now()->firstOfMonth());
            } elseif ($param['filter_periodo'] == 'current_week') {
                $day = now()->dayOfWeek;
                $week_start = now()->startOfWeek();
                $week_end = now()->endOfWeek();
        
                $query->where('payment.date', '>=', $week_start)
                    ->where('payment.date', '<=', $week_end);
            } elseif ($param['filter_periodo'] == 'current_year') {
                $query->where('payment.date', '>=', now()->firstOfYear())
                    ->where('payment.date', '<=', now()->lastOfYear());
            } elseif ($param['filter_periodo'] == 'last_year') {
                $query->where('payment.date', '>=', now()->startOfYear()->subYear())
                    ->where('payment.date', '<=', now()->endOfYear()->subYear());
            }
        }
        if (isset($param['filter_type']) && $param['filter_type']) {
            if ($param['filter_type'] == 'cobranca') {
                $query->whereNotNull('payment.payment_id');
            } elseif ($param['filter_type'] == 'transferencia_boleto') {
                $query->where(function ($query) {
                    $query->where('payment.tipo', 'boleto')
                        ->orWhere('payment.tipo', 'transferencia');
                });
            } else {
                $query->where('payment.tipo', $param['filter_type']);
            }
        }
        
        if (isset($param['get_total']) && $param['get_total']) {
            return $query->count();
        }
        
        $limit = isset($param['per_page']) ? $param['per_page'] : 10;
        $start = isset($param['page_start']) ? $param['page_start'] : 0;
        
        // Quando exportar não limitar em paginação
        if (!isset($param['export']) || !$param['export']) {
            $query->offset($start)
                ->limit($limit)
                ->orderByDesc('payment.date');
        } else {
            $query->orderBy('payment.date');
        } 
        
        $rows = $query->get();

        foreach ($rows as $i) {

            if ($i['tipo'] == 'mercado_pago') {
                $i['tipo'] = 'Mercado Pago';
            }
        
            if (auth()->user()->user_id == '3748') {
                $i['descontos'] = DB::select("SELECT * FROM `payment_credit_discount` WHERE `payment_id` = ? ORDER BY `id` DESC", [$i['id']]);
            }
        
            if (strlen($i['description'])) {
                continue;
            }
        
            // Verificar coletas relacionadas
            $coletas = DB::table('coletas')->where('payment_id', $i['payment_id'])->get();
        
            $str_desc = '';
            foreach ($coletas as $c) {
                $str_desc .= $c->id . ',';
            }
        
            if (strlen($str_desc)) {
                $str_desc = substr($str_desc, 0, -1);
            }
        
            if (!$i['description']) {
                $i['description'] = "Conjunto de etiquetas: " . $str_desc;
                DB::table('payment')->where('id', $i['row_id'])->update(['description' => $i['description']]);
            }
        
            $i['descricao'] = $i['description'];
            $i['coleta_id'] = $str_desc;
        }
        
        return $rows;
    }

    public function updateCredits2($data)
    {
        $infoDivergencia = $data['info_divergencia'];
        $infoSaldoColeta = $data['info_saldo_coleta'];
        $infoSaldoDivergencia = $data['info_saldo_divergencia'];
        $coletaId = $data['coleta_id'];
        $userId = $data['user_id'];
        
        if ($infoSaldoColeta) {
            foreach ($infoSaldoColeta['itens'] as $paymentId => $info) {
                if ($info['baixar']) {
                    DB::table('payment')
                        ->where('id','=', $paymentId)
                        ->whereNull('status')
                        ->update(['status' => 'FINALIZED']);
                }

                DB::table('payment_credit_discount')->insert([
                    'payment_id' => $paymentId,
                    'coleta_id' => $coletaId,
                    'value' => -$info['valor_descontar'],
                    'date' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        if ($infoSaldoDivergencia) {
            foreach ($infoSaldoDivergencia['divergencias'] as $divergencia) {
                // Obtemos saldo atual a cada interação
                $saldo = $this->payment_model->getCreditoSaldo(['user_id' => $userId, 'valor_total' => $divergencia['valor_divergente']]);

                // Nao ha mais saldo
                if (!$saldo) {
                    break;
                }

                // Saldo insulficiente
                if ($saldo['valor_descontar'] < $divergencia['valor_divergente']) {
                    break;
                }

                foreach ($saldo['itens'] as $paymentId => $info) {
                    if ($info['baixar']) {
                        DB::table('payment')
                            ->where('id','=', $paymentId)
                            ->whereNull('status')
                            ->update(['status' => 'FINALIZED']);
                    }

                    DB::table('payment_credit_discount')->insert([
                        'payment_id' => $paymentId,
                        'coleta_id' => $divergencia['coleta_id'],
                        'ref_coleta_id' => $coletaId,
                        'value' => -$info['valor_descontar'],
                        'date' =>date('Y-m-d H:i:s'),
                    ]);
                    $enviotaModel = app(Envio::class);
                    $enviotaModel->update_indo_pagto_divergencia(['id' => $paymentId], [['id' => $divergencia['id']]]);
                }
            }
        }
    }

    public function updateCredits($saldo, $coletaId, $userId, $infoDivergencia)
    {
        $envioModel = new Envio();
        if (!$saldo) {
            return;
        }

        foreach ($saldo['itens'] as $paymentId => $info) {
            if ($info['baixar']) {
                DB::table('payment')
                    ->where('id', $paymentId)
                    ->whereNull('status')
                    ->update(['status' => 'FINALIZED']);
            }

            DB::table('payment_credit_discount')->insert([
                'payment_id' => $paymentId,
                'coleta_id' => $coletaId,
                'value' => -$info['valor_descontar'],
                'date' => now(),
            ]);
        }

        if (true) {
            if ($infoDivergencia) {
                foreach ($infoDivergencia as $divergencia) {
                    // Obtemos saldo atual a cada interação
                    $saldo = $this->payment_model->getCreditoSaldo(['user_id' => $userId, 'valor_total' => $divergencia['valor_divergente']]);

                    // Nao ha mais saldo
                    if (!$saldo) {
                        break;
                    }

                    // Saldo insulficiente
                    if ($saldo['valor_descontar'] < $divergencia['valor_divergente']) {
                        break;
                    }

                    foreach ($saldo['itens'] as $paymentId => $info) {
                        if ($info['baixar']) {
                            DB::table('payment')
                                ->where('id', $paymentId)
                                ->whereNull('status')
                                ->update(['status' => 'FINALIZED']);
                        }

                        DB::table('payment_credit_discount')->insert([
                            'payment_id' => $paymentId,
                            'coleta_id' => $divergencia['coleta_id'],
                            'ref_coleta_id' => $coletaId,
                            'value' => -$info['valor_descontar'],
                            'date' => now(),
                        ]);

                        $envioModel->updateIndoPagtoDivergencia(['id' => $paymentId], [['id' => $divergencia['id']]]);
                    }
                }
                return;
            }

            // Obtendo divergências para desconto via crédito
            $infoAddDivergencia = $envioModel->getDivergencias(['user_id' => $userId]);

            if ($infoAddDivergencia) {
                // Verificar cada divergência
                foreach ($infoAddDivergencia as $divergencia) {
                    // Obtemos saldo atual a cada interação
                    $saldo = $this->payment_model->getCreditoSaldo(['user_id' => $userId, 'valor_total' => $divergencia['valor_divergente']]);

                    // Nao ha mais saldo
                    if (!$saldo) {
                        break;
                    }

                    // Saldo insulficiente
                    if ($saldo['valor_descontar'] < $divergencia['valor_divergente']) {
                        break;
                    }

                    foreach ($saldo['itens'] as $paymentId => $info) {
                        if ($info['baixar']) {
                            DB::table('payment')
                                ->where('id', $paymentId)
                                ->whereNull('status')
                                ->update(['status' => 'FINALIZED']);
                        }

                        DB::table('payment_credit_discount')->insert([
                            'payment_id' => $paymentId,
                            'coleta_id' => $divergencia['coleta_id'],
                            'value' => -$info['valor_descontar'],
                            'date' => now(),
                        ]);

                        $envioModel->updateIndoPagtoDivergencia(['id' => $paymentId], [['id' => $divergencia['id']]]);
                    }
                }
            }
        }
    }

    public function getCreditoSaldo($param = [])
    {
        if (true) {
            $valorTotal = isset($param['valor_total']) ? $param['valor_total'] : 0;
            $saldoTotalValue = 0;
            $valorDescontar = 0;
            $descontado = 0;

            $creditos = DB::table('payment')
                ->select('*')
                ->where('user_id', $param['user_id'])
                ->whereNull('status')
                ->where('value', '<', 0)
                ->get();

            $info = ['saldo_total_value' => 0, 'valor_descontar' => 0, 'itens' => []];

            foreach ($creditos as $credito) {
                $discounted = DB::table('payment_credit_discount')
                    ->where('payment_id', $credito->id)
                    ->sum('value');

                $saldoValue = abs($credito->value) - abs($discounted);

                if ($saldoValue <= 0) {
                    DB::table('payment')
                        ->where('id', $credito->id)
                        ->update(['status' => 'FINALIZED']);
                    continue;
                }

                $info['saldo_total_value'] += $saldoValue;

                if ($valorTotal > 0) {
                    $resto = (float) number_format($valorTotal - $info['saldo_total_value'], 2, '.', '');

                    if ($resto > 0) {
                        $descontado += $saldoValue;
                    }

                    $info['itens'][$credito->id] = [
                        'value' => $credito->value,
                        'saldo' => $saldoValue,
                        'valor_descontar' => $resto <= 0 ? ($valorTotal - $descontado) : $saldoValue,
                        'baixar' => $resto < 0 ? false : true,
                    ];

                    if ($valorTotal <= $info['saldo_total_value']) {
                        $info['valor_descontar'] = $valorTotal;
                        return $info;
                    }
                }
            }

            if ($info['saldo_total_value'] > 0 && $valorTotal > $info['saldo_total_value']) {
                $info['valor_descontar'] = $info['saldo_total_value'];
                return $info;
            }

            if ($info['saldo_total_value'] <= 0 || $info['valor_descontar'] <= 0) {
                if (!isset($param['valor_total'])) {
                    return $info;
                } else {
                    return false;
                }
            }

            return $info;
        } else {
            // Retirando créditos já descontados
            DB::update(
                "UPDATE payment p SET p.status = ? WHERE p.value < 0 AND (SELECT SUM(pcd.value) * -1 FROM payment_credit_discount pcd WHERE pcd.payment_id = p.id) >= (p.value * -1)",
                ['FINALIZED']
            );

            // Somatório de créditos disponíveis por usuário
            $creditos = DB::table('payment')
                ->select('*')
                ->where('user_id', $param['user_id'])
                ->whereNull('status')
                ->where('value', '<', 0)
                ->get();

            $info = ['saldo_total_value' => 0];

            foreach ($creditos as $credito) {
                $discounted = DB::table('payment_credit_discount')
                    ->where('payment_id', $credito->id)
                    ->sum('value');

                $saldoValue = $credito->value - $discounted;

                $info['saldo_total_value'] += $saldoValue;
            }

            // Próximo crédito a descontar
            $credito = DB::table('payment')
                ->select('*')
                ->where('user_id', $param['user_id'])
                ->whereNull('status')
                ->where('value', '<', 0)
                ->orderBy('date')
                ->first();

            if ($credito) {
                $discounted = DB::table('payment_credit_discount')
                    ->where('payment_id', $credito->id)
                    ->sum('value');

                $info['credito'] = (array) $credito;
                $info['credito']['valor_descontar'] = $credito->value - $discounted;
                $info['saldo_total_value'] = abs($info['saldo_total_value']);
            }

            return $info;
        }
    }

    public function saveCredito($post = [])
    {
        $boletoModel = app(Boleto::class);
        $boletos_expirados = $boletoModel->getExpirados('user_id', $post['user_id'])->get();

        if ($boletos_expirados->count() > 0) {
            $er = '<h5>Cliente possui boletos expirados com liberação de crédito. Pagamento necessário.</h5><br>';
            
            foreach ($boletos_expirados as $b) {
                $er .= '** Boleto valor ' . number_format($b->value, 2, ',', '.') . " gerado em " . Carbon::parse($b->geracao) . " liberado em " . Carbon::parse($b->liberacao) . '<br><br>';
            }

            $this->error = $er;
            return false;
        }

        unset($post['is_credito']);
        unset($post['is_cancelamento']);
        $formBuilder = new FormBuilder();
        $data_post = $formBuilder->validadeData($this->getFieldsCredito(), $post);

        if (!$data_post) {
            return false;
        } else {
            $date = now();
            $desc = 'Credito';

            switch ($post['description_tipo']) {
                case 'boleto':
                    $desc = 'Boleto confirmado em ' . $date;
                    break;
                case 'credito':
                    $desc = strlen($post['description']) ? $post['description'] : ' Crédito concedido em  ' . $date;
                    break;
                case 'transferencia':
                    $desc = strlen($post['description']) ? $post['description'] : ' Crédito concedido por transferência em  ' . $date;
                    break;
                case 'mercado_pago':
                    $desc = strlen($post['description']) ? $post['description'] : ' Crédito concedido por transferência (Mercado Pago) em  ' . $date;
                    break;
                default:
                    $desc = strlen($post['description']) ? $post['description'] : ' Crédito concedido em  ' . $date;
                    break;
            }

            $post['value'] = number_format(preg_replace('/,/', '.', $post['value']), '2', '.', '');
            $user = User::find($post['user_id']);

            $date_agendamento = (now() >= '2020-11-20' && now() < '2020-11-23') ?
                '2020-11-23 12:00:00' :
                Carbon::now()->addDays(1)->toDateTimeString();

            $data_credito = [
                'user_id' => $user->id,
                'payment_id' => isset($post['payment_id']) ? $post['payment_id'] : null,
                'user_id_creator' => isset($post['user_id_creator']) ? $post['user_id_creator'] : 0,
                'environment' => $user->environment,
                'registry_type' => 'C',
                'tipo' => $post['description_tipo'],
                'is_agendamento' => (isset($post['is_agendamento']) && (int)$post['is_agendamento']) ? 1 : null,
                'description' => $desc,
                'obs' => $post['obs'],
                'value' => -$post['value'],
                'gatetway' => isset($post['gateway']) ? $post['gateway'] : null,
                'date' => ((isset($post['is_agendamento']) && (int)$post['is_agendamento']) ? $date_agendamento : now()),
                'date_insert' => now(),
            ];

            if (isset($post['transferencia_id']) && (int)$post['transferencia_id']) {
                $data_credito['transferencia_id'] = (int)$post['transferencia_id'];
            }

            if (isset($post['flag_assoc_payment']) && (int)$post['flag_assoc_payment']) {
                $data_credito['flag_assoc_payment'] = 1;
            }

            $ins = DB::table('payment')->insertGetId($data_credito);

            if (!$ins) {
                $this->error = "Falha ao inserir Crédito, tente novamente mais tarde.";
                return false;
            }

            return $ins;
        }
    }

    public function addPayment($param = [], $data_divergencia = [])
    {
        if ($data_divergencia && count($data_divergencia)) {
            foreach ($data_divergencia as $d) {
                $param['value'] = number_format($param['value'] + $d['valor_divergente'], 2, '.', '');
            }
        }

        $inserted = DB::table('payments')->insertGetId($param);

        return $inserted;
    }

    public function savePayment($post)
    {
        $formBuilder = new FormBuilder();
        $data_post = $formBuilder->validadeData($this->getFieldsCobranca(), $post);
        if (!$data_post) {
            $this->error = $formBuilder->getErrorValidation();
            return FALSE;
        } else {
            $user = User::find($post['user_id']);

            // trocar quando houver a librarie 
            if ($user->environment == 'test') {
                $paypalPayment = app()->make('PaypalPayment', ['environment' => 'test']);
            } else {
                $paypalPayment = app()->make('PaypalPayment');
            }

            $valor_cobrar = number_format(preg_replace('/,/', '.', $post['value']), '2', '.', '');

            $BA_ = $this->getAuthorization(['user_id' => $post['user_id']]);
            if (!$BA_) {
                $this->error = "TOKEN do Acordo Cobrança para o cliente " . $user->name . " invalido.";
                return false;
            }

            $str_coletas_id = '';
            if (isset($post['coletas_id']) && count($post['coletas_id'])) {
                $str_coletas_id = implode(',', $post['coletas_id']);
            }

            $data_payment = [
                'user_id' => $user->id,
                'user_id_creator' => $post['user_id_creator'] ?? 0,
                'description' => $post['description'],
                'obs' => $post['obs'],
                'value' => $valor_cobrar,
                'date' => now(),
            ];

            DB::table('payments')->insert($data_payment);
            $payment_id = DB::getPdo()->lastInsertId();

            $error = [];
            $param = [
                'billing_agreement_id' => $BA_->billing_agreement_id,
                'valor_total' => $valor_cobrar,
                'invoice_id' => $payment_id,
                'name' => "Cobranca Avulsa $payment_id" . (strlen($str_coletas_id) ? " Coleta(s): " . $str_coletas_id : ''),
                'description' => $post['description'],
                'user_id' => $user->id,
            ];
            //trocar quando houver a librarie
            $paymentResult = $paypalPayment->createBilPayment($param);

            if (!$paymentResult) {
                if ($paypalPayment->get_error() == 'INSTRUMENT_DECLINED') {
                    $error[] = 'Pagamento Negado.';
                } else {
                    $error[] = $paypalPayment->get_error();
                }
            }

            if (!isset($paymentResult['id']) || !isset($paymentResult['transactions'][0]['related_resources'][0]['sale']['id'])) {
                $error[] = 'Falha, tente novamente mais tarde';
            }

            if (!$error) {
                $PAYPAL_ID = $paymentResult['transactions'][0]['related_resources'][0]['sale']['id'];
                $PAYPAL_STATUS = $paymentResult['transactions'][0]['related_resources'][0]['sale']['state'];

                DB::table('payment')
                    ->where('id', $payment_id)
                    ->update([
                        'payment_id' => $PAYPAL_ID,
                        'status' => $PAYPAL_STATUS,
                        'environment' => $paypalPayment->get_environment(),
                    ]);

                if (strlen($str_coletas_id)) {
                    DB::table('coletas')
                        ->whereIn('id', $post['coletas_id'])
                        ->where('user_id', $user->id)
                        ->whereNull('payment_id')
                        ->update([
                            'payment_id' => $PAYPAL_ID,
                            'id_payment' => $payment_id,
                            'date_update' => now(),
                        ]);

                    return true;
                }

                return true;
            } else {
                $this->error = implode("<br>", $error);
                return false;
            }
        }
    }

    public function cancelToken($auth)
    {
        return DB::table('paypal_authorizations')
            ->where('id', $auth->id)
            ->where('user_id', $auth->user_id)
            ->where('billing_agreement_id', $auth->billing_agreement_id)
            ->update(['status' => 'CANCEL']);
    }

    public function updateTokenAgree($param = [])
    {
        $oldToken = DB::table('paypal_authorizations a')
            ->where('user_id', $param['user_id'])
            ->where('status', 'ACTIVE')
            ->where('billing_agreement_id', $param['agree']['id'])
            ->first();

        if ($oldToken) {
            // Já autorizado
            return true;
        }

        $linkCancel = '';
        foreach ($param['agree']['links'] as $link) {
            if ($link['rel'] == 'cancel') {
                $linkCancel = $link['href'];
                break;
            }
        }

        $paramInsert = [
            'user_id' => $param['user_id'],
            'payer_id' => $param['agree']['payer']['payer_info']['payer_id'],
            'billing_agreement_id' => $param['agree']['id'],
            'status' => $param['agree']['state'],
            'link_cancel' => $linkCancel,
            'email' => $param['agree']['payer']['payer_info']['email'],
            'date_created' => now(),
        ];

        return DB::table('paypal_authorizations')->insert($paramInsert);
    }

    public function updateInfoReturn($param = [])
    {
        $paramUpdate = ['status' => $param['paypal_status']];

        if (isset($param['paypal_id'])) {
            $paramUpdate['payment_id'] = $param['paypal_id'];
        }

        if (isset($param['paypal_fee'])) {
            $paramUpdate['fee'] = $param['paypal_fee'];
        }

        DB::table('payment')
            ->whereNull('payment_id')
            ->where('id', $param['id'])
            ->update($paramUpdate);
    }

    public function get($id)
    {
        return DB::table('payment')->where('id', $id)->first();
    }

    public function getAdicionalPaypal($date = null)
    {
        if (!$date || $date <= '2019-10-29 20:30:00') {
            return 0.40;
        } else {
            // 2.65% + 0,25 centavos
            return 0.25;
        }
    }

    public function getPercTaxaPaypal($date = null)
    {
        if (!$date || $date <= '2019-10-29 20:30:00') {
            return 2.87 / 100;
        } else {
            // 2.65% + 0,25 centavos
            return 2.65 / 100;
        }
    }

    public function getPaypalTaxa($param = [])
    {
        $valor = $param['value'];
        $date = isset($param['date']) ? $param['date'] : now();

        if ($valor == 0) {
            return number_format(0, 2, '.', '');
        }

        return number_format(($valor * $this->getPercTaxaPaypal($date)) + $this->getAdicionalPaypal($date), 2, '.', '');
    }

    public function removeCredit($id)
    {
        $used = DB::table('payment_credit_discount')->where('payment_id', $id)->first();

        if ($used) {
            $this->error = "Registro não pode ser apagado, Crédito já foi usado";
            return false;
        }

        $payment = $this->get($id);

        if ($payment->boleto_id) {
            $credAntecip = DB::table('payment')
                ->where('tipo', 'credito_antecipado')
                ->where('boleto_id', $payment->boleto_id)
                ->first();

            if ($credAntecip) {
                $this->error = "Registro não pode ser apagado, pois possui associação à crédito antecipado.";
                return false;
            }
        }

        if ($payment->tipo == 'credito_pix') {
            // Retirando transferencia da inserção
            if ($payment->transferencia_id) {
                DB::table('transferencia')
                    ->where('id', $payment->transferencia_id)
                    ->where('status', 'liberado')
                    ->update(['status' => 'delete-pos-credito']);
            }

            return DB::table('payment')
                ->where('id', $id)
                ->where('value', '<', 0)
                ->delete();
        } else {
            // Retirando transferencia da inserção
            if ($payment->transferencia_id) {
                DB::table('transferencia')
                    ->where('id', $payment->transferencia_id)
                    ->where('status', 'liberado')
                    ->delete();
            }

            return DB::table('payment')
                ->where('id', $id)
                ->where('value', '<', 0)
                ->where(function ($query) {
                    $query->where('gateway', 'BOLETO')
                        ->orWhereNull('payment_id');
                })
                ->delete();
        }
    }

    public function getEntradasPagtos($data)
    {
        $dateUtils = new DateUtils();
        $dateStart = $dateUtils->toEn($data['date_start']) . ' 00:00:00';
        $dateEnd = $dateUtils->toEn($data['date_end']) . ' 23:59:59';

        $list = DB::table('payment')
            ->select('payment.*', 'user.razao_social as cliente', 'user.tipo_cliente', 'user.cpf', 'user.cnpj', 'ec.id as cancelamento_id')
            ->leftJoin('user', 'user.id', '=', 'payment.user_id')
            ->leftJoin('envios_cancelamento as ec', 'ec.payment_id', '=', 'payment.id')
            ->where('payment.date', '>=', $dateStart)
            ->where('payment.date', '<=', $dateEnd)
            ->where('payment.tipo', 'NOT LIKE', 'credito')
            ->whereNull('ec.id')
            ->where(function ($query) {
                $query->whereNull('payment.status')
                    ->orWhere('payment.status', 'completed')
                    ->orWhere('payment.status', 'FINALIZED');
            })
            ->orderBy('user.razao_social')
            ->get();

        $info = [];

        foreach ($list as $i) {
            $doc = null;

            if ($i->transferencia_id) {
                $transf = $this->getTransf(['id' => $i->transferencia_id, 'group' => 'mandabem', 'banco' => 'neon']);

                if ($transf) {
                    $doc = $transf->documento;
                }
            }

            if (!isset($info[$i->user_id]['saldo'])) {
                $saldo = $this->getCreditoSaldo(['user_id' => $i->user_id]);

                if (!$saldo) {
                    $info[$i->user_id]['saldo'] = 0;
                } else {
                    $info[$i->user_id]['saldo'] = $saldo['saldo_total_value'];
                }
            }

            $info[$i->user_id]['divergencias'] = $i->total_divergente;

            $info[$i->user_id]['nome'] = $i->cliente;

            if ($doc) {
                $info[$i->user_id]['documento'] = $doc;
            } else {
                if ($i->tipo_cliente == 'PF') {
                    $info[$i->user_id]['documento'] = $i->cpf;
                }

                if ($i->tipo_cliente == 'PJ') {
                    $info[$i->user_id]['documento'] = $i->cnpj;
                }
            }

            if (!isset($info[$i->user_id]['transacoes'][$i->tipo]['total'])) {
                $info[$i->user_id]['transacoes'][$i->tipo]['total'] = 0;
            }

            $i->forma_pagamento = $i->tipo;

            if ($i->tipo == 'mercado_pago') {
                $i->forma_pagamento = 'Transferência (MP)';
            }

            if ($i->tipo == 'transferencia') {
                $i->forma_pagamento = 'Transferência';
            }

            if ($i->tipo == 'cobranca') {
                $i->forma_pagamento = 'PayPal';
            }

            if ($i->tipo == 'boleto') {
                $i->forma_pagamento = 'Boleto';
            }
            $i->date = $dateUtils->toBr($i->date);
            $i->value = abs($i->value);

            $info[$i->user_id]['transacoes'][$i->tipo]['total'] += abs($i->value);
            $info[$i->user_id]['transacoes'][$i->tipo]['itens'][] = $i;

            if (!isset($info[$i->user_id]['valor_carregado'])) {
                $info[$i->user_id]['valor_carregado'] = 0;
            }

            $info[$i->user_id]['valor_carregado'] += abs($i->value);
        }

        return $info;
    }

    public function isUsed($id)
    {
        $total = DB::table('payment_credit_discount')
            ->select(DB::raw('(SUM(value) * -1) as total'))
            ->where('payment_id', $id)
            ->first()->total;

        return $total != 0;
    }

    public function saveTransferencia($request)
    {
        $validation = new Validation();

        $doc = $request->banco == 'pixiugu' ? $request->doc : preg_replace('/[0-9^]/', '', $request->doc);

        // Banco neon, Necessário CPF ou CNPJ
        if ($request->banco == 'neon') {
            $validCpf = $validation->validCpf($doc);
            $validCnpj = $validation->validCnpj($doc);

            if (!$validCpf && !$validCnpj) {
                $this->error = "Documento fornecido para o pagante é inválido";
                return false;
            }
        }

        $dataSave = [
            'user_id' => $request->user_id,
            'banco' => $request->banco,
            'documento' => $doc,
            'anexo' => $request->anexo,
            'valor_solicitado' => $request->valor,
            'date_insert' => now(),
            'date_update' => now(),
        ];

        $transferencia = DB::table('transferencia')->insertGetId($dataSave);

        if (!$transferencia) {
            return response()->json(['error' => 'Falha ao inserir Transferência, tente novamente mais tarde.'], 500);
        }

        if ($request->banco == 'pixiugu' && $request->has('creditos_antecipados') && false) {
            foreach ($request->creditos_antecipados as $ca) {
                DB::table('transferencia_cred_antecipado')->insert([
                    'transferencia_id' => $transferencia,
                    'payment_id' => $ca,
                    'date' => now(),
                ]);
            }
        }

        return response()->json(['id' => $transferencia], 201);
    }

    public function getTransf($data)
    {
        if (isset($data['type']) && $data['type'] == 'pendentes') {
            if (auth()->user()->group_code == 'mandabem') {
                $query = DB::table('transferencia')
                    ->select('transferencia.*')
                    ->join('user', 'user.id', '=', 'transferencia.user_id')
                    ->leftJoin('payment', 'payment.transferencia_id', '=', 'transferencia.id')
                    ->leftJoin('user as user_creator', 'user_creator.id', '=', 'payment.user_id_creator')
                    ->whereNotNull('user.id');

                if (isset($data['user_id'])) {
                    $query->where('transferencia.user_id', $data['user_id']);
                } else {
                    if (auth()->user()->id == '3748') {
                        $query->where(function ($query) {
                            $query->where('transferencia.banco', '!=', 'pixiugu')
                                ->orWhere(function ($query) {
                                    $query->where('transferencia.status', 'liberado')
                                        ->where('transferencia.date_liberacao', '>=', now()->subHour());
                                });
                        });
                    } else {
                        $query->where('transferencia.banco', 'NOT LIKE', 'pix%');
                    }
                }

                $query->where(function ($query) {
                    $query->whereNull('transferencia.status')
                        ->orWhere('transferencia.status', 'completed')
                        ->orWhere('transferencia.status', 'FINALIZED');
                });

                $query->orderBy('transferencia.status', 'ASC')
                    ->orderBy('transferencia.date_liberacao', 'DESC')
                    ->orderBy('transferencia.id', 'DESC');

                return $query->get();
            } else {
                if (!(int)$data['user_id']) {
                    return false;
                }

                $query = DB::table('transferencia')
                    ->select('transferencia.*')
                    ->leftJoin('payment', 'payment.transferencia_id', '=', 'transferencia.id')
                    ->where('transferencia.status', '=', null)
                    ->where('payment.id', '=', null);

                if (isset($data['user_id'])) {
                    $query->where('transferencia.user_id', $data['user_id']);
                }

                $query->orderBy('transferencia.id', 'desc');

                return $query->get();
            }
        }

        if (isset($data['id'])) {
            $query = DB::table('transferencia')
                ->select('transferencia.*')
                ->leftJoin('payment', 'payment.transferencia_id', '=', 'transferencia.id')
                ->where('transferencia.id', $data['id']);

            if (auth()->user()->group_code != 'mandabem' && !isset($data['group'])) {
                $query->where('transferencia.user_id', $data['user_id']);
            }

            $query->orderBy('transferencia.id', 'desc');

            return $query->first();
        }
    }

    public function removeComprovTransf($id)
    {
        $transf = DB::table('transferencias')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($transf) {
            $path = '/home/sysuser/files/transf_comprovante/' . $transf->anexo;

            if (is_file($path)) {
                @unlink($path);
            }

            DB::table('transferencias')
                ->where('id', $id)
                ->update(['anexo' => null]);
        }
    }

    public function liberarTransferencia($param)
    {
        $transferenciaOld = $param['transferencia'];
        $valor = $param['valor'];
        $creditosAntecipados = isset($param['creditos_antecipados']) ? $param['creditos_antecipados'] : [];

        $transferencia = DB::table('transferencia')->where('id', $transferenciaOld->id)->first();

        if ($transferencia->credito || $transferencia->status) {
            return true;
        }

        $value = preg_replace('/,/', '', $valor);

        $date = now();
        $desc = '';
        $tipo = '';

        if ($transferencia->banco == 'neon') {
            $desc = 'Crédito concedido por transferência (Banco Neon) em  ' . $date->format('d/m/Y H:i:s');
            $tipo = 'credito';
        } elseif ($transferencia->banco == 'itau') {
            $desc = 'Crédito concedido por transferência (Banco Itau) em  ' . $date->format('d/m/Y H:i:s');
            $tipo = 'credito';
        } elseif ($transferencia->banco == 'mercadopago') {
            $tipo = 'mercado_pago';
        }

        if ($valor > 0) {
            $dataCredito = [
                'user_id' => $transferencia->user_id,
                'value' => $value,
                'description' => $desc,
                'description_tipo' => $tipo,
                'is_agendamento' => $param['is_agendamento'],
                'user_id_creator' => auth()->id(),
                'obs' => null,
            ];

            if ($creditosAntecipados) {
                $dataCredito['flag_assoc_payment'] = 1;
            }

            $creditoId = DB::table('payment')->insertGetId($dataCredito);

            if (!$creditoId) {
                $this->error = 'Falha ao adicionar crédito';
                return false;
            }

            DB::table('payment')->where('id', $creditoId)->whereNull('transferencia_id')->update(['transferencia_id' => $transferencia->id]);
        }

        DB::table('transferencia')->where('id', $transferencia->id)->update([
            'status' => 'liberado',
            'date_liberacao' => now(),
        ]);

        if ($creditosAntecipados) {
            foreach ($creditosAntecipados as $ca) {
                $payment = DB::table('payment')->where('id', $ca)->first();

                $isExpired = false;
                if ($payment->boleto_id) {
                    $boletoOld = DB::table('boletos')->where('id', $payment->boleto_id)->first();
                    if ($boletoOld->status == 'EXPIRED') {
                        $isExpired = true;
                    }
                }

                $paramUpdatePayment = [
                    'transferencia_id' => $transferencia->id,
                    'payment_assoc_id' => $creditoId,
                ];

                if ($valor <= 0) {
                    $paramUpdatePayment['tipo'] = 'credito';
                    $paramUpdatePayment['obs'] = 'Ref. Antecipação crédito';
                }

                if ($isExpired) {
                    $paramUpdatePayment['obs'] = 'Ref. Boleto Expirado';
                }

                DB::table('payment')->where('id', $ca)->whereNull('transferencia_id')->update($paramUpdatePayment);

                if ($isExpired) {
                    DB::table('boletos')->where('id', $boletoOld->id)->where('status', 'EXPIRED')->update(['status' => 'EXPIRED-OK']);
                }
            }
        }

        return true;
    }

    public function removeTransf($id)
    {
        $cred = DB::table('payment')->where('transferencia_id', $id)->first();

        if ($cred) {
            $this->error = 'Transferência possui crédito lançado.';
            return false;
        }

        $transf = DB::table('transferencia')->where('id', $id)->first();

        if ($transf->banco == 'pixiugu') {
            return DB::table('transferencia')->where('id', $id)->whereNull('status')->update(['status' => 'delete']);
        } else {
            return DB::table('transferencia')->where('id', $id)->whereNull('status')->delete();
        }
    }

    public function getCreditosAntecipados($userId)
    {
        return DB::table('payment')
            ->leftJoin('boletos', 'boletos.id', '=', 'payment.boleto_id')
            ->where(function ($query) {
                $query->where('tipo', 'credito_antecipado')
                    ->orWhere('boletos.status', 'EXPIRED');
            })
            ->where('payment.user_id', $userId)
            ->whereNull('payment.payment_assoc_id')
            ->get();
    }

    public function getAssocPayment($id)
    {
        return DB::table('payment')->where('payment_assoc_id', $id)->get();
    }

    public function getTransferenciasUltimas24h($userId, $valor)
    {
        $date1Dia = now()->subDay()->toDateTimeString();

        return DB::table('transferencia')
            ->where('user_id', $userId)
            ->where('date_insert', '>=', $date1Dia)
            ->where('status', 'liberado')
            ->where('valor_solicitado', $valor)
            ->get();
    }

    public function editBancoTransferencia($transferencia, $banco)
    {
        $trans = $this->getTransf(['id' => $transferencia->id]);

        if ($trans->status && auth()->id() != 2521 && auth()->id() != 3748) {
            return;
        }

        return DB::table('transferencia')->where('id', $transferencia->id)->update(['banco' => $banco]);
    }

    public function getLastDocUsed($userId)
    {
        $doc = DB::table('transferencia')
            ->select(DB::raw('MAX(ID) as max_id, documento'))
            ->where('user_id', $userId)
            ->whereNotNull('documento')
            ->first();

        if ($doc) {
            return $doc->documento;
        }

        return null;
    }

    public function addDiscount($data)
    {
        $paramIns = [
            'payment_id' => $data['payment_id'],
            'value' => -$data['value'],
            'date' => now(),
        ];

        if (isset($data['coleta_id'])) {
            $paramIns['coleta_id'] = $data['coleta_id'];
        }
        if (isset($data['ref_coleta_id'])) {
            $paramIns['ref_coleta_id'] = $data['ref_coleta_id'];
        }
        if (isset($data['ref_envio_id'])) {
            $paramIns['ref_envio_id'] = $data['ref_envio_id'];
        }
        if (isset($data['type'])) {
            $paramIns['type'] = $data['type'];
        }
        if (isset($data['obs'])) {
            $paramIns['obs'] = $data['obs'];
        }
        if (isset($data['user_id_creator'])) {
            $paramIns['user_id_creator'] = $data['user_id_creator'];
        }

        return DB::table('payment_credit_discount')->insert($paramIns);
    }
    
    public function updateTransf($data)
    {
        if (isset($data['id'])) {
            $dataUpd = [];

            if (isset($data['invoice_id'])) {
                $dataUpd['invoice_id'] = $data['invoice_id'];
            }
            if (isset($data['anexo'])) {
                $dataUpd['anexo'] = $data['anexo'];
            }

            if ($dataUpd) {
                return DB::table('transferencia')->where('id', $data['id'])->update($dataUpd);
            }
        }

        return false;
    }

    public function sumCreditosAntecipados($param)
    {
        $valorTotal = 0;

        foreach ($param['creditos_antecipados'] as $c) {
            $sql = 'SELECT payment.* FROM payment LEFT JOIN boletos ON boletos.id = payment.boleto_id ';
            $sql .= ' WHERE payment.id = ? AND payment.user_id = ? AND ( payment.tipo = ? OR boletos.status = ? ) ';
            $cred = DB::select($sql, [$c, $param['user_id'], 'credito_antecipado', 'EXPIRED'])[0];

            if (!$cred) {
                return 'FAIL_USER';
            }

            $valorTotal += abs($cred->value);
        }

        return $valorTotal;
    }

}
