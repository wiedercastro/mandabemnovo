<?php

use App\Http\Controllers\{
    AcompanhamentoController,
    AfiliadosController,
    ColetasController,
    EnderecoController,
    EtiquetasController,
    GerarEnvioController,
    PagamentoController,
    ProfileController,
    CotacaoController,
    EstatisticasAdminController,
    FaqAjudaController,
    ManifestacaoController,
    NotFoundPermissionController,
    ReversaController,
    SimulacaoController,
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
  Route::get('/excluirEnvio/{id}',[GerarEnvioController::class, 'excluirEnvio'])->name('excluirEnvio');
  Route::post('/excluirEnviosSelecionados', [GerarEnvioController::class, 'excluirEnviosSelecionados'])->name('excluirEnviosSelecionados'); 
  Route::get('/buscarEnvio/{id}',[GerarEnvioController::class, 'buscarEnvio'])->name('buscarEnvio');
  Route::get('/buscaClientes',[GerarEnvioController::class, 'buscaClientes']);

  /*
  Etiquetas
  */
  Route::get('/etiquetas', [EtiquetasController::class, 'index'])->name('etiquetas');
  Route::get('/etiquetas/{idEtiqueta}', [EtiquetasController::class, 'buscaDetalhesDasEtiquetas']);
  Route::get('/etiquetas/send_auditor/{idEtiqueta}', [EtiquetasController::class, 'getAuditor']);
  Route::get('/etiquetas/manifestacao/{idEtiqueta}', [EtiquetasController::class, 'getManifestacao']);
  Route::post('/etiquetas/manifestacao', [EtiquetasController::class, 'manifestacaoObjeto']);
  Route::get('/etiquetas/cancelamento/{idEtiqueta}', [EtiquetasController::class, 'getCancelamento']);
  Route::post('/etiquetas/cancelamento', [EtiquetasController::class, 'cancelaEnvio']);
  Route::post('/etiquetas/send_auditor', [EtiquetasController::class, 'sendAuditor']);
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
  Estatisticas ADMIN
  */
  Route::get('/estatisticas',[EstatisticasAdminController::class, 'index'])->name('estatisticas_admin_index')->middleware('user_admin_mandabem');
  Route::get('/estatisticas/pega-estatisticas',[EstatisticasAdminController::class, 'getDadosEstatisticas'])->middleware('user_admin_mandabem');

  /*
  Pagamentos
  */
  Route::get('/pagamentos',[PagamentoController::class, 'index'])->name('pagamentos.index');
  Route::get('/transferencia',[PagamentoController::class, 'get_transferencia']);
  Route::get('/boleto',[PagamentoController::class, 'get_boletos']);
  Route::post('/afiliados-pagamentos',[PagamentoController::class, 'afiliados']);
  Route::post('/creditos',[PagamentoController::class, 'creditos']);
  Route::post('/cobranca',[PagamentoController::class, 'cobranca']);

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
  Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios')->middleware('user_admin_mandabem');
  Route::get('/usuarios/{id}', [UserController::class, 'show'])->middleware('user_admin_mandabem');
  Route::post('/usuarios', [UserController::class, 'update'])->middleware('user_admin_mandabem');
  
  
  /*
  Profile
  */
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  /*
  Afiliados
  */
  Route::get('/afiliados', [AfiliadosController::class, 'index'])->name('afiliados.index');
  
  /*
  FAQ Me ajuda
  */
  Route::get('/faq', [FaqAjudaController::class, 'index'])->name('faq.index');
  Route::post('/faq', [FaqAjudaController::class, 'store']);
  Route::get('/faq/{id}', [FaqAjudaController::class, 'show']);
  Route::put('/faq/{id}', [FaqAjudaController::class, 'update']);
  Route::delete('/faq/{id}', [FaqAjudaController::class, 'destroy']);

   /*
  Simulacao
  */
  Route::get('/tabela_simulacao', [SimulacaoController::class, 'index'])->name('simulacao.index');

   /*
  Simulacao
  */
  Route::get('/manifestacoes', [ManifestacaoController::class, 'index'])->name('manifestacao.index');
  
   /*
  Not Found Permission
  */
  Route::get('/not-found', NotFoundPermissionController::class)->name('not-found');
});



require __DIR__ . '/auth.php';
