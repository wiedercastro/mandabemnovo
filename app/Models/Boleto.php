<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Boleto extends Model
{
    protected $table = 'boletos'; 

    public function getError()
    {
        return $this->error;
    }


    public function getDateInsertAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y H:i:s');
    }

    public function getBoleto($data)
    {
        $query = $this->select('boletos.*', 'payment.id as credito')
                      ->leftJoin('payment', 'payment.boleto_id', '=', 'boletos.id');

        if (isset($data['id'])) {
            $query->where('boletos.id', $data['id']);
        }

        if (isset($data['invoice_id'])) {
            $query->where('boletos.invoice_id', $data['invoice_id']);
        }

        if ($data['user_id'] != 'mandabem') {
            $query->where('boletos.user_id', $data['user_id']);
        }

        return $query->first();
    }

    public function getBoletoList(bool $limitParam, ?string $cliente)
    {
        $usuarioLogado = auth()->user()->user_group_id;

        $query = $this->select(
                    DB::raw(
                        'CONCAT(user.razao_social, " | ", user.name ) as cliente'
                    ),
                    'boletos.id', 
                    'boletos.date_insert', 
                    'boletos.value', 
                    'boletos.file_comprovante', 
                    'boletos.doc as impressao', 
                    'boletos.status', 
                    'boletos.user_id',   
                    'p_credito.id as credito'
                )
                ->leftJoin('payment as p_credito', 'p_credito.boleto_id', '=', 'boletos.id')
                ->leftJoin('user', 'user.id', '=', 'boletos.user_id')
                ->where('boletos.bar_code', 'IS NOT', null)
                ->where('boletos.status', 'NOT LIKE', 'DELETE');

        if ($usuarioLogado != 1) {
            $query->where('boletos.user_id', $usuarioLogado);
        }

        if ($cliente) {
            $query->where(DB::raw('CONCAT(user.razao_social, " | ", user.name)'), 'LIKE', "%$cliente%");
        }

        if ($limitParam) {
            return $query->limit(10, 10)->get();
        } else {
            return $query->paginate(15);
        }

    }

    public function saveBoleto($data)
    {
        $data['value'] = (float) number_format(preg_replace('/,/', '.', $data['value']), 2, '.', '');

        $boletoId = DB::table('boletos')->insertGetId([
            'user_id'       => $data['user_id'],
            'type'          => isset($data['type']) ? $data['type'] : 'normal',
            'value'         => $data['value'],
            'value_request' => $data['value'],
            'doc'           => $data['doc'],
            'date_insert'   => now(),
        ]);

        return $boletoId;
    }

    public function saveReturn($data)
    {
        $query = DB::table('boletos')
                   ->where('id', $data['boleto_id'])
                   ->where('user_id', $data['user_id']);

        if ($data['status'] == 'gerado') {
            return $query->update([
                'bar_code'   => $data['identification'],
                'url_pdf'    => $data['pdf'],
                'invoice_id' => $data['invoice_id'],
                'status'     => 'GERADO',
                'type'       => (isset($data['avulso']) && $data['avulso']) ? 'avulso' : 'normal',
            ]);
        }
    }

    public function getPendentes($param)
    {
        if (isset($param['filtrar_consulta_iugu']) && $param['filtrar_consulta_iugu']) {
            $query = $this->select('*')
                          ->whereNotNull('bar_code')
                          ->where('status', '!=', 'PAGO')
                          ->whereNotLike('status', '%EXPIRED%')
                          ->where('status', '!=', 'DELETE')
                          ->orderBy('id')
                          ->limit(300);

            $list = $query->get();

            echo $list->count();

            return $list;
        }

        if (isset($param['pendentes_permissao_gerar']) && $param['pendentes_permissao_gerar']) {
            if (true || auth()->user()->id == 18) {
                $sql = 'SELECT boletos.* FROM boletos ';
                $sql .= 'WHERE boletos.type = "normal" AND boletos.bar_code IS NOT NULL AND ( boletos.status = "GERADO" OR boletos.status = "EXPIRED" ) ';
                $sql .= 'AND IF(boletos.status = "EXPIRED", ( SELECT COUNT(*) FROM payment WHERE payment.boleto_id = boletos.id ) > 0, (boletos.id > 0) ) ';
                $sql .= 'AND boletos.user_id = ? ';

                $list = DB::select($sql, [$param['user_id']]);
            } else {
                $query = $this->select('boletos.*', 'payment.id as payment_id')
                              ->whereNotNull('bar_code')
                              ->whereIn('status', ['GERADO', 'EXPIRED'])
                              ->where('user_id', $param['user_id'])
                              ->leftJoin('payment', function ($join) {
                                  $join->on('payment.boleto_id', '=', 'boletos.id')
                                       ->where(function ($query) {
                                           $query->where('boletos.status', 'EXPIRED')
                                                 ->whereRaw('payment.id IS NOT NULL AND payment.date >= "2020-07-15"');
                                       });
                              });

                $list = $query->get();
            }

            return $list;
        }

        if (true) {
            $query = $this->select('boletos.*', DB::raw('(SELECT payment.id FROM payment WHERE payment.boleto_id = boletos.id LIMIT 1) as credito'))
                          ->select(DB::raw('CONCAT("<strong>",user.id,"</strong>-",user.razao_social," | ",user.name) as cliente'))
                          ->where(function ($query) use ($param) {
                              if (($param['filter_cliente'] ?? null) == '5887' || auth()->user()->id == '5887') {
                                  $query->where(function ($q) {
                                      $q->where('boletos.status', 'EXPIRED')
                                         ->whereRaw('(boletos.date_insert >= "2020-01-01" AND ( SELECT count(*) FROM payment WHERE payment.boleto_id = boletos.id ) > 0 )');
                                  });
                              } else {
                                  $query->where(function ($q) {
                                      $q->where('boletos.status', 'EXPIRED')
                                         ->whereRaw('(boletos.date_insert >= "2020-07-15" AND ( SELECT count(*) FROM payment WHERE payment.boleto_id = boletos.id ) > 0 )');
                                  });
                              }

                              $query->orWhere(function ($q) {
                                  $q->where('boletos.status', 'GERADO')
                                     ->orWhere('boletos.status', 'EXPIRED')
                                     ->orWhere(function ($inner) {
                                         $inner->whereRaw('(SELECT count(*) FROM payment WHERE payment.boleto_id = boletos.id ) = 0');
                                     });
                              });

                              $query->where('boletos.type', 'normal')
                                    ->whereNotNull('boletos.bar_code')
                                    ->whereNotLike('boletos.status', 'DELETE')
                                    ->whereNotLike('boletos.status', 'EXPIRED-%');

                              if ($param['user_id'] != 'mandabem') {
                                  $query->where('boletos.user_id', $param['user_id']);
                              }

                              if ($param['filter_cliente'] ?? null) {
                                  $query->where('boletos.user_id', $param['filter_cliente']);
                              }

                              if ($param['filter_status'] ?? null) {
                                  if ($param['filter_status'] == 'credito_pending') {
                                      $query->whereRaw('(SELECT count(*) FROM payment WHERE payment.boleto_id = boletos.id ) = 0');
                                  }

                                  if ($param['filter_status'] == 'pgto_pending') {
                                      $query->where(function ($q) {
                                          $q->whereRaw('(SELECT count(*) FROM payment WHERE payment.boleto_id = boletos.id ) > 0')
                                             ->where(function ($inner) {
                                                 $inner->where('boletos.status', '!=', 'PAGO')
                                                       ->orWhereNull('boletos.status');
                                             });
                                      });
                                  }
                              }
                          })
                          ->leftJoin('user', 'user.id', '=', 'boletos.user_id')
                          ->orderBy('boletos.date_insert', 'desc');

            $list = $query->get();
            return $list;
        } else {
            $query = $this->select('boletos.*', 'payment.id as credito')
                          ->select(DB::raw('CONCAT("<strong>",user.id,"</strong>-",user.razao_social," | ",user.name) as cliente'))
                          ->where(function ($query) use ($param) {
                              $query->where(function ($q) {
                                  $q->where('boletos.status', 'GERADO')
                                     ->orWhereNull('payment.id');
                              });

                              $query->whereNotNull('boletos.bar_code')
                                    ->whereNotLike('boletos.status', 'DELETE');

                              if ($param['user_id'] != 'mandabem') {
                                  $query->where('boletos.user_id', $param['user_id']);
                              }

                              if ($param['filter_cliente'] ?? null) {
                                  $query->where('boletos.user_id', $param['filter_cliente']);
                              }

                              if ($param['filter_status'] ?? null) {
                                  if ($param['filter_status'] == 'credito_pending') {
                                      $query->whereNull('payment.id');
                                  }

                                  if ($param['filter_status'] == 'pgto_pending') {
                                      $query->whereNotNull('payment.id')
                                            ->where(function ($inner) {
                                                $inner->where('boletos.status', '!=', 'PAGO')
                                                      ->orWhereNull('boletos.status');
                                            });
                                  }
                              }
                          })
                          ->leftJoin('user', 'user.id', '=', 'boletos.user_id')
                          ->leftJoin('payment', 'payment.boleto_id', '=', 'boletos.id')
                          ->orderBy('boletos.date_insert', 'desc');

            return $query->get();
        }
    }

    public function saveComprovante($data)
    {
        return DB::table('boletos')
            ->where('id', $data['id'])
            ->where('user_id', $data['user_id'])
            ->update(['file_comprovante' => $data['file']]);
    }

    public function cleanTrash($user_id)
    {
        return DB::table('boletos')
            ->where('user_id', $user_id)
            ->whereNull('bar_code')
            ->whereNull('url_pdf')
            ->whereNull('status')
            ->delete();
    }

    public function liberarCredito($boleto, $args = [])
    {
        $paymentModel = new Payment();  

        if ($boleto->credito) {
            return true;
        }

        $exist = DB::table('payment')
            ->where('boleto_id', $boleto->id)
            ->first();

        if ($exist) {
            return true;
        }

        $value = !isset($args['valor_liberar']) ? $boleto->value : $args['valor_liberar'];

        if ($value < 100) {
            $value = $value - 2.90;
        }

        if ($value > 0) {
            $dataCredito = [
                'user_id'          => $boleto->user_id,
                'payment_id'       => $boleto->invoice_id,
                'value'            => $value,
                'description'      => 'Boleto confirmado em ' . now()->toDateTimeString(),
                'description_tipo' => 'boleto',
                'gateway'          => 'BOLETO',
                'user_id_creator'  => auth()->user()->id ?? 0,
                'obs'              => null,
            ];

            if (isset($args['creditos_antecipados']) && $args['creditos_antecipados']) {
                $dataCredito['flag_assoc_payment'] = 1;
            }

            $creditoId = $paymentModel->saveCredito($dataCredito);

            if (!$creditoId) {
                $this->error = $paymentModel->getError();
                return false;
            }

            DB::table('payment')
                ->where('id', $creditoId)
                ->whereNull('boleto_id')
                ->update(['boleto_id' => $boleto->id]);

            if (!$creditoId) {
                return false;
            }
        }

        if (isset($args['creditos_antecipados']) && $args['creditos_antecipados']) {
            foreach ($args['creditos_antecipados'] as $ca) {
                $payment = DB::table('payment')->where('id', $ca)->first();
                
                $isExpired = false;
                if ($payment->boleto_id) {
                    $boletoOld = DB::table('boletos')
                        ->where('id', $payment->boleto_id)
                        ->where('user_id', 'mandabem')
                        ->first();
        
                    if ($boletoOld->status == 'EXPIRED') {
                        $isExpired = true;
                    }
                }
        
                $paramUpdPayment = [
                    'boleto_id'        => $boleto->id,
                    'payment_assoc_id' => isset($credito_id) ? $credito_id : null,
                ];
        
                if ($value <= 0) {
                    $paramUpdPayment['tipo']       = 'boleto';
                    $paramUpdPayment['payment_id'] = $boleto->invoice_id;
                    $paramUpdPayment['obs']        = 'Ref. Antecipação crédito';
                }
                if ($isExpired) {
                    $paramUpdPayment['obs'] = 'Ref. Boleto Expirado';
                }
        
                DB::table('payment')
                    ->where('id', $ca)
                    ->where('user_id', $boleto->user_id)
                    ->when(!$isExpired, function ($query) {
                        $query->whereNull('boleto_id');
                    })
                    ->update($paramUpdPayment);
        
                if ($isExpired) {
                    DB::table('boletos')
                        ->where('id', $boletoOld->id)
                        ->where('status', 'EXPIRED')
                        ->update(['status' => 'EXPIRED-OK']);
                }
            }
        }
        return true;
    }

    public function editValue($boleto, $value)
    {
        $hasCredito = $this->where(['id' => $boleto->id, 'user_id' => $boleto->user_id])->first();

        if ($hasCredito->credito) {
            return false;
        }

        return DB::table('boletos')
            ->where('id', $boleto->id)
            ->where('user_id', $boleto->user_id)
            ->update(['value' => $value]);
    }

    public function setPago($id, $valuePaid)
    {
        $boleto = $this->where(['id' => $id, 'user_id' => 'mandabem'])->first();

        if (!$boleto) {
            // \Mail::to('regygom@gmail.com')->send(new \App\Mail\SetPagoEmail([
            //     'id' => $id,
            //     'valuePaid' => $valuePaid,
            // ]));

            return false;
        }

        $setar = DB::table('boletos')
            ->where('id', $id)
            ->update([
                'status' => 'PAGO',
                'value_paid' => $valuePaid,
                'date_pgto' => now(),
            ]);

        // Liberando o crédito automaticamente
        if ($setar && $boleto->status != 'PAGO') {
            $this->liberarCredito($boleto);
        }

        return $setar;
    }

    public function getInfoTotal(string $type = 'PAGO')
    {
        if ($type == 'PAGO') {
            return DB::table('boletos')
                ->select(DB::raw('count(*) as total, sum(value_paid) as value'))
                ->where('status', 'PAGO')
                ->first();
        }

        if ($type == 'PENDING') {
            return DB::table('boletos')
                ->select(DB::raw('count(*) as total, sum(value) as value'))
                ->whereNotNull('bar_code')
                ->where(function ($query) {
                    $query->whereNull('status')->orWhere('status', 'GERADO');
                })
                ->first();
        }
    }

    public function setExpired($id)
    {
        return DB::table('boletos')
            ->where('id', $id)
            ->where('status', '!=', 'PAGO')
            ->update(['status' => 'EXPIRED']);
    }

    public function remove($id, $onlyHide = true)
    {
        //corrigir apos librarie adicionada
        $iugu = new \App\Libraries\Iugu();  

        $bol = DB::table('boletos')->where('id', $id)->where('status', 'NOT LIKE', 'PAGO')->first();

        if (!$bol) {
            $this->error = 'Boleto não encontrado.';
            return false;
        }

        $cred = DB::table('payment')->where('boleto_id', $bol->id)->first();

        if ($cred) {
            $this->error = 'Boleto possui crédito lançado.';
            return false;
        }

        $info = $iugu->consultarFatura($bol->invoice_id);

        if ($info['status'] != 'expired' && $info['status'] != 'pending') {
             
            // \Mail::to('regygom@gmail.com')->send(new \App\Mail\RemocaoBoletoEmail([
            //     'info' => $info,
            // ]));

            $this->error = 'Boleto não pode ser removido, contate suporte';
            return false;
        }

        if ($onlyHide) {
            return DB::table('boletos')
                ->where('id', $id)
                ->where(function ($query) {
                    $query->whereNull('status')
                        ->orWhere('status', 'GERADO')
                        ->orWhere('status', 'EXPIRED');
                })
                ->update(['status' => 'DELETE']);
        } else {
            return DB::table('boletos')
                ->where('id', $id)
                ->where('status', 'EXPIRED')
                ->delete();
        }
    }

    public function removeComprovante($id)
    {
        $boleto = $this->where(['id' => $id, 'user_id' => auth()->user()->id])->first();

        if ($boleto) {
            $path = '/home/sysuser/files/boleto_comprovante/' . $boleto->file_comprovante;
            if (is_file($path)) {
                @unlink($path);
            }
        }

        DB::table('boletos')
            ->where('id', $id)
            ->update(['file_comprovante' => null]);
    }

    public function getExpirados($data)
    {
        return DB::table('boletos')
            ->select('boletos.value', 'boletos.date_insert as geracao', 'payment.date as liberacao')
            ->join('payment', 'payment.boleto_id', '=', 'boletos.id')
            ->where('boletos.status', 'EXPIRED')
            ->where('boletos.user_id', $data['user_id'])
            ->where('boletos.date_insert', '>=', '2020-08-01')
            ->get();
    }

    public function saveBaixa($data)
    {
        $save = DB::table('boletos_baixa')->insert([
            'boleto_id' => $data['boleto_id'],
            'user_id_creator' => $data['user_id_creator'],
            'forma_baixa' => $data['forma_baixa'],
            'ref_pgto_bol_id' => $data['ref_pgto_bol_id'],
            'value' => $data['value'],
            'anexo' => $data['anexo'],
            'obs' => $data['obs'],
            'date' => now(), 
        ]);

        if (!$save) {
            return false;
        }

        // Se for crédito antecipado, mudar o pagamento para crédito antecipado
        if ($data['forma_pagamento'] == 'credito_antecipado' && auth()->id() != '3748') {
            DB::table('payment')
                ->where('boleto_id', $data['boleto_id'])
                ->where('tipo', 'boleto')
                ->update(['tipo' => 'credito_antecipado']);
        }

        if (auth()->id() != '3748') {
            DB::table('boletos')
                ->where('id', $data['boleto_id'])
                ->where('status', 'EXPIRED')
                ->update(['status' => 'EXPIRED-OK']);
        }

        return true;
    }

    public function hasBaixa($boletoId)
    {
        return DB::table('boletos_baixa')->where('boleto_id', $boletoId)->first();
    }

    public function getNumBolPenLiberados($userId)
    {
        $sql = 'SELECT COUNT(boletos.id) as total FROM boletos JOIN payment ON payment.boleto_id = boletos.id ';
        $sql .= 'WHERE boletos.user_id = ? AND boletos.status != ? AND boletos.status != ? ';

        $row = DB::select($sql, [$userId, 'PAGO', 'EXPIRED-OK'])[0];

        return $row->total;
    }

    public function getMesmoValorLiberado($boleto)
    {
        $dateLimitLiberacao = now()->subSeconds(86400); 

        $rows = DB::table('boletos')
            ->select('boletos.*', 'payment.date as date_liberacao')
            ->join('payment', 'payment.boleto_id', '=', 'boletos.id')
            ->where('boletos.date_insert', '>=', $dateLimitLiberacao)
            ->where('boletos.user_id', $boleto->user_id)
            ->where('boletos.value', $boleto->value)
            ->where('boletos.id', '!=', $boleto->id)
            ->get();

        return $rows;
    }

}
