<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Envio;
use App\Models\Log;
use App\Models\Webservice;
//corrigir quando houver a librarie
use App\Services\Correio\Correio;


class Acompanhamento extends Model
{
    private $fieldsEmail = [];
    private $error;

    public function __construct()
    {
        parent::__construct();

        $this->fieldsEmail = [
            "id" => ['type' => 'hidden', 'required' => true],
            "subject" => ['type' => 'text', 'label' => 'Assunto', 'required' => true, 'cols' => [2, 10]],
            "body" => ['type' => 'textarea', 'label' => 'Corpo do Email', 'rows' => 20, 'required' => true, 'cols' => [2, 10]],
        ];
    }

    public function notifyEnvio($data)
    {
        $error = [];
        //alterar quando houver as libraries
        $envioModel = new Envio();  
        $emailMaker = new EmailMaker();  

        $envioId = $data['envio_id'];
        $etiqueta = isset($data['etiqueta']) ? $data['etiqueta'] : null;

        $envio = DB::table('envios')
            ->whereNotNull('ref_id')
            ->where('id', $envioId)
            ->when($etiqueta, function ($query) use ($etiqueta) {
                return $query->where('etiqueta_correios', $etiqueta);
            })
            ->first();

        if (!$envio || !$envio->etiqueta_correios) {
            $error[] = "Envio não encontrado (1)\n" . print_r($data, true);
        }

        if (!$error) {
            // Integracoes permitidas
            if (!in_array($envio->integration, ['Wordpress', 'LojaIntegrada', 'Bling', 'NuvemShop', 'Tiny', 'Yampi', 'Plugg', 'Fastcommerce','Linx'])) {
                return;
            }

            // Wordpress
            if ($envio->integration == 'Wordpress') {
                $api = DB::table('api')->where('user_id', $envio->user_id)->first();
            }

            if ($envio->integration == 'Yampi') {
                app('App\Http\Controllers\YampiController')->notifyEnvio([
                    'envio_id' => $envioId,
                    'etiqueta' => $etiqueta,
                    'type' => $data['type'],
                ]);

                return true;
            }
            if ($envio->integration == 'Linx') {
                app('App\Http\Controllers\LinxController')->notifyEnvio([
                    'envio_id' => $envioId,
                    'etiqueta' => $etiqueta,
                    'type' => $data['type'],
                ]);

                return true;
            }
            if ($envio->integration == 'Fastcommerce') {
                app('App\Http\Controllers\FastcommerceController')->notifyEnvio([
                    'envio_id' => $envioId,
                    'etiqueta' => $etiqueta,
                    'type' => $data['type'],
                ]);

                return true;
            }
            if ($envio->integration == 'Plugg') {
                app('App\Http\Controllers\PluggController')->notifyEnvio([
                    'envio_id' => $envioId,
                    'etiqueta' => $etiqueta,
                    'type' => $data['type'],
                ]);

                return true;
            }
            $user = DB::table('users')->find($envio->user_id);

            // Nuvem precisa da API
            if (in_array($envio->integration, ['NuvemShop', 'LojaIntegrada', 'Tiny'])) {
                if (!strlen($envio->ref_id_api_source)) {
                    $error[] = "Sem id de referência.(" . $envio->etiqueta_correios . ") Integração: " . $envio->integration . " ";
                }

                if ($envio->integration == 'NuvemShop') {
                    $api = DB::table('api_nuvem_shop')->where('user_id', $user->id)->first();
                }
                // Futuro: Necessitará de API
                elseif ($envio->integration == 'LojaIntegrada') {
                    $api = DB::table('api_loja_integrada')->where('user_id', $user->id)->first();
                } elseif ($envio->integration == 'Tiny') {
                    $api = DB::table('api_tiny')->where('user_id', $user->id)->first();
                }
            }

            // Bling precisa do ID dos serviços
            $apiBling = DB::table('api_bling')
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            if ($envio->integration == 'Bling' || $apiBling) {
                $apiB = DB::table('api_bling')
                    ->where('user_id', $user->id)
                    ->where('status', 'active')
                    ->first();

                // ID serviço usado para informar LOGÍSTICA
                $idServico = null;

                if ($envio->forma_envio == 'SEDEX') {
                    $idServico = $apiB->id_sedex;
                } elseif ($envio->forma_envio == 'PAC') {
                    $idServico = $apiB->id_pac;
                } elseif ($envio->forma_envio == 'PACMINI') {
                    $idServico = $apiB->id_pacmini;
                }

                if (!$idServico) {
                    $error[] = "Falha ao encontrar ID do serviço: " . print_r($envio, true);

                    // Continuar mesmo com erro em casos NuvemShop
                    if ($envio->integration == 'NuvemShop') {
                        $error = [];
                    }
                }
            }
            if ($data['type'] == 'date_postagem') {

                $notifyTiny = false;
    
                if (isset($data['is_test'])) {
                    echo "Iniciando WP -0\n";
                }
    
                // Não postado ainda
                if (!$envio->date_postagem) {
                    $error[] = "Sem data de postagem (2)\n" . print_r($data, true);
                }
    
                // Já foi informado
                if (strlen($envio->date_postagem) && $envio->integration == 'NuvemShop' && $envio->aviso_nuvem_shop == 1) {
                    $error[] = "Data de postagem já informada ($etiqueta)\n";
                }
    
                // Verificar em Envio Notify se já existe notificação
                if (in_array($envio->integration, ['Wordpress', 'NuvemShop', 'Bling', 'LojaIntegrada', 'Tiny'])) {
                    $notify = DB::table('envios_notify')
                        ->where('type', $data['type'])
                        ->where('integration', $envio->integration)
                        ->where('status', 'OK')
                        ->where('envio_id', $envio->id)
                        ->first();
    
                    if ($notify) {
                        $error[] = "Data de postagem já informada ($etiqueta)\n";
                    }
    
                    if (isset($data['is_test'])) {
                        print_r($error);
                        echo "Iniciando OK\n";
                    }
                }
    
                // Caso não encontre API
                if (!$error && !isset($api) && !$apiBling) {
                    if (isset($data['is_test'])) {
                        echo "Iniciando WP 0\n";
                    }
    
                    DB::table('logs')->insert([
                        'text' => 'Notificando Envio Sem API: Envio: ' . "\n" . print_r($data, true),
                        'user_id' => $user->id
                    ]);
    
                    $error[] = "Notificando Envio Sem API: Envio: " . $envio->id;
                }
                if (!$error) {
                    if ($envio->integration == 'Wordpress') {
                        if (isset($data['is_test'])) {
                            echo "Iniciando WP\n";
                        }
        
                        $webserviceModel = new Webservice(); // Certifique-se de ajustar o namespace conforme necessário
        
                        $informar = $webserviceModel->sendTrackNumber([
                            'integracao' => 'wordpress',
                            'user_id' => $api->user_id,
                            'ref_id' => $envio->ref_id,
                            'etiqueta' => $envio->etiqueta_correios . 'BR',
                            'date_postagem' => $envio->date_postagem
                        ]);
        
                        if ($informar) {
                            $dataInsert = [
                                'envio_id' => $envio->id,
                                'id_return' => now()->timestamp,  
                                'type' => $data['type'],
                                'integration' => $envio->integration,
                                'status' => 'OK',
                                'date' => now()  
                            ];
                            $ins = DB::table('envios_notify')->insert($dataInsert);
        
                            if (!$ins) {
                                $error[] = "Falha ao inserir envio Notify: <pre>" . print_r($dataInsert) . "</pre>";
                            }
                        }
                    }
        
                    if ($envio->integration == 'NuvemShop') {
                        if (isset($data['is_test'])) {
                            echo "API\n";
                            print_r($api);
                        }
        
                        $post = json_encode([
                            "shipping_tracking_number" => $envio->etiqueta_correios . 'BR',
                            "shipping_tracking_url" => "https://www2.correios.com.br/sistemas/rastreamento/",
                            "notify_customer" => true
                        ]);
                        //corrigir depois com a librarie
                        $endpoint = Modules::run('mandabem/api_nuvem_shop/get_url_base') . $api->store_id . '/orders/' . $envio->ref_id_api_source . '/fulfill';
                        //corrigir depois com a librarie
                        $response = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Bearer ' . $api->token,
                        ])->post($endpoint, ['post' => $post]);
        
                        $json = $response->json();
        
                        if (isset($json['id'])) {
                            echo "****************** Notify Nuvem OK ***************\n";
                            $dataInsert = [
                                'envio_id' => $envio->id,
                                'id_return' => $json['id'],
                                'type' => $data['type'],
                                'integration' => $envio->integration,
                                'status' => 'OK',
                                'date' => now()  
                            ];
                            $ins = DB::table('envios_notify')->insert($dataInsert);
        
                            if (!$ins) {
                                $error[] = "Falha ao inserir envio Notify: <pre>" . print_r($dataInsert) . "</pre>";
                            }
                        } else {
                            $error[] = "Notify envio NuvemShop ($envio->id) Fail\nData: " . print_r($data, true) . "\nResp: " . $response->body() . "\n";

                            $logModel = new Log();
                            $logModel->log([
                                'text' => 'ERROR NOTIFY : ' . "\n" . print_r($error, true),
                                'type' => "NUVEM_SHOP_API",
                                'user_id' => $user->id
                            ]);
                        }
                    }

                    if ($envio->user_id == '42823') {
                        $apiTiny = User::getApiTiny($envio->user_id);  
                        if ($apiTiny) {
                            $api = DB::table('api_tiny')
                                ->where('user_id', $user->id)
                                ->first();
                    
                            $notifyTiny = true;
                        }
                    }

                    if ($envio->integration == 'Bling') {
                        $xml = '<rastreamentos><rastreamento><id_servico>' . $idServico . '</id_servico><codigo>' . $envio->etiqueta_correios . 'BR</codigo></rastreamento></rastreamentos>';
            
                        $post = [
                            'apikey' => $apiBling->api_key,
                            'xml' => $xml
                        ];
            
                        if (!$error) {
                            $endpointBlingPost1 = 'logistica/rastreamento/pedido/' . $envio->ref_id . '/json/';
            
                            $response = Http::withHeaders([
                                'Content-Type' => 'application/x-www-form-urlencoded',
                            ])->post($endpointBlingPost1, $post);
            
                            $infoResp = $response->json();
            
                            if (!isset($infoResp['retorno']['logisticas'][0])) {
                                $error[] = "Retorno Bling para data de postagem difere do esperado: " . $response->body() . " Envio: " . print_r($envio, true);
                            } else {
                                $dataInsert = [
                                    'envio_id' => $envio->id,
                                    'id_return' => $infoResp['retorno']['logisticas'][0]['pedido']['rastreamentos'][0]['rastreamento']['id_servico'],
                                    'type' => $data['type'],
                                    'integration' => $envio->integration,
                                    'status' => 'OK',
                                    'date' => now() // Ou utilize o método adequado para obter a data atual
                                ];
                                $ins = DB::table('envios_notify')->insert($dataInsert);
            
                                if (!$ins) {
                                    $error[] = "Falha ao inserir envio Notify: <pre>" . print_r($dataInsert) . "</pre>";
                                }
            
                                // Add evento
                                $postEvento = [
                                    'apikey' => $apiBling->api_key,
                                    'xml' => '<evento>
                                                <id_servico>' . $idServico . '</id_servico>
                                                <data_evento>' . date('d/m/Y', strtotime($envio->date_postagem)) . '</data_evento>
                                                <codigo_situacao>0</codigo_situacao>
                                                <url><![CDATA[https://www2.correios.com.br/sistemas/rastreamento/]]></url>
                                            </evento>'
                                ];
            
                                $endpointBlingPost2 = 'logistica/evento/' . $envio->etiqueta_correios . 'BR/json/';
            
                                $responseEvento = Http::withHeaders([
                                    'Content-Type' => 'application/x-www-form-urlencoded',
                                ])->post($endpointBlingPost2, $postEvento);
            
                                $this->log_model->log([
                                    'text' => "Retorno para envio de evento:\n" . $responseEvento->body(),
                                    'type' => "BLING_API_EVENT",
                                    'user_id' => $envio->user_id
                                ]);
            
                                $infoEvento = $responseEvento->json();
                                
                            }
                        }
                    }

                    if (strtolower($envio->integration) == 'tiny' || $notifyTiny) {

                        if (!$error) {
                            $previsaoEntrega = $this->date_utils->DiasUteisFromInit(substr($envio->date_postagem, 0, 10), $envio->prazo);
                            $urlRastreamento = urlencode("http://www2.correios.com.br/sistemas/rastreamento/default.cfm?code=" . $envio->etiqueta_correios);
                            $endpointTiny = 'cadastrar.codigo.rastreamento.pedido.php?token=' . $api->api_key . '&id=' . $envio->ref_id_api_source . '&urlRastreamento=' . $urlRastreamento . '&dataPrevista=' . $previsaoEntrega . '&codigoRastreamento=' . $envio->etiqueta_correios . 'BR&formaEnvio=C&formato=JSON';
                    
                            $responseTiny = Http::get($endpointTiny); // Usando HTTP Client do Laravel
                            $infoResp = $responseTiny->json();

                            $infoResp1 = json_decode($infoResp->body(), true);

                            $endpointTiny2 = 'pedido.alterar.situacao?token=' . $api->api_key . '&id=' . $envio->ref_id_api_source . '&situacao=enviado&formato=JSON';
                            $response2 = Http::get($endpointTiny2);
                            $infoResp2 = $response2->json();

                            $dadosPedido['dados_pedido']['data_envio'] = date('d/m/Y H:i:s', strtotime($envio->date_postagem));
                            $dadosPedido['dados_pedido']['data_prevista'] = $previsaoEntrega;

                            $endpointTiny1 = 'pedido.alterar.php?token=' . $api->api_key . '&id=' . $envio->ref_id_api_source;

                            $response1 = Http::post($endpointTiny1, $dadosPedido);
                            $infoResp1 = $response1->json();
                    
                            if ($infoResp['retorno']['status_processamento'] != 3) {
                                $error[] = "Retorno Tiny para data de postagem difere do esperado: " . $responseTiny->body() . " Envio: " . print_r($envio, true);
                            } else {
                                $dataInsert = [
                                    'envio_id' => $envio->id,
                                    'id_return' => $infoResp['retorno']['status_processamento'],
                                    'type' => $data['type'],
                                    'integration' => $envio->integration,
                                    'status' => 'OK',
                                    'date' => now() // Ou utilize o método adequado para obter a data atual
                                ];
                                DB::table('envios_notify')->insert($dataInsert);
                    
                                // Add evento
                    
                                $this->log_model->log([
                                    'text' => "Retorno para envio de evento:\n" . $infoResp1,
                                    'type' => "TINY_API_EVENT",
                                    'user_id' => $envio->user_id
                                ]);
                    
                                if ($envio->user_id == 5) {
                                    echo "Enviando Cadastro do Evento: \n";
                                    echo "RESPONSE ";
                                }
                            }
                        }
                    }

                    if ($envio->integration == 'LojaIntegrada') {
                        $response = Http::post('mandabem/api_loja_integrada/send_track_number', [
                            'api' => $api,
                            'show_header' => false,
                            'objeto' => $envio->etiqueta_correios . 'BR',
                            'source_id' => $envio->ref_id_api_source,
                            'order_id' => $envio->ref_id,
                        ]);
                    
                        $resp = $response->json();
                    
                        if (!$resp || !isset($resp['id'])) {
                            $error[] = "Retorno Loja Integrada para data de postagem difere do esperado: (" . $response->body() . ')' . "\n" . print_r($envio, true);
                        } else {
                            $dataInsert = [
                                'envio_id' => $envio->id,
                                'id_return' => $resp['id'],
                                'type' => $data['type'],
                                'integration' => $envio->integration,
                                'status' => 'OK',
                                'date' => now(),  
                            ];
                            DB::table('envios_notify')->insert($dataInsert);
                    
                            if (!$ins) {
                                $error[] = "Falha ao inserir envio Notify: <pre>" . print_r($dataInsert) . "</pre>";
                            }
                        }
                    }
                }
            }else if ($data['type'] == 'date_entregue') {
                // Não entregue ainda
                if (!$envio->date_entregue) {
                    $error[] = "Sem data de entrega (2)\n" . print_r($data, true);
                }

                // Ja foi informado
                if (strlen($envio->date_entregue)) {
                    $notify = DB::table('envios_notify')
                        ->where('type', $data['type'])
                        ->where('integration', $envio->integration)
                        ->where('status', 'OK')
                        ->where('envio_id', $envio->id)
                        ->first();

                    if ($notify && ($notify->envio_id != 7125346)) {
                        $error[] = "Data de entregue ja informada ($etiqueta)\n";
                    }
                }

                if (!$error) {
                    $notify_tiny = false;

                    if ($envio->integration == 'NuvemShop') {
                        $data_post = [
                            'status' => 'delivered',
                            'description' => 'Objeto entregue ao destinatário',
                            'city' => $envio->cidade,
                            'province' => $envio->estado,
                            'country' => 'BR',
                            'happened_at' => $this->date_utils->get_now(false) . 'T' . $this->date_utils->get_hour() . '-03:00',
                            'estimated_delivery_at' => substr($envio->date_entregue, 0, 10) . 'T' . substr($envio->date_entregue, 11) . '-03:00',
                        ];

                        $post = json_encode($data_post);

                        $endpoint = Modules::run('mandabem/api_nuvem_shop/get_url_base') . $api->store_id . '/orders/' . $envio->ref_id_api_source . '/fulfillments';

                        $resp = Modules::run('mandabem/api_nuvem_shop/request', [
                            'show_header' => false,
                            'post' => $post,
                            'token' => $api->token,
                            'endpoint' => $endpoint
                        ]);

                        $json = json_decode($resp, true);

                        if (isset($json['id'])) {
                            $data_insert = [
                                'envio_id' => $envio->id,
                                'id_return' => $json['id'],
                                'type' => $data['type'],
                                'integration' => $envio->integration,
                                'status' => 'OK',
                                'date' => $this->date_utils->get_now()
                            ];

                            $ins = DB::table('envios_notify')->insert($data_insert);

                            if (!$ins) {
                                $error[] = "Falha ao inserir envio Notify: <pre>" . print_r($data_insert) . "</pre>";
                            }
                        } else {
                            $error[] = "Notify envio nuvem shop ($envio->id) Fail\nData: " . print_r($data, true) . "\nResp: $resp \n";
                        }

                        if ($envio->user_id == '42823') {
                            $api_tiny_ = $this->user_model->get_api_tiny($envio->user_id);
                            if ($api_tiny_) {
                                $api = DB::table('api_tiny')->where('user_id', $user->id)->first();
                                $notify_tiny = true;
                            }
                        }
                    }

                    if ($envio->integration == 'Bling') {
                        // Add evento
                        $post_evento = [
                            'apikey' => $apiB->api_key,
                            'xml' => '<evento>
                                        <id_servico>' . $idServico . '</id_servico>
                                        <data_evento>' . $this->date_utils->to_br($envio->date_entregue, true, true) . '</data_evento>
                                        <codigo_situacao>3</codigo_situacao>
                                        <url><![CDATA[https://www2.correios.com.br/sistemas/rastreamento/]]></url>
                                    </evento>'
                        ];
                    
                        print_r($post_evento);
                    
                        $json_evento = Modules::run('mandabem/api_bling/request', [
                            'show_header' => false,
                            'post' => $post_evento,
                            'type' => 'informar_data_entregue',
                            'endpoint' => 'logistica/evento/' . $envio->etiqueta_correios . 'BR/json/'
                        ]);
                    
                        $info_resp = json_decode($json_evento, true);
                    
                        if ($envio->user_id == 5) {
                            print_r($info_resp);
                        }
                    
                        if (!isset($info_resp['retorno']['logisticas'][0])) {
                            $error[] = "Retorno Bling para data de entregue difere do esperado: " . $json_evento . " Envio: " . print_r($envio, true);
                        } else {
                            $data_insert = [
                                'envio_id' => $envio->id,
                                'id_return' => $info_resp['retorno']['logisticas'][0]['evento']['id_servico'],
                                'type' => $data['type'],
                                'integration' => $envio->integration,
                                'status' => 'OK',
                                'date' => $this->date_utils->get_now()
                            ];
                    
                            $ins = DB::table('envios_notify')->insert($data_insert);
                    
                            if (!$ins) {
                                $error[] = "Falha ao inserir envio Notify: <pre>" . print_r($data_insert) . "</pre>";
                            }
                        }
                    }
                    
                    if ($envio->integration == 'Tiny' || $notify_tiny) {
                        $endpoint_tiny = 'pedido.alterar.situacao?token=' . $api->api_key . '&id=' . $envio->ref_id_api_source . '&situacao=entregue&formato=JSON';
                    
                        $resp = Modules::run('mandabem/api_tiny/request', [
                            'endpoint' => $endpoint_tiny
                        ]);
                    
                        $info_resp = json_decode($resp, true);
                    
                        if ($info_resp['retorno']['status'] != 'OK') {
                            $error[] = "Retorno Tiny para data de entregue difere do esperado: " . $json_evento . " Envio: " . print_r($envio, true);
                        } else {
                            $data_insert = [
                                'envio_id' => $envio->id,
                                'id_return' => $info_resp['retorno']['status_processamento'],
                                'type' => $data['type'],
                                'integration' => $envio->integration,
                                'status' => 'OK',
                                'date' => $this->date_utils->get_now()
                            ];
                    
                            $ins = DB::table('envios_notify')->insert($data_insert);
                    
                            if (!$ins) {
                                $error[] = "Falha ao inserir envio Notify: <pre>" . print_r($data_insert) . "</pre>";
                            }
                        }
                    }
                    
                    if ($envio->integration == 'Wordpress') {
                        if (isset($data['is_test'])) {
                            echo "Iniciando WP Data Entrega\n";
                        }
                    
                        $this->load->model('webservice_model');
                    
                        $informar = $this->webservice_model->send_track_number([
                            'integracao' => 'wordpress',
                            'user_id' => $api->user_id,
                            'ref_id' => $envio->ref_id,
                            'etiqueta' => $envio->etiqueta_correios . 'BR',
                            'date_postagem' => $envio->date_postagem
                        ]);
                    
                        if ($informar) {
                            $data_insert = [
                                'envio_id' => $envio->id,
                                'id_return' => $this->date_utils->get_time(),
                                'type' => $data['type'],
                                'integration' => $envio->integration,
                                'status' => 'OK',
                                'date' => $this->date_utils->get_now()
                            ];
                    
                            $ins = DB::table('envios_notify')->insert($data_insert);
                    
                            if (!$ins) {
                                $error[] = "Falha ao inserir envio Notify: <pre>" . print_r($data_insert) . "</pre>";
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    public function sortTimestamp($a, $b)
    {
        $a = $a['date_time'];
        $b = $b['date_time'];

        if ($a == $b) {
            return 0;
        }
        return ($a > $b) ? 1 : -1;
    }

    public function save($post = [])
    {
        if ($post['group_code'] == 'mandabem') {
            $dataPost = $this->formBuilder->validateData($this->fieldsEmail, $post);
            if (!$dataPost) {
                $this->error = $this->formBuilder->getErrorValidation();
                return false;
            }

            DB::table('acomp_email_default')->where('id', $dataPost['id'])->update([
                'subject' => $dataPost['subject'],
                'body' => $dataPost['body'],
                'date_update' => now(),
            ]);

            return true;
        } elseif ($post['group_code'] != 'franquia') { // cliente_contrato e cliente_sem_contrato
            $dataPost = $this->formBuilder->validateData($this->fieldsEmail, $post);
            if (!$dataPost) {
                $this->error = $this->formBuilder->getErrorValidation();
                return false;
            }

            DB::table('acomp_email_user')
                ->where('user_id', $post['user_id'])
                ->where('id', $dataPost['id'])
                ->update([
                    'subject' => $dataPost['subject'],
                    'body' => $dataPost['body'],
                    'date_update' => now(),
                ]);

            return true;
        }
    }

    public function getEmailsDefault($param = [])
    {
        if (isset($param['id'])) {
            return DB::table('acomp_email_default b_default')->where('b_default.id', $param['id'])->first();
        }

        if (isset($param['user_id'])) {
            DB::table('acomp_email_default b_default')
                ->leftJoin('acomp_email_user a_user', function ($join) use ($param) {
                    $join->on('a_user.email_default_id', '=', 'b_default.id')
                        ->where('a_user.status', 1)
                        ->where('a_user.user_id', $param['user_id']);
                });
        }

        return DB::table('acomp_email_default b_default')
            ->select('b_default.*', 'a_user.id as email_user_id')
            ->orderBy('b_default.id')
            ->get();
    }

    public function getEmailsUser($param = [])
    {
        if (isset($param['id'])) {
            return DB::table('acomp_email_user a')->where('a.id', $param['id'])->first();
        }
    }

    public function activeEmailUser($param = [])
    {
        $emailDefault = DB::table('acomp_email_default')->where('id', $param['id'])->first();
        $error = [];

        if (!$emailDefault) {
            $error[] = "Falha(1), tente novamente mais tarde.";
        }

        if (!$error) {
            $exist = DB::table('acomp_email_user')
                ->where('email_default_id', $emailDefault->id)
                ->where('user_id', $param['user_id'])
                ->first();

            // Se existe apenas ativa
            if ($exist) {
                DB::table('acomp_email_user')
                    ->where('id', $exist->id)
                    ->where('user_id', $param['user_id'])
                    ->update(['status' => 1]);

                return true;
            }

            // Associando email
            $ins = DB::table('acomp_email_user')->insert([
                'user_id' => $param['user_id'],
                'email_default_id' => $emailDefault->id,
                'subject' => $emailDefault->subject,
                'body' => $emailDefault->body,
                'date_insert' => now(),
                'date_update' => now(),
            ]);

            if (!$ins) {
                $error[] = "Falha(2), tente novamente mais tarde.";
            }
        }

        if (!$error) {
            return true;
        }

        $this->error = implode('<br>', $error);
        return false;
    }

    public function inativeEmailUser($param = [])
    {
        $emailDefault = DB::table('acomp_email_default')->where('id', $param['id'])->first();
        $error = [];

        if (!$emailDefault) {
            $error[] = "Falha(1), tente novamente mais tarde.";
        }

        if (!$error) {
            DB::table('acomp_email_user')
                ->where('email_default_id', $emailDefault->id)
                ->where('user_id', $param['user_id'])
                ->update(['status' => 0]);

            return true;
        }

        $this->error = implode('<br>', $error);
        return false;
    }

    private function insertEmailDefault()
    {
        $this->load->database();
        $this->load->library('date_utils');
        $emailsDefault = [];
        foreach ($emailsDefault as $e) {
            DB::table('acomp_email_default')->insert([
                'name' => $e['name'],
                'subject' => $e['subject'],
                'body' => $e['body'],
                'date_insert' => now(),
                'date_update' => now(),
            ]);
        }
    }

    public function getObjetosCrise($param = [])
    {
        $events = DB::table('etiqueta_events')
            ->where('descricao', 'like', 'A entrega não pode ser efetuada', 'both')
            ->get();

        $eventsId = $events->pluck('id')->implode(',');

        $result = DB::table('envios')
            ->select('envios.*', DB::raw('CONCAT(envios.etiqueta_correios, "BR") as etiqueta'), 'ev.descricao as etiqueta_status')
            ->join('etiqueta_status es', 'es.envio_id', '=', 'envios.id')
            ->join('etiqueta_events ev', 'ev.id', '=', 'es.etiqueta_event_id')
            ->whereIn('ev.id', explode(',', $eventsId))
            ->where('envios.user_id', $param['user_id'])
            ->whereNull('es.status_crise')
            ->get();

        return $result;
    }

    public function criseSolved($param = [])
    {
        $etiqueta = substr($param['etiqueta'], -2) == 'BR' ? substr($param['etiqueta'], 0, -2) : $param['etiqueta'];

        $envio = DB::table('envios')->where('user_id', $param['user_id'])->where('etiqueta_correios', $etiqueta)->first();

        if ($envio) {
            DB::table('etiqueta_status')
                ->where('envio_id', $envio->id)
                ->update(['status_crise' => 1]);
        }

        return true;
    }

    public function hasCrise($user_id)
    {
        $result = DB::table('crise_manage_warning a')
            ->select('b.*')
            ->whereNull('a.status')
            ->where('b.user_id', $user_id)
            ->join('envios b', 'b.etiqueta_correios', '=', 'a.etiqueta')
            ->first();

        return $result;
    }

    public function setSeenCrise($user_id)
    {
        $ids = DB::table('crise_manage_warning a')
            ->select('a.id')
            ->whereNull('a.status')
            ->where('b.user_id', $user_id)
            ->join('envios b', 'b.etiqueta_correios', '=', 'a.etiqueta')
            ->get();

        foreach ($ids as $id) {
            DB::table('crise_manage_warning')
                ->where('id', $id->id)
                ->update(['status' => 'SEEN']);
        }
    }

    public function getFieldsEmail()
    {
        return $this->fields_email;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getMessageCode($param)
    {
        $messageCode = '';

        if ($param['status_number'] == '01' && preg_match('/Objeto saiu para entrega ao destinat/i', $param['status_desc'])) {
            $messageCode = 'OBJ_SAIU_ENTREGA';
        } elseif ($param['status_number'] == '01' && preg_match('/Objeto postado/i', $param['status_desc'])) {
            $messageCode = 'OBJ_POSTADO';
        } elseif (
            in_array($param['status_number'], ['21', '20', '02', '18'])
            && preg_match('/A entrega n(.*?)o pode ser efetuada(.*?)Carteiro n(.*?)o atendido/i', $param['status_desc'])
        ) {
            $messageCode = 'ENTR_NAO_PODE_SER_EFETUADA';
        } elseif (
            in_array($param['status_number'], ['01', '03'])
            && preg_match('/Objeto aguardando retirada no endere(.*?)o indicado/i', $param['status_desc'])
        ) {
            $messageCode = 'OBJ_ENC_P_RETIRADA';
        }

        return $messageCode;
    }

    public function getListTransito($param)
    {
        $query = DB::table('envios')
            ->leftJoin('envios_cancelamento as cancel', 'cancel.envio_id', '=', 'envios.id');

        if (isset($param['filter_aguardando_e_rota_entrega']) && $param['filter_aguardando_e_rota_entrega']) {
            $query->leftJoin('etiqueta_status as etiq', 'etiq.envio_id', '=', 'envios.id');
        }

        $query->where('envios.date_postagem', '>=', '2020-06-01')
            ->where('envios.user_id', $param['user_id'])
            ->whereNull('envios.is_finalizado')
            ->whereNull('envios.date_entregue')
            ->whereNull('cancel.id');

        if ($param['user_id'] == 8483) {
            $query->where('envios.date_insert', '>=', '2023-01-01 12:00:00');
        }

        if (isset($param['filter_objeto'])) {
            $query->where(function ($query) use ($param) {
                $query->where('envios.etiqueta_correios', substr($param['filter_objeto'], 0, -2))
                    ->orWhere('envios.etiqueta_correios', $param['filter_objeto']);
            });
        }

        if (isset($param['filter_aguardando_e_rota_entrega']) && $param['filter_aguardando_e_rota_entrega']) {
            $query->whereNotIn('etiq.etiqueta_event_id', [84, 85, 87, 88]) // aguardando retirada
                ->whereNotIn('etiq.etiqueta_event_id', [83, 89, 129, 132]); // saiu para entrega
        }

        if (isset($param['get_total']) && $param['get_total']) {
            return $query->count();
        } else {
            $query->orderBy('envios.id');
            $info = $query->limit(isset($param['limit']) ? $param['limit'] : 300)->get()->toArray();

            foreach ($info as $k => $i) {
                if ($i->postado_apos_h_limite) {
                    $info[$k]->prazo = $i->prazo + 2;
                }
            
                $previsaoEntrega = app('DateUtils')->DiasUteisFromInit(substr($i->date_postagem, 0, 10), $i->prazo);
                $info[$k]->previsao_entrega = $previsaoEntrega;
                $info[$k]->date_time = strtotime(app('DateUtils')->to_en($previsaoEntrega));
            
                $timeNow = time();
                $timePrev = strtotime(app('DateUtils')->to_en($info[$k]->previsao_entrega));
            
                $s_ = DB::table('etiqueta_status as es')
                    ->select('ev.descricao as etiqueta_status')
                    ->join('etiqueta_events as ev', 'ev.id', '=', 'es.etiqueta_event_id')
                    ->where('es.envio_id', $i->id)
                    ->first();
            
                $info[$k]->status = $s_ ? $s_->etiqueta_status : '';
                $info[$k]->atrasado = false;
                $info[$k]->manifestar = false;
                if (($timePrev + (86400 * 1)) < $timeNow) {
                    $info[$k]->atrasado = true;
                }
                if (($timePrev + (86400 * 2)) < $timeNow) {
                    $info[$k]->manifestar = true;
                }
            
                if (isset($param['filter_status'])) {
                    if ($param['filter_status'] == 'atrasado' && !$info[$k]->atrasado) {
                        unset($info[$k]);
                        continue;
                    }
                    if ($param['filter_status'] == 'no_prazo' && $info[$k]->atrasado) {
                        unset($info[$k]);
                        continue;
                    }
                    if ($param['filter_status'] == 'manifestar' && !$info[$k]->manifestar) {
                        unset($info[$k]);
                        continue;
                    }
                }
            
                $m_ = DB::table('envios_manifestacao')
                    ->where('envio_id', $i->id)
                    ->orderBy('id', 'desc')
                    ->first();
            
                $info[$k]->manifestacao_id = $m_ ? $m_->id : null;
                $info[$k]->manifestacao = $m_ ? $m_ : null;
            
                if (isset($param['filter_sem_manifestacao']) && $param['filter_sem_manifestacao']) {
                    if ($info[$k]->manifestacao_id) {
                        unset($info[$k]);
                        continue;
                    }
                }
            
                if (!$info[$k]->atrasado && $info[$k]->manifestacao_id) {
                    $info[$k]->manifestacao_id = null;
                    $info[$k]->destinatario .= '.';
                }
            }

            usort($info, function ($a, $b) {
                return $a->date_time - $b->date_time;
            });

            return $info;
        }
    }

    public function sendEmailNotification($param = [], $returnMsg = false)
    {

        $messageCode = '';
        if (($param['status_number'] == '01' || $param['status_number'] == '00') && preg_match('/Objeto saiu para entrega ao destinat/i', $param['status_desc'])) {
            $messageCode = 'OBJ_SAIU_ENTREGA';
        } elseif (($param['status_number'] == '01' || $param['status_number'] == '09') && preg_match('/Objeto postado/i', $param['status_desc'])) {
            $messageCode = 'OBJ_POSTADO';
        } elseif (($param['status_number'] == '21' || $param['status_number'] == '20' || $param['status_number'] == '02' || $param['status_number'] == '18') && preg_match('/A entrega n(.*?)o pode ser efetuada(.*?)Carteiro n(.*?)o atendido/i', $param['status_desc'])) {
            $messageCode = 'ENTR_NAO_PODE_SER_EFETUADA';
        } elseif (($param['status_number'] == '01' || $param['status_number'] == '03') && preg_match('/Objeto aguardando retirada no endere(.*?)o indicado/i', $param['status_desc'])) {
            $messageCode = 'OBJ_ENC_P_RETIRADA';
        }

        $messageList = [
            'Objeto postado',
            'Objeto saiu para entrega ao destinatário',
            'A entrega não pode ser efetuada - Carteiro não atendido',
            'Objeto aguardando retirada no endereço indicado'
        ];

        // Aviso para mensagens que não estão cadastradas
        if (!$messageCode && array_search($param['status_desc'], $messageList) !== false) {
            $paramMsg = [
                'subject' => 'CRON - STATUS ETIQUETA (Sem Mensagem Cadastrada)',
                'msg' => "<pre>XX Params:<br>" . print_r($param, true) . "</pre>\n",
                'to' => 'regygom@gmail.com'
            ];
            $paramMsg['unique'] = true;
            //corrigir quando houver a librarie mail 
            Mail::raw($paramMsg['msg'], function ($message) use ($paramMsg) {
                $message->to($paramMsg['to'])
                        ->subject($paramMsg['subject']);
            });

            return false;
        } elseif (!$messageCode) {
            return false;
        }

        $messageInfo = DB::table('acomp_email_default')->where('message_code', $messageCode)->first();

        // Envio
        $envio = DB::table('envios')
            ->select('envios.*', DB::raw('CONCAT(etiqueta_correios,"BR") as codigo_rastreio'))
            ->join('user', 'user.id', '=', 'envios.user_id')
            ->where('etiqueta_correios', $param['etiqueta'])
            ->first();

        if (!$envio) {
            echo "Envio Não encontrado\n " . print_r($param, true) . "\n";
            return false;
        }

        // Mensagem
        $message = DB::table('acomp_email_user a')
            ->where('a.email_default_id', $messageInfo->id)
            ->where('a.user_id', $envio->user_id)
            ->first();

        if (!$message || !(int)$message->status) {
            return true;
        }

        // Check mensagem já enviada ou com erro
        $messageSent = DB::table('acomp_email_notification')
            ->where('envio_id', $envio->id)
            ->where('message_id', $messageInfo->id)
            ->first();

        $sendEmail = false;
        if ($messageSent) {
            if ((!isset($param['test']) || !$param['test']) && $messageSent->status == 'SENT') {
                return true;
            } elseif ($messageSent->status == 'NO_EMAIL' && !$envio->email) {
                return true;
            } else {
                $sendEmail = true;
            }
        } else {
            $sendEmail = true;
        }

        if (!$envio->email) {
            $status = 'NO_EMAIL';
            $sendEmail = false;
        }

        if ($sendEmail) {
            $destinatario = explode(' ', $envio->destinatario);

            $body1 = preg_replace('/\<codigo_rastreio\>/', $envio->codigo_rastreio, $message->body);
            $body2 = preg_replace('/\<nome_ecommerce\>/', ucfirst($envio->name_ecommerce), $body1);
            $body = preg_replace('/\<nome_destinatario\>/', $destinatario[0], $body2);

            if ($messageCode == 'OBJ_ENC_P_RETIRADA') {
                $body = preg_replace(
                    [
                        '/\<local_retirada\>/',
                        '/\<logradouro_retirada\>/',
                        '/\<numero_retirada\>/',
                        '/\<bairro_retirada\>/',
                        '/\<cidade_retirada\>/',
                        '/\<uf_retirada\>/'
                    ],
                    [
                        $param['local_retirada'],
                        $param['endereco_retirada']['logradouro'],
                        $param['endereco_retirada']['numero'],
                        $param['endereco_retirada']['bairro'],
                        $param['endereco_retirada']['localidade'],
                        $param['endereco_retirada']['uf']
                    ],
                    $body
                );
            }

            $paramMsg = [
                'subject' => $message->subject,
                'msg' => preg_replace("/\n/", "<br>", $body),
                'to' => $envio->email,
                'email_from' => $envio->email_ecommerce,
                'name_from' => $envio->name_ecommerce
            ];

            if (isset($param['test']) && $param['test']) {
                $paramMsg['to'] = 'regygom@gmail.com';
            }

            if ($returnMsg) {
                return $paramMsg;
            }
            $paramMsg['unique'] = true;

            if (isset($param['email_replicate'])) {
                $paramMsg['email_replicate'] = $param['email_replicate'];
            }

            try {
                //corrigir quando houver a librarie mail 
                Mail::send([], [], function ($message) use ($paramMsg) {
                    $message->to($paramMsg['to'])
                            ->from($paramMsg['email_from'], $paramMsg['name_from'])
                            ->subject($paramMsg['subject'])
                            ->setBody($paramMsg['msg'], 'text/html');
                });

                $status = 'SENT';
            } catch (\Exception $e) {
                $status = 'ERROR';
                $msgLog = 'Falha envio de email' . "\n";
                $msgLog .= "Envio:\n" . print_r([$envio->id, $envio->etiqueta_correios], true) . "\n";
                $msgLog .= "Mensagem:\n" . print_r($message, true) . "\n";
                $msgLog .= "Error:\n" . $e->getMessage() . "\n";
                //corrigir quando houver a librarie mail 
                Mail::raw($msgLog, function ($message) {
                    $message->to('regygom@gmail.com')
                            ->subject('Error: Falha no envio de e-mail');
                });
            }
        }

        $exist = DB::table('acomp_email_notification')
            ->where('envio_id', $envio->id)
            ->where('message_id', $messageInfo->id)
            ->first();

        if ($exist) {
            DB::table('acomp_email_notification')
                ->where('id', $exist->id)
                ->update([
                    'status' => $status,
                    'date' => app('DateUtils')->getNow()
                ]);
        } else {
            DB::table('acomp_email_notification')->insert([
                'envio_id' => $envio->id,
                'message_id' => $messageInfo->id,
                'status' => $status,
                'date' => app('DateUtils')->getNow()
            ]);
        }

        return true;
    }

    public function rawUpdateObjeto($envio)
    {
        //corrigir quando houver a librarie
        $correio = app(Correio::class);
        $userModel = app(User::class);
        $envioModel = app(Envio::class);

        $user = $userModel->find($envio->user_id);

        $error = [];
        $data = new \stdClass();
        $data->events = [];

        $events = $correio->statusEtiqueta([
            'user' => $user,
            'environment' => 'production',
            'etiqueta' => $envio->etiqueta_correios . 'BR',
        ]);

        if (isset($events['data']->return->objeto->evento)) {
            $data->events = $events['data']->return->objeto->evento;

            if (isset($data->events->tipo)) {
                $tmp = $data->events;
                unset($data->events);
                $data->events[0] = $tmp;
            }

            // Atualizando objeto, em caso de postagem desfeita (Favor desconsiderar informação anterior)
            $envioModel->rollbackPostagem([
                'envio_id' => $envio->id,
                'events' => $data->events,
            ]);

            if (is_array($data->events)) {
                $evento = $data->events[0];
            } else {
                $evento = $data->events;
            }

            if (!$evento) {
                $error[] = "Fluxo não encontrado nos Correios, tente novamente mais tarde.";
            }

            if (!$error) {
                $envioModel->updateEventsEnvio([
                    'evento' => object_to_array($evento),
                    'envio_id' => $envio->id,
                ]);
            }
        }

        return $events;
    }

}
