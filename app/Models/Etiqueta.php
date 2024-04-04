<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoapClient;
use Exception;

class Etiqueta extends Model
{
    protected $table = 'etiquetas_cache';  

    private $error;

    public function etiquetasCache($params = [])
    {
        $idsServicos = [
            'SEDEX' => '162022',
            'PAC' => '162026',
            'PACMINI' => '159982',
        ];

        if (!empty($params)) {
            $qtde = $params['quantidade'];
            $formaEnvio = $params['forma_envio'];

            $idsServicos = [$formaEnvio => $idsServicos[$formaEnvio]];

            $etiquetas = $this->whereNull('date_used')
                ->where('forma_envio', $formaEnvio)
                ->limit($qtde)
                ->orderBy('id', 'asc')
                ->get();

            $codigoEtiquetas = [];

            if ($qtde == count($etiquetas)) {
                foreach ($etiquetas as $etiqueta) {
                    $etiqueta->update(['date_used' => now()]);
                    $codigoEtiquetas[] = $etiqueta->etiqueta;
                }

                return $codigoEtiquetas;
            }
        } else {
            $qtde = 100;
        }
        $user = array();
        $ambiente = getCredentialsEtiqueta($user);

        $clientSoap = new SoapClient($ambiente['production']['link'], [
            'stream_context' => stream_context_create([
                'http' => [
                    'protocol_version' => '1.0',
                    'header' => 'Connection: Close',
                    'timeout' => 1.0,
                ],
            ]),
        ]);

        $etiquetas = [];

        foreach ($idsServicos as $k => $i) {
            $solicitaEtiquetas = [
                'tipoDestinatario' => 'C',
                'identificador' => $ambiente['production']['cnpj'],
                'idServico' => $i,
                'qtdEtiquetas' => $qtde,
                'usuario' => $ambiente['production']['usuario'],
                'senha' => $ambiente['production']['senha'],
            ];

            try {
                $result = $clientSoap->solicitaEtiquetas($solicitaEtiquetas);
                $etiquetas = explode(",", str_replace(' BR', '', $result->return));
                if ($qtde == 1) {
                    array_pop($etiquetas);
                }

                if ($qtde > 2) {
                    $sigla = substr($etiquetas[0], 0, 2); // Sigla OF PE
                    $inicio = substr($etiquetas[0], 2);   // Início
                    $inicioAux = substr("{$inicio}abc", 0, 1);

                    for ($i = 1; $i < $qtde; $i++) {
                        $inicio++;
                        $etiquetas[$i] = $sigla . str_pad($inicio, 8, '0', STR_PAD_LEFT);
                    }
                }

                $geraDigitoVerificadorEtiquetas = [
                    'etiquetas' => [],
                    'usuario' => $ambiente['production']['usuario'],
                    'senha' => $ambiente['production']['senha'],
                ];

                // Buscar dígito das etiquetas
                foreach ($etiquetas as $etiqueta) {
                    $geraDigitoVerificadorEtiquetas['etiquetas'][] = $etiqueta . ' BR';
                }

                $digitos = $clientSoap->geraDigitoVerificadorEtiquetas($geraDigitoVerificadorEtiquetas);

                // Mesclando Etiqueta + Dígitos
                for ($i = 0; $i < $qtde; $i++) {
                    if (is_array($digitos)) {
                        $etiquetas[$i] = $etiquetas[$i] . '' . $digitos[$i];
                    } else {
                        $etiquetas[$i] = $etiquetas[$i] . '' . $digitos;
                    }
                }

                foreach ($etiquetas as $et) {
                    Etiqueta::create([
                        'etiqueta' => $et,
                        'forma_envio' => $k,
                        'date_generate' => now(),  
                    ]);
                }
            } catch (Exception $e) {
                if ($_SERVER['REMOTE_ADDR'] == '177.25.214.210') {
                    print_r($e);
                }

                $this->error = 'PROBLEMAS NO WEBSERVICE DOS CORREIOS.<br>O servidor dos Correios não está respondendo à nossa solicitação, Por favor tente novamente em alguns minutos.';
                return false;
            }
        }

        return true;
    }

}
