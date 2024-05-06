<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cupom extends Model
{
    protected $table = 'cupons';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $guarded = [];

    public function getCupons()
    {
        return $this->orderBy('id', 'desc')->get();
    }

    public function getDuracaoAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function getCupomById($cupomId)
    {
        return $this->where('id', $cupomId)->count();
    }

    public function getUserCupons($cupomId, $userId)
    {
        return $this->where('user_id', $userId)
            ->where('cupom_id', $cupomId)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getCupomId($cupomId, $userId = null, $usado = null)
    {
        $query = $this->where('cupom_id', $cupomId);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($usado) {
            $query->whereNotNull('envio_id');
        }

        return $query->get();
    }

    public function getCupomIdUser($cupomId, $userId)
    {
        return $this->where('cupom_id', $cupomId)
            ->where('user_id', $userId)
            ->whereNull('envio_id')
            ->first();
    }

    public function getCupomUsadoIdUser($cupomId, $userId)
    {
        return $this->where('cupom_id', $cupomId)
            ->where('user_id', $userId)
            ->whereNotNull('envio_id')
            ->get();
    }

    public function getCuponsUser($userId)
    {
        return DB::select('SELECT cupons_user.*, cupons.name, cupons.status, cupons.type, cupons.valor, cupons.num_envios, cupons.duracao FROM cupons_user ' .
            'JOIN cupons ON cupons.id = cupons_user.cupom_id ' .
            'WHERE cupons_user.user_id = ? AND cupons.status = 1 AND cupons_user.envio_id IS NULL GROUP BY cupons_user.cupom_id', [$userId]);
    }

    public function getAllCuponsUser($userId)
    {
        return DB::select('SELECT cupons_user.*, cupons.name, cupons.status, cupons.type, cupons.valor, cupons.num_envios, cupons.duracao FROM cupons_user ' .
            'JOIN cupons ON cupons.id = cupons_user.cupom_id ' .
            'WHERE cupons_user.user_id = ? GROUP BY cupons_user.cupom_id', [$userId]);
    }

    public function getValidarCupom($param)
    {
        $cupom = $this->where('name', $param['name'])->where('status', 1)->first();

        if ($cupom) {
            $userCupom = DB::select('SELECT * FROM cupons_user WHERE cupom_id = ? AND user_id = ?', [$cupom->id, $param['user_id']]);

            if ($userCupom) {
                return ["Cupom já cadastrado para esse usuário."];
            } else {
                $cupomAtivo = DB::select('SELECT cupons_user.*, cupons.name FROM cupons_user ' .
                    'JOIN cupons ON cupons.id = cupons_user.cupom_id ' .
                    'WHERE cupons_user.user_id = ? AND cupons.status = 1 AND cupons_user.envio_id IS NULL', [$param['user_id']]);

                if (!$cupomAtivo) {
                    $dataInsert = [
                        "cupom_id" => $cupom->id,
                        "user_id" => $param['user_id'],
                        "date_update" => now(),
                        "date_insert" => now(),
                    ];

                    DB::table('cupons_user')->insert($dataInsert);
                    return true;
                } else {
                    return ["Usuário já possui Cupom Ativo."];
                }
            }
        } else {
            return ["Cupom não Cadastrado ou Desativado."];
        }
    }

    public function salvar(array $param = []): void
    {
        if ($param['tempo_duracao_dias']) {
            $param['tempo_duracao_dias'] = now()->addDays($param['tempo_duracao_dias'])->startOfDay();
        } else {
            // $param['duracao'] = now()->addDays(30)->startOfDay();
        }

        if ($param['vincular_afiliado']) {
            $param['vincular_afiliado'] = User::where('id', $param['vincular_afiliado'])->value('name');
        }

        $dataInsert = [
            "type" => isset($param['tipo_cupom']) ? $param['tipo_cupom'] : null,
            "name" => isset($param['nome_ativacao']) ? $param['nome_ativacao'] : null,
            "valor" => isset($param['valor']) ? $param['valor'] : null,
            "duracao" => isset($param['tempo_duracao_dias']) ? $param['tempo_duracao_dias'] : null,
            "num_envios" => isset($param['qtd_envios']) ? $param['qtd_envios'] : null,
            "status" => 1,
            "afiliados" => isset($param['vincular_afiliado']) ? $param['vincular_afiliado'] : null,
            "date_update" => now(),
            "date_insert" => now(),
        ];
        $this->create($dataInsert);
    }

    public function ativar($id)
    {
        return $this->where('id', $id)->update(['status' => 1, 'date_update' => now()]);
    }

    public function desativar($id)
    {
        return $this->where('id', $id)->update(['status' => 0, 'date_update' => now()]);
    }

    public function deleteCupom($cupomId)
    {
        // Corrigir, validar permissão de usuário

        return $this->where('id', $cupomId)->delete();
    }

}
