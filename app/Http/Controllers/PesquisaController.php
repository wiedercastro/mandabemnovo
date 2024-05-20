<?php

namespace App\Http\Controllers;

use App\Models\UserResearch;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use stdClass;

class PesquisaController extends Controller
{
    public function __construct(protected UserResearch $user_research_model)
    { }

    public function research(): View
    {
        $data = new stdClass();
        $lista = [];

        $resultado = $this->user_research_model->getRespostasPesquisas(); 
        $resultado_nulos = $this->user_research_model->getRespostasPesquisasNulas(); 
                        
        $data->numero_respostas = count($resultado);
        $data->numero_respostas_nulas = count($resultado_nulos);

        foreach ($resultado as $i) {
            if (!isset($lista[$i->value])) {
                $lista[$i->value] = 0;
            }
            $lista[$i->value] ++;
        }

        krsort($lista);
        $resumo_bar = array();

        foreach ($lista as $key => $v) {         
            if ($key <= 6) {
                $resumo_bar[] = array(
                    'resposta' => 'Resp.' . $key,
                    'value' => $v
                );
            }
        
            switch ($key) {
                case 10:
                    $key = 'Dez';
                    break;
                case 9:
                    $key = 'Nove';
                    break;
                case 8:
                    $key = 'Oito';
                    break;
                case 7:
                    $key = 'Sete';
                    break;
                case 6:
                    $key = 'Seis';
                    break;
                case 5:
                    $key = 'Cinco';
                    break;
                case 4:
                    $key = 'Quatro';
                    break;
                case 3:
                    $key = 'Três';
                    break;
                case 2:
                    $key = 'Dois';
                    break;
                case 1:
                    $key = 'Um';
                    break;
            }
        
            $resumo_chart[] = array(
                'label' => '' . $key,
                'value' => $v
            );
        }
        
        $data->lista = $lista;
        $data->resumo['chart'] = $resumo_chart;
        $data->resumo['bar'] = $resumo_bar;
        
        return view('layouts.pesquisa.index', ['data' => $data]);
    }
}
