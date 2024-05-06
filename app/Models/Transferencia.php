<?php

namespace App\Models;

use Carbon\Carbon;
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
    }

    public function getDateInsertAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function getPixDeleteAuto($param = [])
    {
        $seteDias = now()->subDays(7)->startOfDay();

        $idUsuario = (int) $param['filter_cliente'];

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
