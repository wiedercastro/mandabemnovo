<?php

use App\Http\Controllers\ColetasController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\EtiquetasController;
use App\Http\Controllers\GerarEnvioController;
use App\Http\Controllers\PagamentoController;
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

  /*
  Etiquetas
  */
  Route::get('/etiquetas', [EtiquetasController::class, 'index'])->name('etiquetas');
  Route::get('/etiquetas/{id}', [EtiquetasController::class, 'show'])->name('etiqueta.show');
  Route::get('/teste', [EtiquetasController::class, 'teste']);

  /*
  Coletas
  */
  Route::get('coleta/{id}',[ColetasController::class, 'getlistItens'])->name('coleta.show');

   /*
  Coletas
  */
  Route::get('/buscacep',[EnderecoController::class, 'getCEp'])->name('endereco.show');

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
  Pagamentos
  */
  Route::get('pagamentos',[PagamentoController::class, 'index'])->name('pagamentos.index');
  
  /*
  Profile
  */
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__ . '/auth.php';
