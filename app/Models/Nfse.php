<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use DOMDocument;
use App\Http\Controllers\EnderecoController;

class Nfse extends Model
{
    public function getLinkNfse($nfse)
    {
        if ($nfse->status != 'OK' && $nfse->status != 'CANCEL') {
            return '';
        }

        $nfse->codigo_verificacao = preg_replace('/-/', '', $nfse->codigo_verificacao);
        $dom = new \DOMDocument();
        @$dom->loadXML($nfse->xml_resposta);
        $nfse->numero_nfse = $dom->getElementsByTagName('InfNfse')->item(0)->$dom->getElementsByTagName('Numero')->item(0)->nodeValue;

        $environment = config('nfse.enviroment');  

        if ($nfse->environment == 'sandbox') {
            return 'https://homologacao.notacarioca.rio.gov.br/contribuinte/notaprint.aspx?inscricao=10468930&nf=' . $nfse->numero_nfse . '&verificacao=' . $nfse->codigo_verificacao;
        } else {
            return 'https://notacarioca.rio.gov.br/contribuinte/notaprint.aspx?inscricao=10468930&nf=' . $nfse->numero_nfse . '&verificacao=' . $nfse->codigo_verificacao;
        }
    }

    public function get_error() {
        return $this->error;
    }

    public function getFieldsGerar()
    {
        $users = User::whereIn('group_code', ['cliente_contrato', 'cliente_sem_contrato'])->get();
        $lista_users = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name . ' | ' . $user->razao_social,
            ];
        });

        $fields_gerar = [
            "user_id" => ['required' => true, 'type' => 'select', 'opts' => $lista_users->toArray(), 'label' => 'Empresa (Tomador)', 'cols' => [4, 8]],
            "valor" => ['required' => true, 'class' => 'input-money', 'label' => 'Valor NF', 'cols' => [4, 8]],
            "perc_iss" => ['default_value' => '2.00', 'placeholder' => 'Percentual ISS aplicado sobre o valor total', 'class' => 'input-money', 'label' => 'ISS (%)', 'cols' => [4, 8]],
            "iss_retido" => ['label' => 'ISS Retido?', 'type' => 'checkbox'],
            "descriminacao" => ['required' => true, 'label' => 'Descriminação', 'type' => 'textarea', 'default_value' => 'Pagamento de envios. Valor aproximadamente de tributos R$ 31,02 (13,45% Fonte IBPT).'],
            "date_period_start" => ['default_value' => '', 'placeholder' => 'DD/MM/AAAA', 'class' => 'inp-date', 'label' => 'Data Inicial', 'cols' => [4, 8]],
            "date_period_end" => ['default_value' => '', 'placeholder' => 'DD/MM/AAAA', 'class' => 'inp-date', 'label' => 'Data Final', 'cols' => [4, 8]],
        ];

        return $fields_gerar;
    }

    public function getList($param = [])
    {
        $environment = config('nfse.enviroment');

        $query = $this->select('nfse.*')
            ->selectRaw('CONCAT(user.id, "-", user.name, " - ", user.razao_social) as cliente')
            ->join('user', 'user.id', '=', 'nfse.user_id')
            ->where('nfse.environment', $environment)->get();

        return $query;

      /*   if (isset($param['get_total']) && $param['get_total']) {
            return $query->count('nfse.id');
        } elseif (isset($param['get_valor_total']) && $param['get_valor_total']) {
            $result = $query->where('nfse.status', 'OK')->selectRaw('sum(nfse.valor) as total, count(*) as num_rows')->first();
            return ['valor_total' => $result->total, 'num_rows' => $result->num_rows];
        } else {
            if (isset($param['date_autorizacao_start'])) {
                $query->where('date_autorizacao', '>=', $param['date_autorizacao_start']);
            }
            if (isset($param['date_autorizacao_end'])) {
                $query->where('date_autorizacao', '<=', $param['date_autorizacao_end']);
            }

            if (isset($param['filter_date_start'])) {
                $query->where('date_autorizacao', '>=', $param['filter_date_start']);
            }

            if (isset($param['filter_date_end'])) {
                $query->where('filter_date_end', '>=', $param['filter_date_end']);
            }

            if (isset($param['filter_cliente']) && (int) $param['filter_cliente']) {
                $query->where('user_id', $param['filter_cliente']);
            }

            if (isset($param['user_id'])) {
                $query->where('user_id', $param['user_id']);
            }

            if (isset($param['get_total']) && $param['get_total']) {
                return $query->count();
            } else {
                if (isset($param['per_page'])) {
                    $limit = isset($param['per_page']) ? $param['per_page'] : 10;
                    $start = isset($param['page_start']) ? $param['page_start'] : 0;

                    $query->limit($limit)->offset($start);
                }

                if (isset($param['order_by']) && $param['order_by'] == 'numero.asc') {
                    $query->orderBy('nfse.numero');
                } else {
                    $query->orderByDesc('nfse.numero');
                }

                return $query->get();
            }
        } */
    }

    public function cancelar($nfse_id)
    {
        $nfse = $this->find($nfse_id);

        if (!$nfse) {
            $this->error = "NFSe não encontrada";
            return false;
        }

        $xml_cancel = $this->nfseLib->getXmlCancel($nfse);

        if (!$xml_cancel) {
            $this->error = $this->nfseLib->getError();
            return false;
        }

        $data_webserv = [
            'environment' => $nfse->environment,
            'action' => 'CancelarNfse',
            'xml' => $xml_cancel
        ];

        DB::beginTransaction();

        try {
            $webservice_response = $this->webserv->send($data_webserv);

            if (!$webservice_response) {
                $this->error = $this->webserv->getError();
                DB::rollBack();
                return false;
            }

            $dom = new DOMDocument('1.0', 'UTF-8');

            if (!$dom->loadXML($webservice_response)) {
                $this->error = "Falha DOM load xml: $webservice_response |";
                DB::rollBack();
                return false;
            }

            $cancelamento = $dom->getElementsByTagName('Cancelamento')->item(0);

            if (!$cancelamento) {
                $mensagemRetorno = $dom->getElementsByTagName('MensagemRetorno')->item(0)->$dom->getElementsByTagName('Mensagem')->item(0)->nodeValue;
                $correcao = '';

                if ($dom->getElementsByTagName('MensagemRetorno')->item(0)->$dom->getElementsByTagName('Correcao')->length) {
                    $correcao = $dom->getElementsByTagName('MensagemRetorno')->item(0)->$dom->getElementsByTagName('Correcao')->item(0)->nodeValue;
                }

                if ($mensagemRetorno) {
                    $this->error = $mensagemRetorno . '<br>' . $correcao;
                } else {
                    $this->error = "Falha DOM load xml(2)";
                }

                DB::rollBack();
                return false;
            }

            $nfse->xml_cancelamento = $webservice_response;
            $nfse->status = 'CANCEL';
            $nfse->date_cancelamento = now();  
            $nfse->save();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            DB::rollBack();
            return false;
        }
    }

    public function gerar($data = [])
    {
        $error = [];
        $codigo_verificacao = null;

        $user = User::find($data['user_id']);

        if (!$user) {
            $error[] = "Empresa não encontrada.";
        }

        $environment = config('app.enviroment_nfse');

        if (!$error) {
            $user->razao_social = preg_replace('/\&/', 'e', $user->razao_social);

            $serie = '001'; // serie inicial
            $serieInt = (int) $serie;

            $dateGeracao = isset($data['date_emissao']) ? $data['date_emissao'] : now();
            $numeroDb = DB::table('nfse')
                ->where('serie', $serieInt)
                ->where('environment', $environment)
                ->max('numero');

            $numero = $numeroDb ? $numeroDb + 1 : 5;
        }

        if (!$error) {
            $valor = str_replace(',', '.', $data['valor']);
            $perc_iss = (float) str_replace(',', '.', $data['perc_iss']);
            $iss_retido = (isset($data['iss_retido']) && $data['iss_retido']) ? '1' : '2';

            $dataGerar = [
                'numero' => $numero,
                'serie' => $serie,
                'data_emissao' => preg_replace('/\s/', 'T', $dateGeracao),
                'valor_servico' => $valor,
                'iss_retido' => $iss_retido,
                'perc_iss' => $perc_iss,
                'descriminacao' => $data['descriminacao'],
                'prestador' => getInfoPrestadorNfse(),
            ];
            $enderecoController = new EnderecoController();
            $cod_municipio = $enderecoController->getByCep(['cep' => $user->CEP]);

            $dataGerar['tomador'] = [
                'cnpj' => $user->cnpj,
                'cpf' => $user->cpf,
                'tipo_cliente' => $user->tipo_cliente,
                'nome' => preg_replace('/\&/', 'e', $user->name),
                'razao_social' => $user->razao_social,
                'fantasia' => '',
                'logradouro' => $user->logradouro,
                'numero' => $user->numero,
                'complemento' => $user->complemento,
                'bairro' => $user->bairro,
                'uf' => $user->uf,
                'cep' => $user->CEP,
                'cod_municipio' => $cod_municipio->ibge,
                'email' => $user->email,
            ];

            if ($user->inscricao_municipal) {
                $dataGerar['tomador']['inscricao_municipal'] = $user->inscricao_municipal;
            }

            if ($user->inscricao_municipal) {
                $dataGerar['tomador']['telefone'] = $user->telefone;
            }

            $dataGerar['percentual_iss'] = number_format(($perc_iss / 100), 2, '.', '');

            $xmlNfseNormal = $this->nfse_lib->get_xml_normal($dataGerar);

            $dataInsertNfse = [
                'user_id' => $user->id,
                'numero' => $numero,
                'serie' => $serie,
                'environment' => $environment,
                'perc_iss' => $perc_iss,
                'iss_retido' => $iss_retido,
                'valor' => $valor,
                'descriminacao' => $data['descriminacao'],
                'date_insert' => now(),
                'date_update' => now(),
                'xml_envio' => $xmlNfseNormal,
            ];

            if (isset($data['nao_enviar_WS']) && $data['nao_enviar_WS']) {
                $dataInsertNfse['status'] = 'PENDENTE_GERAR';
            }

            if (!$xmlNfseNormal) {
                if ($this->nfse_lib->get_error_code() == 'NO_DOC') {
                    $dataInsertNfse['status'] = 'SEM_DOC';
                } else {
                    $error[] = "Erro desconhecido";
                }
            }

            if (!$error) {
                if (isset($data['date_period_start']) && $data['date_period_start']) {
                    $dataInsertNfse['date_period_start'] = $data['date_period_start'];
                }
                if (isset($data['date_period_end']) && $data['date_period_end']) {
                    $dataInsertNfse['date_period_end'] = $data['date_period_end'];
                }

                $insertNfse = self::create($dataInsertNfse);
                $nfseId = $insertNfse->id;

                if (!$insertNfse) {
                    $error[] = "Falha ao inserir NFse";
                }
            }
        }

        if (!isset($data['nao_enviar_WS']) || !$data['nao_enviar_WS']) {
            if (!$error) {
                $dataWebserv = [
                    'environment' => $environment,
                    'action' => 'GerarNfse',
                    'xml' => $xmlNfseNormal,
                ];
                $webserviceResponse = $this->webserv->send($dataWebserv);

                if (!$webserviceResponse) {
                    $error[] = $this->webserv->get_error();
                }
            }

            if (!$error) {
                $dom = new DOMDocument('1.0', 'UTF-8');
                if (!$dom->loadXML($webserviceResponse)) {
                    $error[] = "Falha DOM load xml: $webserviceResponse |";
                }
                if (!$error) {
                    $Nfse = $dom->getElementsByTagName('Nfse')->item(0);
                    if (!$Nfse) {
                        $MensagemRetorno = $dom->getElementsByTagName('MensagemRetorno')->item(0)->$dom->getElementsByTagName('Mensagem')->item(0)->nodeValue;
                        $Correcao = '';
                        if ($dom->getElementsByTagName('MensagemRetorno')->item(0)->$dom->getElementsByTagName('Correcao')->length) {
                            $Correcao = $dom->getElementsByTagName('MensagemRetorno')->item(0)->$dom->getElementsByTagName('Correcao')->item(0)->nodeValue;
                        }
                        if ($MensagemRetorno) {
                            $error[] = $MensagemRetorno . '<br>' . $Correcao;
                        } else {
                            $error[] = "Falha DOM load xml(2)";
                        }
                    } else {
                        $codigo_verificacao = $Nfse->$dom->getElementsByTagName('CodigoVerificacao')->item(0)->nodeValue;
                    }
                }
            }
            if (!$error) {
                $nfse = self::find($nfseId);
                $nfse->update([
                    'date_autorizacao' => $dateGeracao,
                    'xml_resposta' => $webserviceResponse,
                    'status' => 'OK',
                    'codigo_verificacao' => $codigo_verificacao,
                ]);
            } else {
                self::destroy($nfseId);
            }
        }
        if ($error) {
            $this->error = implode('<br>', $error);
            return false;
        }
        return true;
    }

    public function consultar()
    {
        $xml = <<<XML
        <ConsultarNfseEnvio xmlns="http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd">
            <Prestador>
                <Cnpj>27347642000118</Cnpj>
                <InscricaoMunicipal>10468930</InscricaoMunicipal>
            </Prestador>
            <NumeroNfse>1</NumeroNfse>
        </ConsultarNfseEnvio>
        XML;

                $data = [
                    'numero' => 1,
                    'serie' => '001',
                    'data_emissao' => now()->format('Y-m-d\TH:i:s'),
                    'valor_servico' => '200.50',
                    'prestador' => [
                        'cnpj' => '27347642000118',
                        'inscricao_municipal' => '10468930',
                        'razao_social' => 'MANDA BEM INTERMEDIACOES LTDA',
                        'fantasia' => 'MANDA BEM',
                        'logradouro' => 'RUA MAL MASCARENHAS DE MORAIS',
                        'numero' => '191',
                        'complemento' => 'AP T303',
                        'bairro' => 'COPACABANA',
                        'uf' => 'RJ',
                        'cep' => '22030040',
                        'cod_municipio' => '3304557',
                        'telefone' => '21983100077',
                        'email' => 'maduartedecastro@gmail.com',
                    ],
                    'tomador' => [
                        'cnpj' => '27570422000159',
                        'inscricao_municipal' => '10512662',
                        'razao_social' => 'TOTTA COMERCIO DO VESTUARIO E ACESSORIOS EIRELI',
                        'fantasia' => '',
                        'logradouro' => 'RUA MARQ DE SAO VICENTE',
                        'numero' => '124',
                        'complemento' => 'LOJ 215',
                        'bairro' => 'GAVEA',
                        'uf' => 'RJ',
                        'cep' => '22451040',
                        'cod_municipio' => '3304557',
                        'telefone' => '',
                        'email' => 'contato.totta@gmail.com',
                    ]
                ];

                $xml_gerar = <<<XML
        <GerarNfseEnvio xmlns="http://notacarioca.rio.gov.br/WSNacional/XSD/1/nfse_pcrj_v01.xsd">
            <Rps>
                <InfRps xmlns="http://www.abrasf.org.br/ABRASF/arquivos/nfse.xsd" Id="R1">
                    <IdentificacaoRps>
                        <Numero>{$data['numero']}</Numero>
                        <Serie>{$data['serie']}</Serie>
                        <Tipo>1</Tipo>
                    </IdentificacaoRps>
                    <DataEmissao>{$data['data_emissao']}</DataEmissao>
                    <NaturezaOperacao>1</NaturezaOperacao>
                    <OptanteSimplesNacional>1</OptanteSimplesNacional>
                    <IncentivadorCultural>2</IncentivadorCultural>
                    <Status>1</Status>
                    <Servico>
                        <Valores>
                            <ValorServicos>{$data['valor_servico']}</ValorServicos>
                            <ValorDeducoes>0</ValorDeducoes>
                            <ValorPis>0.00</ValorPis>
                            <ValorCofins>0.00</ValorCofins>
                            <ValorInss>0.00</ValorInss>
                            <ValorIr>0.00</ValorIr>
                            <ValorCsll>0.00</ValorCsll>
                            <IssRetido>1</IssRetido>
                            <ValorIssRetido>{$data['valor_servico']}</ValorIssRetido>
                            <Aliquota>0.02</Aliquota>
                        </Valores>
                        <ItemListaServico>1002</ItemListaServico>
                        <CodigoTributacaoMunicipio>100201</CodigoTributacaoMunicipio>
                        <Discriminacao>Pagamento de envios. Data: {$data['data_emissao']}</Discriminacao>
                        <CodigoMunicipio>3304557</CodigoMunicipio>
                    </Servico>
                    <Prestador>
                        <Cnpj>{$data['prestador']['cnpj']}</Cnpj>
                        <InscricaoMunicipal>{$data['prestador']['inscricao_municipal']}</InscricaoMunicipal>
                    </Prestador>
                    <Tomador>
                        <IdentificacaoTomador>
                            <CpfCnpj>
                                <Cnpj>{$data['tomador']['cnpj']}</Cnpj>
                            </CpfCnpj>
                        </IdentificacaoTomador>
                        <RazaoSocial>{$data['tomador']['razao_social']}</RazaoSocial>
                        <Endereco>
                            <Endereco>{$data['tomador']['logradouro']}</Endereco>
                            <Numero>{$data['tomador']['numero']}</Numero>
                            <Complemento>{$data['tomador']['complemento']}</Complemento>
                            <Bairro>{$data['tomador']['bairro']}</Bairro>
                            <CodigoMunicipio>{$data['tomador']['cod_municipio']}</CodigoMunicipio>
                            <Uf>{$data['tomador']['uf']}</Uf>
                            <Cep>{$data['tomador']['cep']}</Cep>
                        </Endereco>
                    </Tomador>
                </InfRps>
            </Rps>
        </GerarNfseEnvio>
        XML;

        file_put_contents('/var/www/html/xml_nfse.xml', $xml_gerar);
        echo "\n\n<![CDATA[" . $xml_gerar . "]]>\n\n";
        exit;

        $webservice_response = $this->sendRequest($xml_gerar, 'http://seuwebservice.com/consultar');
        return $webservice_response;
    }

    private function sendRequest($xml, $url)
    {
        $response = Http::post($url, ['xml' => $xml]);

        return $response->body();
    }

}