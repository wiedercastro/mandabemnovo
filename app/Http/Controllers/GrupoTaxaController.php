<?php

namespace App\Http\Controllers;

use App\Models\GrupoTaxa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function save(Request $request)
    {        
        $messages = [
            'name.required' => 'O campo nome é obrigatório.',
            'application.required' => 'O campo aplicação é obrigatório.',
            'type.required' => 'O campo tipo é obrigatório.',
            'tabela.required' => 'O campo tabela é obrigatório.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'application' => 'required',
            'type' => 'required',
            'tabela' => 'required',
        ], $messages);
        
        if ($validator->fails()) { 
            return response()->json(['errors' => $validator->errors()], 422);
        } 

        if ($request->type == 'fixos') {
            if (empty($request->taxas[0]) || $request->taxas[0] === '0') {
                return response()->json(['errors' => [
                    'faixa' => ['Preencha o valor para a faixa: De -- até 14,00']
                ]], 422);
            } 
        }

        if ($request->type == 'percentual') {
            if (empty($request->percentual)) {
                return response()->json(['errors' => [
                    'percentual' => ['Informe o valor Percentual %']
                ]], 422);
            }
        }
        $jsonResult = [];
        $data = $request->all();
        $grupoTaxaId = $this->grupoTaxaModel->saveGrupoTaxa($data);

        if (! $grupoTaxaId) {
            $jsonResult['status'] = 0;
            $jsonResult['error'] = 'Erro ao salvar o grupo taxa';
        } else {
            $jsonResult['status'] = 1;
            $jsonResult['success'] = "Grupo Taxa salvo com sucesso.";
        }
        
        return response()->json($jsonResult);
    }
    
    public function remove(int $id): JsonResponse
    {
        if ($this->grupoTaxaModel->deleteGrupoTaxa($id)) {
            return response()->json(['success' => 'Deletada com sucesso!']);
        } else {
            return response()->json(['error' => 'Falha ao apagar Grupo de Taxa, tente novamente mais tarde.']);
        }
    }
}
