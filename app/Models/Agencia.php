<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Agencia extends Model
{
    protected $table = 'agencias';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function updateAgenciaEnvio($param)
    {
        if (isset($param['codigo_fatura'])) {
            $paramSearch = ['codigo_fatura' => $param['codigo_fatura']];
        } else {
            $paramSearch = ['codigo' => $param['codigo']];
        }

        $agencia = DB::table($this->table)->where($paramSearch)->first();

        if (!$agencia) {
            $agenciaId = DB::table($this->table)->insertGetId([
                'nome' => $param['nome'],
                'codigo' => isset($param['codigo']) ? $param['codigo'] : null,
                'codigo_fatura' => isset($param['codigo_fatura']) ? $param['codigo_fatura'] : null,
                'cidade' => isset($param['cidade']) ? $param['cidade'] : null,
                'uf' => isset($param['codigo']) ? $param['uf'] : null,
                'date_insert' => now(),
            ]);
        } else {
            $agenciaId = $agencia->id;
        }

        $updA = DB::table('envio_origem')->where('envio_id', $param['envio_id'])->update([
            'agencia_id' => $agenciaId,
        ]);

        if (!$updA) {
            return false;
        }

        return true;
    }
}
