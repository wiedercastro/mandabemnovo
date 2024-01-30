<?php 

namespace App\Libraries\Correios;

use App\Models\Log;
use App\Models\Envio;
use Illuminate\Support\Facades\DB;
use App\Libraries\DateUtils;
use App\Libraries\EmailMaker;
use App\Libraries\Correios\CalPrazoFrete;
 
class CorreiosPrazoFreteOffline
{
        private $error;
        private $log = '';
        private $testV2 = true; // TESTAR V2 - JA VALIDADA
        private $versao_correios = '1';
        private $valor_AR = 7.4; // 7,40 Valor AR 
        private $ip_test = '45.181.35.133';
        private $set_versao_rest = false;
        
        public function __construct() 
        {
            $this->versao_correios = '2';
            $this->set_versao_rest = true; 
        }
    
        public function dolog($msg) 
        {
            $this->log .= $msg . "\n";
        }
    
        public function writeLog($add_log = null) 
        {
            if ($this->testV2) {
                $logModel = new Log();
                $logModel->log(array(
                    'text' => $this->log . ( $add_log ? "\n\n" . $add_log : ""),
                    'type' => 'CORREIOS.' . request()->server('REMOTE_ADDR')
                ));
            }
        }
    
        public function saveValorOffline($param = array(), $id_cache_offline = false) 
        {
            $dateUtils = new DateUtils();
            $set_log = ( isset($param['set_log']) && $param['set_log']) ? true : false;
    
            if ($this->testV2) {
    
                $faixa_origem_id = $param['faixa_origem_id'];
                $faixa_destino_id = $param['faixa_destino_id'];
                $forma_envio = $param['forma_envio'];
                $peso = $param['peso'];
                $valor = $param['valor'];
                $prazo = $param['prazo'];
    
                if (!$id_cache_offline) {
                    $correios_cache = DB::table('correios_cache')
                        ->where('faixa_origem_id', $faixa_origem_id)
                        ->where('faixa_destino_id', $faixa_destino_id)
                        ->where('forma_envio', $forma_envio)
                        ->where('peso', $peso)
                        ->first();
                } else {
                    $correios_cache = (object)['id' => $id_cache_offline];
                }
                if ($correios_cache) {
                    if ($set_log) {
                        $this->dolog("Atualizando Valor");
                    }
                    $data_update = array(
                        'prazo' => $prazo,
                        'date_update' => $dateUtils->getNow()
                    );
    
                    if (isset($param['status'])) {
                        $data_update['status'] = $param['status'];
                    }
    
                    if (isset($param['cod_empresa'])) {
                        $data_update['valor_contrato'] = preg_replace('/,/', '.', $valor);
                    } else if (preg_replace('/,/', '.', $valor) > 0) {
                        $data_update['valor_balcao'] = preg_replace('/,/', '.', $valor);
                    }
                    DB::table('correios_cache')
                        ->where('id', $correios_cache->id)
                        ->update($data_update);
                } else {
                    if ($set_log) {
                        $this->dolog("Inserindo Valor");
                    }
                    $data_insert = array(
                        'faixa_origem_id' => $faixa_origem_id,
                        'faixa_destino_id' => $faixa_destino_id,
                        'forma_envio' => $forma_envio,
                        'peso' => $peso,
                        'prazo' => $prazo,
                        'cep_origem_search' => $param['cep_origem'],
                        'cep_destino_search' => $param['cep_destino'],
                        'date_update' => $dateUtils->getNow(),
                    );
    
                    // Valor maior que Zero = STATUA = 1 = OK
                    if (isset($param['status'])) {
                        $data_insert['status'] = $param['status'];
                    } else {
                        if ($valor > 0) {
                            $data_insert['status'] = 1;
                        }
                    }
    
                    if (isset($param['cod_empresa'])) {
                        $data_insert['valor_contrato'] = preg_replace('/,/', '.', $valor);
                    } elseif (preg_replace('/,/', '.', $valor) > 0) {
                        $data_insert['valor_balcao'] = preg_replace('/,/', '.', $valor);
                    }
    
                    DB::table('correios_cache')->insert($data_insert);
                }
    
                return true;
            }
        }
    
        public function calc($data = array()) 
        {
     
            $set_log = true;
    
            if ($this->testV2) {
    
                if ($data['forma_envio'] == 'PACMINI') {
                    if ((float) $data['peso'] > 0.3) {
                        $this->error = "Peso inválido para Envio Mini";
                        return false;
                    }
                    if (isset($data['altura']) && $data['altura'] > 4) {
                        $this->error = "Altura inválida para Envio Mini";
                        return false;
                    }
                    if (isset($data['largura']) && $data['largura'] > 16) {
                        $this->error = "Largura inválida para Envio Mini";
                        return false;
                    }
                    if (isset($data['comprimento']) && $data['comprimento'] > 24) {
                        $this->error = "Comprimento inválido para Envio Mini";
                        return false;
                    }
                }
    
                if ($data['forma_envio'] == 'PAC') {
                    if (isset($data['seguro']) && $data['seguro'] > 3000) {
                        $this->error = "Valor seguro superior ao permitido";
                        return false;
                    }
                }
                if ($data['forma_envio'] == 'SEDEX') {
                    if (isset($data['seguro']) && $data['seguro'] > 10000) {
                        $this->error = "Valor seguro superior ao permitido";
                        return false;
                    }
                }
                if ($data['forma_envio'] == 'PACMINI') {
                    if (isset($data['seguro']) && $data['seguro'] > 100) {
                        $this->error = "Valor seguro superior ao permitido";
                        return false;
                    }
                }
    
                //$ci->load->library('correio/CalPrazoFrete', array(), 'CalPrazoFreteInner');
                $calPrazoFreteInner = new CalPrazoFrete();
    
                $sum = (int) (isset($data['altura']) ? $data['altura'] : 0) + (int) (isset($data['largura']) ? $data['largura'] : 0) + (int) (isset($data['comprimento']) ? $data['comprimento'] : 0);
    
                // Nao permitir SOMA dimensoes acima de 200
                if ($sum > 200) {
                    $this->error = "Somatório (altura + largura + comprimento) acima de 200 cm.<br>Somatório deve ser menor do que 200 cm.";
                    return false;
                }
                
                // se soma for maior que 90 trocar o peso do pedido pelo peso cubico 
                //if($sum >= 90){
                //    $medidas = $data['altura'] * $data['largura'] * $data['comprimento'];
                //    $data['peso'] = $medidas / 6000;
                //}
    
                // INDUSTRIAL (VALIDAR DEPOIS!)
                if (isset($data['cal_industrial']) && $data['cal_industrial']) {
                    $__return = $calPrazoFreteInner->calc($data);
                    if (!$__return) {
                        $this->error = $calPrazoFreteInner->getError();
                        return false;
                    } else {
                        return $__return;
                    }
                }
                
    
                // Somatorio acima de 80 cm não fazer cache por enquanto
                if ($sum > 80) {
                     
                    if ($this->set_versao_rest) {
                         
                        $__return = $calPrazoFreteInner->calc($data);
                        //se valor menor que 7 retorna false
                        if (!$__return || ($__return['valor'] < 7)) {
                            $this->error = $calPrazoFreteInner->getError();
                            return false;
                        } else { 
                            
                            $seg = 0;
                            $__return['valor'] = preg_replace('/\,/', '.', $__return['valor']);
                            // Add Valor seguro
                            if (isset($data['seguro']) && $data['seguro']){
                                $seg = $this->calValorSeguro($data);
                                $__return['valor'] += $seg;
                            }
                            
                            // Adicionando Valor AR
                            if (($data['AR']) && (($data['AR'] == 'S') || ($data['AR'] == 1))) {
                                $__return['valor'] += $this->valor_AR;
                            }
                            $__return['valor'] = preg_replace('/\./', ',', $__return['valor']);
                            return $__return; 
                        }
                    } else {
                        $__return = $calPrazoFreteInner->calc($data);
                        if (!$__return) {
                            $this->error = $calPrazoFreteInner->getError();
                            return false;
                        } else {
                            return $__return;
                        }
                    }
                }
                // (!NAO ENTRA AQUI) Seguro nao fazer cache | Fazendo cache com calculo Local (todos)
                if (isset($data['seguro']) && $data['seguro'] && !isset($data['cal_seguro_local'])) { 
                    $__return = $calPrazoFreteInner->calc($data);
                    if (!$__return || ($__return['valor'] < 7)) {
                        $this->error = "Falha ao calcular frete, tente novamente mais tarde (1);";
                        return false;
                    } else {
                        return $__return;
                    }
                }
    
                $valor_add_AR = 0;
    
                if ($this->versao_correios == '1') {
                    // AR nao fazer caache
                    if (isset($data['AR']) && $data['AR'] == 'S') {
                        $__return = $calPrazoFreteInner->calc($data);
                        if (!$__return) {
                            $this->error = $calPrazoFreteInner->getError();
                            return false;
                        } else {
                            return $__return;
                        }
                    }
                } else if ($this->versao_correios == '2') {
                    if (isset($data['AR']) && $data['AR'] == 'S') {
                        $valor_add_AR = $this->valor_AR;
                        unset($data['AR']);
                    }
                }
                // Calculo industrial | Sem cache
                if (isset($data['is_industrial']) && $data['is_industrial']) {
                    $__return = $calPrazoFreteInner->calc($data);
                    if (!$__return) {
                        $this->error = $calPrazoFreteInner->getError();
                        return false;
                    } else {
                        $seg = 0;
                        $__return['valor'] = preg_replace('/\,/', '.', $__return['valor']);
    
                        // Add Valor seguro
                        if (isset($data['seguro']) && $data['seguro']){
                            $seg = $this->calValorSeguro($data);
                            $__return['valor'] += $seg;
                        }
    
                        // Adicionando Valor AR
                        if ($valor_add_AR > 0) {
                            $__return['valor'] += $this->valor_AR;
                        }
    
                        $__return['valor'] = preg_replace('/\./', ',', $__return['valor']);
                        return $__return;
                    }
                }
    
                $data['cep_origem'] = preg_replace('/[^0-9]/', '', $data['cep_origem']);
                $data['cep_destino'] = preg_replace('/[^0-9]/', '', $data['cep_destino']);
                $envioModel = new Envio();
                if ($_SERVER['REMOTE_ADDR'] == '177.25.218.168' || $_SERVER['REMOTE_ADDR'] == 'x') {
                    if ($data['forma_envio'] == 'PAC' || $data['forma_envio'] == 'PACMINI') {
                        if ($envioModel->isTrechoLocal(['cep_origem' => $data['cep_origem'], 'cep_destino' => $data['cep_destino']])) {
                            $this->error = "Para este CEP apenas oferecemos o serviço de SEDEX por favor altere a forma de envio para SEDEX";
                            return false;
                        } else {

                        }
                    }
                } else if (($data['forma_envio'] == 'PAC' || $data['forma_envio'] == 'PACMINI' ) && (
                        ( $data['cep_origem'] == '23058680' && $data['cep_destino'] == '24350310' ) ||
                        ( $data['cep_origem'] == '04138001' && $data['cep_destino'] == '08900000' ) ||
                        ( $data['cep_origem'] == '03176001' && $data['cep_destino'] == '05041000' ) ||
                        ( $data['cep_origem'] == '03176001' && $data['cep_destino'] == '08121820' ) ||
                        ( $data['cep_origem'] == '13025240' && $data['cep_destino'] == '13179180' ) ||
                        ( $data['cep_origem'] == '03176001' && $data['cep_destino'] == '13466000' ) ||
                        ( $data['cep_origem'] == '03176001' && $data['cep_destino'] == '01135020' ) ||
                        ( $data['cep_origem'] == '03176001' && $data['cep_destino'] == '05088010' ) ||
                        ( $data['cep_origem'] == '03176001' && $data['cep_destino'] == '11085090' ) ||
                        ( $data['cep_origem'] == '03176001' && $data['cep_destino'] == '13911266' )
    
                        )) {
                    $this->error = "Para este CEP apenas oferecemos o serviço de SEDEX por favor altere a forma de envio para SEDEX";
                    return false;
                }
    
                // Busca faixas de CEP
                $faixa_origem = DB::table('faixa_cep')
                    ->select('id')
                    ->whereRaw('? BETWEEN cep_ini AND cep_fim', [$data['cep_origem']])
                    ->first();

                $faixa_destino = DB::table('faixa_cep')
                    ->select('id')
                    ->whereRaw('? BETWEEN cep_ini AND cep_fim', [$data['cep_destino']])
                    ->first();

                if (in_array($data['cep_destino'], ['32800076', '32800728', '32807098', '32800578', '32800076', '32800076'])) {
                    $faixa_destino = DB::table('faixa_cep')
                        ->select('id')
                        ->where('localidade', 'Esmeraldas')
                        ->first();
                }

                if ($data['cep_destino'] == '96797981') {
                    $faixa_destino = DB::table('faixa_cep')
                        ->select('id')
                        ->where('localidade', 'Camaqua')
                        ->first();
                }

                if ($data['cep_origem'] == '96797981') {
                    $faixa_origem = DB::table('faixa_cep')
                        ->select('id')
                        ->where('localidade', 'Camaqua')
                        ->first();
                }
    
                if (!$faixa_origem || !$faixa_destino) {
                    if (!$faixa_origem) {
                        $this->error = "CEP de origem é inválido.";
                    }
                    if (!$faixa_destino) {
                        $this->error = "CEP de destino é inválido.";
                    }
    
                    $this->writeLog($this->error . "\nCEP data: " . print_r($data, true) . "\nFaixas: " . print_r($faixa_origem, true) . "\n" . print_r($faixa_destino, true) . "\n");
                    return false;
                }
    
                if ($data['peso'] < 0.3) {
                    $data['peso'] = 0.3;
                }
    
                if ($data['peso'] > 0.3 && $data['peso'] < 1) {
                    $data['peso'] = 1;
                }
    
                if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == '177.25.218.77') {
                    echo $faixa_origem->id . " - " . $faixa_destino->id . "\n";
                }
    
                $param_get_valor_offline = array(
                    'faixa_origem_id' => $faixa_origem->id,
                    'faixa_destino_id' => $faixa_destino->id,
                    'forma_envio' => $data['forma_envio'],
                    'peso' => $data['peso'],
                );
    
                $has_valor_offline = false;
    
                // renovar os valores offline
                $valor_offline = DB::table('correios_cache')
                    ->where('date_update', '>=', '2022-08-24 09:55:00')
                    ->where($param_get_valor_offline)
                    ->first();
    
                $update_value = false;
                if ($valor_offline) {
                    // não permitido
                    if (isset($data['cod_empresa']) && $valor_offline->status == 2) {
                        $this->error = "Para este CEP apenas oferecemos o serviço de SEDEX por favor altere a forma de envio para SEDEX";
                        return false;
                    }
                    if (isset($data['cod_empresa']) && $valor_offline->valor_contrato < 4) {
                        $update_value = true;
                    }
                    if (!isset($data['cod_empresa']) && $valor_offline->valor_balcao < 4) {
                        $update_value = true;
                    }
                    if (!$update_value) {
    
                        DB::table('correios_cache')
                            ->where('id', $valor_offline->id)
                            ->update(['num_used' => DB::raw('num_used + 1')]);
    
                        if (isset($data['seguro']) && $data['seguro'] && isset($data['cal_seguro_local']) && $data['cal_seguro_local']) {
    
                            $__valor = isset($data['cod_empresa']) ? $valor_offline->valor_contrato : $valor_offline->valor_balcao;
                            
                            if ($data['seguro'] > 12.5) {
                                 
                                $seg = $this->calValorSeguro($data);
                                
                                if (isset($data['cod_empresa'])) {
                                    $valor_offline->valor_contrato += $seg;
                                } else {
                                    $valor_offline->valor_balcao += $seg;
                                }
                            }
                        }
                         
    
                        // Adicionando Valor AR
                        if ($this->versao_correios == '2') {
                            if ($valor_add_AR > 0) {
                                if (isset($data['cod_empresa'])) {
                                    $valor_offline->valor_contrato += $valor_add_AR;
                                } else {
                                    $valor_offline->valor_balcao += $valor_add_AR;
                                }
                            }
                        }
    
                        $return_value = array(
                            'status' => '1',
                            'msg' => 'dados pesquisados com sucesso.',
                            'valor' => isset($data['cod_empresa']) ? number_format($valor_offline->valor_contrato, 2, ',', '') : number_format($valor_offline->valor_balcao, 2, ',', ''),
                            'prazo' => $valor_offline->prazo,
                        );
                        return $return_value;
                    }
                }
    
    
                // Calculo diretamente na BASE WS dos Correios
                // removendo seguro quando o calculo for local
                $valor_seguro_cache = 0;
                if (true) {
                    if ((isset($data['seguro']) && $data['seguro'] > 0) && (isset($data['cal_seguro_local']) && $data['cal_seguro_local'])) {
                        $valor_seguro_cache = $data['seguro'];
                        unset($data['seguro']);
                    }
                }
                $return = $calPrazoFreteInner->calc($data);
                
                if (!$return || ($return['valor'] < 7)) {
                    $this->error = "Falha ao calcular frete, tente novamente mais tarde (2);";
                    return false;
                }
                
    
                if ($return) {
                    // NORMALIZANDO VALOR CASO VENHA COM VIRGULA
                    $return['valor'] = preg_replace('/,/', '.', $return['valor']);
                    // Adicionando valores a serem salvos
                    if (isset($data['cod_empresa'])) {
                        $param_get_valor_offline['cod_empresa'] = $data['cod_empresa'];
                    }
                    $param_get_valor_offline['set_log'] = $set_log;
                    $param_get_valor_offline['valor'] = $return['valor'];
                    $param_get_valor_offline['prazo'] = $return['prazo'];
    
                    $param_get_valor_offline['cep_origem'] = $data['cep_origem'];
                    $param_get_valor_offline['cep_destino'] = $data['cep_destino'];
    
                    $id_valor_offline = $valor_offline ? $valor_offline->id : false;
    
                    $this->saveValorOffline($param_get_valor_offline, $id_valor_offline);
    
                    if ($valor_seguro_cache > 12.5) {
                        $data['seguro'] = $valor_seguro_cache;
                        $seg = $this->calValorSeguro($data);
    
                        $return['valor'] += $seg;
                    }
    
                    // Adicionando Valor AR
                    if ($this->versao_correios == '2') {
                        if ($valor_add_AR > 0) {
                            if (isset($data['cod_empresa'])) {
                                $return['valor'] += $valor_add_AR;
                            } else {
                                $return['valor'] += $valor_add_AR;
                            }
                        }
                    }
                    return $return;
                } else {
    
                    $this->error = $calPrazoFreteInner->getError();
    
                    if (preg_match('/Para este CEP apenas oferecemos o serviço de SEDEX por favor altere a forma de envio para SEDEX/i', $calPrazoFreteInner->getError())) {
                        $param_get_valor_offline['valor'] = 0;
                        $param_get_valor_offline['prazo'] = 0;
    
                        $param_get_valor_offline['cep_origem'] = $data['cep_origem'];
                        $param_get_valor_offline['cep_destino'] = $data['cep_destino'];
                        $param_get_valor_offline['status'] = 2;
    
                        $id_valor_offline = $valor_offline ? $valor_offline->id : false;
    
                        $this->saveValorOffline($param_get_valor_offline, $id_valor_offline);
                    }
    
                    return false;
                }
            }
        }
    
        public function calValorSeguro($data) 
        {
            $seg = 0;
            if ($this->set_versao_rest) {
                        
                $servico = $data['servico'];
                //valida se o servico é balcão para que o seguro seja calculado como balcão
                if($servico == '04510' || $servico == '04014' || $servico == '40215' || $servico == '04227'){
                    $mult = 0.02;  
                }else{
                    $mult = 0.01;  
                }
    
                if (strtoupper($data['forma_envio']) == 'PACMINI') {
                    if ($data['seguro'] >= 12.25) {
                        $rest_seg = $data['seguro'];
                        $seg = number_format(($rest_seg * $mult), 2, '.', '');
                    }
                } else {
                    if ($data['seguro'] > 24.50) {
                        $rest_seg = $data['seguro'] - 24.50;
                        $seg = number_format(($rest_seg * $mult), 2, '.', '');
                    }
                }
            } else {
    
                if (strtoupper($data['forma_envio']) == 'PACMINI') {
                    if ($data['seguro'] >= 12.25) {
                        $rest_seg = $data['seguro'];
                        $seg = number_format(($rest_seg * 0.02), 2, '.', '');
                    }
                } else {
                    if ($data['seguro'] > 24.50) {
                        $rest_seg = $data['seguro'] - 21;
                        $seg = number_format(($rest_seg * 0.01), 2, '.', '');
                    }
                } 
            }
            return $seg;
        }
    
        public function getError() 
        {
            return $this->error;
        }
    
        public function sendMsgFeedback($msg) 
        {
            $emailMaker =  new EmailMaker();
            $emailMaker->msg([
                'subject' => 'CACHE Valor Frete',
                'to' => 'reginaldo@mandabem.com.br',
                'msg' => $msg
            ]);
        }
    
    
 
     
}
