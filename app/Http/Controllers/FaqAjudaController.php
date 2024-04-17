<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqCategorie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqAjudaController extends Controller
{
    public function __construct(
        protected Faq $fac,
        protected FaqCategorie $facCategorie
    ){ }

    public function index(Request $req)
    {
        return view('layouts.faq.index', [
            'faqs'       => $this->fac->getListFaqs(filter: $req->filter ?? null),
            'categories' => $this->facCategorie->getListCategories(),
        ]);
    }

    public function store(Request $req): JsonResponse
    {
        Faq::query()->create([
            'category_id'      => (int) $req->categoria,
            'question'         => $req->pergunta,
            'answer'           => $req->resposta,
            'visible_mandabem' => $req->visivel_mandabem ? 1 : 0,
            'visible_customer' => $req->visivel_ecommerce ? 1 : 0,
            'user_id_creator'  => auth()->user()->id,
            'date_insert'      => now()->format('Y-m-d H:i:s')
        ]);
    
        return response()->json([
            'success' => true,
            'message' => "Pergunta criada com sucesso!"
        ]);
    }
    
    public function show(int $id): JsonResponse
    {
        $faq = Faq::query()->where('id', '=', $id)->first();

        if (! $faq) {
            abort(403);
        }

        return response()->json(['faq' => $faq]);
    }

    public function update(Request $req, int $id): JsonResponse
    {
        $faq = Faq::query()->where('id', '=', $id)->first();

        if (! $faq) {
            abort(403);
        }
        
        $faq->update([
            'category_id'      => (int) $req->categoria,
            'question'         => $req->pergunta,
            'answer'           => $req->resposta,
            'visible_mandabem' => $req->visivel_mandabem ? 1 : 0,
            'visible_customer' => $req->visivel_ecommerce ? 1 : 0,
            'user_id_creator'  => auth()->user()->id,
            'date_update'      => now()->format('Y-m-d H:i:s')
        ]);

        return response()->json([
            'success' => true,
            'message' => "Pergunta atualizada com sucesso!"
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $faq = Faq::query()->where('id', '=', $id)->first();

        if (! $faq) {
            abort(403);
        }

        $faq->delete();

        return response()->json([
            'success' => true,
            'message' => "Pergunta deletada com sucesso!"
        ]);
    }
    
}
