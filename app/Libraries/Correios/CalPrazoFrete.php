<?php 

/*
  Retorno dos dados
  O retorno dos dados � um XML informando a situa��o da consulta e uma lista (array) com
  os dados de pre�o e prazo dos servi�os SEDEX, e-SEDEX e PAC.
  O e-SEDEX � um servi�o exclusivo para as principais localidades e clientes do segmento
  de Com�rcio Eletr�nico, portanto, caso o CEP Origem e Destino estejam fora da �rea de
  abrang�ncia do e-SEDEX, o mesmo n�o ser� informado e uma mensagem relativa �
  indisponibilidade do servi�o ser� apresentada
 * 
 * 
 * EX de USO
  $param_correio = array(
  'servico' => 04669, # este codigo eh diferenciado para cada situacao PAC NORMAL
  # OS TIPOS SAO: PAC SEM CONTRATO: 04510, PAC CONTRATO: 04669, SEDEX SEM CONT: 04014, SEDEX CONTRATO: 04162
  'cep_origem' => $cep_origem,
  'cep_destino' => $cep_destino,
  'peso' => 0.300,
  );
  if ($data_post['seguro']) {
  $param_correio['seguro'] = $data_post['seguro'];
  }
  $calc_frete = new CalPrazoFrete();
  $frete = $calc_frete->calc($param_correio);
  if (!$frete) {
  $this->error = $calc_frete->get_error();
  return false;
  }
 * 
 * 
 */



namespace App\Libraries\Correios;

use DOMDocument;
use App\Libraries\Correios\CorreioRest;


class CalPrazoFrete
{

    private $error;

    private $ip_test =  '177.25.218.77';
    
    private function defaultValueMin($data = array(), $key = '', $value = 0) 
    {
        if (!isset($data[$key]))
            return $value;
        if (!is_numeric($data[$key]))
            return $value;
        if ($data[$key] < $value)
            return $value;
        return $data[$key];
    }

    public function maskCep($value = '') {
        if ($value == '')
            return '';
        if (strlen($value) != 8)
            return $value;
        return substr($value, 0, 5) . '-' . substr($value, 5, 3);
    }

    public function getError() 
    {
        return $this->error;
    }

    public function calc($data = array()) {
        
        $correioRest = new CorreioRest();
        
        $servico = isset($data['servico']) ? $data['servico'] : null;

        $cep_origem = isset($data['cep_origem']) ? $data['cep_origem'] : null;
        $cep_destino = isset($data['cep_destino']) ? $data['cep_destino'] : null;

        $peso = ( isset($data['peso']) && strlen($data['peso'])) ? $data['peso'] : 1;
        $altura = $this->defaultValueMin($data, 'altura', 2);
        $largura = $this->defaultValueMin($data, 'largura', 11);
        $comprimento = $this->defaultValueMin($data, 'comprimento', 16);



        if (!preg_match('/^([0-9]{8})$/', $cep_origem)) {
            $this->error = 'Cep de origem: ' . $cep_origem . ' inválido';
            return false;
        }
        if (!preg_match('/^([0-9]{8})$/', $cep_destino)) {
            $this->error = 'Cep de destino: ' . $cep_destino . ' inválido';
            return false;
        }

        if ( true ) {

            $param_consulta = [
                'cep_origem' => $cep_origem,
                'cep_destino' => $cep_destino,
                'forma_envio' => $data['forma_envio'],
                'cod_servico' => $servico,
                'peso' => ( $peso * 1000 ), // Peso em gramas, foi alterado nessa nova API
                'altura' => $altura,
                'largura' => $largura,
                'comprimento' => $comprimento,
                'is_contrato' => false,
//                'seguro' => '250.40',
//                'AR' => 'S'
            ];
            
            
            if (isset($data['cod_empresa']) && isset($data['senha_empresa'])) {
                $param_consulta['is_contrato'] = true;
            }

            if (isset($data['seguro']) && $data['seguro']) {
                $param_consulta['seguro'] = $data['seguro'];
            }

            $add_valor_ar_balcao = false;
            
            
            $ret_consulta = $correioRest->consultaPrecoPrazo($param_consulta);
            
            if(!$ret_consulta){
                $this->error = $correioRest->getError(); 
                return false;
            }
            $return = [
                'status' => '1',
                'msg' => 'dados pesquisados com sucesso!',
                'valor' => $ret_consulta[1][0]['pcFinal'],
                'prazo' => $ret_consulta[0][0]['prazoEntrega'],
            ];

            // ajuste para casos de 1.300,20 
            $return['valor'] = preg_replace('/\./', '', $return['valor']);

            if(isset($data['seguro']) && isset($data['AR'])){
                $tmp_rest = $return;
            }
                return $return;
        }

        #$servico,$cep_origem,$cep_destino,$peso,$altura='4',$largura='12',$comprimento='16',$valor='17.00'){
        # URL do WebService 
        # metodos: CalcPrecoPrazo, CalcPreco, CalPrazo
        $metodo = 'CalcPrecoPrazo';
        $url = 'http://ws.correios.com.br/calculador/' . $metodo . '.aspx';

        // Campos
        //nCdEmpresa=08082650&
        //sDsSenha=564321&
        //sCepOrigem=70002900&
        //sCepDestino=04547000&
        //nVlPeso=1&
        //nCdFormato=1&
        //nVlComprimento=20&
        //nVlAltura=20&
        //nVlLargura=20&
        //sCdMaoPropria=n&
        //nVlValorDeclarado=0&
        //sCdAvisoRecebimento=n&
        //nCdServico=04510&
        //nVlDiametro=0&
        //StrRetorno=xml&
        //nIndicaCalculo=3
        $params = array(
            'nCdEmpresa' => (isset($data['cod_empresa']) && $data['cod_empresa']) ? $data['cod_empresa'] : '', # [string] Seu codigo administrativo junto ao ECT. O codigo esta disponivel no corpo do contrato firmado com os Correios
            'sDsSenha' => (isset($data['senha_empresa']) && $data['senha_empresa']) ? $data['senha_empresa'] : '', # [string] Senha para acesso ao servico, associada ao seu coo administrativo. A senha inicial corresponde aos 8
            # primeiros digitos do CNPJ informado no contrato. A qualquer momento, eh possivel alterar a senha no
            # enderechttp://www.corporativo.correios.com.br/encomendas/servicosonline/recuperaSenha
            'nCdServico' => $servico, # [string] Codigo de servico
            # Codigo dos Servicos dos Correios
            # 41106 PAC
            # 40010 SEDEX
            # 40045 SEDEX a Cobrar
            # 40215 SEDEX 10
            'sCepOrigem' => $cep_origem, # [string] Cep de origem, sem pontos e tracos
            'sCepDestino' => $cep_destino, # [string] Cep de destino, sem pontos e tracos
            'nVlPeso' => $peso, # [string] Peso da encomenda, incluindo sua embalagem. O peso deve ser informado em quilogramas. 
            # Se o formato for Envelope, o valor maximo permitido sera 1 kg
            'nCdFormato' => '1', # [int] Formato da encomenda (incluindo embalagem).
            # Valores possiveis: 1, 2 ou 3
            # 1 - Formato caixa/pacote
            # 2 - Formato rolo/prisma
            # 3 - Envelope
            'nVlComprimento' => $comprimento, # [decimal] Comprimento da encomenda (incluindo embalagem), em centimetros.
            'nVlAltura' => $altura, # [decimal] Altura da encomenda (incluindo embalagem), em centimetros. 
            # Se o formato for envelope, informar zero (0)
            'nVlLargura' => $largura, # [decimal]  Largura da encomenda (incluindo embalagem), em centimetros.
            'nVlDiametro' => '0', # [decimal] Diametro da encomenda (incluindo embalagem), em centimetros 
            //'sCdMaoPropria' => 'N', # [string] Indica se a encomenda sera entregue com o servico adicional mo propria.
            # Valores possiveis: S ou N (S Sim, N Nao)
            //'nVlValorDeclarado' => '0', # [decimal] Indica se a encomenda sera entregue com o servico adicional valor declarado. 
            # Neste campo deve ser apresentado o valor declarado desejado, em Reais.
            # Se nao optar pelo servico informar zero
            //'sCdAvisoRecebimento' => 'N', # [string] Indica se a encomenda sera entregue com o servico adicional aviso de recebimento.
            # Valores possiveis: S ou N (S Sim, N Nao)
            'StrRetorno' => 'xml',
            'nIndicaCalculo' => 3
        );

        if (isset($data['seguro']) && $data['seguro']) {
            $params['nVlValorDeclarado'] = number_format($data['seguro'], 2, ',', '');
        }
        if (isset($data['AR']) && $data['AR']) {
            $params['sCdAvisoRecebimento'] = 'S';
        }

        $str_url = $url . '?';

        foreach ($params as $k => $p) {
            $str_url .= $k . '=' . $p . '&';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $str_url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if (isset($data['timeout'])) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $data['timeout']);
            curl_setopt($ch, CURLOPT_TIMEOUT, $data['timeout']);
        }

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        if ($info['http_code'] != '200') {
            if ((int) $info['http_code'] == 0) {
                $return = array('url' => $str_url, 'status' => 0, 'msg' => "Falha em Conexão, por favor tente novamente em alguns minutos.");

                $this->error = 'Falha em Conexão, por favor tente novamente em alguns minutos.';
            } else {

                $msg_error = 'Falha em conexão : ' . $info['http_code'] . '.';
                $return = array('url' => $str_url, 'status' => 0, 'msg' => 'Falha: ' . $msg_error);
                $this->error = 'Falha: ' . $msg_error; // . ' '.$response;

                if ($info['http_code'] == '504') {
                    $this->error = 'Falha em Conexão, por favor tente novamente em alguns minutos.';
                }
            }
            return false;
        }

        $xml = $response;

        $dom = new DOMDocument('1.0', 'UTF-8');

        if (!$dom->loadXML($xml)) {
            $this->error = 'PROBLEMAS NO WEBSERVICE DOS CORREIOS.<br>O servidor dos Correios não está respondendo à nossa solicitação, Por favor tente novamente em alguns minutos.';
            return false;
        }

        $error = $dom->getElementsByTagName('Erro')->item(0)->nodeValue;

        if ($error && $error != '0' && $error != '011' && $error != '010') {
            $msg_error = $dom->getElementsByTagName('MsgErro')->item(0)->nodeValue;
            $return = array('url' => $str_url, 'status' => 0, 'msg' => 'Falha: ' . $msg_error . ' - ' . $altura);
            $this->error = $msg_error;

            if (preg_match('/o foi encontrada precifica(.*?)CEP de origem n(.*?)o pode postar para o CEP de destino/', $this->error)) {
                $this->error = "Para este CEP apenas oferecemos o serviço de SEDEX por favor altere a forma de envio para SEDEX";
            }
            if (preg_match('/o foi encontrada precifica(.*?)Para o servi(.*?)o pre(.*?)o n(.*?)o se aplica para origem/', $this->error)) {
                $this->error = "Para este CEP apenas oferecemos o serviço de SEDEX por favor altere a forma de envio para SEDEX";
            }
            if (preg_match('/CEP de origem n(.*?)o pode postar para o CEP de destino informado/', $this->error)) {
                $this->error = "Para este CEP apenas oferecemos o serviço de SEDEX por favor altere a forma de envio para SEDEX";
            }

            return false; //$return;
        }

        $return = [
            'status' => '1',
            'msg' => 'dados pesquisados com sucesso.',
            'valor' => $dom->getElementsByTagName('Valor')->item(0)->nodeValue,
            'prazo' => $dom->getElementsByTagName('PrazoEntrega')->item(0)->nodeValue,
                //'desc_servico' => $desc_servico[$servico]
        ];
 

        // ajuste para casos de 1.300,20 
        if (true) {
            $return['valor'] = preg_replace('/\./', '', $return['valor']);
        }

        $_tmp_valor = (float) number_format(preg_replace('/,/', '.', $return['valor']), 2, '.', '');

        if ($_tmp_valor <= 5) {
            $this->error = "Falha, tente novamente mais tarde";
            return false;
        }
 

         

        return $return;
    }
}
