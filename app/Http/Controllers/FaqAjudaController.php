<?php

namespace App\Http\Controllers;

use App\Models\Faq;
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
}
