<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    protected $table = 'api_nuvem_shop';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function get($param)
    {
        if ($param['type'] == 'nuvemshop') {

            if (isset($param['code'])) {
                return DB::table('api_nuvem_shop')->where('code', $param['code'])->first();
            } else {
                return null;
            }
        }
    }

    public function updateApi($params = [])
    {
        $dataUpdate = [
            'domain' => $params['domain'],
        ];

        return DB::table('api')
            ->where('id', $params['id'])
            ->where('user_id', $params['user_id'])
            ->update($dataUpdate);
    }
}
