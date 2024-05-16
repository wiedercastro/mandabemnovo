<?php

namespace App\Http\Controllers;

use App\Models\GrupoTaxa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GrupoTaxaController extends Controller
{
    public function __construct(protected GrupoTaxa $grupoTaxaModel) 
    { }
    
    public function grupo_taxa(): View
    {
        $faixas = [];
        $intervaloMinimo = 0;
        $intervaloMaximo = 14;
        $etapa = 10;
    
        while ($intervaloMaximo <= 150) {
            $faixa = [
                'min' => $intervaloMinimo != 0 ? number_format($intervaloMinimo, 2, ',', '') : '--',
                'max' => $intervaloMaximo <= 150 ? number_format($intervaloMaximo, 2, ',', '') : '--',
                'value' => ''
            ];
            array_push($faixas, $faixa);
    
            $intervaloMinimo += $etapa;
            $intervaloMaximo += $etapa;
        }

        return view('layouts.grupo-taxa.index', [
            'faixas' => $faixas,
            'grupos' => $this->grupoTaxaModel->getList()
        ]);
    }

    public function save(): JsonResponse
    {

        request()->validate([
            'name' => 'required',
            'application' => 'required',
            'type' => 'required',
            'tabela' => 'required',
        ]);

        $jsonResult = [];
        $data = request()->all();
        
        $grupoTaxa = $this->grupoTaxaModel->saveGrupoTaxa($data);
        if (! $grupoTaxa) {
            $jsonResult['status'] = 0;
            $jsonResult['error'] = "Erro";
        } else {
            $jsonResult['status'] = 1;
            $jsonResult['success'] = "Grupo Taxa salvo com sucesso.";
        }
        
        return response()->json($jsonResult);
    }
    
    public function remove(int $id): JsonResponse
    {
        $param = [];
        $param['id'] = $id;

        if ($this->grupoTaxaModel->delete($param)) {
            return response()->json(['success' => 'Deletada com sucesso!']);
        } else {
            return response()->json(['error' => 'Falha ao apagar Grupo de Taxa, tente novamente mais tarde.']);
        }
    }
}
