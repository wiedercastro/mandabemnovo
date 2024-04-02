<?php

namespace App\Http\Controllers;

use App\Libraries\Correios\CalIndustrial;
use App\Libraries\Correios\CorreioRest;
use App\Libraries\Utils;
use App\Models\Envio;
use App\Models\User;
use App\Services\EnderecoService;
use Illuminate\Http\Request;
use Modules;
use Illuminate\Support\Facades\DB;

class SimuladorController extends Controller
{
    private $error;
    private $userModel;
    private $envioModel;
    private $correioRest;
    private $enable_sedex_hoje;
    private $enable_sedex_doze;
    private $CalIndustrial;

    private $enderecoService;

    private $utils;

    public function __construct()
    {

        $this->userModel = new User();
        $this->envioModel = new Envio();
        $this->correioRest = new CorreioRest();
        $this->CalIndustrial = new CalIndustrial();
        $this->enderecoService = new EnderecoService();
        $this->utils = new Utils();

        $id = auth()->id();

        if (isset($id) && $id) {

            $user = $this->userModel->get($id);
            $user_config = $this->userModel->get_config($user);

            if (isset($user_config['config_enable_sedex_hoje']) && (int) $user_config['config_enable_sedex_hoje']) {
                $this->enable_sedex_hoje = true;
            }
            if (isset($user_config['config_enable_sedex_12']) && (int) $user_config['config_enable_sedex_12']) {
                $this->enable_sedex_doze = true;
            }
        }
    }
    public function simuladorCotacao(Request $request)
    {
        $dados = $request->all();
       
        if (isset($dados['largura']) && $dados['largura'] != null) {
            $largura = explode(' cm', $dados['largura']);
            $dados['largura'] = (int)trim($largura[0]);
        }

        if (isset($dados['altura']) && $dados['altura'] != null) {
            $altura = explode(' cm', $dados['altura']);
            $dados['altura'] = (int)trim($altura[0]);
        }

        if (isset($dados['comprimento']) && $dados['comprimento'] != null) {
            $comprimento = explode(' cm', $dados['comprimento']);
            $dados['comprimento'] = (int)trim($comprimento[0]);
        }
        //  dd($dados);
        $id = auth()->id();

        $dados['user_id'] = $id;

        $error = array();

        // configurações do usuario
        $user_config = null;

        if ($id) {
            $user = $this->userModel->get($id);
        }

        if (isset($user)) {

            $user_config = $this->userModel->getConfig($user);
        }

        if ($id) {
            if (isset($dados['remetente_cep'])) {
                $dados['cep_origem'] = $dados['remetente_cep'];
            } else {

                $dados['cep_origem'] = $dados['cep_origem'] ? $dados['cep_origem'] : $user->CEP;
            }
        }
        // Setando Industrial para o usuario
        if ((isset($user_config['config_enable_industrial']) && (int) $user_config['config_enable_industrial']) || ($user->id == 5 || $user->id == 6727 || $user->id == 16947 || $user->id == 62885 || $user->id == 10743)) {
            $dados['is_industrial'] = true;
        }


        if (strlen(preg_replace('/[^0-9]/', '', $dados['cep_origem'])) != 8) {
            $error[] = "Preencha o CEP de Origem corretamente.";
        }
        if (strlen(preg_replace('/[^0-9]/', '', $dados['cep_destino'])) != 8) {
            $error[] = "Preencha o CEP de Destino corretemente.";
        }

        if (!$error) {
            $cepParamOrigem['cep'] = $dados['cep_origem'];
            $cepParamDestino['cep'] = $dados['cep_destino'];
            //faz verificação dos ceps
            $data_origem = $this->enderecoService->get_by_cep($cepParamOrigem);
            $data_destino = $this->enderecoService->get_by_cep($cepParamDestino);

            if (is_array($data_origem) && isset($data_origem['error'])) {
                $error[] = "CEP origem invalido";
            }
            if (is_array($data_destino) && isset($data_destino['error'])) {
                $error[] = "CEP destino invalido";
            }

            if (!$error) {
                if (isset($dados['valor_declaracao']) && $dados['valor_declaracao']) {
                    $dados['seguro'] = (float) number_format(preg_replace('/,/', '.', $dados['valor_declaracao']), 2, '.', '');

                    if ($dados['seguro'] < 12.25) {
                        echo json_encode(['error' => 'Valor Assegurado, quando informado precisa ser a partir de R$ 12,25']);
                        return;
                    }
                }

                $dados['cal_seguro_local'] = true;

                $dados['forma_envio'] = 'SEDEX';


                // validar e avisar cliente seguro SEDEX sem contrato
                $info_seguro_sedex = '';
                if (isset($dados['seguro']) && ((float) $dados['seguro'] < 24.50 || (float) $dados['seguro'] > 10000)) {
                    $info_seguro_sedex = "Cotação sem seguro, para SEDEX o seguro precisa ficar entre R$ 24,50 e R$ 10000,00";
                    unset($dados['seguro']);
                }
                $dados['peso'] = $dados['peso'] == "0.300" ? (float) $dados['peso'] : (int) $dados['peso'];
                // dd($dados);
                $info_sedex = $this->envioModel->simuleEnvio($dados);


                // Retornando o valor do seguro
                if (isset($dados['valor_declaracao']) && $dados['valor_declaracao']) {
                    $dados['seguro'] = (float) number_format(preg_replace('/,/', '.', $dados['valor_declaracao']), 2, '.', '');
                }
                // Final retorno do valor do seguro


                $erro_sedex = '';
                if (!$info_sedex) {
                    $erro_sedex = $this->envioModel->getError();
                }

                if (preg_match('/CEP de destino invalido/', $erro_sedex)) {
                    echo json_encode(array('error' => "Falha: CEP de Destino invalido. Faça a correção e tente novamente."));
                    return;
                }


                // validar e avisar cliente seguro PAC sem contrato
                $info_seguro_pac = '';
                if (isset($dados['seguro']) && ((float) $dados['seguro'] < 24.50 || (float) $dados['seguro'] > 3000)) {
                    $info_seguro_pac = "Cotação sem seguro, para PAC o seguro precisa ficar entre R$ 24,50 e R$ 3000,00";
                    unset($data_post['seguro']);
                }

                $dados['forma_envio'] = 'PAC';

                $info_pac = $this->envioModel->simuleEnvio($dados);

                // Retornando o valor do seguro
                if (isset($dados['valor_declaracao']) && $dados['valor_declaracao']) {
                    $dados['seguro'] = (float) number_format(preg_replace('/,/', '.', $dados['valor_declaracao']), 2, '.', '');
                }
                // Final retorno do valor do seguro

                $erro_pac = '';
                //  if (!$info_pac) {
                //      $erro_pac = $this->envio_model->get_error();
                //      $this->load->library('email_maker');
                //      $this->email_maker->msg([
                //          'to' => 'regygom@gmail.com',
                //          'subject' => 'Simulacao PAC',
                //          'msg' => '<pre>' . print_r($this->envio_model->get_error(), true) . "\n" . print_r($data_post, true) . '</pre>'
                //      ]);
                //  }

                //                        if ($this->input->server('REMOTE_ADDR') == '149.19.164.18') {
                //                            
                //                            echo "ERROS\n";
                //                            
                //                            print_r($info_pac);
                //                            print_r($info_sedex);
                //                            
                //                            echo $erro_pac;
                //                            echo $erro_sedex;
                //                            exit;
                //                        }

                //                        if (!$info_pac || !$info_sedex) {
                if (!$info_sedex) {
                    if (preg_match('/Somatório deve ser menor do que 200/', $erro_sedex)) {
                        $error[] = $this->envioModel->getError();
                    } else if (preg_match('/CEP de (.*?)inv(.*?)lido/i', $erro_sedex)) {
                        $error[] = $this->envioModel->getError();
                    } else {

                        //                                $this->load->library('email_maker');
                        //                                $this->email_maker->msg([
                        //                                    'to' => 'reginaldo@mandabem.com.br',
                        //                                    'subject' => 'Simulacao Sedex',
                        //                                    'msg' => '<pre>' . print_r($this->envio_model->get_error(), true) . "\n" . print_r($data_post, true) . '</pre>'
                        //                                ]);
                        $this->error = $this->correioRest->getError();
                        if ($this->error) {
                            $error[] = $this->error;
                        } else {
                            $error[] = "Desculpe-nos mas não conseguimos realizar a simulação, tente mais tarde";
                        }
                    }
                }
            }
        }

        if (!$error) {

            // validar e avisar cliente seguro SEDEX sem contrato
            $info_seguro_sedex = '';
            if (isset($dados['seguro']) && ((float) $dados['seguro'] < 24.50 || (float) $dados['seguro'] > 10000)) {
                $info_seguro_sedex = "Cotação sem seguro, para SEDEX o seguro precisa ficar entre R$ 24,50 e R$ 10000,00";
                unset($dados['seguro']);
            }

            $info_pac_mini = [];

            $dados['cod_empresa'] = '18086160';
            $dados['senha_empresa'] = '27347642';
            $dados['forma_envio'] = 'SEDEX';
            $dados['normalize_currency'] = true;
            //                    $info_sedex_contrato = $this->envio_model->simule_envio($data_post);
            //                    
            if (isset($user_config['config_enable_industrial']) && (int) $user_config['config_enable_industrial']) {
                $info_sedex_contrato = $this->CalIndustrial->calc($dados, $user->grupo_taxa);
            } else {
                $info_sedex_contrato = $this->envioModel->simuleEnvio($dados);
            }

            // final sedex

            if ($this->enable_sedex_hoje) {
                $info_seguro_sedex_hoje = '';
                if (isset($dados['seguro']) && ((float) $dados['seguro'] < 24.50 || (float) $dados['seguro'] > 10000)) {
                    $info_seguro_sedex = "Cotação sem seguro, para SEDEX o seguro precisa ficar entre R$ 24,50 e R$ 10000,00";
                    unset($dados['seguro']);
                }

                $info_pac_mini = [];

                $dados['cod_empresa'] = '18086160';
                $dados['senha_empresa'] = '27347642';
                $dados['forma_envio'] = 'SEDEX HOJE';
                $dados['normalize_currency'] = true;
                //                        $info_sedex_hoje_contrato = $this->envio_model->simule_envio($data_post);
                if (isset($user_config['config_enable_industrial']) && (int) $user_config['config_enable_industrial']) {
                    $info_sedex_hoje_contrato = $this->CalIndustrial->calc($dados, $user->grupo_taxa);
                } else {
                    $info_sedex_hoje_contrato = $this->envioModel->simuleEnvio($dados);
                }
            }

            if ($this->enable_sedex_doze) {
                $info_seguro_sedex_doze = '';
                if (isset($dados['seguro']) && ((float) $dados['seguro'] < 24.50 || (float) $dados['seguro'] > 10000)) {
                    $info_seguro_sedex = "Cotação sem seguro, para SEDEX o seguro precisa ficar entre R$ 24,50 e R$ 10000,00";
                    unset($dados['seguro']);
                }

                $info_pac_mini = [];

                $dados['cod_empresa'] = '18086160';
                $dados['senha_empresa'] = '27347642';
                $dados['forma_envio'] = 'SEDEX 12';
                $dados['normalize_currency'] = true;

                //                        $info_sedex_doze_contrato = $this->envio_model->simule_envio($data_post);
                if (isset($user_config['config_enable_industrial']) && (int) $user_config['config_enable_industrial']) {
                    $info_sedex_doze_contrato = $this->CalIndustrial->calc($dados, $user->grupo_taxa);
                } else {
                    $info_sedex_doze_contrato = $this->envioModel->simuleEnvio($dados);
                }
            }


            // Retornando o valor do seguro
            if (isset($dados['valor_declaracao']) && $dados['valor_declaracao']) {
                $dados['seguro'] = (float) number_format(preg_replace('/,/', '.', $dados['valor_declaracao']), 2, '.', '');
            }
            // Final retorno do valor do seguro

            $result = array();
            $result['SEDEX']['sem_contrato'] = $info_sedex;
            $result['SEDEX']['contrato'] = $info_sedex_contrato;

            if (isset($result['SEDEX']['sem_contrato']['valor']) && $result['SEDEX']['sem_contrato']['valor'] == 0) {
                echo json_encode(array('error' => "Falha: Sistema dos Correios não respondeu à nossa Requisição, por gentileza tente novamente mais tarde."));
                return;
            }

            if ($this->enable_sedex_hoje) {

                if ($info_sedex_hoje_contrato) {

                    //                            $result = array();
                    $result['SEDEX HOJE']['sem_contrato'] = ['status' => 1, 'msg' => '0', 'valor' => '--', 'prazo' => ''];
                    $result['SEDEX HOJE']['contrato'] = $info_sedex_hoje_contrato;
                }
            }
            if ($this->enable_sedex_doze) {

                if ($info_sedex_doze_contrato) {

                    //                            $result = array();
                    $result['SEDEX 12']['sem_contrato'] = ['status' => 1, 'msg' => '0', 'valor' => '--', 'prazo' => ''];
                    $result['SEDEX 12']['contrato'] = $info_sedex_doze_contrato;
                }
            }

            $tmp_valor_pac = (float) preg_replace('/,/', '.', $info_pac['valor']);
            $tmp_valor_sed = (float) preg_replace('/,/', '.', $info_sedex['valor']);

            if (!$erro_pac && !$erro_sedex) {
                if ($tmp_valor_pac < 8 || $tmp_valor_sed < 8) {
                    echo json_encode(array('error' => "Falha: Sistema dos Correios não respondeu à nossa Requisição, por gentileza tente novamente mais tarde."));
                    return;
                }
            }
            if (!$erro_sedex) {
                if ($tmp_valor_sed < 8) {
                    echo json_encode(array('error' => "Falha: Sistema dos Correios não respondeu à nossa Requisição, por gentileza tente novamente mais tarde."));
                    return;
                }
            }


            if (($data_origem->ibge != $data_destino->ibge) && $tmp_valor_pac < $tmp_valor_sed) {

                $info_seguro_pac = '';

                if (isset($dados['seguro']) && ((float) $dados['seguro'] < 24.50 || (float) $dados['seguro'] > 3000)) {
                    $info_seguro_pac = "Cotação sem seguro, para PAC o seguro precisa ficar entre R$ 24,50 e R$ 3000,00";
                    unset($dados['seguro']);
                }

                $dados['forma_envio'] = 'PAC';
                //                        $info_pac_contrato = $this->envio_model->simule_envio($data_post);
                //                        if ($this->input->server('REMOTE_ADDR') == '45.181.35.133') {
                //                            print_r($this->error);exit;
                //                        }
                if (isset($user_config['config_enable_industrial']) && (int) $user_config['config_enable_industrial']) {
                    $info_pac_contrato = $this->CalIndustrial->calc($dados, $user->grupo_taxa);
                } else {
                    $info_pac_contrato = $this->envioModel->simuleEnvio($dados);
                }


                //                        if ($this->input->server('REMOTE_ADDR') == '149.19.164.18') {
                //                            print_r($info_pac_contrato);
                //                            echo $this->envio_model->get_error();
                //                            exit;
                //                        }

                // Retornando o valor do seguro
                if (isset($dados['valor_declaracao']) && $dados['valor_declaracao']) {
                    $dados['seguro'] = (float) number_format(preg_replace('/,/', '.', $dados['valor_declaracao']), 2, '.', '');
                }
                // Final retorno do valor do seguro


                if ($info_pac_contrato) {
                    $result['PAC']['sem_contrato'] = $info_pac;
                    $result['PAC']['contrato'] = $info_pac_contrato;

                    if (isset($result['PAC']['sem_contrato']['valor']) && $result['PAC']['sem_contrato']['valor'] == 0) {
                        echo json_encode(array('error' => "Falha: Sistema dos Correios não respondeu à nossa Requisição, por gentileza tente novamente mais tarde."));
                        return;
                    }


                    if (true) { // && $this->session->user_id == 18
                        if (!isset($user_config)) {
                            $user_config['config_enable_pacmini'] = 0;
                        }

                        if ((float) $dados['peso'] <= 0.3 && (!isset($user_config['config_enable_pacmini']) || (int) $user_config['config_enable_pacmini'])) {

                            $info_seguro_pacmini = '';

                            if (isset($dados['seguro']) && ((float) $dados['seguro'] < 12.25 || (float) $dados['seguro'] > 100)) {
                                $info_seguro_pacmini = "Cotação sem seguro, para Envio Mini o seguro precisa ficar entre R$ 12,25 e R$ 100,00";
                                unset($dados['seguro']);
                            }

                            $dados['forma_envio'] = 'PACMINI';
                            //                                    $info_pac_mini = $this->envio_model->simule_envio($data_post);
                            if (isset($user_config['config_enable_industrial']) && (int) $user_config['config_enable_industrial']) {
                                $info_pac_mini = $this->CalIndustrial->calc($dados, $user->grupo_taxa);
                            } else {
                                $info_pac_mini = $this->envioModel->simuleEnvio($dados);
                            }

                            if ($info_pac_mini) {
                                $result['PACMINI']['sem_contrato'] = $info_pac;
                                $result['PACMINI']['contrato'] = $info_pac_mini;
                            }

                            // Retornando o valor do seguro
                            if (isset($dados['valor_declaracao']) && $dados['valor_declaracao']) {
                                $dados['seguro'] = (float) number_format(preg_replace('/,/', '.', $dados['valor_declaracao']), 2, '.', '');
                            }
                            // Final retorno do valor do seguro
                            //                                    print_r($info_sedex);
                            //                                    print_r($info_pac);
                            //                                    print_r($info_pac_mini);
                            //                                    exit;
                        }
                    }
                }
            }

            //                    if ($this->input->server('REMOTE_ADDR') == '149.19.164.18') {
            //                        print_r($result); exit;
            //                    }

            //   $http_user_agent = $this->input->server('HTTP_USER_AGENT');
            $http_user_agent = $request->header('User-Agent');
            $iphone = strpos($http_user_agent, "iPhone");
            $android = strpos($http_user_agent, "Android");
            $palmpre = strpos($http_user_agent, "webOS");
            $berry = strpos($http_user_agent, "BlackBerry");
            $ipod = strpos($http_user_agent, "iPod");
            $ipad = strpos($http_user_agent, "iPad");

            if ($iphone || $android || $palmpre || $ipod || $ipad || $berry) {
                $data['device'] = "mobile";
            } else {
                $data['device'] = "desktop";
            }
            $data['device'] = 'desktop';
            //                    if ($this->input->server('REMOTE_ADDR') == '177.185.208.242') {
            if ($data['device'] != 'desktop') {
                $html = '<div class="row">';
                $html .= '<div class="col-md-12">';
                $html .= '<h5><small>Opções de Frete para:</small> ' . $data_destino->cidade . '</h5>';
                foreach ($result as $forma_envio => $i) {
                    $forma_envio_str = $forma_envio;
                    if ($forma_envio == 'PACMINI') {
                        $forma_envio_str = 'Envio Mini';
                    }

                    $i['contrato']['valor'] = preg_replace('/,/', '.', $i['contrato']['valor']);
                    $i['sem_contrato']['valor'] = preg_replace('/,/', '.', $i['sem_contrato']['valor']);

                    if ($user->id == '28730' && $user_config && (isset($user_config['config_enable_industrial']) && (int) $user_config['config_enable_industrial'])) {



                        //                                    echo "Valor normal: " . $forma_envio . " -- " . $i['contrato']['valor'];
                        //                                    
                        //                                    echo "<br>\n";
                        //                                    
                        //                                    echo $this->envio_model->get_taxa_envio(
                        //                                                array(
                        //                                                    'valor_envio' => $i['contrato']['valor'],
                        //                                                    'forma_envio' => $forma_envio
                        //                                        ));
                        //                                    
                        //                                    echo "<br>\n";

                        $valor_real = $i['contrato']['valor'];
                    } else {


                        $valor_real = $this->envioModel->getTaxaEnvio(
                            array(
                                'valor_envio' => $i['contrato']['valor'],
                                'forma_envio' => $forma_envio,
                            )
                        ) + $i['contrato']['valor'];
                    }

                    $tmp_desc = $i['sem_contrato']['valor'] - $valor_real;

                    if ($tmp_desc <= 0) {

                        $this->send_msg_feedback([
                            'valor_real' => $valor_real,
                            'diferenca' => $tmp_desc,
                            'post' => $dados,
                            'result' => $result
                        ]);

                        $error[] = "Desculpe-nos mas não conseguimos realizar a simulação com os valores informados, tente mais tarde";
                        break;
                    }

                    //                            $html.= print_r($data_destino, true);

                    $html .= '<h4>' . $forma_envio_str . '</h4>';
                    $html .= '<table class="table table-hovered">';
                    $html .= '<tr>';
                    $html .= '<td><span class="font1">Origem</span><br>';
                    $html .= '<strong>' . $dados['cep_origem'] . '</strong>';
                    $html .= '</td>';
                    $html .= '<td><span class="font1">Destino</span><br>';
                    $html .= '<strong>' . $dados['cep_destino'] . '</strong>';
                    $html .= '</td>';
                    $html .= '</tr>';

                    $html .= '<tr>';
                    $html .= '<td><span class="font1">Peso</span><br>';
                    $html .= '<strong>' . $this->envioModel->getPesos($dados['peso']) . '</strong>';
                    $html .= '</td>';
                    $html .= '<td>';

                    if (isset($data_post['seguro'])) {
                        $html .= '<span class="font1">Seguro</span><br>';
                    }
                    if (isset($data_post['seguro'])) {


                        if ($forma_envio_str == 'SEDEX') {
                            if (strlen($info_seguro_sedex)) {
                                $html .= '<small class="red">' . $info_seguro_sedex . '</small>';
                            } else {
                                $html .= '<strong>' . $this->utils->maskMoney($dados['seguro']) . '</strong>';
                            }
                        }
                        if ($forma_envio_str == 'PAC') {
                            if (strlen($info_seguro_pac)) {
                                $html .= '<small class="red">' . $info_seguro_pac . '</small>';
                            } else {
                                $html .= '<strong>' . $this->utils->maskMoney($dados['seguro']) . '</strong>';
                            }
                        }
                        if ($forma_envio_str == 'Envio Mini') {
                            if (strlen($info_seguro_pacmini)) {
                                $html .= '<small class="red">' . $info_seguro_pacmini . '</small>';
                            } else {
                                $html .= '<strong>' . $this->utils->maskMoney($dados['seguro']) . '</strong>';
                            }
                        }
                    }
                    $html .= '</td>';

                    $html .= '</tr>';


                    $html .= '<tr>';
                    $html .= '<td><span class="font1">Prazo</span><br>';
                    $html .= '<strong>' . $i['contrato']['prazo'] . ' Dia(s)</strong>';
                    $html .= '</td>';
                    $html .= '<td><span class="font1">Balcão</span><br>';
                    $html .= '<strong>' . ($i['sem_contrato']['valor'] ? $this->utils->maskMoney($i['sem_contrato']['valor']) : '') . '</strong>';
                    $html .= '</td>';
                    $html .= '</tr>';

                    $html .= '<tr>';
                    $html .= '<td><span class="font1">Desconto</span><br>';
                    $html .= '<strong>' . $this->utils->maskMoney($i['sem_contrato']['valor'] - $valor_real) . '</strong>';
                    $html .= '</td>';
                    $html .= '<td><span class="font1">Total</span><br>';
                    $html .= '<strong>' . $this->utils->maskMoney($valor_real) . '</strong>';
                    $html .= '</td>';
                    $html .= '</tr>';
                    $html .= '</table>';


                    $html .= '<hr>';
                }


                $html .= '</div>';
                $html .= '</div>';
            } else {

                // //   if ($this->config->item('theme_version') == '_v3') {
                //       $html = '<div class="table-responsive-lg"><table class="table-lg table-default table-bordered">';
                // //   } else {
                //       $html = '<table class="table table-hover">';
                // //   }
                //   $html .= '<thead>';
                //   $html .= '<tr>';
                //   $html .= '<th>CEP ORIGEM</th>';
                //   $html .= '<th>CEP DESTINO</th>';
                //   $html .= '<th>PESO</th>';
                //   if (isset($data_post['seguro']))
                //       $html .= '<th>DECLARAÇÃO</th>';

                //   $html .= '<th>FORMA ENVIO</th>';
                //   $html .= '<th>PRAZO</th>';
                //   $html .= '<th>BALCÃO</th>';
                //   $html .= '<th>DESCONTO</th>';
                //   $html .= '<th>TOTAL</th>';
                //   $html .= '</tr>';
                //   $html .= '</thead>';
                //   $html .= '<tbody>';
                $html = '';
                foreach ($result as $forma_envio => $i) {

                    $forma_envio_str = $forma_envio;
                    if ($forma_envio == 'PACMINI') {
                        $forma_envio_str = 'Envio Mini';
                    }

                    $i['contrato']['valor'] = preg_replace('/,/', '.', $i['contrato']['valor']);
                    $i['sem_contrato']['valor'] = preg_replace('/,/', '.', $i['sem_contrato']['valor']);
                    if (isset($user_config['config_enable_industrial']) && (int) $user_config['config_enable_industrial']) {
                        $valor_real = $i['contrato']['valor'];
                    } else {
                        if (false && $i['contrato']['valor'] == 9.73) {
                            $valor_real = 9.60;
                        } else {



                            if ($user->id == '28730' || ($user_config && (isset($user_config['config_enable_industrial']) && (int) $user_config['config_enable_industrial']))) {



                                //                                    echo "Valor normal: " . $forma_envio . " -- " . $i['contrato']['valor'];
                                //                                    
                                //                                    echo "<br>\n";
                                //                                    
                                //                                    echo $this->envio_model->get_taxa_envio(
                                //                                                array(
                                //                                                    'valor_envio' => $i['contrato']['valor'],
                                //                                                    'forma_envio' => $forma_envio
                                //                                        ));
                                //                                    
                                //                                    echo "<br>\n";

                                $valor_real = $i['contrato']['valor'];
                            } else {

                                $valor_real = $this->envioModel->getTaxaEnvio(
                                    array(
                                        'valor_envio' => $i['contrato']['valor'],
                                        'forma_envio' => $forma_envio
                                    )
                                ) + $i['contrato']['valor'];
                            }
                        }
                    }


                    if ($forma_envio != 'SEDEX HOJE' || $forma_envio != 'SEDEX 12') {
                        $tmp_desc = $i['sem_contrato']['valor'] - $valor_real;
                    }
                    //                            if (false) {
                    ////                        if ($tmp_desc > $valor_real) {
                    //
                    //                                
                    //                                
                    ////                                $this->load->library('email_maker');
                    ////                                $this->email_maker->msg(array('msg' => 'Simulador, valor Menor:<br>Vl Real:' . $valor_real . '<br> Post:<br><pre>' . print_r($post, true) . "<br>Lista" . print_r($result, true) . '</pre>'));
                    //
                    //                                $error[] = "Desculpe-nos mas não conseguimos realizar a simulação com os valores informados, tente mais tarde";
                    //                                break;
                    //                            }
                    if (false && $request->ip() != '177.25.222.147' && $request->ip() != '177.25.212.201' && $tmp_desc <= 0) {

                        //                                $this->load->library('email_maker');
                        //                                $this->email_maker->msg(array('msg' => 'Simulador, valor Menor:<br>Vl Real:' . $valor_real . '<br>tmp_desc: ' . $tmp_desc . ' <br> Post:<br><pre>' . print_r($post, true) . "<br>Lista" . print_r($result, true) . '</pre>'));
                        //                                $this->email_maker->msg(array('subject' => 'Simulador - Valor Manda Bem maior Balcão', 'to' => 'reginaldo@mandabem.com.br', 'msg' => 'Simulador, valor Menor:<br>Vl Real:' . $valor_real . '<br>Diferenca: ' . $tmp_desc . '<br> Dados para Simulacao:<br><pre>' . print_r($post, true) . "<br>Lista Metodos\n" . print_r($result, true) . '</pre>'));

                        $this->send_msg_feedback([
                            'valor_real' => $valor_real,
                            'diferenca' => $tmp_desc,
                            'post' => $dados,
                            'result' => $result
                        ]);

                        //                                $error[] = "Desculpe-nos mas não conseguimos realizar a simulação com os valores informados, tente mais tarde";
                        //                                break;
                    }


                    // Ajuste desconto quando frete for muito proximo do valor balcao
                    if ($dados['cep_origem'] == '79071071' || $dados['cep_origem'] == '60811310') {
                        if ($valor_real >= $i['sem_contrato']['valor']) {
                            $old_valor_total = $valor_real;

                            $desc = ($i['sem_contrato']['valor'] * 0.10);

                            // Limitando o desconto a 10 reais
                            if ($desc > 10) {
                                $desc = 10;
                            }


                            $valor_real = $i['sem_contrato']['valor'] - $desc;
                            //                                $data_post['valor_desconto_frete'] = $old_valor_total - $data_post['valor_total'];
                            // Atualizando desconto
                            //                                $data_post['valor_desconto'] = $valor_balcao - $data_post['valor_total'];
                        } else {
                            $old_valor_total = $valor_real;
                            if ($valor_real > 100) {
                                if (($i['sem_contrato']['valor'] - $valor_real) <= 10) {
                                    $valor_real = $valor_real - ($valor_real * 0.10);
                                    //                                        $data_post['valor_desconto_frete'] = $old_valor_total - $data_post['valor_total'];
                                    // Atualizando desconto
                                    //                                        $data_post['valor_desconto'] = $valor_balcao - $data_post['valor_total'];
                                }
                            } else {
                                if (($i['sem_contrato']['valor'] - $valor_real) <= 2) {
                                    $valor_real = $valor_real - ($valor_real * 0.10);
                                    //                                        $data_post['valor_desconto_frete'] = $old_valor_total - $data_post['valor_total'];
                                    // Atualizando desconto
                                    //                                        $data_post['valor_desconto'] = $valor_balcao - $data_post['valor_total'];
                                }
                            }
                        }
                    }

                    $html .= '<tr class="bg-white hover:bg-gray-100 border rounded-full font-light">';
                    $html .= '<td class="px-6 py-2">' . $dados['cep_origem'] . '</td>';
                    $html .= '<td class="px-6 py-2">' . $dados['cep_destino'] . '</td>';
                    $html .= '<td class="px-6 py-2">' . $this->envioModel->getPesos($dados['peso']) . '</td>';
                    if (isset($data_post['seguro'])) {
                        if ($forma_envio_str == 'SEDEX') {
                            if (strlen($info_seguro_sedex)) {
                                $html .= '<td dir="rtl" class="py-2 rounded-s-lg"><small class="red">' . $info_seguro_sedex . '</small></td>';
                            } else {
                                $html .= '<td dir="rtl" class="py-2 rounded-s-lg">' . $this->utils->maskMoney($dados['seguro']) . '</td>';
                            }
                        }
                        if ($forma_envio_str == 'PAC') {
                            if (strlen($info_seguro_pac)) {
                                $html .= '<td dir="rtl" class="py-2 rounded-s-lg"><small class="red">' . $info_seguro_pac . '</small></td>';
                            } else {
                                $html .= '<td dir="rtl" class="py-2 rounded-s-lg">' . $this->utils->maskMoney($dados['seguro']) . '</td>';
                            }
                        }
                        if ($forma_envio_str == 'Envio Mini') {
                            if (strlen($info_seguro_pacmini)) {
                                $html .= '<td dir="rtl" class="py-2 rounded-s-lg"><small class="red">' . $info_seguro_pacmini . '</small></td>';
                            } else {
                                $html .= '<td dir="rtl" class="py-2 rounded-s-lg">' . $this->utils->maskMoney($dados['seguro']) . '</td>';
                            }
                        }
                    }
                    $html .= '<td dir="rtl" class="py-2 rounded-s-lg">' . $forma_envio_str . '</td>';
                    $html .= '<td dir="rtl" class="py-2 rounded-s-lg">' . $i['contrato']['prazo'] . ' Dia(s)</td>';
                    $html .= '<td dir="rtl" class="py-2 rounded-s-lg"">' . ($i['sem_contrato']['valor'] ? $this->utils->maskMoney($i['sem_contrato']['valor']) : '') . '</td>';
                    if (($this->enable_sedex_hoje && $forma_envio_str == 'SEDEX HOJE') || ($this->enable_sedex_doze && $forma_envio_str == 'SEDEX 12')) {
                        $html .= '<td dir="rtl" class="py-2 rounded-s-lg"> -- </td>';
                    } else {
                        $html .= '<td dir="rtl" class="py-2 rounded-s-lg">' . $this->utils->maskMoney($i['sem_contrato']['valor'] - $valor_real) . '</td>';
                    }
                    $html .= '<td dir="rtl" class="py-2 rounded-s-lg">' . $this->utils->maskMoney($valor_real) . '</td>';
                    $html .= '</tr>';
                }

                //   $html .= '</tbody>';
                //   $html .= '</table>';
                //   if ($this->config->item('theme_version') == '_v3') {
                //   $html .= '</div>';
                //   }
                //   $html .= '<hr>';
            }
        }
        return $html;
    }
}
