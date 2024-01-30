<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use SoapClient;

class SoapController extends Controller
{
    public function index()
    {
        // URL do serviço SOAP
        $url = "https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl";

        // Configuração do cliente SOAP
        $options = [
            'trace' => 1,
            'exceptions' => true,
        ];

        $cep = "39406148";

        // Criação do cliente SOAP
        $soapClient = new SoapClient('https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl', array(
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
        // Parâmetros para a chamada SOAP
        $params = [
            'cep' => $cep,
        ];

        try {
            // Chama o método SOAP
            $result = $soapClient->consultaCEP($params);

            // Faça algo com o resultado
            dd($result);
        } catch (\SoapFault $e) {
            // Trata erros SOAP
            dd($e->getMessage());
        }
    }
}
