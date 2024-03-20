<?php

namespace App\Services;

use App\Libraries\Correios\Correio;
use Illuminate\Support\Facades\DB;

class EnderecoService
{
    public function get_by_cep($param = array())
    {
        
        if (!isset($param['cep'])) {
            return array('error' => 'Falha CEP');
        }




        $cep = preg_replace('/[^0-9]/', '', $param['cep']);


        if ($cep == '37200000') {
            return array('error' => 'CEP invalido');
        }

        $address = DB::table('endereco')->where('cep', $cep)->first();
        // dd($address);
        if ($address) {
            return $address;
        } else {
            $error = array();
            $info = $this->search($cep, $error);
            if (!$info) {
                if (!preg_match('/CEP n(.*?)o encontrado/', $error[0]) && !preg_match('/CEP inv(.*?)lido/', $error[0])) {
                    $ci = &get_instance();
                    $ci->load->library('email_maker');
                    $ci->load->library('date_utils');
                    $ci->email_maker->msg([
                        'to' => 'regygom@gmail.com',
                        'subject' => 'Falha em busca de CEP (1) Search',
                        'msg' => "Info: " . print_r($info, true) . "<br><br>" . print_r($error, true) . "\n"
                    ]);
                }

                return array('error' => implode('<br>', $error));
            }

            //            if($this->input->server('REMOTE_ADDR') == '177.185.215.89'){
            //                print_r($info);
            //                exit;
            //            }


            DB::table('endereco')->insert([
                'logradouro' => strlen(trim($info['logradouro'])) ? trim($info['logradouro']) : null,
                'bairro' => strlen(trim($info['bairro'])) ? trim($info['bairro']) : null,
                'cidade' => trim($info['localidade']),
                'uf' => trim($info['uf']),
                'cep' => $cep,
                'ibge' => strlen(trim($info['ibge'])) ? trim($info['ibge']) : null,
                'date_insert' => date('Y-m-d H:i:s')
            ]);


            return  DB::table('endereco')->where('cep', $cep)->first();
        }
    }

    private function search($cep_, &$error = [])
    {

        $cep = preg_replace('/[^0-9]/', '', $cep_);

        if (!strlen($cep)) {
            $error[] = "Falha em busca de CEP, CEP inválido";
            return false;
        }
        if (strlen($cep) != 8) {
            $error[] = "CEP invalido! Cep digitado ($cep) possui " . strlen($cep) . " digitos. Um CEP válido possui 8 digitos!";
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://viacep.com.br/ws/" . $cep . "/json/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $resp = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        //        if ($info['http_code'] != '200') {
        //            
        //        }
        //        if($_SERVER['REMOTE_ADDR'] != '177.185.220.50') {
        if (!$array = json_decode($resp, true)) {
            $error[] = "Falha em busca de CEP, retorno difere do esperado (1)\nCep: $cep\nRetorno: " . $resp . "\n";

            $ci = &get_instance();
            $ci->load->library('email_maker');
            $ci->load->library('date_utils');
            $ci->email_maker->msg([
                'to' => 'regygom@gmail.com',
                'subject' => 'Falha em busca de CEP Retorno difere Esperado',
                'msg' => "Info: " . print_r($info, true) . "<br><br>" . print_r($error, true) . "\n"
            ]);

            return false;
        }
        //        }
        if (isset($array['erro']) || $error) {

            $correio = new Correio();

            $info = $correio->buscaCep($cep);

            if (isset($info->endereco->cep) && trim($info->endereco->cep) != 'CEP NAO ENCONTRADO') {
                $ci = &get_instance();
                $ci->load->library('email_maker');
                $ci->load->library('date_utils');
                $ci->email_maker->msg([
                    'to' => 'regygom@gmail.com',
                    'subject' => 'Falha em busca de CEP (1) - ' . $ci->date_utils->get_now(),
                    'msg' => "Info: <pre>" . print_r($info, true) . "<br><br>" . print_r($error, true) . "</pre>\n"
                ]);
            }

            if (!$info) {
                $error[] = "Falha em busca de CEP (CR), CEP não encontrado";
                return false;
            } else {
                if (isset($info->end)) {
                    return [
                        'logradouro' => $info->end,
                        'bairro' => $info->bairro,
                        'localidade' => $info->cidade,
                        'uf' => $info->uf,
                        'ibge' => 'none'
                    ];
                } else {
                    return [
                        'logradouro' => $info->logradouro,
                        'bairro' => $info->bairro,
                        'localidade' => $info->cidade,
                        'uf' => $info->uf,
                        'ibge' => 'none'
                    ];
                }
            }

            $error[] = "Falha em busca de CEP, CEP não encontrado";
        }

        return $array;
    }
}
