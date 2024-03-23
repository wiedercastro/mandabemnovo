<?php

namespace App\Http\Controllers;

use App\Libraries\Iugu;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use stdClass;

class PixController extends Controller
{
  private $userModel;
  private $paymentModel;
  private $iugu;
  private $validation;
  public function __construct()
  {
    $this->userModel = new User();
    $this->paymentModel = new Payment();
    $this->iugu =  new Iugu();
    // $this->load->library('validation');
  }
  public function gerar(Request $request)
  {
    $dados = $request->all();
    $user_id = auth()->id();


    $valor_formated = number_format((float) preg_replace('/,/', '.', $dados['valor_pix']), 2, '.', '');
    $valor = preg_replace('/\./', '', number_format((float) preg_replace('/,/', '.', $dados['valor_pix']), 2, '.', ''));

    $creditos_antecipados = $this->paymentModel->getCreditosAntecipados($user_id);
    $action = isset($dados['action']) && $dados['action'] != "" ? $dados['action'] : null;

    if ($user_id == '18' && $creditos_antecipados && !strlen($action)) {
      $data = new stdClass();

      $data->creditos_antecipados = $creditos_antecipados;
      $data->valor_solicitado = $valor_formated;

      $total_creditos_antecipados = 0;
      foreach ($data->creditos_antecipados as $ca) {
        $total_creditos_antecipados += abs($ca->value);
      }
      $data->total_creditos_antecipados = $total_creditos_antecipados;

      $json['status'] = 1;
      $json['title'] = '<i class="fa fa-exclamation-triangle"></i> Aviso!';
      $json['html'] = $this->load->view('pagamento/pix_pgto_credito_antecipado', $data, true);
      $json['footer'] = '<button class="btn btn-danger" type="button" data-dismiss="modal">Fechar</button>';
      echo json_encode($json);
      return;
    }

    //Essa validação dever ser feita na chamada na tela
    if (!$valor || $valor < 0) {
      echo json_encode(['error' => 'Informe o valor a ser cobrado']);
      return;
    }
    if ($valor_formated < 1) {
      echo json_encode(['error' => 'Informe o valor a partir de R$ 1,00']);
      return;
    }

    $user = $this->userModel->get($user_id);

    if ($user->tipo_cliente == 'PJ' && strlen($user->cnpj) == 14 && $this->validation->valid_doc('cnpj', $user->cnpj)) {
      $DOC_USER = $user->cnpj;
    } else {
      if (strlen($user->cpf) != 11) {
        //validar essa funcao no service
        echo Modules::run('mandabem/user/box_info_cpf', $data);

        return;
      } else {
        $DOC_USER = $user->cpf;
      }
    }

    $data_gerar = [];
    $data_gerar['user'] = $user;
    $data_gerar['user_id'] = $user->id;
    $data_gerar['value'] = $valor; // pra IUGU formado 2500 = 25,00
    $data_gerar['valor'] = $valor_formated; // para salvar no banco 25.00
    $data_gerar['banco'] = 'pixiugu';
    $data_gerar['doc'] = $DOC_USER;
    $data_gerar['anexo'] = null;

    $creditos_antecipados_post = isset($dados['creditos_antecipados']) && $dados['creditos_antecipados'] != "" ? $dados['creditos_antecipados'] : null;

    //fazer verificacao desse Action na view antiga para entendimento
    if ($action == 'baixar' && $creditos_antecipados_post) {
      $total_creditos_antecipados = $this->paymentModel->sumCreditosAntecipados(['user_id' => $user_id, 'creditos_antecipados' => $creditos_antecipados_post]);

      if ($total_creditos_antecipados == 'FAIL_USER') {
        echo json_encode(['error' => 'Falha ao cobrar, tente novamente (Codigo do erro: 0001PTF)']);
        return;
      }
      if ($total_creditos_antecipados <= 0) {
        echo json_encode(['error' => 'Falha ao cobrar, tente novamente (Codigo do erro: 0002PTF)']);
        return;
      }

      $data_gerar['creditos_antecipados'] = $creditos_antecipados_post;
      $data_gerar['valor'] = $data_gerar['valor'] + $total_creditos_antecipados;
      $data_gerar['value'] = preg_replace('/\./', '', $data_gerar['valor']);
    }

    $transf_id = $this->paymentModel->saveTransferencia($data_gerar);

    $content = $transf_id->getContent();
    $resultados_array = json_decode($content, true);

    if (!$transf_id) {
      echo json_encode(['error' => 'Falha ao gerar dados, contate suporte']);
      return;
    }
  
    $data_gerar['order_id'] = $transf_id;
  
    $return = $this->iugu->gerar_cobranca($data_gerar);
    
    if (!$return) {
      $error[] = "Falha ao gerar, tente novamente mais tarde";
      echo json_encode(['error' => implode('<br>', $error)]);
      return;
    }
   
    $upd = $this->paymentModel->updateTransf([
      'id' => $resultados_array['id'],
      'invoice_id' => $return['id'],
      'anexo' => $return['secure_url']
    ]);
    
    if (!$upd) {
      echo json_encode(['error' => 'Falha ao salvar dados, contate suporte']);
      return;
    }

    return response()->json(["pix" => $return['pix']]);
  }
}
