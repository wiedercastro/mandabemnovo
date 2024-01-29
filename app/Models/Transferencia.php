<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transferencia extends Model
{
    protected $table = 'transferencia';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('date_utils');
    }

    public function getPixDeleteAuto($param = [])
    {
        $dataHoje = $this->date_utils->getNow();
        $seteDias = now()->subDays(7)->startOfDay();

        $idUsuario = $param['filter_cliente'];

        $result = DB::table('transferencia')
            ->select('transferencia.*', 'user.*', 'transferencia.date_insert as data_insercao', 'transferencia.id as id_transferencia', 'transferencia.status as status_transferencia')
            ->join('user', 'user.id', '=', 'transferencia.user_id')
            ->where('transferencia.user_id', $idUsuario)
            ->where(function ($query) {
                $query->whereNull('transferencia.status')
                    ->orWhere('transferencia.status', 'delete_auto');
            })
            ->where('transferencia.date_insert', '>=', $seteDias)
            ->orderByDesc('transferencia.id')
            ->get();

        return $result->toArray();
    }

    public function updateStatusPix($id)
    {
        DB::table('transferencia')->where('id', $id)->update(['status' => null]);

        return true;
    }
}
