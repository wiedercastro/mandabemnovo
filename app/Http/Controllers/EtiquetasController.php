<?php

namespace App\Http\Controllers;

use App\Enums\ManifestacaoObjetoEnum;
use App\Http\Requests\ProfileUpdateRequest;
use App\Libraries\Correios\Correio;
use App\Mail\ResumoAdminMail;
use App\Models\Coleta;
use App\Models\Envio;
use App\Models\Manifestacao;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use stdClass;

class EtiquetasController extends Controller
{

    public function __construct(
        protected Envio $envio,
        protected Coleta $coleta,
        protected Payment $payment
    ){ }

    public function index()
    {
        $view = "";

        // verifica se o usuário tem permissão de admin
        if (Gate::allows('user_admin_mandabem')) {
            $view = 'layouts.etiquetas.index_admin';
        } else {
            $view = 'layouts.etiquetas.index_cliente';
        }

        $mesAtual = now()->format('m');

        $envios = DB::table('coletas')
            ->join('envios', 'coletas.id', '=', 'envios.coleta_id')
            ->join('user', 'coletas.user_id', '=', 'user.id') 
            ->leftJoin('payment', 'payment.id', '=', 'coletas.id_payment')
            ->select(
                'coletas.id',
                'coletas.payment_id',
                'payment.fee as total_paypal',
                'user.razao_social', 
                'user.name as user_name',
                'user.date_insert as data_cliente_cadastro', 
                'user.plataform_integration', 
                DB::raw('COUNT(envios.id) as qte'),
                DB::raw('SUM(envios.valor_total) as total'),
                DB::raw('SUM(envios.valor_desconto) as desconto'),
                DB::raw('SUM(envios.valor_correios) as total_correios'),
                'coletas.type',
                DB::raw('(SELECT COUNT(id) FROM api_nuvem_shop WHERE api_nuvem_shop.user_id = coletas.user_id) as is_nuvem_shop')
            )
        ->where("coletas.user_id", "=", auth()->user()->id)
        ->groupBy("coletas.id")
        ->paginate(12);

        return view($view, [
            'envios'             => $envios,
            'mesAtual'           => getMeses($mesAtual),
            'anoAtual'           => now()->format('Y'),
            'totalEconomia'      => $this->envio->getTotalEconomia(),
            'totalEconomiaDoMes' => $this->envio->getTotalEconomiaDoMes(),
            'totalDivergencia'   => $this->envio->getTotalDivergencia(),
            'valorTotal'         => $this->envio->getTotal(),
            'totalSaldo'         => $this->payment->getCreditoSaldo($this->envio->getTotal())
        ]);
    }

    public function buscaDetalhesDasEtiquetas(int $idEtiqueta)
    {
        $etiquetas = $this->envio->getDetalhesEtiquetasUsuario($idEtiqueta);
        $usuarioLogado = auth()->user()->id;
        $data = new stdClass();

        $coletas = $this->coleta->getList();

        dd($coletas);
        foreach ($coletas as $coleta) {

            if ($usuarioLogado == 1) {
                $coleta->total_cobrado = 0;
                $coleta->total_balcao = 0;
                $coleta->total_correios = 0;
                $coleta->total_divergente = 0;
            }

            $coleta->envios = $this->coleta->getEnvios($coleta->id, $coleta->type);
            $coleta->has_AR = false;
            $total_coleta = 0;

            foreach ($coleta->envios as $i) {
                if ($i->AR) {
                    $coleta->has_AR = true;
                }
                $total_coleta += $i->valor_total;

                if ($usuarioLogado == 1) {
                    $coleta->total_cobrado += $i->valor_total;
                    $coleta->total_balcao += $i->valor_balcao;
                    $coleta->total_correios += $i->valor_correios;
                    $coleta->total_divergente += $i->valor_divergente;
                }
            }
            $coleta->creditos = $this->coleta->getCreditosPagos(['coleta_id' => $coleta->id, 'total_coleta' => $total_coleta]);
            $param_coletas_pagas = ['id_payment' => $coleta->id_payment];
            $coleta->divergencias = $this->coleta->getDivergenciasPagas($param_coletas_pagas);
            $coleta->divergencia_por_creditos = $this->coleta->getDivergenciaPorCredito(['coleta_id' => $coleta->id]);

            //Somar divergencias pagas neste coleta e NAO para esta coleta
            $coleta->divergencia_cobrada = 0;
            foreach ($coleta->divergencias as $d) {
                $coleta->divergencia_cobrada += $d->valor_divergente;
            }
            foreach ($coleta->divergencia_por_creditos as $d) {
                $coleta->divergencia_cobrada += $d->valor;
            }
            if ($coleta->type == 'REVERSA') {
                $coleta->reversa_destino = $this->coleta->getReversaDestino($coleta->id);
            }
        }

        $data->coletas = $coletas;

        if (! $etiquetas) {
        abort(204);
        }

        return response()->json(['data' => $etiquetas]);
    }

    public function getAuditor(int $idEtiqueta)
    {
        $auditor = $this->envio->getSendAuditor($idEtiqueta);

        if (! $auditor) {
        abort(204);
        }

        return response()->json([
            'auditor' => $auditor
        ]);
    }

    public function getManifestacao(int $idEtiqueta)
    {
        $manifestacao = $this->envio->getManifestacaoObjeto($idEtiqueta);

        if (! $manifestacao) {
        abort(204);
        }

        return response()->json(['manifestacao' => $manifestacao]);
    }

    public function manifestacaoObjeto(Request $request) {
        
        $usuarioLogado = auth()->user();

        if ($usuarioLogado->user_group_id != 1 && $usuarioLogado->user_id != 4974 && $usuarioLogado->user_id != 1928) {
            return response()->json(['error' => 'Usuário não permitido']);
        }
        $error = [];

        $params = ['id' => (int) $request->idEtiquetasManifestacao];

        if ($usuarioLogado->user_group_id == 1) {
            $params['user_id'] = 'mandabem';
        } else {
            $params['user_id'] = $usuarioLogado->user_group_id;
        }

        $envio = $this->envio->get($params);
        if (!$envio) {
            $error[] = "Envio não encontrado";
        }

        if (!$error) {
            if ($this->envio->hasManifestacao($envio->id) && $usuarioLogado->user_group_id != 1) {
                return response()->json(['error' => 'Manifestação ja registrada.']);
            }
        }

        /* REGRAS
          Objeto entregue ao destinatário => Remessa/Objeto postal entregue em local divergente
          Objeto devolvido ao remetente => Remessa/Objeto postal devolvida indevidamente
          Objeto não localizado no fluxo postal => Remessa/Objeto postal não entregue
          Objeto roubado => Remessa/Objeto postal não entregue
          Objeto entregue ao remetente => Remessa/Objeto postal devolvida indevidamente
         *          
        */

        $tiposMot = [
            "132" => "Remessa/Objeto Postal entregue em local divergente",
            "133" => "Remessa/Objeto Postal violada",
            "134" => "Remessa/Objeto Postal avariada/danificada",
            "135" => "Remessa/Objeto Postal entregue com atraso",
            "136" => "Remessa/Objeto Postal devolvida indevidamente",
            "141" => "Não recebimento do pedido de confirmação",
            "142" => "Remetente não recebeu o pedido de cópia",
            "148" => "Remetente não recebeu o AR",
            "211" => "Remessa/Objeto Postal não entregue",
            "240" => "AR Digital - Imagem não disponível",
            "1414" => "Remessa/Objeto Postal sem tentativa de entrega domiciliar"
        ];
        
        if ($usuarioLogado->user_group_id != 1) {
            unset($tiposMot['135']);
        }
        
        $data = new stdClass();
        
        if (!$error) {
            
            //$correio = new Correio();
            $cod_motivo = $request->tipo_remessa;

            // ta GERANDO ERRO NESSA FUNÇÃO DE postManifestacao



           /*  $info = $correio->postManifestacao([
                'envio' => $envio,
                'codigo_motivo' => $cod_motivo,
                'str_motivo' => $tiposMot[$cod_motivo]
            ]);

            if (!$info) {

                if (strlen($correio->getError())) {
                    $error[] = $correio->getError();
                } else {
                    $error[] = "O sistema dos Correios está instável, tente abrir daqui a um tempinho! Se o problema persistir, por gentileza, entre em contato com a gente pelo WhatsApp!";
                }

            } else {
                Manifestacao::create([
                    'envio_id' => $envio->id,
                    'numero_pi' => $info['numero_pi'],
                    'numero_lote' => $info['numero_lote'],
                    'objeto' => $envio->etiqueta_correios . 'BR',
                    'codigo_motivo' => $cod_motivo,
                    'str_motivo' => $tiposMot[$cod_motivo]
                ]);
            } */


           /*  Manifestacao::create([
                'envio_id' => $envio->id,
                'numero_pi' => '12',
                'numero_lote' => '2323',
                'objeto' => $envio->etiqueta_correios . 'BR',
                'codigo_motivo' => $cod_motivo,
                'str_motivo' => $tiposMot[$cod_motivo]
            ]); */
            
        }

        if (!$error) {
            return response()->json([
                'status'  => 1,
                'message' => 'Manifestação Registrada Com Sucesso'
            ]);
          /*   echo json_encode(['status' => 1, 'html' => '<h4 style="color: green;"><i class="fa fa-check"></i> Manifestação Registrada Com Sucesso<br><small>Protocolo: <strong>' . $info['numero_pi'] . '</strong></small><br><small>Motivo: <strong>' . $tiposMot[$cod_motivo] . '</strong></small></h4><br><button class="btn btn-danger" type="button" onclick="javascript: bootbox.hideAll()"><i class="fa fa-times"></i> Fechar</button>']);
            return; */
        }


        $check = $this->envio->hasManifestacao($envio->id);
        if ($check && $usuarioLogado->user_group_id != 1) {
            $error[] = "Envio já possui Manifestação Aberta.";
        }
        if ($error) {
            return response()->json(['error' => $error]);
        } else {
            return response()->json(['data' => $data]);
        }
    }
    
    public function getCancelamento(int $idEtiqueta)
    {
        $cancelamento = $this->envio->getManifestacaoObjeto($idEtiqueta);

        if (! $cancelamento) {
        abort(204);
        }

        return response()->json(['cancelamento' => $cancelamento]);
    }

    public function cancelaEnvio(Request $request)
    {
        $usuarioLogado = auth()->user();
        $idEtiqueta = $request->idEtiquetaCancelamento;

        $error = [];
    
        $params = ['id' => (int) $idEtiqueta];

        if ($usuarioLogado->user_group_id != 1) {
            $params['user_id'] = $usuarioLogado->user_id;
        } else {
            $params['user_id'] = 'mandabem';
        }

        $envio = Envio::where('id', '=', (int) $idEtiqueta)
                    ->where('user_id', '=', auth()->user()->id)
                    ->first();

        if (!$envio) {
            $error[] = "Envio não encontrado";
        }

        $cancel_postado = false;
        
        if ($envio->user_id != 5) {
            $cancel = $this->envio->cancelEnvio($envio);
        }

        if ($error) {
            return response()->json(['error' => $error]);
        } else {
            $cancel_postado = false;
            if ($cancel === 'cancel_postado') {
                $cancel_postado = true;
            }
            return response()->json($cancel_postado);
        }

        $check = $this->envio->cancelEnvio([
            'only_check' => true, 
            'envio'      => $envio
        ]);

        if (!$check) {
            return response()->json(['msg' => "ERROOOO 1"]);
        }

        if ($error) {
            return response()->json(['msg' => "ERROOOO 2"]);
        } else {
            return response()->json(['data' => $envio]);
            return;
        }
    }

    public function sendAuditor(Request $request)
    {
        /*
        * Usuario precisa ser admin mandabem
        */
        $envio = Envio::where('id', '=', (int) $request->idEtiquetaAuditor)
                    ->where('user_id', '=', auth()->user()->id)
                    ->first();

        $usuarioLogado = auth()->user();

        if (!$envio) {
            return response()->json(['error' => 'Envio não encontrado.'], 404);
        }

        $listaEmails = 
            explode(
                ',', 
                "rjgecomsupinfinite@correios.com.br,
                reginaldo@mandabem.com.br,
                renan@mandabem.com.br,
                taina@mandabem.com.br,
                carol@mandabem.com.br,
                andrea@mandabem.com.br,
                clayton@mandabem.com.br,
                daisy@mandabem.com.br,
                matheus@mandabem.com.br,
                samarah@mandabem.com.br,nelson@mandabem.com.br"
            );
            
        $listaEmails = array_map('trim', $listaEmails);

        try {
            Mail::to($listaEmails)->send(new ResumoAdminMail($usuarioLogado, $envio, $request->resumo));
            return response()->json([
                'success' => true,
                'message' => 'Mensagem Enviada com sucesso.'
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Falha ao enviar Email (Google). Por favor tente denovo.']);
        }
    }
    
    public function edit(Request $request): View
    {
        return view('profile.edit', [
        'user' => $request->user(),
        ]);
    }

    public function show($id)
    {
        $envios = $this->envio->where("id", $id)->paginate();
        return view('layouts.coleta.detalhesColeta', [
            'envios' => $envios
        ]);
    }


    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
        $request->user()->email_verified_at = null;
        }

        $request->user()->save();
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::to('/');
    }

    public function gerarEtiquetas(Request $request)
    {
        dd($request->all());
    }
}
