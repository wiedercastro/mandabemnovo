<?php

namespace App\Http\Controllers;

use App\Models\Agencia;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class AgenciaController extends Controller
{
    public function index(): View
    {
        $data = new stdClass();

        $list = Agencia::select('agencias.*', DB::raw('COUNT(eo.id) as num_post'))
            ->leftJoin('envio_origem as eo', 'eo.agencia_id', '=', 'agencias.id')
            ->join('envios', 'envios.id', '=', 'eo.envio_id')
            ->where('envios.date_postagem', '>=', '2021-01-01')
            ->groupBy('agencias.id')
            ->orderByDesc('num_post')
            ->limit(1000)
            ->get();

        $list_arr = [];

        foreach ($list as $i) {
            $c = DB::table('envios')
                ->join('envio_origem as eo', 'eo.envio_id', '=', 'envios.id')
                ->where('envios.date_postagem', '>=', '2021-01-01')
                ->where('eo.agencia_id', '=', $i->id)
                ->distinct('envios.user_id')
                ->count();

            $i->num_clientes = $c;

            $list_arr[$i->id] = $i->toArray();
        }
        
        $data->list = $list_arr;

        return view('layouts.agencias.index', [
            'data' => $data->list
        ]);
    }

}
