<?php

namespace App\Http\Controllers;

use App\Models\Cupom;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CupomAdminController extends Controller
{

    public function __construct(
        protected Cupom $cupom_model,
        protected User $user_model,
    )
    { }

    public function index(): View
    {
        $listCupons = $this->cupom_model->getCupons();

        $listCupons->each(function ($item) {
            $cont_cupom = $this->cupom_model->getCupomById($item->id);

            if ($cont_cupom){
                $item->qnt_usados = $cont_cupom;
            } else{
                $item->qnt_usados = 0;
            }
        });
        return view('layouts.cupom.admin.index', [
            'listCupons' => $listCupons,
            'afiliados'  => $this->user_model->getAfiliados()
        ]);
    }

    public function salvar(Request $request): JsonResponse
    {
        try {
            $this->cupom_model->salvar($request->all());
            
            return response()->json([
                'status' => 1,
                'msg' => 'Cupom Cadastrado com Sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
