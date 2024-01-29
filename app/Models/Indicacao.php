<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Indicacao extends Model
{
    public function get_list($data = [])
    {
        $userModel = new User();

        $list = DB::select('SELECT * FROM user WHERE ref_indication is not null');

        $result = [];
        foreach ($list as $i) {
            $result[] = [
                'user' => $userModel->get($i->ref_indication),
                'num_indicacoes' => DB::select('SELECT count(*) as total FROM user WHERE ref_indication = ?', [$i->ref_indication])[0]->total,
            ];
        }

        return $result;
    }
}
