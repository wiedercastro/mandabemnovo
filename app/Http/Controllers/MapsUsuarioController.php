<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapsUsuarioController extends Controller
{
    public function index(): View
    {
        return view('layouts.maps.index', ['listaEstados' => getListaEstados(true)]);
    }

    public function maps(Request $request)
    {
        $inicio_data         = $request->date_inicio ?: date("Y-m-01 00:00:00");
        $fim_data            = $request->date_fim ?: now()->format('Y-m-d');
        $estado              = $request->estado ?: 'RJ';
        $agencias_industrial = $request->view_agencia_industriais;
    
        // Consulta para os usuários e contagem de envios
        $users = Envio::select('user.*', DB::raw('COUNT(envios.id) as num_envios'))
            ->join('user', 'user.id', '=', 'envios.user_id')
            ->where('user.uf', 'LIKE', $estado)
            ->whereBetween('envios.date_insert', [$inicio_data, $fim_data])
            ->whereNotNull('envios.date_postagem')
            ->whereNotNull('envios.coleta_id')
            ->whereNotNull('user.latitude')
            ->groupBy('user.id')
            ->orderBy('num_envios', 'DESC')
            ->limit(100)
            ->get();
    
        // Consulta para as agências
        if ($agencias_industrial === "sim") {
            $agencias = DB::table('agencias')
                ->select('nome', 'lat', 'lng')
                ->where('industrial', 1)
                ->get();
        } else {
            $agencias = DB::table('agencias')
                ->select('nome', 'lat', 'lng')
                ->whereNotNull('lat')
                ->where('uf', 'LIKE', $estado)
                ->get();
        }
    
        // Formatação das agências e dos usuários usando a notação de objeto
        foreach ($agencias as $a) {
            $a->position = "new google.maps.LatLng($a->lat, $a->lng)";
            $a->type = "agencias";
            $a->name = $a->nome;
        }
    
        foreach ($users as $u) {
            $u->position = "new google.maps.LatLng($u->latitude, $u->longitude)";
            $arr = explode(' ', $u->name);
            $u->name = "{$arr[0]} {$arr[count($arr) - 1]}(ENVIOS:{$u->num_envios})";
    
            if ($u->num_envios > 1000) {
                $u->type = "verde";
            } elseif ($u->num_envios < 1000 && $u->num_envios > 200) {
                $u->type = "vermelho";
            } else {
                $u->type = "azul";
            }
        }
    
        // converte as coleções para arrays antes de usar array_merge
        $agencias_array = $agencias->toArray();
        $users_array = $users->toArray();
    
        // combina os arrays
        if ($agencias_industrial === "sim") {
            $data = array_merge($agencias_array, $users_array);
        } else {
            $data = $users_array;
        }

        dd($data);

        return view('layouts.maps.index', [
            'data' => $data,
            'listaEstados' => getListaEstados(true)
        ]);

    }
    
}
