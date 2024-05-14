<?php

namespace App\Http\Controllers;

use App\Models\CorreioCache;
use App\Models\FaixaCep;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RotinaController extends Controller
{

    public function clean_cache_correios(): View
    {

        $limpa_origem_destino = false;
        $limpa_all_origem = true;
        $test_faixa = false;

        if ($test_faixa) {
            $cep_origem = '34006053';
            $cep_destino = '51011000';

            $faixa_origem = FaixaCep::where('cep_ini', '<=', $cep_origem)
                                ->where('cep_fim', '>=', $cep_origem)
                                ->first();

            $faixa_destino = FaixaCep::where('cep_ini', '<=', $cep_destino)
                                ->where('cep_fim', '>=', $cep_destino)
                                ->first();

           /*  echo "F Origem:\n";
            print_r($faixa_origem);
            echo "F Destino:\n";
            print_r($faixa_destino); */

            $list = CorreioCache::where('faixa_origem_id', $faixa_origem->id)
                            ->where('faixa_destino_id', $faixa_destino->id)
                            ->get();

         //   print_r($list);
        }

        if ($limpa_origem_destino) {
            $cep_origem = '05044010';
            $cep_destino = '05044010';

            $faixa_origem = FaixaCep::where('cep_ini', '<=', $cep_origem)
                                    ->where('cep_fim', '>=', $cep_origem)
                                    ->first();
            $faixa_destino = FaixaCep::where('cep_ini', '<=', $cep_destino)
                                    ->where('cep_fim', '>=', $cep_destino)
                                    ->first();
            
            $list = CorreioCache::where('faixa_origem_id', $faixa_origem->id)
                                ->where('faixa_destino_id', $faixa_destino->id)
                                ->get();

            foreach ($list as $i) {
                //print_r($i);
                $del = $i->delete();
                //var_dump($del);
            }
        }

        if ($limpa_all_origem) {
            $cep_origem = '22420030';

            $faixa_origem = FaixaCep::where('cep_ini', '<=', $cep_origem)
                                    ->where('cep_fim', '>=', $cep_origem)
                                    ->first();

            $list = CorreioCache::where('faixa_origem_id', $faixa_origem->id)
                                ->get();

            foreach ($list as $i) {
               // print_r($i);
                $del = $i->delete();
                //var_dump($del);
            }
        }
        //dd("OPA");

        return view('layouts.rotina.clean_cache_correios');
    }
}
