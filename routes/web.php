<?php

use App\Http\Controllers\{
    AcompanhamentoController,
    ColetasController,
    EnderecoController,
    EtiquetasController,
    GerarEnvioController,
    PagamentoController,
    ProfileController,
    CotacaoController,
    ReversaController,
    SoapController,
    UserController
};

use App\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [SiteController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
  /*
  Gerar envios
  */
  Route::get('/gerar', [GerarEnvioController::class, 'index'])->name('gerar');
  Route::post('/saveEnvio', [GerarEnvioController::class, 'saveEnvio'])->name('saveEnvio');
  Route::get('/obter-dados-peso', [GerarEnvioController::class, 'buscarPeso'])->name('retornarPeso');
  Route::get('/obter-dados-estado', [GerarEnvioController::class, 'buscarEstado'])->name('retornarEstado');
  Route::post('/buscarDestinatiro', [GerarEnvioController::class, 'buscarDestinatiro'])->name('buscarDestinatiro');
  Route::get('/excluirEnvio/{id}',[GerarEnvioController::class, 'excluirEnvio'])->name('excluirEnvio');
  Route::post('/excluirEnviosSelecionados', [GerarEnvioController::class, 'excluirEnviosSelecionados'])->name('excluirEnviosSelecionados'); 
  Route::get('/buscarEnvio/{id}',[GerarEnvioController::class, 'buscarEnvio'])->name('buscarEnvio');

  /*
  Etiquetas
  */
  Route::get('/etiquetas', [EtiquetasController::class, 'index'])->name('etiquetas');
  Route::get('/etiquetas/{idEtiqueta}', [EtiquetasController::class, 'buscaDetalhesDasEtiquetas']);
  Route::get('/etiquetas/{id}', [EtiquetasController::class, 'show'])->name('etiqueta.show');
  Route::post('/gerar-etiquetas', [ColetasController::class, 'gerarEtiquetas']);

  /*
  Coletas
  */
  Route::get('/coleta/{id}',[ColetasController::class, 'getlistItens'])->name('coleta.show');

   /*
  Soap
  */
  Route::get('/buscaCep/{cep}',[EnderecoController::class, 'getCEp'])->name('endereco.show');

  Route::get('/soap', [SoapController::class, 'index']);

  /*
  Reversa
  */
  Route::get('/reversa',[ReversaController::class, 'index'])->name('reversa');

  /*
  Declaracoes
  */
  Route::get('/declaracoes',[ReversaController::class, 'index'])->name('declaracoes');

  /*
  Pagamentos
  */
  Route::get('/pagamentos',[PagamentoController::class, 'index'])->name('pagamentos.index');

  /*
  Cotação
  */
  Route::get('/cotacao',[CotacaoController::class, 'index'])->name('cotacao');

  /*
  Acompanhamento
  */
  Route::get('/acompanhamento',[AcompanhamentoController::class, 'index'])->name('acompanhamento');
  Route::get('/acomp_email/{id}',[AcompanhamentoController::class, 'busca_acomp_email']);
  Route::post('/acomp_email',[AcompanhamentoController::class, 'atualiza_acomp_email']);

/*
  Profile
  */
  Route::get('/users', [UserController::class, 'index'])->name('users');
  Route::get('/users/{id}', [UserController::class, 'show']);
  
  
  /*
  Profile
  */
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__ . '/auth.php';
