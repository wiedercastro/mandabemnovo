<?php

/*
            Codigos do industrial
            if ($param['forma_envio'] == 'PAC') {
                $param_correio['servico'] = '03336';
            }
            if ($param['forma_envio'] == 'SEDEX') {
                $param_correio['servico'] = '03280';
            }
            if ($param['forma_envio'] == 'PACMINI') {
                $param_correio['servico'] = '04391';
            }
 * 
 * 
 */

namespace App\Libraries\Correios;

use App\Models\Envio;
use App\Models\GrupoTaxa;
use App\Libraries\Correios\CorreioRest;
// use App\Libraries\EmailMaker;

class CalIndustrial
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

    public function maskCep($value = '')
    {
        if ($value == '')
            return '';
        if (strlen($value) != 8)
            return $value;
        return substr($value, 0, 5) . '-' . substr($value, 5, 3);
    }
    /**
     * $data recebe os dados de envio
     * $grupo_taxa_id recebe o grupo que o cliente faz parti para trazer as taxas
     */
    public function calc($data = array(), $grupo_taxa_id = null)
    {   
        
        $envioModel = new Envio();
        $grupoTaxaModel = new GrupoTaxa();
        $correioRest = new CorreioRest();
        // $emailMaker = new EmailMaker();
       
        if($grupo_taxa_id){
            $grupo_id['id'] = $grupo_taxa_id;
            $grupo = $grupoTaxaModel->getGrupo($grupo_id);

        }else{
            return $envioModel->simule_envio($data);

        }
        
        if ($grupo->tabela == 'varejo') {
            return $envioModel->simule_envio($data);
        } else {
            //$servico = isset($data['servico']) ? $data['servico'] : null;

            //Codigos do industrial
            switch ($data['forma_envio']) {
                case "PAC": $servico = '03336';
                break;

                case "SEDEX": $servico = '03280';
                break;

                case "PACMINI": $servico = '04391';
                break;

            }

            $cep_origem = isset($data['cep_origem']) ? $data['cep_origem'] : null;
            $cep_destino = isset($data['cep_destino']) ? $data['cep_destino'] : null;

            $peso = (isset($data['peso']) && strlen($data['peso'])) ? $data['peso'] : 1;
            $altura = $this->defaultValueMin($data, 'altura', 2);
            $largura = $this->defaultValueMin($data, 'largura', 11);
            $comprimento = $this->defaultValueMin($data, 'comprimento', 16);


            #echo "P: $peso, A: $altura, L: $largura, C: $comprimento <br>\n";
            //        if (!preg_match('/^([0-9]{5})-([0-9]{3})$/', $cep_origem)) {
            if (!preg_match('/^([0-9]{8})$/', $cep_origem)) {
                //            $ret = array('status' => 0, 'msg' => 'Cep de origem: ' . $cep_origem . ' inválido');
                $this->error = 'Cep de origem: ' . $cep_origem . ' inválido';
                return false;
            }
            //        if (!preg_match('/^([0-9]{5})-([0-9]{3})$/', $cep_destino)) {
            if (!preg_match('/^([0-9]{8})$/', $cep_destino)) {
                //            $ret = array('status' => 0, 'msg' => 'Cep de destino: ' . $cep_destino . ' inválido');
                $this->error = 'Cep de destino: ' . $cep_destino . ' inválido';
                return false;
            }

            //ambiente de teste
            //if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == $this->ip_test) {
 
                $param_consulta = [
                    'cep_origem' => $cep_origem,
                    'cep_destino' => $cep_destino,
                    'cod_servico' => $servico,
                    'peso' => ($peso * 1000), // Peso em gramas, foi alterado nessa nova API
                    'altura' => $altura,
                    'largura' => $largura,
                    'comprimento' => $comprimento,
                    'is_contrato' => false,
                ];


                if (isset($data['cod_empresa']) && isset($data['senha_empresa'])) {
                    $param_consulta['is_contrato'] = true;
                }

                //            if (!isset($data['cal_seguro_local']) && isset($data['seguro']) && $data['seguro']) {
                if (isset($data['seguro']) && $data['seguro']) {
                    $param_consulta['seguro'] = $data['seguro'];
                    //                $params['nVlValorDeclarado'] = number_format($data['seguro'], 2, ',', '');
                }

                $ret_consulta = $correioRest->consultaPrecoPrazo($param_consulta);

                if (!$ret_consulta) {
                    $this->error = $correioRest->getError();
                    return false;
                }

                //buscar informações e aplicar taxa de cadastro com base no retorno da consulta de Preço
                $valorRetorno = str_replace(array('.', ','), array('', '.'), $ret_consulta[1][0]['pcFinal']);
                
                //Faz verificação pelo grupo se o valor é por PERCENT ou valor FIX caso fix pega o valor com base na tabela grupo_taxa_itens
                if($grupo->type = "FIX"){
                    $taxa = $grupoTaxaModel->getTaxa($grupo_taxa_id, $ret_consulta[1][0]['pcFinal']);
                    $valorFinal =  $valorRetorno + $taxa;
                }else{
                    $valorFinal = ($valorRetorno * $grupo->percent/100) + $valorRetorno;
                }
                
                $valorFinal = number_format($valorFinal, 2, '.', '');
                
                $return = [
                    'status' => 1,
                    'msg' => 'dados pesquisados com sucesso.',
                    'valor' => $valorFinal,
                    'prazo' => $ret_consulta[0][0]['prazoEntrega'],
                ];

                // ajuste para casos de 1.300,20 
                //$return['valor'] = preg_replace('/\./', '', $return['valor']);
            //}
           
            return $return;
        }
    }

    public function getError()
    {
        return $this->error;
    }
}
