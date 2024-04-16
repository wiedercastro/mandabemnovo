<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqAjudaController extends Controller
{
    public function __construct(protected Faq $fac)
    { }

    public function index(Request $req)
    {
        return view('layouts.faq.index', [
            'faqs' => $this->fac->getListFaqs(filter: $req->filter ?? null)
        ]);
    }

    public function store(Request $req): JsonResponse
    {
        dd($req->all());

        return response()->json([
            'success' => true,
            'message' => "Pergunta deletada com sucesso!"
        ]);
    }
    
    public function show(int $id): JsonResponse
    {
        dd("EXIBIR FAQ: " . $id);

        return response()->json([
            'success' => true,
            'message' => "Pergunta deletada com sucesso!"
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => "Pergunta deletada com sucesso!"
        ]);
    }
    
}
