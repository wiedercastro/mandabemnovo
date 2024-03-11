<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Envio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;

class ColetasController extends Controller
{
  public function edit(Request $request): View
  {
    return view('profile.edit', [
      'user' => $request->user(),
    ]);
  }

  public function getlistItens($id)
  {
    $envios = Envio::where("coleta_id", $id)->paginate();
    //return view('layouts.dashboard',compact('envios'));

    // $coletas = DB::table('coletas')->join('envios','coletas.id','=','envios.coleta_id')->select('coletas.id',DB::raw('sum(envios.valor_total) as total'),DB::raw('sum(envios.valor_desconto) as desconto'))->where("coletas.id","=",$id)->groupBy("coletas.id")->get();
    // print_r($envios);exit;
    return response()->json(['html' => view('layouts.coleta.detalhesColeta', compact('envios'))->render()]);
  }

  public function teste()
  {
    return true;
  }

  public function gerarEtiquetas(Request $request)
  {
    dd($request);

    //        if($this->session->user_id >= 17719 && $this->session->user_id != 'xx17768') {
    //            $this->load->library('email_maker');
    //            $this->email_maker->msg([
    //                'to' => 'regygom@gmail.com',
    //                'subject' => 'Tentativa geração Etiqueta',
    //                'msg' => 'User Id: ' . $this->session->user_id
    //            ]);
    //            return;
    //        }
    //        $error[] = "PROBLEMAS NO WEBSERVICE DOS CORREIOS.<br>O servidor dos Correios não está respondendo à nossa solicitação, Por favor Atualize a página e tente novamente em alguns minutos.";
    //        echo json_encode(array('error' => implode(' ', $error)));
    //        return;
    // Erro trace dos correios para LOG
    $error_trace = '';

    // Flag para nao add mais erros na saida
    $has_msg_error = false;

    // Se For postado Algum envio
    if ($request) {

      // Cache da Coleta Para futura geração
      $cache_coleta = false;

      // Forma de gereção Industrial|Varejo
      //            $TIPO_CONTRATO = 'varejo';
      //            if ($this->input->post('tipo_contrato')) {
      //                $TIPO_CONTRATO = $this->input->post('tipo_contrato');
      //            }

      $this->load->library('correio/Correio', [], 'correio');
      $this->load->library('correio/CorreioRest', [], 'correioRest');

      $user_id = $this->session->user_id;
      $user = $this->user_model->get($user_id);

      if ($user->environment == 'test') {
        $this->load->library('paypal/PaypalPayment', array('environment' => 'test'), 'PaypalPayment');
      } else {
        $this->load->library('paypal/PaypalPayment', array(), 'PaypalPayment');
      }

      $error = array();

      // Obtendo Envios
      $valor_total = 0;
      $str_env = '';
      $xy = 0;
      foreach ($this->input->post('envios') as $e) {
        $str_env .= $e . ',';
        $xy++;
      }
      if (true) {
        if ($xy > 100) {
          $error[] = "Por favor: Selecione no máximo 100 envios por vez.";
        }
      }

      if ($str_env) {
        $str_env = substr($str_env, 0, -1);
      }

      $envios = $this->envio_model->get_by_ids(array('user_id' => $user_id, 'ids' => $str_env));

      // Checka se Houve envios
      if (!$envios) {
        //                $this->load->database();
        //                $this->db->where('date_insert >= "'.$this->date_utils->get_now(false).'" ');
        //                $this->db->where('plp IS NULL');
        //                $this->db->where('user_id', $this->session->user_id);
        //                $error_coletas = $this->db->get('coletas')->result();
        //                
        //                foreach ($error_coletas as $ec) {
        //                    $this->db->query("UPDATE envios SET coleta_id = NULL WHERE coleta_id = ? AND user_id = ?",[$ec->id, $this->session->user_id]);
        //                }
        $error[] = "Envios selecionados não foram encontrados ou ja foram gerados. Por favor, atualize a página e verifique se a Coleta ja não foi gerada.";
        //                $error[] = "PROBLEMAS NO WEBSERVICE DOS CORREIOS.<br>O servidor dos Correios não está respondendo à nossa solicitação, Por favor Atualize a página e tente novamente em alguns minutos.";
      }

      // Validando Se Codigos de Servicos estao preenchidos
      if ($user->group_code == 'cliente_contrato' && (!strlen($user->codigo_servico_pac) || !strlen($user->codigo_servico_sedex))) {
        $error[] = "Codigo de Servico PAC ou SEDEX invalidos.<br>Peça ao Administrador cadastra-los.";
      }
      //            $error[] = "Pessoal, estamos atualizando o nosso sistema agora pela amanhã, a previsão para volta da geração das etiquetas é 12:00. Desculpe pelos transtorno. Muito obrigado";
      //            $error[] = "Sistema dos Correios inativo, Por favor tente novamente mais tarde.";

      if (!$error) {
        // Soma dos Valores dos envios
        $_remetente_validation = array();
        $envio_normal = false;
        $envio_industrial = false;
        $count_cupom = 0;
        $count_cupom_desc = 0;
        $resto_cupom = 0;

        if ($user_id == 6727) {

          foreach ($envios as $envio) {

            if ($envio->etiqueta_correios != 'industrial') {
              $this->load->library('email_maker');
              $this->email_maker->msg(array(
                'subject' => 'Erro Industrial Coleta 6727',
                'msg' => "Envios:\n" . print_r($envios, true),
                'to' => 'reginaldo@mandabem.com.br'
              ));
            }
          }
        }


        foreach ($envios as $envio) {

          // CUPON DESCONTO
          if ($this->input->post('cupom_id')) {
            if ($this->input->post('cupom_type') == 'DESCONTO') {

              $count_cupom = $count_cupom + 1;
              if ($this->input->post('cupons_restam') >= $count_cupom) {
                $desc_cupom =  0;
                $desc_cupom =  ($envio->valor_total * $this->input->post('cupom_valor')) / 100;
                $count_cupom_desc =  $count_cupom_desc + $desc_cupom;
                $envio->valor_total = number_format($envio->valor_total - $desc_cupom, 2, '.', '');
                $valor_total += $envio->valor_total;

                $user_cupom = $this->cupom_model->get_cupom_id_user($this->input->post('cupom_id'), $this->session->user_id);
                if ($user_cupom) {
                  $this->db->where('id', $user_cupom->id);
                  $cup = $this->db->update('cupons_user', array('envio_id' => $envio->id));
                } else {
                  $data_cupom_insert = [
                    "cupom_id" => $this->input->post('cupom_id'),
                    "envio_id" => $envio->id,
                    "user_id" => $this->session->user_id,
                    "date_update" => $this->date_utils->get_now(),
                    "date_insert" => $this->date_utils->get_now(),
                  ];
                  $this->db->insert('cupons_user', $data_cupom_insert);
                }
              } else {
                $desc_cupom =  0;
                $valor_total += $envio->valor_total;
              }
            } else {
              $user_cupom = $this->cupom_model->get_cupom_id_user($this->input->post('cupom_id'), $this->session->user_id);
              $this->db->where('id', $user_cupom->id);
              $this->db->update('cupons_user', array('envio_id' => $envio->id));
              $valor_total += $envio->valor_total;
            }
          } else {
            $valor_total += $envio->valor_total;
          }
          // FINAL CUPON DESCONTO

          // Substituido pela regra acima
          //$valor_total += $envio->valor_total;



          $_remetente_validation[$envio->user_remetente_id] = $envio->user_remetente_id;

          if ($envio->etiqueta_correios == 'industrial') {
            $envio_industrial = true;
          } else {
            $envio_normal = true;
            if (($user_id == 5) || ($user_id == 6727) || ($user_id == 70648) || ($user_id == 42823) || ($user_id == 16947)) {
              $alterações['id_user_alt'] = $envio->user_id;
              $alterações['type'] = 'ERROR_INDUSTRIAL';
              $alterações['id_table'] = $envio->id;
              $alterações['data_before'] = json_encode($envio);
              $alterações['data_after'] = json_encode($envio);
              $alterações['date_insert'] = $this->date_utils->get_now();
              $this->db->insert('alt_registros_log', $alterações);
            }
          }
        }

        if (($user_id == 6727) || ($user_id == 70648) || ($user_id == 42823) || ($user_id == 16947) || ($user_id == 8483)) {
          $envio_industrial = true;
        }


        if ($this->input->post('cupom_id')) {
          if ($this->input->post('cupom_type') == 'CREDITO') {
            $valor_total = number_format($valor_total - $this->input->post('cupom_valor'), 2, '.', '');
          }
        }
        if ($envio_normal && $envio_industrial) {
          $error[] = "Envio Normal e Envio Industrial Selecionado. Por favor, selecione apenas envios do mesmo formato.";
        }

        //                if($user->id == 6727){
        //                    print_r($error);
        //                    print_r($envios);
        //                    exit;
        //                }


        $other_remetente = array();
        if (count($_remetente_validation) == 1) {
          $_remetente_id = (int) array_shift($_remetente_validation);
          if ($_remetente_id) {
            $info_remetente = $this->user_model->get_user_remetente($_remetente_id, $user->id);
            $other_remetente = object_to_array($info_remetente);
          }
        }

        if (count($_remetente_validation) > 1) {
          $error[] = "Voce selecionou envios que possuem mais de um Remetente.<br>Selecione envios de um único remetente.";
        }

        if (!$error) {

          $info_add_divergencia = $this->envio_model->get_divergencias(array('user_id' => $user_id));
          $valor_total_divergencia = $this->envio_model->get_divergencias(array('user_id' => $user_id, 'return_total' => true));
          $info_saldo = $this->payment_model->get_credito_saldo(array('user_id' => $user_id, 'valor_total' => ($valor_total + $valor_total_divergencia)));


          // Recupernado TOKEN de auth de pagamento Paypal
          $BA_PALPAL = $this->payment_model->get_authorization(array('user_id' => $user_id));

          // Flag para gerar pagamento
          $gerar_pagamento = true;

          //                    if ($user_id == '5') {
          if ($this->input->server('REMOTE_ADDR') == 'x131.0.217.34') {
            print_r([
              $valor_total + $valor_total_divergencia,
              ($valor_total + $valor_total_divergencia) - $info_saldo['valor_descontar'],
              number_format((($valor_total + $valor_total_divergencia) - $info_saldo['valor_descontar']), 2, '.', '') > 0 ? 'true' : 'false',
              number_format((($valor_total + $valor_total_divergencia) - $info_saldo['valor_descontar']), 2, '.', ''),
              $info_add_divergencia,
              $valor_total_divergencia,
              $info_saldo,
              $valor_total,
              $valor_total + $valor_total_divergencia,
            ]);
            exit;
          }

          // Desabilitando geração de Pagamento para estes usuarios 
          //                    if ($user_id == '5') { // || $user_id == 875
          //                        $gerar_pagamento = true;
          //                    }
        }
      }



      if (!$error) {

        // Gerando Coleta
        $param_coleta = array(
          'user_id' => $user_id,
          'type' => 'NORMAL',
          'environment' => $user->environment
        );

        $coleta_id = $this->coleta_model->save($param_coleta);

        if (!$coleta_id) {
          $error[] = "Falha ao gerar coleta, tente novamente mais tarde.";
        }
        // Final geracao coleta


        if (!$error) {
          // Inserindo linha pagamento
          $description_cobranca = "Conjunto de etiquetas $coleta_id";

          $data_payment = array(
            'user_id' => $user_id,
            'environment' => $user->environment,
            'description' => $description_cobranca,
            'obs' => '',
            'date' => $this->date_utils->get_now()
          );

          // Total da Coleta
          $data_payment['value'] = $valor_total;


          $info_saldo_divergencia = array();
          $info_saldo_coleta = array();
          $pagar_divegencia = false;

          // Se tem saldo: usar o saldo
          if ($info_saldo) {
            // se data[value] <= 0 significa que o saldo é suficiente
            $data_payment['value'] = number_format((($valor_total + $valor_total_divergencia) - $info_saldo['valor_descontar']), 2, '.', '');

            // Se o valor menos os creditos ainda for para descontar
            // vamos tirar a divergencia para se descontada via PayPal
            if ($data_payment['value'] > 0) {

              // tira o total divergente do saldo, e o resto vai descontar do paypal
              // 17 - (20 - 30) = 7
              $info_saldo_coleta = $this->payment_model->get_credito_saldo(array('user_id' => $user_id, 'valor_total' => ($valor_total)));
              $data_payment['value'] = number_format($valor_total - $info_saldo_coleta['valor_descontar'], 2, '.', '');
              if ($data_payment['value'] == 0) {
                $pagar_divegencia = true;
                //                                    $info_saldo_coleta = $this->payment_model->get_credito_saldo(array('user_id' => $user_id, 'valor_total' => ($valor_total - 0.10)));
                //                                    $data_payment['value'] = 0.10;
              } else {
                $info_saldo_coleta = $this->payment_model->get_credito_saldo(array('user_id' => $user_id, 'valor_total' => ($valor_total)));
              }

              // tem saldo suficiente
            } else {

              $info_saldo_coleta = $this->payment_model->get_credito_saldo(array('user_id' => $user_id, 'valor_total' => ($valor_total)));
              if ($valor_total_divergencia > 0) {
                $info_saldo_divergencia = $this->payment_model->get_credito_saldo(array('user_id' => $user_id, 'valor_total' => ($valor_total_divergencia)));
                $info_saldo_divergencia['divergencias'] = $info_add_divergencia;
              }
            }
            //$data_payment['value'] = number_format(( ( $valor_total ) - $info_saldo['valor_descontar']), 2, '.', '');
            //$valor_total_geral_descontado = number_format(( $info_saldo['valor_descontar'] - ( $valor_total + $valor_total_divergencia)), 2, '.', '');
          }
          // caso contrario vai cobrar sempre
          // 
          $log_tmp = "User " . print_r($user_id, true) . "\n";
          $log_tmp .= "Info Saldo:\n" . print_r($info_saldo, true) . "\n";
          $log_tmp .= "Info Saldo Coleta:\n" . print_r($info_saldo_coleta, true) . "\n";
          $log_tmp .= "Info Saldo Divergencia:\n" . print_r($info_saldo_divergencia, true) . "\n";
          $log_tmp .= "Info Add Divergencia:\n" . print_r($info_add_divergencia, true) . "\n";
          $log_tmp .= "Data Payment:\n" . print_r($data_payment, true) . "\n";
          $log_tmp .= "Valor Total:\n" . print_r($valor_total, true) . "\n";
          $log_tmp .= "Pagar Divergencia:\n" . print_r($pagar_divegencia, true) . "\n";

          if ($this->input->server('REMOTE_ADDR') == '131.0.217.34') {
            echo $log_tmp . "\n";
            exit;
          }

          //                    $this->log_model->log([
          //                        'user_id' => $user_id, 
          //                        'type' => 'COLETA_GERAR',
          //                        'text' => $log_tmp
          //                    ]);
          //                    print_r();
          //                    print_r();
          //                    print_r();
          //                    print_r();
          //                    echo "\nValor Total: ".$valor_total."\n";
          //                    var_dump();
          //                    exit;
        }

        // Gerando etiquetas
        if (!$error) {

          foreach ($envios as $envio) {
            $this->db->where('id', $envio->id);
            $this->db->where('coleta_id is null'); // Não pode ja ter coleta gerada
            if ($this->input->post('cupom_type') == 'DESCONTO') {
              $this->db->update('envios', array('coleta_id' => $coleta_id, 'valor_total' => $envio->valor_total));
            } else {
              $this->db->update('envios', array('coleta_id' => $coleta_id));
            }
            //$this->db->update('envios', array('coleta_id' => $coleta_id));
          }

          $coleta = $this->coleta_model->get(array('user_id' => $user->id, 'id' => $coleta_id));

          $dados_sedex = array();
          $dados_pac = array();
          $dados_pacmini = array();
          $dados_sedex_hj = array();
          $dados_sedex_12 = array();

          $s = 0;
          $p = 0;
          $pm = 0;
          $sh = 0; //sedex hoje
          $s12 = 0; //sedex 12
          $index = 0;
          foreach ($envios as $envio) {

            if (strtoupper($envio->forma_envio) == 'SEDEX') {
              $envio->index = $s;
              $dados_sedex[$s] = $envio;
              $s++;
            } else if (strtoupper($envio->forma_envio) == 'PAC') {
              $envio->index = $p;
              $dados_pac[$p] = $envio;
              $p++;
            } else if (strtoupper($envio->forma_envio) == 'PACMINI') {
              $envio->index = $pm;
              $dados_pacmini[$pm] = $envio;
              $pm++;
            } else if (strtoupper($envio->forma_envio) == 'SEDEX HOJE') {
              $envio->index = $sh;
              $dados_sedex_hj[$sh] = $envio;
              $sh++;
            } else if (strtoupper($envio->forma_envio) == 'SEDEX 12') {
              $envio->index = $s12;
              $dados_sedex_12[$s12] = $envio;
              $sh++;
            }
            $index++;
          }
          $total_sedex = count($dados_sedex);
          $total_pac = count($dados_pac);
          $total_pacmini = count($dados_pacmini);
          $total_sedex_hj = count($dados_sedex_hj);
          $total_sedex_12 = count($dados_sedex_12);

          $param_etiquetas = array(
            //                        'user' => object_to_array($user),
            'sedex' => $total_sedex,
            'pac' => $total_pac,
            'pacmini' => $total_pacmini,
            'sedex_hj' => $total_sedex_hj,
            'sedex_12' => $total_sedex_12,
          );

          //                    if ($envio_industrial) {
          //                        $param_etiquetas['is_industrial'] = true;
          //                    }

          if ($this->input->server('REMOTE_ADDR') == '177.185.221.225') {
            $error[] = "Falha teste";
            $cache_coleta = true;
            // Manter os envios na Coleta para tentar gerar novamente
            foreach ($envios as $envio) {
              $this->db->where('id', $envio->id);
              $this->db->where('coleta_id is null'); // Não pode ja ter coleta gerada
              $this->db->update('envios', array('coleta_id' => $coleta->id));
            }
          } else {
            //                        print_r($param_etiquetas);exit;
            //                        $data_etiquetas = $this->correio->solicitar_etiquetas($param_etiquetas);

            //                        if($this->session->user_id == '18') {
            //                            print_r($data_etiquetas);
            //                            print_r($param_etiquetas);exit;
            //                            echo "Init";
            //                        }

            //                        if (!$data_etiquetas) {
            //                            $error[] = $this->correio->get_error();
            //                        }
          }

          // Gerando PLP 
          if (!$error) {
            $param_plp = array(
              'user' => object_to_array($user),
              'etiquetas' => $param_etiquetas,
              'dados_sedex' => $dados_sedex,
              'dados_pac' => $dados_pac,
              'dados_pacmini' => $dados_pacmini,
              'dados_sedex_hj' => $dados_sedex_hj,
              'dados_sedex_12' => $dados_sedex_12,
              'coleta_id' => $coleta->id
            );

            if ($other_remetente) {
              $param_plp['other_remetente'] = $other_remetente;
            }
            if (!$envio_industrial) {
              $plp = $this->correioRest->gerar_ppn($param_plp);
            } else {
              // gerano PLP para formato industrial
              //                            $param_plp['tipo_contrato'] = 'industrial';
              //                            $param_plp['plp'] = 'industrial';
              //                            $plp = 'industrial';
              $param_plp['is_industrial'] = true;
              $plp = $this->correioRest->gerar_ppn($param_plp);
            }

            if (!$plp) {
              $error[] = $this->correio->get_error();
              $error_trace = $this->correio->get_error_trace();
            }
            //                        
          }

          // Final geração etiquetas
          // Salvando informações: Etiquetas e numero PLP
          if (!$error) {
            $param_plp['plp'] = $plp;
            $upd_coleta = $this->coleta_model->update_plp($param_plp);

            if (!$upd_coleta) {
              $error[] = "Falha ao gravar coleta, tente novamente mais tarde.";
            }
          }


          // Gerando Pagamento
          $has_payment = false;
          if (!$error && $user->group_code == 'cliente_sem_contrato' && $gerar_pagamento) {

            if ($data_payment['value'] > 0 || $pagar_divegencia) {
              //                        if (( $valor_total_geral_descontado > 0 && !$info_saldo) || ($info_saldo && $valor_total_geral_descontado < 0)) { // verificar total descontado, mais ao cobrar será feito separado


              if (!$BA_PALPAL && ($user->group_code != 'cliente_contrato')) {
                $error[] = $this->msg_cobranca_inativa;

                // Mensagem sobre saldo
                if ($info_saldo['valor_descontar'] > 0 && $valor_total_divergencia > 0) {
                  $error[] = "Somatório Etiquetas + Divergências maior que o saldo disponível." . "<br> Você tem o total de " . $this->utils->mask_money($valor_total_divergencia) . " em divergência.";
                } else if ($info_saldo['valor_descontar'] > 0) {
                  $error[] = "Somatório Coleta maior que o saldo disponível.";
                }
              }
              if (!$error) {
                $has_payment = true;
                $id_payment_local = $this->payment_model->add_payment($data_payment, $info_add_divergencia);

                $param = array();
                $param['billing_agreement_id'] = $BA_PALPAL->billing_agreement_id;
                $param['valor_total'] = $data_payment['value'];
                $param['invoice_id'] = $id_payment_local;
                $param['name'] = $description_cobranca;
                $param['description'] = $description_cobranca;
                $param['user_id'] = $user_id;

                if ($info_add_divergencia) {
                  foreach ($info_add_divergencia as $divergencia) {
                    $divergencia_desc = 'Divergencia Conjunto Etiquetas ' . $divergencia['coleta_id'] . " Envio " . $divergencia['cep'];
                    $param['cobranca_adicional'][] = array(
                      'valor_total' => $divergencia['valor_divergente'],
                      'name' => $divergencia_desc,
                      'description' => $divergencia_desc,
                      'invoice_id' => $divergencia['id']
                    );
                  }
                }

                $payment = $this->PaypalPayment->create_bil_payment($param);
                if (!$payment) {

                  $this->payment_model->update_info_return(array(
                    'id' => $id_payment_local,
                    'paypal_status' => $this->PaypalPayment->get_error(),
                  ));

                  if (true) {
                    $has_msg_error = true;
                    $error[] = $this->get_info_motivo_paypal($this->PaypalPayment->get_error_json());
                  } else {
                    if ($this->PaypalPayment->get_error() == 'INSTRUMENT_DECLINED') {
                      $error[] = "Somatório Etiquetas " . ($valor_total_divergencia > 0 ? "  + Divergências  " : '') . "maior que o saldo disponível.";
                      $error[] = "<h3>Cobrança Recusada :(</h3>";
                      $error[] = "Por favor, entre em contato com a operadora do seu cartão de crédito para liberar a transação e tente novamente.";
                    } else {
                      $error[] = "Somatório Etiquetas " . ($valor_total_divergencia > 0 ? "  + Divergências  " : '') . "maior que o saldo disponível.";
                      $error[] = "<h3>Cobrança Recusada :(</h3>";
                      $error[] = "Por favor, entre em contato com a operadora do seu cartão de crédito para liberar a transação e tente novamente.";
                      if ($this->input->server('REMOTE_ADDR') == '187.104.142.48') {
                        $error[] = ' Valor Total: ' . $data_payment['value'];
                        $error[] = print_r($info_add_divergencia, true);
                      }
                    }
                  }
                }

                if (!isset($payment['id'])) {
                  $this->payment_model->update_info_return(array(
                    'id' => $id_payment_local,
                    'paypal_status' => $this->PaypalPayment->get_error(),
                  ));
                  // nao precisa adicionar erro se ja tem
                  if (!$has_msg_error) {
                    $error[] = 'Falha, tente novamente mais tarde';
                  }
                } else if (!isset($payment['transactions'][0]['related_resources'][0]['sale']['id'])) {
                  $error[] = 'Falha, tente novamente mais tarde';
                }
              }
            }
          }
        }

        // Salvando ID do pagamento
        if (!$error) {
          $cobrou_divergencia = false;
          if ($has_payment && $user->group_code != 'cliente_contrato') {

            $PAYPAL_ID = $payment['transactions'][0]['related_resources'][0]['sale']['id'];
            $PAYPAL_STATUS = $payment['transactions'][0]['related_resources'][0]['sale']['state'];
            $PAYPAL_FEE = $payment['transactions'][0]['related_resources'][0]['sale']['transaction_fee']['value'];

            $this->coleta_model->update_info_payment(array(
              'coleta_id' => $coleta_id,
              'user_id' => $user_id,
              'id_payment' => $id_payment_local, # Ref Local do pagamento
              'payment_id' => $PAYPAL_ID,
              'payment_status' => $PAYPAL_STATUS
            ));

            if ($info_add_divergencia) {
              $cobrou_divergencia = true;
              $this->envio_model->update_indo_pagto_divergencia(array('id' => $id_payment_local), $info_add_divergencia);
            }

            $this->payment_model->update_info_return(array(
              'id' => $id_payment_local,
              'paypal_id' => $PAYPAL_ID,
              'paypal_status' => $PAYPAL_STATUS,
              'paypal_fee' => $PAYPAL_FEE,
            ));
          }
          $this->payment_model->update_credits2(array(
            'info_saldo_coleta' => $info_saldo_coleta,
            'info_saldo_divergencia' => $info_saldo_divergencia,
            'coleta_id' => $coleta_id,
            'user_id' => $user_id,
            'info_divergencia' => ($cobrou_divergencia ? array() : $info_add_divergencia),
          ));
        } else {

          // Liberando os envios para serem reenviados
          if (!$cache_coleta) {
            foreach ($envios as $envio) {
              $this->db->where('coleta_id', $coleta->id);
              $this->db->where('user_id', $user_id);
              $this->db->update('envios', array('coleta_id' => null));
            }
            $this->db->where('id', $coleta->id);
            $this->db->where('user_id', $user_id);
            $this->db->where('status is null');
            $this->db->update('coletas', array('status' => 'ERROR'));

            $this->db->insert('coleta_log', array(
              'coleta_id' => $coleta->id,
              'description' => implode("\n", $error),
              'date' => $this->date_utils->get_now()
            ));
          } else {
            $this->db->where('id', $coleta_id);
            $this->db->where('status is null'); // Status null = Coleta NOva
            $this->db->update('coletas', array('status' => 'PENDENTE_GERAR'));
          }
        }
      }

      if ($error) {
        //                if ($coleta) {
        $this->db->insert('coleta_log', array(
          'coleta_id' => isset($coleta->id) ? $coleta->id : null,
          'description' => $error_trace . "\n" . implode("\n", $error) . "\nUser: " . print_r($user, true),
          'date' => $this->date_utils->get_now()
        ));
        if (isset($coleta)) {
          foreach ($envios as $envio) {
            $this->db->where('coleta_id', $coleta->id);
            $this->db->where('user_id', $user_id);
            $this->db->update('envios', array('coleta_id' => null));
          }
        }
        //                }
        echo json_encode(array("status" => 0, 'error' => implode("<br>", $error)));
        exit;
      }

      // migrar declaracao 
      if (!$error) {
        $this->declaracao_model->migrate(array(
          'coleta_id' => $coleta->id,
          'user' => $user
        ));
      }


      $html_success = '';
      $html_success .= '<center>';
      $html_success .= '<h2 class="center">';
      $html_success .= '     <span style="color: green;"><strong>Parabéns!</strong></span><br>';
      //            if($this->session->user_id == 18 || $this->session->user_id == 3786) {
      if (true) {
        $html_success .= '     Etiqueta(s) gerada(s) com sucesso!';
      } else {
        $html_success .= '     Coleta gerada com sucesso!';
      }
      $html_success .= ' </h2>';

      if (true) {
        $html_success .= '<i class="fa fa-info-circle blue"></i> Faça a impressão pela Aba <a href="' . base_url('etiquetas') . '">Etiquetas</a>';
      }

      $html_success .= ' </center>';
      echo json_encode(array("status" => 1, 'msg' => $html_success));

      return;
    }
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
}
