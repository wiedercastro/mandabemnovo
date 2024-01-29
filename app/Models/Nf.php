<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nf extends Model
{
    public function consultar()
    {
        //corrigir library
        $this->load->library('nf/nf_normal');
        $xml = '<ConsultarNfseEnvio>';
        $xml .= '<PrestadorServico>';
        $xml .= '<IdentificacaoPrestador>';
        $xml .= '<Cnpj>27347642000118</Cnpj>';
        $xml .= '</IdentificacaoPrestador>';
        $xml .= '</PrestadorServico>';
        $xml .= '<tsNumeroNfse>1</tsNumeroNfse>';
        $xml .= '</ConsultarNfseEnvio>';

        echo "\n\n" . $xml . "\n\n"; // você pode querer remover isso no ambiente de produção
        exit;

        $info['action'] = 'NfeRetAutorizacao';
        $info['uf'] = $customer['uf'];
        $info['version'] = $this->definitions['nfe_version'];
        $info['environment'] = 2;
        $info['xml'] = $xml_recibo;
        $info['cod_uf'] = 35;
        $info['certificate'] = $customer['certificate'];
        $info['customer_id'] = $customer['id'];

        return $info;
    }
}
