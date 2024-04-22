<?php

namespace App\Http\Controllers;

use App\Enums\ManifestacaoObjetoEnum;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Envio;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class EtiquetasController extends Controller
{
    public function __construct(
        protected Envio $envio,
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
    $
    $manifestacao = $this->envio->getManifestacaoObjeto($idEtiqueta);

    if (! $manifestacao) {
      abort(204);
    }

    return response()->json(['manifestacao' => $manifestacao]);
  }

  public function sendAuditor(Request $req)
  {
    //enviar email:

  /*   $envio = $this->envio_model->get(['id' => $id, 'user_id' => 'mandabem']);

    if (!$envio) {
        echo json_encode(['error' => 'Envio não encontrado.']);
        return;
    } */

    /* if ($this->input->post('confirm')) {

        $msg_to_auditor = $this->input->post('msg_to_auditor');

        $user = $this->user_model->get($envio->user_id);

        $subject = sprintf('MANDA BEM Cliente: %s | Objeto %s', $user->razao_social, $envio->etiqueta_correios . 'BR');

        $body = 'Olá Pessoal, tudo Bem?<br><br>';
        $body .= 'Codigo de Rastreio: ' . $envio->etiqueta_correios . 'BR' . '<br><br>';
        $body .= preg_replace("/\n/", '<br>', $msg_to_auditor);
        $body .= '<br>';
        $body .= 'Pode verificar por favor?';
        $body .= '<br><br>';
        $body .= 'Muito obrigado,<br>';
        $body .= 'Equipe Manda Bem';

        // claudiasil@correios.com.br,lucineaguiar@correios.com.br,
        // gracieledasilva@correios.com.br, chrissousa@correios.com.br
        $this->load->library('email_maker');
        $sent = $this->email_maker->msg(array(
            'server_send' => 'google',
            //                'to' => 'gracieledasilva@correios.com.br,claraines@correios.com.br,reginaldo@mandabem.com.br,renan@mandabem.com.br,taina@mandabem.com.br,carol@mandabem.com.br,andrea@mandabem.com.br,barbara@mandabem.com.br,clayton@mandabem.com.br,daisy@mandabem.com.br,gabriela@mandabem.com.br,matheus@mandabem.com.br,samarah@mandabem.com.br',
            'to' => 'rjgecomsupinfinite@correios.com.br,reginaldo@mandabem.com.br,renan@mandabem.com.br,taina@mandabem.com.br,carol@mandabem.com.br,andrea@mandabem.com.br,clayton@mandabem.com.br,daisy@mandabem.com.br,matheus@mandabem.com.br,samarah@mandabem.com.br,nelson@mandabem.com.br',
            //                'cc' => 'regygom@hotmail.com',
            'subject' => $subject,
            'msg' => $body,
            'email_from' => 'marcos@mandabem.com.br',
            'name_from' => 'Marcos Castro',
            'credenciais' => array('user' => 'marcos@mandabem.com.br', 'pass' => 'Maquinabem17!')
        ));
        if (!$sent) {
            echo json_encode(['error' => 'Falha ao enviar Email (Google). Por favor tente denovo.']);
            return;
        }
        echo json_encode(['status' => 1, 'html' => '<h5><i class="fa fa-check"></i> Mensagem Enviada com sucesso.</h5>']);

        return; */
     dd("CHEGOU");
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
