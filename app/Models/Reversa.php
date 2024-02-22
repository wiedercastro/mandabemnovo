<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reversa extends Model
{
    private $error;

    public function __construct()
    {
        parent::__construct();
    }

    public function getList($param = [])
    {
        if (!isset($param['user_id'])) {
            $this->error = "ID user não fornecido";
            return false;
        }

        $result = DB::table('coletas')
            ->select('coletas.*', 'user.name as user_name')
            ->join('user', 'user.id', '=', 'coletas.user_id')
            ->where('coletas.user_id', $param['user_id'])
            ->get();

        return $result->toArray();
    }
}
