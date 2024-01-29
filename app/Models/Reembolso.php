<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reembolso extends Model
{
    protected $table = 'isw_correios_OLD_etiquetas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    private $error;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('date_utils');
    }

    public function getList($param = [])
    {
        $query = DB::table('isw_correios_OLD_etiquetas as a')
            ->select('a.*')
            ->selectRaw('CONCAT(a.etiqueta, "BR") as objeto')
            ->select('a.etiqueta_status as status')
            ->select('a.solicitacao_indenizacao as protocolo')
            ->select('a.data_solicitacao_indenizacao as data')
            ->select('c.name as ecommerce')
            ->select('b.date_insert as date_envio')
            ->leftJoin('envios as b', 'b.etiqueta_correios', '=', 'a.etiqueta')
            ->leftJoin('user as c', 'c.id', '=', 'b.user_id');

        if (isset($param['type'])) {
            if ($param['type'] == 'atraso') {
                $query->whereNotNull('b.date_postagem')
                    ->whereNotLike('a.solicitacao_indenizacao', 'PROTOCOLO', 'both')
                    ->whereNotLike('a.solicitacao_indenizacao', 'ENTREGUE_OK', 'both')
                    ->whereNotLike('a.solicitacao_indenizacao', 'EXPIRADO', 'both');
            }
        } else {
            $query->where('a.solicitacao_indenizacao', 'like', 'PROTOCOLO%', 'right');
        }

        if (isset($param['user_id'])) {
            $query->where('b.user_id', $param['user_id']);
        }

        if (isset($param['get_total']) && $param['get_total']) {
            return $query->count();
        } else {
            $limit = isset($param['per_page']) ? $param['per_page'] : 10;
            $start = isset($param['page_start']) ? $param['page_start'] : 0;

            $query->limit($limit)->offset($start)->orderBy('b.date_insert', 'DESC');
            return $query->get()->toArray();
        }
    }
}
