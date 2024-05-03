<?php

namespace App\Http\Controllers;

use App\Models\Mensagem;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class MensagemController extends Controller
{
    public function __construct(protected Mensagem $mensagem) 
    { }

    public function index(): View
    {
        return view('layouts.mensagem.index', [
            'messages' => $this->mensagem->getMensagens()
        ]);
    }
}
