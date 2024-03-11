<?php

use App\Http\Controllers\ColetasController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\EtiquetasController;
use App\Http\Controllers\GerarEnvioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReversaController;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\SoapController;
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
  Route::get('/etiquetas/{id}', [EtiquetasController::class, 'show'])->name('etiqueta.show');
  Route::post('/gerar-etiquetas', [ColetasController::class, 'gerarEtiquetas']);
  Route::get('/teste', [EtiquetasController::class, 'teste']);

  /*
  Coletas
  */
  Route::get('coleta/{id}',[ColetasController::class, 'getlistItens'])->name('coleta.show');

   /*
  Soap
  */
  Route::get('/buscaCep/{cep}',[EnderecoController::class, 'getCEp'])->name('endereco.show');

  Route::get('/soap', [SoapController::class, 'index']);

  /*
  Reversa
  */
  Route::get('reversa',[ReversaController::class, 'index'])->name('reversa');

  /*
  Declaracoes
  */
  Route::get('declaracoes',[ReversaController::class, 'index'])->name('declaracoes');
  
  /*
  Profile
  */
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__ . '/auth.php';
