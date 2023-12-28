<?php

use App\Http\Controllers\Admin\{ReplySupportController, SupportController};
use App\Http\Controllers\ColetaController;
use App\Http\Controllers\EtiquetaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Site\SiteController;
use App\Models\Envio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/login', [SiteController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $envios = DB::table('coletas')->join('envios','coletas.id','=','envios.coleta_id')->select('coletas.id',DB::raw('Count(envios.id) as qte'),DB::raw('sum(envios.valor_total) as total'),DB::raw('sum(envios.valor_desconto) as desconto'),'coletas.type')->where("coletas.user_id","=",5)->groupBy("coletas.id")->paginate();
    return view('layouts.dashboard',compact("envios"));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/etiquetas/{id}', [EtiquetaController::class, 'show'])->middleware(['auth', 'verified'])->name('etiqueta.show');

Route::get('/teste', [EtiquetaController::class, 'teste']);

Route::get('coleta/{id}',[ColetaController::class, "getlistItens"])->middleware(['auth', 'verified'])->name('coleta.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/supports/{id}/replies', [ReplySupportController::class, 'store'])->name('replies.store');
    Route::delete('/supports/{id}/replies/{reply}', [ReplySupportController::class, 'destroy'])->name('replies.destroy');
    Route::get('/supports/{id}/replies', [ReplySupportController::class, 'index'])->name('replies.index');

    // Route::resource('/supports', SupportController::class);
    Route::delete('/supports/{id}', [SupportController::class, 'destroy'])->name('supports.destroy');
    Route::put('/supports/{id}', [SupportController::class, 'update'])->name('supports.update');
    Route::get('/supports/{id}/edit', [SupportController::class, 'edit'])->name('supports.edit');
    Route::get('/supports/create', [SupportController::class, 'create'])->name('supports.create');
    Route::post('/supports', [SupportController::class, 'store'])->name('supports.store');
    Route::get('/supports', [SupportController::class, 'index'])->name('supports.index');
});

require __DIR__ . '/auth.php';
