<?php

namespace App\Libraries\Correios;
use App\Libraries\Correios\CorreioRest;
use App\Models\Envio;
use App\Models\Coleta;
use App\Models\User;
use App\Models\Log;
use App\Models\Setting;
use App\Libraries\EmailMaker;
use App\Libraries\DateUtils;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;
use DOMDocument;
use SoapClient;


class Correio {

    private $error;
    private $error_trace;

    public function getError() 
    {
        return $this->error;
    }

    public function getErrorTrace() 
    {
        return $this->error_trace;
    }

    public function teste($cep){
        $clientSoap = new SoapClient('https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl', array(
            'stream_context' => stream_context_create(
                    array('http' =>
                        array(
                            'protocol_version' => '1.0',
                            'header' => 'Connection: Close'
                        )
                    )
            ),
                )
        );
        print_r($clientSoap);exit;
    }
    //corrigir ao fazer instalacao do SoapClient
    public function buscaCep($cep) 
    {

        $userModel = new User();
        $emailMaker = new EmailMaker();

        // Nova versão!
        if (true) {
            try {

                $clientSoap = new SoapClient('https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl', array(
                    'stream_context' => stream_context_create(
                            array('http' =>
                                array(
                                    'protocol_version' => '1.0',
                                    'header' => 'Connection: Close'
                                )
                            )
                    ),
                        )
                );

                $data_send = array(
                    'cep' => $cep,
                );

                $result = $clientSoap->consultaCEP($data_send);
                print_r($result);exit;
                if (!isset($result)) {
                    $emailMaker->msg(array(
                        'subject' => '** Consulta CEP WS Correios NOT RESULT',
                        'msg' => "ERROR " . print_r($e, true),
                        'to' => 'regygom@gmail.com'
                    ));
                    return false;
                }
                return isset($result->return) ? $result->return : $result->retorno;
            } catch (\Exception $e) {
                if (isset($e->message) && preg_match('/CEP NAO ENCONTRADO/', $e->message)) {
                    return false;
                } else {
                    Log::error('SOAP Error: ' . $e->getMessage());
                    return false;
                }
            }
            return false;
        }

        try {
            $clientSoap = new SoapClient('https://cws.correios.com.br/cws/cepService/cepWS?wsdl', array(
                'stream_context' => stream_context_create(
                        array('http' =>
                            array(
                                'protocol_version' => '1.0',
                                'header' => 'Connection: Close'
                            )
                        )
                ), 'login' => 'maquinamqn', 'password' => 'manda2020'
                    )
            );

            $data_send = array(
                'cep' => $cep,
            );


            $result = $clientSoap->consultarCEP($data_send);

            if (!isset($result)) {
                $emailMaker->msg(array(
                    'subject' => 'Consulta CEP WS Correios NOT RESULT',
                    'msg' => "ERROR " . print_r($e, true),
                    'to' => 'regygom@gmail.com'
                ));
                return false;
            }

            return isset($result->return) ? $result->return : $result->retorno;
        } catch (\Exception $e) {
            $emailMaker->msg(array(
                'subject' => 'Consulta CEP WS Correios ERROR',
                'msg' => "ERROR " . print_r($e, true),
                'to' => 'regygom@gmail.com'
            ));
        }
 

        if (!isset($result->retorno) && !isset($result->return)) {
            $emailMaker->msg(array(
                'subject' => 'Consulta CEP WS Correios NOT RESULT',
                'msg' => "ERROR " . print_r($e, true),
                'to' => 'regygom@gmail.com'
            ));
            return false;
        }

        return isset($result->retorno) ? $result->retorno : $result->return;
    }
    //corrigir ao fazer instalacao do SoapClient
    public function statusManifestacao($data = []) 
    {
        ini_set("default_socket_timeout", 20);
        $userModel = new User();
        $user = json_decode(json_encode($userModel->get(1)), true);
        $env = getCredentialsEtiqueta(($user))['production'];


        $clientSoap = new SoapClient('https://cws.correios.com.br/pedidoInformacaoWS/pedidoInformacaoService/pedidoInformacaoWS?wsdl', array(
            'stream_context' => stream_context_create([
                'http' => [
                    'timeout' => 1.0
                ]
            ]),
            'login' => $env['ws_login'],
            'password' => $env['ws_password']
                )
        );

        $data_send = array(
            'contrato' => $env['contrato'],
            'codigodoObjetoOuProtocolo' => $data['numero_pi'] == 'f' ? $data['objeto'] : $data['numero_pi'],
            'canal' => 'C'
        );
 
        $result = null;
        $has_exception = false;
        try {
            $result = $clientSoap->consultarAcompanharRegistrarOcorreciaComContrato($data_send);
        } catch (\Exception $e) {
            print_r($e);
            $has_exception = true;
        }
 
        if ((!isset($result) || !$result) && !$has_exception) {
            return false;
        }

        if ($result) {
            $user = json_decode(json_encode($result), true);
        }

        if (!isset($result['mensagem'])) {
            return false;
        }

        return $result['mensagem'];
    }
    //corrigir ao fazer instalacao do SoapClient
    public function postManifestacao($data = []) 
    {
        $userModel = new User();
        $emailMaker = new EmailMaker();
        $dateUtils = new DateUtils();
        $userAdmin = json_decode(json_encode($userModel->get(1)), true);
        $user = $userModel->get($data['envio']->user_id);
        $env = getCredentialsEtiqueta(($userAdmin))['production'];

        $clientSoap = new SoapClient('https://cws.correios.com.br/pedidoInformacaoWS/pedidoInformacaoService/pedidoInformacaoWS?wsdl', array(
            'stream_context' => stream_context_create(
                    array('http' =>
                        array(
                            'protocol_version' => '1.0',
                            'header' => 'Connection: Close'
                        )
                    )
            ), 'login' => $env['ws_login'], 'password' => $env['ws_password']
                )
        );

        $data_send = array(
            'contrato' => $env['contrato'],
            'cartao' => $env['cartao'],
            'telefone' => '21979227345',
            'pi' => [
                'codigoObjeto' => $data['envio']->etiqueta_correios . 'BR',
                'emailResposta' => 'marcos@mandabem.com.br',
                'codigoMotivoReclamacao' => $data['codigo_motivo'],
                'tipoEmbalagem' => 'C',
            ]
        );
        $result = [];
        try {
            $result = $clientSoap->cadastrarPIComContrato($data_send);
                           //print_r($result);
        } catch (\Exception $e) {
                           //print_r($e);

            $emailMaker->msg(array(
                'subject' => 'Abertura de Manifestacao - Error (0)',
                'msg' => $dateUtils->getNow() . "\n<pre>" . print_r($result, true) . "\n" . print_r($e, true) . "</pre>",
                'to' => 'reginaldo@mandabem.com.br'
            ));
            if (isset($data['flag_automatic']) || $_SERVER['REMOTE_ADDR'] == '131.0.217.14') {
                print_r($e);
            }
            return false;
        }
        if ($result) {
            $result = json_decode(json_encode($result), true);  
        }

        if (!isset($result['pedido']['pi']) || !isset($result['pedido']['pi']['codigoRetorno'])) {
            $this->error = "Falha. Dados Recebidos difere do esperado.";
            $emailMaker->msg(array(
                'subject' => 'Abertura de Manifestacao - Error (1)',
                'msg' => $dateUtils->getNow() . "\n" . print_r($result, true),
                'to' => 'regygom@gmail.com'
            ));
            return false;
        }
        if (isset($result['pedido']['pi']['numeroPi']) && $result['pedido']['pi']['numeroPi'] == 'f') {
            $emailMaker->msg(array(
                'subject' => 'Abertura de Manifestacao - RETORNO FF ',
                'msg' => $dateUtils->getNow() . "\n" . print_r($result, true),
                'to' => 'regygom@gmail.com'
            ));
        }

        $codigo_retorno = $result['pedido']['pi']['codigoRetorno'];

        if ($codigo_retorno != '0' && $codigo_retorno != '900' && $codigo_retorno != '546') {

            $this->error = "Falha: " . $result['pedido']['pi']['descricaoRetorno'];
            if (!preg_match('/Prezado Cliente, o objeto est(.*?) dentro do prazo previsto de entrega, conforme sistema calculador de pre(.*?)os e prazos\./', $result['pedido']['pi']['descricaoRetorno'])) {
                $emailMaker->msg(array(
                    'subject' => 'Abertura de Manifestacao - Error (2)',
                    'msg' => $dateUtils->getNow() . "\n<pre>" . print_r($result, true) . "</pre>",
                    'to' => 'regygom@gmail.com'
                ));
            }
            return false;
        }
        // 546 abre mais da um aviso
        // 900 ja foi aberto, pega apenas o protocolo
        if (( (int) $codigo_retorno === 0 || $codigo_retorno == '900' || $codigo_retorno == '546') && isset($result['pedido']['pi']['numeroPi'])) {
            return [
                'numero_pi' => $result['pedido']['pi']['numeroPi'],
                'numero_lote' => $result['pedido']['numeroLote'],
            ];
        } else {
            $this->error = "Falha. Dados Recebidos difere do esperado.";
            $emailMaker->msg(array(
                'subject' => 'Abertura de Manifestacao - Error (3)',
                'msg' => $dateUtils->getNow() . "\n" . print_r($result, true),
                'to' => 'regygom@gmail.com'
            ));
            return false;
        }
    }
    //corrigir ao fazer instalacao do SoapClient
    public function getInfoServicosMandabem() 
    {
        $userModel = new User();
        $user = json_decode(json_encode($userModel->get(1)), true);
        $ambiente = getCredentialsEtiqueta(($user))['production'];

        print_r($ambiente);
        $clientSoap = new SoapClient($ambiente['link'], array(
            'stream_context' => stream_context_create(
                    array('http' =>
                        array(
                            'protocol_version' => '1.0',
                            'header' => 'Connection: Close'
                        )
                    )
            )
                )
        );

        $buscaCliente = array(
            'idContrato' => $ambiente['contrato'],
            'idCartaoPostagem' => $ambiente['cartao'],
            'usuario' => $ambiente['usuario'],
            'senha' => $ambiente['senha']
        );
        $data_servicos = array();
        $result = $clientSoap->buscaCliente($buscaCliente);
        $servicos = $result->return->contratos->cartoesPostagem->servicos;
        foreach ($servicos as $k => $s) {
            unset($s->servicoSigep);
            $data_servicos[] = $s;
        }
        print_r($data_servicos);
        return $data_servicos;
    }
    //corrigir ao fazer instalacao do SoapClient
    public function getInfoIdServico($user = array()) 
    {
        $ambiente = getCredentialsEtiqueta($user)['production'];

        $clientSoap = new SoapClient($ambiente['link'], array(
            'stream_context' => stream_context_create(
                    array('http' =>
                        array(
                            'protocol_version' => '1.0',
                            'header' => 'Connection: Close'
                        )
                    )
            )
                )
        );
        $buscaCliente = array(
            'idContrato' => $user['contrato_correios'],
            'idCartaoPostagem' => $user['cartao_correios'],
            'usuario' => $ambiente['usuario'],
            'senha' => $ambiente['senha']
        );
        $data_servicos = array();
        $result = $clientSoap->buscaCliente($buscaCliente);
        if (isset($result->return->contratos->cartoesPostagem->servicos)) {
            $servicos = $result->return->contratos->cartoesPostagem->servicos;
            foreach ($servicos as $s) {
                $s->codigo = trim($s->codigo);
                if ($s->codigo == $user['codigo_servico_pac']) {
                    $data_servicos['id_servico_pac'] = $s->id;
                }
                if ($s->codigo == $user['codigo_servico_sedex']) {
                    $data_servicos['id_servico_sedex'] = $s->id;
                }
            }
        } else {
            $this->error = "Falha ao obter ID Serviços dos Correios.";
            return false;
        }
        if (!isset($data_servicos['id_servico_pac']) || !isset($data_servicos['id_servico_sedex'])) {
            $this->error = "Falha ao obter ID Serviços dos Correios.<br>";
            $this->error .= "Verifique as informações de Codigos de Serviço PAC e Sedex.";
            return false;
        }
        return $data_servicos;
    }
    //corrigir ao fazer instalacao do SoapClient
    public function solicitarEtiquetas($param = array()) 
    {
        ini_set("default_socket_timeout", 20);
         
        $settingModel = new Setting();
        $dateUtils = new DateUtils();
        $ambiente = getCredentialsEtiqueta($param['user'])[$param['user']['environment']];

        $clientSoap = new SoapClient($ambiente['link'], array(
            'stream_context' => stream_context_create(
                    array('http' =>
                        array(
                            'protocol_version' => '1.0',
                            'header' => 'Connection: Close',
                            'timeout' => 1.0
                        )
                    )
            )
                )
        );

        $etiquetas_sedex = array();
        $etiquetas_pac = array();
        $etiquetas_pacmini = array();
        $etiquetas_sedex_hj = array();
        $etiquetas_sedex_12 = array();

        $id_servico_sedex_ = '124849';
        $id_servico_pac_ = '124884';
        $id_servico_pac_mini_ = '159982';
        $id_servico_sedex_hj_ = '162414'; // NOVO
        $id_servico_sedex_12_ = '162015'; // NOVO
        if (true) {
            $id_servico_sedex_ = '162022';
            $id_servico_pac_ = '162026';
            $id_servico_pac_mini_ = '159982';
        }

        // Usando IDs servico referente à postagem industrial
        if (isset($param['is_industrial']) && $param['is_industrial']) {
            $id_servico_sedex_ = '162025';
            $id_servico_pac_ = '162030';
            $id_servico_pac_mini_ = '159982';
        }

        if ($param['user']['group_code'] == 'cliente_contrato') {
            if (!$param['user']['id_servico_pac'] || !$param['user']['id_servico_sedex']) {
                $info_id_servico = $this->getInfoIdServico($param['user']);
        
                if (!$info_id_servico) {
                    return false;
                }
        
                DB::table('user')
                    ->where('id', $param['user']['id'])
                    ->update([
                        'date_update' => $dateUtils->getNow(),
                        'id_servico_pac' => $info_id_servico['id_servico_pac'],
                        'id_servico_sedex' => $info_id_servico['id_servico_sedex']
                    ]);
            } else {
                $id_servico_pac_ = $param['user']['id_servico_pac'];
                $id_servico_sedex_ = $param['user']['id_servico_sedex'];
            }
        }


        if ($param['total_sedex'] > 0) {

            $solicitaEtiquetas = array(
                'tipoDestinatario' => 'C',
                'identificador' => $ambiente['cnpj'],
                #124849   (40215 - SEDEX 10)
                #124849   (04162 - SEDEX)
                #124884   (04669 - PAC)		
                'idServico' => $id_servico_sedex_, #'124849',
                'qtdEtiquetas' => $param['total_sedex'],
                'usuario' => $ambiente['usuario'],
                'senha' => $ambiente['senha']
            );

            try {
                $result = $clientSoap->solicitaEtiquetas($solicitaEtiquetas);
                $etiquetas_sedex = explode(",", str_replace(' BR', '', $result->return));
                if ($param['total_sedex'] == 1):
                    array_pop($etiquetas_sedex);
                endif;

                if ($param['total_sedex'] > 2) {

                    $sigla = substr($etiquetas_sedex[0], 0, 2); //sigla OF PE 
                    $inicio = substr($etiquetas_sedex[0], 2);   //inicio 
                    $inicio_aux = substr("{$inicio}abc", 0, 1);

                    for ($i = 1; $i < $param['total_sedex']; $i++) {
                        $inicio++;
                        $etiquetas_sedex[$i] = $sigla . str_pad($inicio, 8, '0', STR_PAD_LEFT);
                    }
                }

                $geraDigitoVerificadorEtiquetas = array(
                    'etiquetas' => array(),
                    'usuario' => $ambiente['usuario'],
                    'senha' => $ambiente['senha'],
                );

                           //        buscar digito das etiquetas 
                foreach ($etiquetas_sedex as $etiqueta) {
                    $geraDigitoVerificadorEtiquetas['etiquetas'][] = $etiqueta . ' BR';
                }

                $digitos = $clientSoap->geraDigitoVerificadorEtiquetas($geraDigitoVerificadorEtiquetas);

                #Mesclando Etiqueta + Digitos
                $i = 0;

                $digito = $digitos->return;

                for ($i = 0; $i < $param['total_sedex']; $i++):
                    if (is_array($digito)) {
                        $etiquetas_sedex[$i] = $etiquetas_sedex[$i] . '' . $digito[$i];
                    } else {
                        $etiquetas_sedex[$i] = $etiquetas_sedex[$i] . '' . $digito;
                    }
                endfor;
            } catch (\Exception $e) {

                $this->error = 'PROBLEMAS NO WEBSERVICE DOS CORREIOS.<br>O servidor dos Correios não está respondendo à nossa solicitação, Por favor tente novamente em alguns minutos.';
                if (preg_match('/N(.*?)mero do Logradouro de Origem n(.*?)o informado./', print_r($e, true))) {
                    $this->error = 'Número do Logradouro de Origem não informado.';
                }
                return false;
            }
        }


        if ($param['total_pac'] > 0) {

            #124849   (04162 - SEDEX)
            #124884   (04669 - PAC)		
                           //$id_servico = '124884';

            $solicitaEtiquetas = array(
                'tipoDestinatario' => 'C',
                'identificador' => $ambiente['cnpj'],
                'idServico' => $id_servico_pac_, #$id_servico: subsituido por questoes de contrato,
                'qtdEtiquetas' => $param['total_pac'],
                'usuario' => $ambiente['usuario'],
                'senha' => $ambiente['senha']
            );

            try {
                $result = $clientSoap->solicitaEtiquetas($solicitaEtiquetas);
                $etiquetas_pac = explode(",", str_replace(' BR', '', $result->return));
                //#["return"]=> string(27) "EC34781501 BR,EC34781501 BR" }
                if ($param['total_pac'] == 1):
                    array_pop($etiquetas_pac);
                endif;

                if ($param['total_pac'] > 2):

                    $sigla = substr($etiquetas_pac[0], 0, 2); //sigla OF PE 
                    $inicio = substr($etiquetas_pac[0], 2);   //inicio 
                    $inicio_aux = substr("{$inicio}abc", 0, 1);
                    for ($i = 1; $i < $param['total_pac']; $i++) {
                        $inicio++;
                        $etiquetas_pac[$i] = $sigla . str_pad($inicio, 8, '0', STR_PAD_LEFT);
                    }
                endif;

                $geraDigitoVerificadorEtiquetas = array(
                    'etiquetas' => array(),
                    'usuario' => $ambiente['usuario'],
                    'senha' => $ambiente['senha'],
                );
                #buscar digito das etiquetas 
                foreach ($etiquetas_pac as $etiqueta) {
                    $geraDigitoVerificadorEtiquetas['etiquetas'][] = $etiqueta . ' BR';
                }

                $digitos = $clientSoap->geraDigitoVerificadorEtiquetas($geraDigitoVerificadorEtiquetas);

                #Mesclando Etiqueta + Digitos
                $i = 0;

                $digito = $digitos->return;
                for ($i = 0; $i < $param['total_pac']; $i++):
                    if (is_array($digito)) {
                        $etiquetas_pac[$i] = $etiquetas_pac[$i] . '' . $digito[$i];
                    } else {
                        $etiquetas_pac[$i] = $etiquetas_pac[$i] . '' . $digito;
                    }
                endfor;
            } catch (\Exception $e) {
                $this->error = 'PROBLEMAS NO WEBSERVICE DOS CORREIOS.<br>O servidor dos Correios não está respondendo à nossa solicitação, Por favor tente novamente em alguns minutos.';
                           //    $this->error = ' Servidor dos Correios não está respondendo à nossa solicitação para geração de envios PAC ! As demais formas SEDEX e MINI ENVIOS estão sendo geradas normalmente! Estamos em contato com os Correios para normalizar o quanto antes a situação!';
                           //    $this->error = 'Falha conexão Correios, Por favor tente novamente em alguns minutos.';
                           //    $this->error .= print_r($e, true);

                if (preg_match('/N(.*?)mero do Logradouro de Origem n(.*?)o informado./', print_r($e, true))) {
                    $this->error = 'Número do Logradouro de Origem não informado.';
                }
                return false;
            }
        }


        /// PAC MINI PAC MINI
        if ($param['total_pacmini'] > 0) {

                           //$id_servico = '159982';

            $solicitaEtiquetas = array(
                'tipoDestinatario' => 'C',
                'identificador' => $ambiente['cnpj'],
                'idServico' => $id_servico_pac_mini_, #$id_servico: subsituido por questoes de contrato,
                'qtdEtiquetas' => $param['total_pacmini'],
                'usuario' => $ambiente['usuario'],
                'senha' => $ambiente['senha']
            );

            try {
                $result = $clientSoap->solicitaEtiquetas($solicitaEtiquetas);
                $etiquetas_pacmini = explode(",", str_replace(' BR', '', $result->return));
                //#["return"]=> string(27) "EC34781501 BR,EC34781501 BR" }
                if ($param['total_pacmini'] == 1):
                    array_pop($etiquetas_pacmini);
                endif;

                if ($param['total_pacmini'] > 2):

                    $sigla = substr($etiquetas_pacmini[0], 0, 2); //sigla OF PE 
                    $inicio = substr($etiquetas_pacmini[0], 2);   //inicio 
                    $inicio_aux = substr("{$inicio}abc", 0, 1);

                    for ($i = 1; $i < $param['total_pacmini']; $i++) {
                        $inicio++;
                        $etiquetas_pacmini[$i] = $sigla . str_pad($inicio, 8, '0', STR_PAD_LEFT);
                    }
                endif;

                $geraDigitoVerificadorEtiquetas = array(
                    'etiquetas' => array(),
                    'usuario' => $ambiente['usuario'],
                    'senha' => $ambiente['senha'],
                );

                #buscar digito das etiquetas 
                foreach ($etiquetas_pacmini as $etiqueta) {
                    $geraDigitoVerificadorEtiquetas['etiquetas'][] = $etiqueta . ' BR';
                }
                $digitos = $clientSoap->geraDigitoVerificadorEtiquetas($geraDigitoVerificadorEtiquetas);
                #Mesclando Etiqueta + Digitos
                $i = 0;

                $digito = $digitos->return;
                for ($i = 0; $i < $param['total_pacmini']; $i++):
                    if (is_array($digito)) {
                        $etiquetas_pacmini[$i] = $etiquetas_pacmini[$i] . '' . $digito[$i];
                    } else {
                        $etiquetas_pacmini[$i] = $etiquetas_pacmini[$i] . '' . $digito;
                    }
                endfor;
            } catch (\Exception $e) {
                $this->error = 'PROBLEMAS NO WEBSERVICE DOS CORREIOS.<br>O servidor dos Correios não está respondendo à nossa solicitação, Por favor tente novamente em alguns minutos.';
                           //    $this->error = 'Falha conexão Correios, Por favor tente novamente em alguns minutos.';
                           //    $this->error .= print_r($e, true);


                if (preg_match('/N(.*?)mero do Logradouro de Origem n(.*?)o informado./', print_r($e, true))) {
                    $this->error = 'Número do Logradouro de Origem não informado.';
                }
                return false;
            }
        }

        // SEDEX HOJE
        if ($param['total_sedex_hj'] > 0) {


            $solicitaEtiquetas = array(
                'tipoDestinatario' => 'C',
                'identificador' => $ambiente['cnpj'],
                'idServico' => $id_servico_sedex_hj_, #$id_servico: subsituido por questoes de contrato,
                'qtdEtiquetas' => $param['total_sedex_hj'],
                'usuario' => $ambiente['usuario'],
                'senha' => $ambiente['senha']
            );

            try {
                $result = $clientSoap->solicitaEtiquetas($solicitaEtiquetas);
                $etiquetas_sedex_hj = explode(",", str_replace(' BR', '', $result->return));
                if ($param['total_sedex_hj'] == 1):
                    array_pop($etiquetas_sedex_hj);
                endif;

                if ($param['total_sedex_hj'] > 2):

                    $sigla = substr($etiquetas_sedex_hj[0], 0, 2); //sigla OF PE 
                    $inicio = substr($etiquetas_sedex_hj[0], 2);   //inicio 
                    $inicio_aux = substr("{$inicio}abc", 0, 1);

                    for ($i = 1; $i < $param['total_sedex_hj']; $i++) {
                        $inicio++;
                        $etiquetas_sedex_hj[$i] = $sigla . str_pad($inicio, 8, '0', STR_PAD_LEFT);
                    }
                endif;

                $geraDigitoVerificadorEtiquetas = array(
                    'etiquetas' => array(),
                    'usuario' => $ambiente['usuario'],
                    'senha' => $ambiente['senha'],
                );
                #buscar digito das etiquetas 
                foreach ($etiquetas_sedex_hj as $etiqueta) {
                    $geraDigitoVerificadorEtiquetas['etiquetas'][] = $etiqueta . ' BR';
                }

                $digitos = $clientSoap->geraDigitoVerificadorEtiquetas($geraDigitoVerificadorEtiquetas);

                #Mesclando Etiqueta + Digitos
                $i = 0;

                $digito = $digitos->return;
                for ($i = 0; $i < $param['total_sedex_hj']; $i++):
                    if (is_array($digito)) {
                        $etiquetas_sedex_hj[$i] = $etiquetas_sedex_hj[$i] . '' . $digito[$i];
                    } else {
                        $etiquetas_sedex_hj[$i] = $etiquetas_sedex_hj[$i] . '' . $digito;
                    }
                endfor;
            } catch (\Exception $e) {
                $this->error = 'PROBLEMAS NO WEBSERVICE DOS CORREIOS.<br>O servidor dos Correios não está respondendo à nossa solicitação, Por favor tente novamente em alguns minutos.';
                           //    $this->error = 'Falha conexão Correios, Por favor tente novamente em alguns minutos.';
                           //    $this->error .= print_r($e, true);

                if (preg_match('/N(.*?)mero do Logradouro de Origem n(.*?)o informado./', print_r($e, true))) {
                    $this->error = 'Número do Logradouro de Origem não informado.';
                }
                return false;
            }
        }


        // SEDEX 12
        if ($param['total_sedex_12'] > 0) {


            $solicitaEtiquetas = array(
                'tipoDestinatario' => 'C',
                'identificador' => $ambiente['cnpj'],
                'idServico' => $id_servico_sedex_12_, #$id_servico: subsituido por questoes de contrato,
                'qtdEtiquetas' => $param['total_sedex_12'],
                'usuario' => $ambiente['usuario'],
                'senha' => $ambiente['senha']
            );

            try {
                $result = $clientSoap->solicitaEtiquetas($solicitaEtiquetas);
                $etiquetas_sedex_12 = explode(",", str_replace(' BR', '', $result->return));
                if ($param['total_sedex_12'] == 1):
                    array_pop($etiquetas_sedex_12);
                endif;

                if ($param['total_sedex_12'] > 2):

                    $sigla = substr($etiquetas_sedex_12[0], 0, 2); //sigla OF PE 
                    $inicio = substr($etiquetas_sedex_12[0], 2);   //inicio 
                    $inicio_aux = substr("{$inicio}abc", 0, 1);

                    for ($i = 1; $i < $param['total_sedex_12']; $i++) {
                        $inicio++;
                        $etiquetas_sedex_12[$i] = $sigla . str_pad($inicio, 8, '0', STR_PAD_LEFT);
                    }
                endif;

                $geraDigitoVerificadorEtiquetas = array(
                    'etiquetas' => array(),
                    'usuario' => $ambiente['usuario'],
                    'senha' => $ambiente['senha'],
                );
                #buscar digito das etiquetas 
                foreach ($etiquetas_sedex_12 as $etiqueta) {
                    $geraDigitoVerificadorEtiquetas['etiquetas'][] = $etiqueta . ' BR';
                }


                $digitos = $clientSoap->geraDigitoVerificadorEtiquetas($geraDigitoVerificadorEtiquetas);
                #Mesclando Etiqueta + Digitos
                $i = 0;

                $digito = $digitos->return;
                for ($i = 0; $i < $param['total_sedex_12']; $i++):
                    if (is_array($digito)) {
                        $etiquetas_sedex_12[$i] = $etiquetas_sedex_12[$i] . '' . $digito[$i];
                    } else {
                        $etiquetas_sedex_12[$i] = $etiquetas_sedex_12[$i] . '' . $digito;
                    }
                endfor;
            } catch (\Exception $e) {
                $this->error = 'PROBLEMAS NO WEBSERVICE DOS CORREIOS.<br>O servidor dos Correios não está respondendo à nossa solicitação, Por favor tente novamente em alguns minutos.';
                           //    $this->error = 'Falha conexão Correios, Por favor tente novamente em alguns minutos.';
                           //    $this->error .= print_r($e, true);


                if ($_SERVER['REMOTE_ADDR'] == '177.25.213.35') {
                    print_r($e);
                }

                if (preg_match('/N(.*?)mero do Logradouro de Origem n(.*?)o informado./', print_r($e, true))) {
                    $this->error = 'Número do Logradouro de Origem não informado.';
                }
                return false;
            }
        }
        $lista_retorno = array(
            'sedex' => $etiquetas_sedex,
            'pac' => $etiquetas_pac,
            'pacmini' => $etiquetas_pacmini,
            'sedex_hj' => $etiquetas_sedex_hj,
            'sedex_12' => $etiquetas_sedex_12,
        );

        return $lista_retorno;
    }
    //corrigir ao fazer instalacao do SoapClient
    public function gerarPlp($param = array()) 
    {
        ini_set("default_socket_timeout", 20);

        $userModel = new User();

        $ambiente = getCredentialsEtiqueta($param['user'])[$param['user']['environment']];

        $clientSoap = new SoapClient($ambiente['link'], array(
            'stream_context' => stream_context_create(
                    array('http' =>
                        array(
                            'protocol_version' => '1.0',
                            'header' => 'Connection: Close',
                            'timeout' => 1.0
                        )
                    )
            )
                )
        );

        try {

            $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
            $xml .= "<correioslog>";
            $xml .= "<tipo_arquivo>Postagem</tipo_arquivo>";
            $xml .= "<versao_arquivo>2.3</versao_arquivo>";
            $xml .= "<plp>";
            $xml .= "<id_plp />";
            $xml .= "<valor_global />";
            $xml .= "<mcu_unidade_postagem/>";
            $xml .= "<nome_unidade_postagem/>";
            $xml .= "<cartao_postagem>{$ambiente['cartao']}</cartao_postagem>";
            $xml .= "</plp>";


            $xml .= "<remetente>";
            $xml .= "<numero_contrato>{$ambiente['contrato']}</numero_contrato>";
            $xml .= "<numero_diretoria>{$ambiente['num_diretoria']}</numero_diretoria>";
            $xml .= "<codigo_administrativo>{$ambiente['cod_adm']}</codigo_administrativo>";

            $fax_cliente = '';

            if (isset($param['other_remetente']) && $param['other_remetente']) {

                $xml .= "<nome_remetente><![CDATA[{$param['other_remetente']['nome']}]]></nome_remetente>";
                $xml .= "<logradouro_remetente><![CDATA[{$param['other_remetente']['logradouro']}]]></logradouro_remetente>";
                $xml .= "<numero_remetente>" . substr(trim($param['other_remetente']['numero']), 0, 5) . "</numero_remetente>";
                           //    $xml .= "<numero_remetente>{$param['other_remetente']['numero']}</numero_remetente>";
                $xml .= "<complemento_remetente><![CDATA[{$param['other_remetente']['complemento']}]]></complemento_remetente>";
                $xml .= "<bairro_remetente><![CDATA[{$param['other_remetente']['bairro']}]]></bairro_remetente>";
                $xml .= "<cep_remetente>{$param['other_remetente']['cep']}</cep_remetente>";
                $xml .= "<cidade_remetente><![CDATA[{$param['other_remetente']['cidade']}]]></cidade_remetente>";
                $xml .= "<uf_remetente>{$param['other_remetente']['uf']}</uf_remetente>";
            } else {


                foreach ($param['user'] as $k => $v) {
                    $param['user'][$k] = Str::e($v);
                }

                $xml .= "<nome_remetente><![CDATA[{$param['user']['razao_social']}]]></nome_remetente>";
                $xml .= "<logradouro_remetente><![CDATA[{$param['user']['logradouro']}]]></logradouro_remetente>";
                $xml .= "<numero_remetente>" . substr(trim($param['user']['numero']), 0, 5) . "</numero_remetente>";
                $xml .= "<complemento_remetente><![CDATA[{$param['user']['complemento']}]]></complemento_remetente>";
                $xml .= "<bairro_remetente><![CDATA[{$param['user']['bairro']}]]></bairro_remetente>";
                $xml .= "<cep_remetente>{$param['user']['CEP']}</cep_remetente>";
                $xml .= "<cidade_remetente><![CDATA[{$param['user']['cidade']}]]></cidade_remetente>";
                $xml .= "<uf_remetente>{$param['user']['uf']}</uf_remetente>";
            }

                           //$xml .= "<telefone_remetente><![CDATA[" . preg_replace('/[^0-9]/', '', $param['user']['telefone']) . "]]></telefone_remetente>";
            $xml .= "<telefone_remetente><![CDATA[" . substr(preg_replace('/[^0-9]/', '', $param['user']['telefone']), 0, 11) . "]]></telefone_remetente>";

            if (true) { // $param['user']['id'] == '18' | iniciando 23/08/2022 as 08:00
                if ($param['user']['tipo_cliente'] == 'PF') {
                    $n_doc = $param['user']['cpf'];
                } else {
                    $n_doc = $param['user']['cnpj'];
                }

                $xml .= "<cpf_cnpj_remetente><![CDATA[" . $n_doc . "]]></cpf_cnpj_remetente>";
            }

            $xml .= "<fax_remetente><![CDATA[{$fax_cliente}]]></fax_remetente>";
                           //$xml .= "<email_remetente><![CDATA[{$param['user']['email']}]]></email_remetente>";
            $xml .= "<email_remetente><![CDATA[]]></email_remetente>";
            $xml .= "</remetente>";

            if (isset($param['user']['id']) && $param['user']['id'] == 'xxx18') {
                $xml .= "<remetente>";
                $xml .= "<numero_contrato>{$ambiente['contrato']}</numero_contrato>";
                $xml .= "<numero_diretoria>{$ambiente['num_diretoria']}</numero_diretoria>";
                $xml .= "<codigo_administrativo>{$ambiente['cod_adm']}</codigo_administrativo>";

                $fax_cliente = '';

                $xml .= "<nome_remetente><![CDATA[Manda Bem Intermediacoes]]></nome_remetente>";
                $xml .= "<logradouro_remetente><![CDATA[Rua dos Bambus]]></logradouro_remetente>";
                $xml .= "<numero_remetente>1223</numero_remetente>";
                $xml .= "<complemento_remetente><![CDATA[]]></complemento_remetente>";
                $xml .= "<bairro_remetente><![CDATA[Sao Paulo]]></bairro_remetente>";
                $xml .= "<cep_remetente>13468200</cep_remetente>";
                $xml .= "<cidade_remetente><![CDATA[Americana]]></cidade_remetente>";
                $xml .= "<uf_remetente>SP</uf_remetente>";


                $xml .= "<telefone_remetente><![CDATA[" . substr(preg_replace('/[^0-9]/', '', $param['user']['telefone']), -10) . "]]></telefone_remetente>";
                $xml .= "<fax_remetente><![CDATA[{$fax_cliente}]]></fax_remetente>";
                                           //$xml .= "<email_remetente><![CDATA[{$param['user']['email']}]]></email_remetente>";
                $xml .= "<email_remetente><![CDATA[]]></email_remetente>";
                $xml .= "</remetente>";
            }

            $xml .= "<forma_pagamento />";


            $xml_tmp = "";

            $codido_serv_sedex = '03220';
            if (isset($param['is_industrial']) && $param['is_industrial']) {
                $codido_serv_sedex = '03280';
            }


            // DESTINATÁRIO SEDEX
            for ($j = 0; $j < count($param['etiquetas']['sedex']); $j++) {
                #124849   (04162 - SEDEX)
                #124884   (04669 - PAC)

                $param_plp_sedex = array(
                    'numero_etiqueta' => $param['etiquetas']['sedex'][$j] . 'BR',
                           //        'codigo_servico_postagem' => $param['user']['group_code'] == 'cliente_contrato' ? $param['user']['codigo_servico_sedex'] : '04162',
                    'codigo_servico_postagem' => $param['user']['group_code'] == 'cliente_contrato' ? $param['user']['codigo_servico_sedex'] : $codido_serv_sedex,
                    'destinatario' => Str::e($param['dados_sedex'][$j]->destinatario),
                           //        'email' => $param['dados_sedex'][$j]->email,
                    'email' => '',
                    'logradouro' => Str::e($param['dados_sedex'][$j]->logradouro),
                    'complemento' => Str::e($param['dados_sedex'][$j]->complemento),
                    'numero' => Str::e($param['dados_sedex'][$j]->numero),
                    'bairro' => Str::e($param['dados_sedex'][$j]->bairro),
                    'cidade' => Str::e($param['dados_sedex'][$j]->cidade),
                    'estado' => Str::e($param['dados_sedex'][$j]->estado),
                    'cep' => $param['dados_sedex'][$j]->CEP,
                    'nota_fiscal' => $param['dados_sedex'][$j]->nota_fiscal,
                    'valor_cobrar' => '0,0',
                    'peso' => $param['dados_sedex'][$j]->peso,
                    'altura' => $param['dados_sedex'][$j]->altura,
                    'comprimento' => $param['dados_sedex'][$j]->comprimento,
                    'largura' => $param['dados_sedex'][$j]->largura,
                    'tipo_envio' => 'SEDEX'
                );
                if ($param['dados_sedex'][$j]->seguro) {
                    $param_plp_sedex['valor_seguro'] = $param['dados_sedex'][$j]->seguro;
                }
                if ($param['dados_sedex'][$j]->AR == 'S') {
                    $param_plp_sedex['AR'] = true;
                }
                $xml_tmp .= $this->getXmlObjetoPostal($param_plp_sedex);
            }

            $codido_serv_pac = '03298';
            if (isset($param['is_industrial']) && $param['is_industrial']) {
                $codido_serv_pac = '03336';
            }

            // DESTINATÁRIO PAC
            for ($j = 0; $j < count($param['etiquetas']['pac']); $j++) {

                $param_plp_pac = array(
                    'numero_etiqueta' => $param['etiquetas']['pac'][$j] . 'BR',
                           //        'codigo_servico_postagem' => $param['user']['group_code'] == 'cliente_contrato' ? $param['user']['codigo_servico_pac'] : '04669',
                    'codigo_servico_postagem' => $param['user']['group_code'] == 'cliente_contrato' ? $param['user']['codigo_servico_pac'] : $codido_serv_pac,
                    'destinatario' => Str::e($param['dados_pac'][$j]->destinatario),
                           //        'email' => $param['dados_pac'][$j]->email,
                    'email' => '',
                    'logradouro' => Str::e($param['dados_pac'][$j]->logradouro),
                    'complemento' => Str::e($param['dados_pac'][$j]->complemento),
                    'numero' => Str::e($param['dados_pac'][$j]->numero),
                    'bairro' => Str::e($param['dados_pac'][$j]->bairro),
                    'cidade' => Str::e($param['dados_pac'][$j]->cidade),
                    'estado' => Str::e($param['dados_pac'][$j]->estado),
                    'cep' => $param['dados_pac'][$j]->CEP,
                    'nota_fiscal' => $param['dados_pac'][$j]->nota_fiscal,
                    'valor_cobrar' => '0,0',
                    'peso' => $param['dados_pac'][$j]->peso,
                    'altura' => $param['dados_pac'][$j]->altura,
                    'comprimento' => $param['dados_pac'][$j]->comprimento,
                    'largura' => $param['dados_pac'][$j]->largura,
                    'tipo_envio' => 'PAC'
                );
                if (isset($param['dados_pac'][$j]->seguro) && $param['dados_pac'][$j]->seguro) {
                    $param_plp_pac['valor_seguro'] = $param['dados_pac'][$j]->seguro;
                }
                if ($param['dados_pac'][$j]->AR == 'S') {
                    $param_plp_pac['AR'] = true;
                }
                $xml_tmp .= $this->getXmlObjetoPostal($param_plp_pac);
            }

            $codido_serv_pacmini = '04227';
            if (isset($param['is_industrial']) && $param['is_industrial']) {
                $codido_serv_pacmini = '04391';
            }

            // DESTINATÁRIO PACMINI
            for ($j = 0; $j < count($param['etiquetas']['pacmini']); $j++) {

                $param_plp_pacmini = array(
                    'numero_etiqueta' => $param['etiquetas']['pacmini'][$j] . 'BR',
                    'codigo_servico_postagem' => $codido_serv_pacmini,
                           //        'codigo_servico_postagem' => '04391',
                    'destinatario' => Str::e($param['dados_pacmini'][$j]->destinatario),
                           //        'email' => $param['dados_pacmini'][$j]->email,
                    'email' => '',
                    'logradouro' => Str::e($param['dados_pacmini'][$j]->logradouro),
                    'complemento' => Str::e($param['dados_pacmini'][$j]->complemento),
                    'numero' => Str::e($param['dados_pacmini'][$j]->numero),
                    'bairro' => Str::e($param['dados_pacmini'][$j]->bairro),
                    'cidade' => Str::e($param['dados_pacmini'][$j]->cidade),
                    'estado' => Str::e($param['dados_pacmini'][$j]->estado),
                    'cep' => $param['dados_pacmini'][$j]->CEP,
                    'nota_fiscal' => $param['dados_pacmini'][$j]->nota_fiscal,
                    'valor_cobrar' => '0.0',
                    'peso' => $param['dados_pacmini'][$j]->peso,
                    'altura' => $param['dados_pacmini'][$j]->altura,
                    'comprimento' => $param['dados_pacmini'][$j]->comprimento,
                    'largura' => $param['dados_pacmini'][$j]->largura,
                    'tipo_envio' => 'PACMINI'
                );
                if (isset($param['dados_pacmini'][$j]->seguro) && $param['dados_pacmini'][$j]->seguro) {
                    $param_plp_pacmini['valor_seguro'] = $param['dados_pacmini'][$j]->seguro;
                }
                           //    if ($param['dados_pacmini'][$j]->AR == 'S') {
                           //        $param_plp_pacmini['AR'] = true;
                           //    }
                $xml_tmp .= $this->getXmlObjetoPostal($param_plp_pacmini);
            }

            $log_sedex_hoje = false;
            // DESTINATÁRIO SEDEX HOJE
            for ($j = 0; $j < count($param['etiquetas']['sedex_hj']); $j++) {

                $param_plp_sedex_hj = array(
                    'numero_etiqueta' => $param['etiquetas']['sedex_hj'][$j] . 'BR',
                    'codigo_servico_postagem' => '03662',
                    'destinatario' => Str::e($param['dados_sedex_hj'][$j]->destinatario),
                           //        'email' => $param['dados_pacmini'][$j]->email,
                    'email' => '',
                    'logradouro' => Str::e($param['dados_sedex_hj'][$j]->logradouro),
                    'complemento' => Str::e($param['dados_sedex_hj'][$j]->complemento),
                    'numero' => Str::e($param['dados_sedex_hj'][$j]->numero),
                    'bairro' => Str::e($param['dados_sedex_hj'][$j]->bairro),
                    'cidade' => Str::e($param['dados_sedex_hj'][$j]->cidade),
                    'estado' => Str::e($param['dados_sedex_hj'][$j]->estado),
                    'cep' => $param['dados_sedex_hj'][$j]->CEP,
                    'nota_fiscal' => $param['dados_sedex_hj'][$j]->nota_fiscal,
                    'valor_cobrar' => '0.0',
                    'telefone' => $param['dados_sedex_hj'][$j]->telefone,
                    'peso' => $param['dados_sedex_hj'][$j]->peso,
                    'altura' => $param['dados_sedex_hj'][$j]->altura,
                    'comprimento' => $param['dados_sedex_hj'][$j]->comprimento,
                    'largura' => $param['dados_sedex_hj'][$j]->largura,
                    'tipo_envio' => 'SEDEX HOJE'
                );
                if (isset($param['dados_sedex_hj'][$j]->seguro) && $param['dados_sedex_hj'][$j]->seguro) {
                    $param_plp_sedex_hj['valor_seguro'] = $param['dados_sedex_hj'][$j]->seguro;
                }
                $xml_tmp .= $this->getXmlObjetoPostal($param_plp_sedex_hj);
                $log_sedex_hoje = true;
            }

            // DESTINATÁRIO SEDEX 12
            for ($j = 0; $j < count($param['etiquetas']['sedex_12']); $j++) {

                $param_plp_sedex_12 = array(
                    'numero_etiqueta' => $param['etiquetas']['sedex_12'][$j] . 'BR',
                    'codigo_servico_postagem' => '03140',
                    'destinatario' => Str::e($param['dados_sedex_12'][$j]->destinatario),
                           //        'email' => $param['dados_pacmini'][$j]->email,
                    'email' => '',
                    'logradouro' => Str::e($param['dados_sedex_12'][$j]->logradouro),
                    'complemento' => Str::e($param['dados_sedex_12'][$j]->complemento),
                    'numero' => Str::e($param['dados_sedex_12'][$j]->numero),
                    'bairro' => Str::e($param['dados_sedex_12'][$j]->bairro),
                    'cidade' => Str::e($param['dados_sedex_12'][$j]->cidade),
                    'estado' => Str::e($param['dados_sedex_12'][$j]->estado),
                    'cep' => $param['dados_sedex_12'][$j]->CEP,
                    'nota_fiscal' => $param['dados_sedex_12'][$j]->nota_fiscal,
                    'valor_cobrar' => '0.0',
                    'telefone' => $param['dados_sedex_12'][$j]->telefone,
                    'peso' => $param['dados_sedex_12'][$j]->peso,
                    'altura' => $param['dados_sedex_12'][$j]->altura,
                    'comprimento' => $param['dados_sedex_12'][$j]->comprimento,
                    'largura' => $param['dados_sedex_12'][$j]->largura,
                    'tipo_envio' => 'SEDEX 12'
                );
                if (isset($param['dados_sedex_12'][$j]->seguro) && $param['dados_sedex_12'][$j]->seguro) {
                    $param_plp_sedex_hj['valor_seguro'] = $param['dados_sedex_12'][$j]->seguro;
                }
                $xml_tmp .= $this->getXmlObjetoPostal($param_plp_sedex_12);
            }

            $xml .= $xml_tmp . "</correioslog>";

            if ($log_sedex_hoje || (isset($param['user']['id']) && $param['user']['id'] == '8483' && isset($param['coleta_id'])) || (isset($param['user']['id']) && $param['user']['id'] == '5' && isset($param['coleta_id']))) {
                DB::table('coletas')
                    ->where('id', $param['coleta_id'])
                    ->whereNull('xml_plp')
                    ->update(['xml_plp' => $xml]);
            }


            $etiqueta_sem_dv = array();
            foreach ($param['etiquetas']['sedex'] as $etiqueta) {
                array_push($etiqueta_sem_dv, substr($etiqueta, 0, -1) . 'BR'); //remove digito 
            }
            foreach ($param['etiquetas']['pac'] as $etiqueta) {
                array_push($etiqueta_sem_dv, substr($etiqueta, 0, -1) . 'BR'); //remove digito 
            }
            foreach ($param['etiquetas']['pacmini'] as $etiqueta) {
                array_push($etiqueta_sem_dv, substr($etiqueta, 0, -1) . 'BR'); //remove digito 
            }
            foreach ($param['etiquetas']['sedex_hj'] as $etiqueta) {
                array_push($etiqueta_sem_dv, substr($etiqueta, 0, -1) . 'BR'); //remove digito 
            }
            foreach ($param['etiquetas']['sedex_12'] as $etiqueta) {
                array_push($etiqueta_sem_dv, substr($etiqueta, 0, -1) . 'BR'); //remove digito 
            }

            $idPlpCliente = str_pad($param['coleta_id'], 9, '0', STR_PAD_LEFT);
            $listaEtiquetas = $etiqueta_sem_dv; #array('EC31118267BR', 'EC31118268BR'); 
            $params = array(
                'xml' => $xml,
                'idPlpCliente' => $idPlpCliente,
                'cartaoPostagem' => $ambiente['cartao'],
                'listaEtiquetas' => $listaEtiquetas,
                'usuario' => $ambiente['usuario'],
                'senha' => $ambiente['senha']
            );

            $result = $clientSoap->fechaPlpVariosServicos($params);
            $plp = $result->return;
        } catch (\Exception $e) {

            if (preg_match('/Logradouro(.*?)informado./', $e->getMessage())) {
                $this->error = $e->getMessage();
                return false;
            }

            $this->error = 'PROBLEMAS NO WEBSERVICE DOS CORREIOS.<br>O servidor dos Correios não está respondendo à nossa solicitação, Por favor tente novamente em alguns minutos.';
            $this->error_trace = print_r($e, true);
            return false;
        }

        return $plp;
    }

    public function getXmlObjetoPostal($param = array()) 
    {
        $xml = "<objeto_postal>";
        $xml .= "<numero_etiqueta>" . $param['numero_etiqueta'] . "</numero_etiqueta>";
        $xml .= "<codigo_objeto_cliente/>";
        $xml .= "<codigo_servico_postagem>" . $param['codigo_servico_postagem'] . "</codigo_servico_postagem>";
        $xml .= "<cubagem>0,00</cubagem>";
        // em gramas
        if ($param['peso'] < 1) {
            $xml .= "<peso>" . preg_replace('/\./', '', ($param['peso'] * 1000)) . "</peso>";
        } else {
            $xml .= "<peso>" . preg_replace('/\./', '', $param['peso']) . "</peso>";
        }
        $xml .= "<rt1/><rt2/>";
        $xml .= "<destinatario>";
        $xml .= "<nome_destinatario><![CDATA[" . $param['destinatario'] . "]]></nome_destinatario>";
        if (isset($param['telefone'])) {
            $xml .= "<telefone_destinatario><![CDATA[" . substr(preg_replace('/[^0-9]/', '', $param['telefone']), 0, 11) . "]]></telefone_destinatario>";
        } else {
            $xml .= "<telefone_destinatario><![CDATA[]]></telefone_destinatario>";
        }
        $xml .= "<celular_destinatario><![CDATA[]]></celular_destinatario>";
        $xml .= "<email_destinatario><![CDATA[]]></email_destinatario>";
        $xml .= "<logradouro_destinatario><![CDATA[" . $param['logradouro'] . "]]></logradouro_destinatario>";
        $xml .= "<complemento_destinatario><![CDATA[" . $param['complemento'] . "]]></complemento_destinatario>";
        $xml .= "<numero_end_destinatario>" . substr(trim($param['numero']), 0, 5) . "</numero_end_destinatario>";
        $xml .= "</destinatario>";
        $xml .= "<nacional>";
        $xml .= "<bairro_destinatario><![CDATA[" . $param['bairro'] . "]]></bairro_destinatario>";
        $xml .= "<cidade_destinatario><![CDATA[" . $param['cidade'] . "]]></cidade_destinatario>";
        $xml .= "<uf_destinatario>" . $param['estado'] . "</uf_destinatario>";
        $xml .= "<cep_destinatario><![CDATA[" . $param['cep'] . "]]></cep_destinatario>";
        $xml .= "<codigo_usuario_postal/>";
        $xml .= "<centro_custo_cliente/>";
        $xml .= "<numero_nota_fiscal>" . $param['nota_fiscal'] . "</numero_nota_fiscal>";
        $xml .= "<serie_nota_fiscal/>";
        $xml .= "<valor_nota_fiscal/>";
        $xml .= '<natureza_nota_fiscal/>';
        $xml .= '<descricao_objeto><![CDATA[]]></descricao_objeto>';
        $xml .= '<valor_a_cobrar>' . $param['valor_cobrar'] . '</valor_a_cobrar>';
        $xml .= '</nacional>';

        if (true) {

            $xml .= '<servico_adicional>';
            $xml .= '<codigo_servico_adicional>025</codigo_servico_adicional>';

            if ($param['tipo_envio'] != 'PACMINI') {
                if (isset($param['AR']) && $param['AR']) {
                    $xml .= "<codigo_servico_adicional>001</codigo_servico_adicional>";
                }
            }
            if (isset($param['valor_seguro']) && $param['valor_seguro'] > 0) {
                if ($param['tipo_envio'] == 'SEDEX' || $param['tipo_envio'] == 'SEDEX 10' || $param['tipo_envio'] == 'SEDEX HOJE') {
                    $xml .= '<codigo_servico_adicional>019</codigo_servico_adicional>';
                } else if ($param['tipo_envio'] == 'PACMINI') {
                    $xml .= '<codigo_servico_adicional>065</codigo_servico_adicional>';
                } else if ($param['tipo_envio'] == 'PAC') {
                    $xml .= '<codigo_servico_adicional>064</codigo_servico_adicional>';
                }
                $xml .= '<valor_declarado>' . number_format($param['valor_seguro'], 2, '.', '') . '</valor_declarado>';
            } else {
                $xml .= '<valor_declarado>0.00</valor_declarado>';
            }

            $xml .= "</servico_adicional>";
        }
        $xml .= "<dimensao_objeto>";
        $xml .= "<tipo_objeto>002</tipo_objeto>";

        if ($param['tipo_envio'] == 'PACMINI') {
            if (isset($param['altura']) && (int) $param['altura'] && (int) $param['altura'] > 1) {
                $xml .= "<dimensao_altura>" . (int) $param['altura'] . "</dimensao_altura>";
            } else {
                $xml .= "<dimensao_altura>1</dimensao_altura>";
            }
            if (isset($param['largura']) && (int) $param['largura'] && (int) $param['largura'] > 11) {
                $xml .= "<dimensao_largura>" . (int) $param['largura'] . "</dimensao_largura>";
            } else {
                $xml .= "<dimensao_largura>11</dimensao_largura>";
            }
            if (isset($param['comprimento']) && (int) $param['comprimento'] && (int) $param['comprimento'] > 16) {
                $xml .= "<dimensao_comprimento>" . (int) $param['comprimento'] . "</dimensao_comprimento>";
            } else {
                $xml .= "<dimensao_comprimento>16</dimensao_comprimento>";
            }
        } else {

            if (isset($param['altura']) && (int) $param['altura'] && (int) $param['altura'] > 2) {
                $xml .= "<dimensao_altura>" . (int) $param['altura'] . "</dimensao_altura>";
            } else {
                $xml .= "<dimensao_altura>2</dimensao_altura>";
            }
            if (isset($param['largura']) && (int) $param['largura'] && (int) $param['largura'] > 11) {
                $xml .= "<dimensao_largura>" . (int) $param['largura'] . "</dimensao_largura>";
            } else {
                $xml .= "<dimensao_largura>11</dimensao_largura>";
            }
            if (isset($param['comprimento']) && (int) $param['comprimento'] && (int) $param['comprimento'] > 16) {
                $xml .= "<dimensao_comprimento>" . (int) $param['comprimento'] . "</dimensao_comprimento>";
            } else {
                $xml .= "<dimensao_comprimento>16</dimensao_comprimento>";
            }
        }

        $xml .= "<dimensao_diametro>0</dimensao_diametro>";
        $xml .= "</dimensao_objeto>";
        $xml .= "<data_postagem_sara/>";
        $xml .= "<status_processamento>0</status_processamento>";
        $xml .= "<numero_comprovante_postagem/>";
        $xml .= "<valor_cobrado/>";
        $xml .= "</objeto_postal>";


        return $xml;
    }

    public function statusEtiqueta($data = [])
    {
        $etiqueta = substr($data['etiqueta'], 0, -2);
        $envio = [];
        $envio[0]->etiqueta_correios = $etiqueta;

        $correioRest = new CorreioRest();
        $result = $correioRest->rastreamento(['envios' => $envio]);

        $res = new \stdClass();  

        if (isset($result['objetos'][0]['eventos'])) {
            foreach ($result['objetos'][0]['eventos'] as $k => $i) {
                $res->return->objeto->evento[$k]->status = $i['tipo'];
                $res->return->objeto->evento[$k]->tipo = $i['codigo'];
                $res->return->objeto->evento[$k]->descricao = $i['descricao'];
                $res->return->objeto->evento[$k]->dtHrCriado = $i['dtHrCriado'];
                $res->return->objeto->evento[$k]->unidade = $i['unidade'];
            }
        } else {
            if (!empty($result['objetos'][0]['codObjeto'])) {
                $res->codObjeto = $result['objetos'][0]['codObjeto'];
                $res->mensagem = $result['objetos'][0]['mensagem'];
            }
        }

        $return = [];
        $return['status'] = $this->getStatusObject($data['etiqueta'], $res);
        $return['data'] = $res;

        return $return;
    }

    public function isObjetoFinalizado($tipo, $status) 
    {
        if (preg_match('/BDE|BDI|BDR/', $tipo) && ($status == '01' || $status == '23')) {
            return true;
        }
        return false;
    }

    public function getStatusObject($etiqueta, $data) 
    {
        $logModel = new Log();
        if (!isset($data->return->objeto)) {
            return false;
        }
        if (isset($data->return->objeto->erro)) {
            if (preg_match('/Objeto n(.*?)o encontrado na base de dados dos Correios/', $data->return->objeto->erro)) {
                return 'NAO_ENCONTRADO';
            }
            return false;
        }
        if (!isset($data->return->objeto->evento)) {
            return false;
        }

        if (is_array($data->return->objeto->evento) && isset($data->return->objeto->evento[0])) {
            $status = $data->return->objeto->evento[0]->status;
            $tipo = $data->return->objeto->evento[0]->tipo;
            $descricao = $data->return->objeto->evento[0]->descricao;
        } else {
            $status = $data->return->objeto->evento->status;
            $tipo = $data->return->objeto->evento->tipo;
            $descricao = $data->return->objeto->evento->descricao;
        }
        if (($tipo == 'BDE' || $tipo == 'BDI' || $tipo == 'BDR') && ($status == '01' || $status == '23')) {
            return 'FINALIZADO';
        }

        return $descricao;

    }
    //corrigir ao fazer instalacao do SoapClient
    public function cancelReversa($data = array()) 
    {
        $environment = $data['environment'];

        $logModel = new Log();

        $env = getCredentialsReversa($data['user'])[$environment];

        if (true || Request::ip() == '177.66.211.136') {
            $param_cancel = array(
                'codAdministrativo' => $env['cod_adm'],
                'numeroPedido' => $data['numero_pedido'],
                'tipo' => 'A'
            );

            $msg = '<?xml version="1.0" encoding="UTF-8"?>
                    <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://service.logisticareversa.correios.com.br/">
                        <SOAP-ENV:Body>
                            <ns1:cancelarPedido>
                                <codAdministrativo>' . $param_cancel['codAdministrativo'] . '</codAdministrativo>
                                <numeroPedido>' . $param_cancel['numeroPedido'] . '</numeroPedido>
                                <tipo>' . $param_cancel['tipo'] . '</tipo>
                            </ns1:cancelarPedido>
                        </SOAP-ENV:Body>
                    </SOAP-ENV:Envelope>';


            // ajuste para cancelar com problema nos Correios
            if ($param_cancel['numeroPedido'] == '2154189761') {
                return true;
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $env['link']);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);

            $outgoing = array();
            $outgoing[] = "User-Agent: NuSOAP/0.9.5 (1.123)";
            $outgoing[] = "Content-Type: text/xml;charset=UTF-8";
            $outgoing[] = 'Authorization: Basic ' . base64_encode($env['ws_login'] . ":" . $env['ws_password']);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $outgoing);

            $resp = curl_exec($ch);
            $info = curl_getinfo($ch);
            $curl_error = curl_error($ch);

            if ($info['http_code'] != '200') {
                $error[] = "Falha conexão Correios, Por favor tente novamente em alguns minutos.";
            }

            $logModel->log(array(
                'type' => 'CANCELAMENTO Reversa',
                'text' => 'LOG: ' . print_r($resp, true) . "\n" . print_r($info, true) . "\n" . print_r($curl_error, true)
            ));

            if (!$resp) {
                $logModel->log(array(
                    'type' => 'CANCELAMENTO Reversa',
                    'text' => 'Falha(1): Retorno cancelamento difere do esperado: ' . print_r($resp, true)
                ));
                return false;
            }

            $dom = new DOMDocument('1.0', 'UTF-8');
            if (!$dom->loadXML($resp)) {
                $logModel->log(array(
                    'type' => 'CANCELAMENTO Reversa',
                    'text' => 'Falha(2): Retorno cancelamento difere do esperado: ' . print_r($resp, true)
                ));
                return false;
            }

            if ($dom->getElementsByTagName('msg_erro')->length) {
                $msgError = $dom->getElementsByTagName('msg_erro')->item(0)->nodeValue;
                if (isset($msgError) && preg_match('/DESISTÊNCIA DO CLIENTE ECT/', $msgError)) {
                    return true;
                }
            }

            $cancelarPedido = $dom->getElementsByTagName('cancelarPedido')->item(0);
            $numero_pedido = $cancelarPedido->getElementsByTagName('objeto_postal')->item(0)->getElementsByTagName('numero_pedido')->item(0)->nodeValue;
            $codeError = $cancelarPedido->getElementsByTagName('cod_erro')->length ? $cancelarPedido->getElementsByTagName('cod_erro')->item(0)->nodeValue : '';

            if (strlen($numero_pedido)) {
                $logModel->log(array(
                    'type' => 'CANCELAMENTO Reversa OK',
                    'text' => 'LOG: ' . print_r($resp, true) . "\n" . print_r($info, true) . "\n" . print_r($curl_error, true)
                ));
                return true;
            } else {

                if (isset($codeError) && $codeError == '-9') {
                    $logModel->log(array(
                        'type' => 'CANCELAMENTO Reversa OK',
                        'text' => 'LOG: ' . print_r($resp, true) . "\n" . print_r($info, true) . "\n" . print_r($curl_error, true)
                    ));
                    return true;
                }

                $logModel->log(array(
                    'type' => 'CANCELAMENTO Reversa)',
                    'text' => 'Falha (3): Retorno cancelamento difere do esperado: ' . print_r($resp, true) . "\n" . print_r($info, true) . "\n" . print_r($curl_error, true)
                ));
                return false;
            }
        } else {

            $clientSoap = new SoapClient($env['link'], array(
                'stream_context' => stream_context_create(
                        array('http' =>
                            array(
                                'protocol_version' => '1.0',
                                'header' => 'Connection: Close'
                            )
                        )
                ), 'login' => $env['ws_login'], 'password' => $env['ws_password']
            ));


            $param_cancel = array(
                'codAdministrativo' => $env['cod_adm'],
                'numeroPedido' => $data['numero_pedido'],
                'tipo' => 'A'
            );



            try {
                $result = $clientSoap->cancelarPedido($param_cancel);

                if (isset($result->cancelarPedido->objeto_postal)) {
                    return true;
                } else {

                    if (isset($result->cancelarPedido->cod_erro) && $result->cancelarPedido->cod_erro == '-9') {
                        return true;
                    }

                    $logModel->log(array(
                        'type' => 'CANCELAMENTO Reversa)',
                        'text' => 'Falha (2): Retorno cancelamento difere do esperado: ' . print_r($result, true)
                    ));
                    return false;
                }
            } catch (\Exception $e) {
                $logModel->log(array(
                    'type' => 'CANCELAMENTO Reversa',
                    'text' => 'Falha(1): Retorno cancelamento difere do esperado: ' . print_r($e, true)
                ));
                return false;
            }
        }
    }
    //corrigir ao fazer instalacao do SoapClient
    public function cancelPlp($data = array()) 
    {

        $env = getCredentialsEtiqueta()['production'];

        $clientSoap = new SoapClient($env['link'], array(
            'stream_context' => stream_context_create(
                    array('http' =>
                        array(
                            'protocol_version' => '1.0',
                            'header' => 'Connection: Close'
                        )
                    )
            ), 'login' => $env['ws_login'], 'password' => $env['ws_password']
        ));


        $param_cancel = array(
            'codAdministrativo' => $env['cod_adm'],
            'idPostagem' => $data['plp'],
            'usuario' => $env['usuario'],
            'senha' => $env['senha'],
            'tipo' => 'A'
        );

        $logModel = new Log();

        try {
            // reversa
            $result = $clientSoap->cancelarPedidoScol($param_cancel);
            print_r($result);
        } catch (\Exception $e) {
            print_r($e);
            $logModel->log(array(
                'type' => 'CANCELAMENTO PLP',
                'text' => 'Falha(1): Retorno cancelamento difere do esperado: ' . print_r($e, true)
            ));
            return false;
        }


        return true;
    }
    //corrigir ao fazer instalacao do SoapClient
    // Bloquear cancela o que já foi postado
    public function bloquearObjeto($data = array()) 
    {
        
        $environment = $data['environment'];

        $ambiente = getCredentialsEtiqueta($data['user'])[$environment];

        $clientSoap = new SoapClient($ambiente['link'], array(
            'stream_context' => stream_context_create(
                    array('http' => array(
                            'protocol_version' => '1.0',
                            'header' => 'Connection: Close'
                        )
                    )
            )
                )
        );

        $param_bloquear = array(
            'numeroEtiqueta' => $data['etiqueta'],
            'idPlp' => $data['plp'],
            'tipoBloqueio' => 'FRAUDE_BLOQUEIO',
            'acao' => 'DEVOLVIDO_AO_REMETENTE',
            'usuario' => $ambiente['usuario'],
            'senha' => $ambiente['senha']);
        
        $logModel = new Log();
        $emailMaker = new EmailMaker();
        try {
            $result = $clientSoap->bloquearObjeto($param_bloquear);
             
            $logModel->log(array(
                'type' => 'CANCELAMENTO OBJ '.$data['etiqueta'],
                'text' => 'Retorno OK: ' . print_r($result, true)
            ));

            if ($result->return == 'Registro gravado') {
                
                return true;
            } else {
                $this->error = 'Falha ao cancelar';
                $emailMaker->msg(array(
                    'to' => 'reginaldo@mandabem.com.br,clayton@mandabem.com.br,wieder@mandabem.com.br',
                    'subject' => 'Erro Bloqueio de Objeto '.$data['etiqueta'],
                    'msg' => "<pre>" . print_r($result, true) . "</pre>"
                ));
                $logModel->log(array(
                    'type' => 'BLOQUEIO OBJ',
                    'text' => 'Falha: Retorno cancelamento difere do esperado: ' . print_r($result, true)
                ));
                return false;
            }
        } catch (\Exception $e) {
                           //if ($_SERVER['REMOTE_ADDR'] == '45.181.35.133') {
                           //    print_r('$param_bloquear');exit;
                           //}
            $emailMaker->msg(array(
                'to' => 'reginaldo@mandabem.com.br,clayton@mandabem.com.br,wieder@mandabem.com.br',
                'subject' => 'Erro Bloqueio de Objeto erro '.$data['etiqueta'],
                'msg' => "<pre>" . print_r($e->getMessage(), true) . "</pre>"
            ));

                           //if(isset($e->message)){
            if (preg_match('/Objeto n(.*?)o pode ser bloqueado pois foi cancelado/', $e->getMessage())) {
                $this->error = 'Objeto não pode ser bloqueado pois foi cancelado';
                return false;
            }
                           //}

            $logModel->log(array(
                'type' => 'CANCELAMENTO OBJ',
                'text' => 'Falha(2): Retorno cancelamento difere do esperado: ' . print_r($e, true)
            ));
                           //echo "Exception\n";
                           //print_r($e);
            return false;
        }
    }
    //corrigir ao fazer instalacao do SoapClient
    // cancelar impede de postar
    public function cancelarObjeto($data = array()) 
    {
        $envioModel = new Envio();
        $coletaModel = new Coleta();
        $environment = 'production';

        $ambiente = getCredentialsEtiqueta($data['user'])[$environment];

        $clientSoap = new SoapClient($ambiente['link'], array(
            'stream_context' => stream_context_create(
                    array('http' => array(
                            'protocol_version' => '1.0',
                            'header' => 'Connection: Close'
                        )
                    )
            )
                )
        );
        
       
        $dados['etiqueta'] =  $data['numeroEtiqueta'];
        $etiqueta = $this->statusEtiqueta($dados);
        //valido se o envio foi postado
        if (preg_match('/Objeto não encontrado na base de dados dos Correios/i', $etiqueta['data']->mensagem)) {
            if($_SERVER['REMOTE_ADDR'] == '45.181.35.133'){
                //busca o envio pela etiqueta
                $env = $envioModel->getByEtiqueta(substr($data['numeroEtiqueta'], 0, -2));
                //busco todos os envios com a mesma coleta id
                $envios = $coletaModel->getEnvios($env->coleta_id);
                //foreach para validar se nenhum dos envios da coleta foi postado
                foreach($envios as $e){
                    //verifico se houve algum envio postado, caso tenha cancelo direto e adc o saldo na conta do user
                    if($e->etiqueta_status != 'Aguardando Postagem'){
                        return true;
                    }
                }
                //se nenhum envio da coleta tiver sido postado faço o cancelamento pelo metodo dos correios
                $param_cancelar = array(
                    'idPlp' => $data['plp'],
                    'numeroEtiqueta' => $data['numeroEtiqueta'],
                    'usuario' => $ambiente['usuario'],
                    'senha' => $ambiente['senha']
                );
                $logModel = new Log();

                try {
                    $result = $clientSoap->cancelarObjeto($param_cancelar);
                    $logModel->log(array(
                        'type' => 'CANCELAMENTO OBJ '. $data['numeroEtiqueta'],
                        'text' => 'Retorno OK: ' . print_r($result, true)
                    ));
                } catch (\Exception $e) {
                    if(isset($e->faultstring)){
                        $this->error = $e->faultstring;
                    } 
                }
                return false;
            }
            return true;
            
        }else{
            $param_bloquear = array(
                'user' => $data['user'],
                'plp' => $data['plp'],
                'environment' => "production",
                'etiqueta' => $data['numeroEtiqueta']
            );
            $bloqueio = $this->bloquearObjeto($param_bloquear);
            if($bloqueio){
                $param_cancelar = array(
                    'idPlp' => $data['plp'],
                    'numeroEtiqueta' => $data['numeroEtiqueta'],
                    'usuario' => $ambiente['usuario'],
                    'senha' => $ambiente['senha']
                );
                $logModel = new Log();

                try {
                    $result = $clientSoap->cancelarObjeto($param_cancelar);
                    $logModel->log(array(
                        'type' => 'CANCELAMENTO OBJ',
                        'text' => 'Retorno OK: ' . print_r($result, true)
                    ));

                    return $result;

                } catch (\Exception $e) {
                    if(isset($e->faultstring)){

                        $this->error = $e->faultstring;
                    } 
                }
                return false;
            }else{
                return false;
            }
            
        }
    }

    public function arEventos($objeto) 
    {
        $data = array(
            'cnpj' => '27347642000118',
            'objetos' => [$objeto],
        );
        $data_send = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://cws.correios.com.br/areletronico/v1/ars/eventos');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_send);

        $outgoing = array();
        $outgoing[] = "User-Agent: NuSOAP/0.9.5 (1.123)";
        $outgoing[] = "Content-Type: application/json";
        $outgoing[] = 'Authorization: Basic ' . base64_encode('maquinamqn' . ":" . 'manda2020');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $outgoing);

        $resp = curl_exec($ch);
        $info = curl_getinfo($ch);
        $curl_error = curl_error($ch);

        $retorno = json_decode($resp);

        if ($info['http_code'] != '200') {
            echo "Falha conexão Correios, Por favor tente novamente em alguns minutos.";
            return false;
        }
        return $retorno[0];
    }
    //corrigir ao fazer instalacao do SoapClient
    public function verificaDispServicos($params = array()) 
    {

        $clientSoap = @new SoapClient('https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl', array('stream_context' => stream_context_create(
                            array('http' =>
                                array(
                                    'protocol_version' => '1.0',
                                    'header' => 'Connection: Close'
                                )
                            )
                    )
                        )
        );

        $environment = 'production';

        $ambiente = getCredentialsEtiqueta([])[$environment];

        if (!$clientSoap) {
                           //echo "Falha conexão\n";
            return false;
        }

        if ($params['forma_envio'] == 'PAC') {
            $cod_servico = '03298';
        } else if ($params['forma_envio'] == 'SEDEX') {
            $cod_servico = '03220';
        } else if ($params['forma_envio'] == 'PACMINI') {
            $cod_servico = '04227';
        } else if ($params['forma_envio'] == 'SEDEX HOJE') {
            $cod_servico = '03662';
        } else if ($params['forma_envio'] == 'SEDEX 10') {
            $cod_servico = '03158';
        } else if ($params['forma_envio'] == 'SEDEX 12') {
            $cod_servico = '03140';
        }

        $consultaCEP = [
            'usuario' => $ambiente['usuario'],
            'senha' => $ambiente['senha'],
            'codAdministrativo' => $ambiente['cod_adm'],
            'cepOrigem' => preg_replace('/[^0-9]/','', $params['cep_origem']),
            'cepDestino' => preg_replace('/[^0-9]/','', $params['cep_destino']),
            'numeroServico' => $cod_servico
        ];

        try {
            $result = $clientSoap->verificaDisponibilidadeServico($consultaCEP);
            if ($result->return == "0#") {
                return 'ok';
            } else {
                return 'nok';
            }
        } catch (\Exception $e) {
            // Objeto não encontrado na base de dados do
            if (!preg_match('/Objeto n(.*?)o encontrado na base de dados dos Correios/', $e->getMessage())) {
            }
            return false;
        }
    }

}
