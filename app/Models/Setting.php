<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getCredentialsEtiqueta($type = null)
    {
        if (!$type) {
            $type = config('app.environment_coleta'); // Ajustado para utilizar a configuração do Laravel
        }

        $conexao = [
            'producao' => [
                'link' => 'https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl',
                'cnpj' => '27347642000118',
                'usuario' => 'MAQUINAMQN',
                'senha' => 'urbju5',
                'cod_adm' => '18086160',
                'contrato' => '9912437691',
                'cod_serv' => '000',
                'cartao' => '0073996360',
                'num_diretoria' => '50',
            ],
            'testes' => [
                'link' => 'https://apphom.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl',
                'cnpj' => '34028316000103',
                'usuario' => 'sigep',
                'senha' => 'n5f9t8',
                'cod_adm' => '17000190',
                'contrato' => '9992157880',
                'cod_serv' => '000',
                'cartao' => '0067599079',
                'num_diretoria' => '10',
            ],
        ];

        return $conexao[$type];
    }
}
