<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Afiliado extends Model
{
    protected $table = 'indicacoes';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    public function getIndicacoes($param = [])
    {
        $query = DB::table($this->table)
            ->select('indicacoes.*', 'user.*', 'indicacoes.status as status_indicacoes')
            ->join('user', 'user.id', '=', 'indicacoes.id_user')
            ->groupBy('user.id')
            ->orderByDesc('indicacoes.id_indicador');

        if (isset($param['status'])) {
            $query->where('indicacoes.status', NULL);
        }

        return $query->where('indicacoes.id_indicador', $param['indicador'])->get();
    }

    public function getIndicados($param = [])
    {
        $query = DB::table($this->table)
            ->select('indicacoes.*', 'user.*', 'indicacoes.status as status_indicacoes')
            ->join('user', 'user.id', '=', 'indicacoes.id_user')
            ->groupBy('user.id');

        if ($param['per_page']) {
            $query->limit($param['per_page'])->offset($param['page_start']);
        }

        if ($param['filter_email']) {
            $query->orderByDesc('indicacoes.id_indicador')
                ->where('user.email', $param['filter_email']);
            return $query->where('indicacoes.id_indicador', $param['indicador'])->get();
        }

        if ($param['filter_name']) {
            $query->orderByDesc('indicacoes.id_indicador')
                ->where('user.name', 'like', '%' . $param['filter_name'] . '%');
            return $query->where('indicacoes.id_indicador', $param['indicador'])->get();
        }

        $query->orderByDesc('indicacoes.id_indicador');
        return $query->where('indicacoes.id_indicador', $param['indicador'])->get();
    }

    public function getById($id_user)
    {
        return DB::table($this->table)->where('id_user', $id_user)->first();
    }

    public function updateIndicacoes($id_user)
    {
        DB::table($this->table)->where('id_user', $id_user)
            ->update([
                'status' => NULL,
                'id_user_aprove' => session('user_id'),
                'date_approve' => now(),
            ]);
    }

    public function getIndicacoesPendentes()
    {
        $sql = 'SELECT indicacoes.*, user.name as name_indicante FROM indicacoes ';
        $sql .= 'JOIN user ON user.id = indicacoes.id_indicador ';
        $sql .= 'WHERE indicacoes.status = ? AND indicacoes.id_user_aprove IS NULL AND indicacoes.date_approve IS NULL';

        return DB::select($sql, ['PENDENTE_APROVACAO']);
    }

    public function getFaturamentoAno()
    {
        $sql = 'SELECT SUM(envios.taxa_mandabem) as valor_total FROM indicacoes ';
        $sql .= 'JOIN envios ON envios.user_id = indicacoes.id_user AND indicacoes.status IS NULL AND indicacoes.id_payment IS NOT NULL';

        return DB::select($sql)[0]->valor_total;
    }

    public function getFaturamento30Dias()
    {
        $date_trinta = now()->subDays(30)->startOfDay();

        $sql = 'SELECT SUM(envios.taxa_mandabem) as valor_total FROM indicacoes ';
        $sql .= 'JOIN envios ON envios.user_id = indicacoes.id_user ';
        $sql .= 'WHERE envios.date_insert > ? AND indicacoes.status IS NULL AND indicacoes.id_payment IS NOT NULL';

        return DB::select($sql, [$date_trinta])[0]->valor_total;
    }

    public function getQuantIndicacoesNovo()
    {
        $data_hoje = now();
        $date_sete_dias = now()->subDays(7)->startOfDay();

        $sql = 'SELECT COUNT(id_user) as date_novo FROM indicacoes ';
        $sql .= 'WHERE date_insert > ? AND status IS NULL AND id_payment IS NOT NULL ';

        return DB::select($sql, [$date_sete_dias])[0]->date_novo;
    }

    public function getUserPayment($id)
    {
        $val = DB::table('payment')->where('id', $id)->sum('value');
        return $val;
    }

    public function getQuantAfiliadosAtivos()
    {
        $data_hoje = now();
        $date_trinta = now()->subDays(30)->startOfDay();

        $sql = 'SELECT indicacoes.id_indicador  FROM indicacoes ';
        $sql .= 'JOIN envios ON envios.user_id = indicacoes.id_indicador ';
        $sql .= 'WHERE envios.date_insert > ? AND indicacoes.id_payment IS NOT NULL GROUP BY indicacoes.id_indicador ';

        return DB::select($sql, [$date_trinta]);
    }

    public function getQuantAfiliadosInativos()
    {
        $date_trinta = now()->subDays(30)->startOfDay();

        $sql = 'SELECT indicacoes.id_indicador  FROM indicacoes ';
        $sql .= 'JOIN envios ON envios.user_id = indicacoes.id_indicador ';
        $sql .= 'WHERE envios.date_insert < ? AND indicacoes.status IS NULL AND indicacoes.id_payment IS NOT NULL GROUP BY indicacoes.id_indicador ';

        return DB::select($sql, [$date_trinta]);
    }

    public function getIndicadoAtivo()
    {
        $date_trinta = now()->subDays(30)->startOfDay();

        $sql = 'SELECT indicacoes.id_user  FROM indicacoes ';
        $sql .= 'JOIN envios ON envios.user_id = indicacoes.id_user ';
        $sql .= 'WHERE envios.date_insert > ? AND indicacoes.status IS NULL AND indicacoes.id_payment IS NOT NULL GROUP BY indicacoes.id_user ';

        return DB::select($sql, [$date_trinta]);
    }

    public function getIndicadoDesativo()
    {
        $date_trinta = now()->subDays(30)->startOfDay();

        $sql = 'SELECT indicacoes.id_user  FROM indicacoes ';
        $sql .= 'JOIN envios ON envios.user_id = indicacoes.id_user ';
        $sql .= 'WHERE envios.date_insert < ? AND indicacoes.status IS NULL  GROUP BY indicacoes.id_user ';

        return DB::select($sql, [$date_trinta]);
    }

    public function getComissaoAno()
    {
        $sql = 'SELECT SUM(payment.value) as valor_total  FROM indicacoes ';
        $sql .= 'JOIN payment ON payment.id = indicacoes.id_payment ';
        $sql .= 'WHERE indicacoes.id_payment IS NOT NULL';

        return DB::select($sql)[0]->valor_total;
    }

    public function getComissao30Dias()
    {
        $date_trinta = now()->subDays(30)->startOfDay();
        $sql = 'SELECT SUM(payment.value) as valor_total  FROM indicacoes ';
        $sql .= 'JOIN payment ON payment.id = indicacoes.id_payment ';
        $sql .= 'WHERE indicacoes.date_insert > ? AND indicacoes.id_payment IS NOT NULL';

        return DB::select($sql, [$date_trinta])[0]->valor_total;
    }

    public function getIndicador($param = array())
    {
        $query = DB::table('indicacoes')
            ->select('indicacoes.*', 'user.*', 'indicacoes.status as status_indicacoes', DB::raw('COUNT(indicacoes.id_indicador) as rank'))
            ->join('user', 'user.id', '=', 'indicacoes.id_indicador');

        if (isset($param['filter_email'])) {
            $query->where('user.email', $param['filter_email']);
        }

        if (isset($param['filter_name'])) {
            $query->where('user.name', 'like', '%' . $param['filter_name'] . '%');
        }

        $query->where('user.status', 'ACTIVE')
            ->groupBy('user.id')
            ->orderBy('rank', 'desc');

        if (isset($param['per_page'])) {
            $limit = $param['per_page'];
            $start = $param['page_start'] ?? 0;
            $query->limit($limit)->offset($start);
        }

        return $query->get();
    }

    public function getIndicadorName()
    {
        return DB::table('indicacoes')
            ->select('user.id', 'user.name')
            ->join('user', 'user.id', '=', 'indicacoes.id_indicador')
            ->where('user.status', 'ACTIVE')
            ->groupBy('user.id')
            ->get();
    }

    public function getQuantAfiliadoNovo()
    {
        $data_hoje = now();
        $date_sete_dias = now()->subDays(7)->startOfDay();

        $sql = 'SELECT COUNT(id_indicador) as date_novo FROM indicacoes ';
        $sql .= 'WHERE date_insert > ? AND status IS NULL AND id_payment IS NOT NULL GROUP BY (id_indicador)';

        return DB::select($sql, [$date_sete_dias])[0]->date_novo;
    }


}
