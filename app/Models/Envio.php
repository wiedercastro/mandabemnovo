<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use App\Services\CorreiosPrazoFreteOffline;
use App\Services\CalPrazoFrete;
use App\Models\Log;
use App\Models\Coleta;
use App\Libraries\Correio\Correio;
use App\Models\User;
use App\Models\Payment;

class Envio extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'coleta_id',
        'user_id',
        'date_postagem',
    ];
    private $CalPrazoFrete;
    protected $table = "envios";

    public function getError()
    {
        return $this->error;
    }

    public function simuleEnvio($param = [])
    {
        $this->error = '';

        // restringir dimensoes e AR
        if (true || request()->server('REMOTE_ADDR')) {
            $this->CalPrazoFrete = new CorreiosPrazoFreteOffline();
        } else {
            $this->CalPrazoFrete = new CalPrazoFrete();
            exit;
        }

        //se soma for maior que 90 trocar o peso do pedido pelo peso cubico
        if (
            ($param['user_id'] == 69865) ||
            ($param['user_id'] == 75477) ||
            ($param['user_id'] == 43114) ||
            ($param['user_id'] == 72533) ||
            ($param['user_id'] == 14046) ||
            ($param['user_id'] == 16152) ||
            ($param['user_id'] == 76039) ||
            ($param['user_id'] == 76804)
        ) {
            $sum = (int) $param['altura'] + (int) $param['comprimento'] + (int) $param['largura'];
            $medidas = (int) $param['altura'] * (int) $param['comprimento'] * (int) $param['largura'];
            $peso_cubico = $medidas / 6000;
            if (($sum > 90) && ($peso_cubico > $param['peso']) && ($peso_cubico > 5)) {
                $peso_cubico = $param['peso'];
            }
        }

        if (!isset($param['peso']) || !$param['peso']) {
            $param['peso'] = 0.300;
        }

        if ((int) $param['peso'] > 30) {
            $this->error = 'Peso informado é superior ao permitido, o valor máximo é de 30 Kilos.';
            return false;
        }

        // Sem Contrato
        if ($param['forma_envio'] == 'PAC') {
            $tipo_envio = '04510';
        }
        if ($param['forma_envio'] == 'SEDEX') {
            $tipo_envio = '04014';
        }
        if ($param['forma_envio'] == 'SEDEX 10') {
            $tipo_envio = '40215';
        }
        if ($param['forma_envio'] == 'SEDEX HOJE') {
            $tipo_envio = '03204';
        }
        if ($param['forma_envio'] == 'SEDEX 12') {
            $tipo_envio = '03140';
        }
        if ($param['forma_envio'] == 'PACMINI') {
            $tipo_envio = '04227';
        }

        if ($param['peso'] > 0.3) {
            $param['peso'] = ceil($param['peso']);
        }

        $param_correio = [
            'forma_envio' => $param['forma_envio'],
            'servico' => $tipo_envio,
            'cep_origem' => $param['cep_origem'],
            'cep_destino' => $param['cep_destino'],
            'peso' => $param['peso'],
        ];

        if (isset($param['altura']))
            $param['altura'] = ceil($param['altura']);

        if (isset($param['comprimento']))
            $param['comprimento'] = ceil($param['comprimento']);

        if (isset($param['largura']))
            $param['largura'] = ceil($param['largura']);

        if (isset($param['altura']) && (int) $param['altura']) {
            $param_correio['altura'] = (int) $param['altura'];
        }
        if (isset($param['comprimento']) && (int) $param['comprimento']) {
            $param_correio['comprimento'] = (int) $param['comprimento'];
        }
        if (isset($param['largura']) && (int) $param['largura']) {
            $param_correio['largura'] = (int) $param['largura'];
        }

        if (
            (isset($param_correio['altura']) && $param_correio['altura'] > 70) ||
            (isset($param_correio['largura']) && $param_correio['largura'] > 70) ||
            ( isset($param_correio['comprimento']) && $param_correio['comprimento'] > 70)
        ) {
            $logModel = new Log();
            $logModel->log([
                'text' => "Frete não calculado, dimensões acima do permitido\n\nDados: " . print_r($param, true),
                'type' => "LOG_USER",
                'user_id' => ( isset($param['user_id']) ? $param['user_id'] : ( isset($this->session->user_id) ? $this->session->user_id : null ) )
            ]);

            $this->error = "Frete não calculado, dimensões acima do permitido.";
            return false;
        }

        // Com contrato
        if (isset($param['cod_empresa'])) {
            $param_correio['cod_empresa'] = $param['cod_empresa'];
            $param_correio['senha_empresa'] = $param['senha_empresa'];

            // Industrial **** Nâo cotar pelo industrial, cotar normal mas sem taxa

            if ($param['forma_envio'] == 'PAC') {
                $param_correio['servico'] = '03298';
            }
            if ($param['forma_envio'] == 'SEDEX') {
                $param_correio['servico'] = '03220';
            }
            if ($param['forma_envio'] == 'SEDEX 10') {
                $param_correio['servico'] = '40940';
            }
            if ($param['forma_envio'] == 'SEDEX 12') {
                $param_correio['servico'] = '03140';
            }
            if ($param['forma_envio'] == 'PACMINI') {
                $param_correio['servico'] = '04227';
            }
            if ($param['forma_envio'] == 'SEDEX HOJE') {
                $param_correio['servico'] = '03662';
            }
        }

        if (isset($param['cal_industrial']) && $param['cal_industrial']) {
            if ($param['forma_envio'] == 'PAC') {
                $param_correio['servico'] = '03336';
            }
            if ($param['forma_envio'] == 'SEDEX') {
                $param_correio['servico'] = '03280';
            }
            if ($param['forma_envio'] == 'PACMINI') {
                $param_correio['servico'] = '04391';
            }
            $param_correio['cal_industrial'] = true;
        }

        $valid_sed_user_id = ( isset($param['user_id']) ? $param['user_id'] : ( isset($this->session->user_id) ? $this->session->user_id : null ) );

        // Retirando usuário de validação sobre limite de seguro envio mini
        if ($valid_sed_user_id == '28487' || $valid_sed_user_id == '61673') {
            if ((float) $param['seguro'] > 100) {
                $param['seguro'] = 100;
            }
        } else {
            // Validacoes do Seguro PAC mini
            if ($param['forma_envio'] == 'PACMINI') {
                if ((float) $param['seguro'] > 100) {
                    $this->error = 'Em formas de envio "Envio Mini" o Seguro informado não pode ser maior que R$ 100.';
                    return false;
                }
            }
        }

        // Validacoes Seguro para Sedex e PAC
        if ($param['forma_envio'] == 'SEDEX' || $param['forma_envio'] == 'PAC') {
            if ($param['forma_envio'] == 'PAC') {
                if (isset($param['seguro']) && (float) $param['seguro'] > 3000) {
                    $this->error = 'Em formas de envio "PAC" o Seguro informado não pode ser maior que R$ 3000.';
                    return false;
                }
            } else {
                if (isset($param['seguro']) && (float) $param['seguro'] > 10000) {
                    $this->error = 'Em formas de envio "SEDEX" o Seguro informado não pode ser maior que R$ 10000.';
                    return false;
                }
            }
        }

        // 0.00 - Decimal em pontos
        if (isset($param['seguro'])) {
            $param_correio['seguro'] = $param['seguro'];
        }

        // AR - Default null
        if (isset($param['AR']) && $param['AR'] === true) {
            $param_correio['AR'] = true;
        }

        if (isset($param['is_industrial']) && $param['is_industrial']) {
            $param_correio['is_industrial'] = true;
        }

        if (isset($param['cal_seguro_local']) && $param['cal_seguro_local'] === true) {
            $param_correio['cal_seguro_local'] = true;
        }

        if (true) {
            $param_correio['cal_seguro_local'] = true;
        }

        $frete = $this->CalPrazoFrete->calc($param_correio);

        // ... Código posterior ...

        if (!$frete) {
            $this->error = $this->CalPrazoFrete->get_error();
            if (!$this->CalPrazoFrete->get_error()) {
                $this->error = "Erro, por favor, contate o suporte!";
            }
            return false;
        }

        if (isset($param['normalize_currency']) && $param['normalize_currency']) {
            $frete['valor'] = preg_replace('/,/', '.', $frete['valor']);
        }

        if ($this->error) {
            return false;
        }

        if ((float) preg_replace('/,/', '.', $frete['valor']) < 6) {
            $this->error = "Sistema dos Correios não respondeu à nossa Requisição, por gentileza tente novamente mais tarde (VL).";
            return false;
        }

        return $frete;
    }

    public function saveEnvio($post)
    {
        $this->error = '';
        $userModel = app(User::class);

        $post['CEP'] = preg_replace('/[^0-9]/', '', $post['CEP']);
        $error_description = [];
        $error_description_cubagem = [];

        // Se a soma for maior que 90, trocar o peso do pedido pelo peso cúbico
        if (in_array( $post['user_id'], ['69865', '75477', '43114', '72533', '14046', '16152', '76039', '76804'] )) {
            $sum = (int) $post['altura'] + (int) $post['comprimento'] + (int) $post['largura'];
            $medidas = (int) $post['altura'] * (int) $post['comprimento'] * (int) $post['largura'];
            $peso_cubico = $medidas / 6000;

            if (($sum > 90) && ($peso_cubico > $post['peso']) && ($peso_cubico > 5)) {
                $emailCubagem['pesoInicial'] = $post['peso'];
                $emailCubagem['pesoCubico'] = $peso_cubico;
                $emailCubagem['somaMedidas'] = $medidas;
                $emailCubagem['altura'] = $post['altura'];
                $emailCubagem['largura'] = $post['largura'];
                $emailCubagem['comprimento'] = $post['comprimento'];
                $emailCubagem['userId'] = $post['user_id'];
                $emailCubagem['destinatario'] = $post['destinatario'];
                $emailCubagem['formaEnvio'] = $post['forma_envio'];
                $emailCubagem['endereco'] = $post['CEP'] . ' - ' . $post['logradouro'] . ' - ' . $post['bairro'] . ' - ' . $post['cidade'] . ' - ' . $post['estado'];

                app()->make('email_maker')->msg([
                    'to' => 'reginaldo@mandabem.com.br,clayton@mandabem.com.br,wieder@mandabem.com.br',
                    'subject' => 'Calculo Cubico User ' . $post['user_id'],
                    'msg' => "<pre>" . print_r($emailCubagem, true) . "</pre>"
                ]);

                $post['peso'] = $peso_cubico;
                $error_description_cubagem[] = '<br>' . "Devido à soma das medidas do produto ultrapassarem os 90 CM, o peso será calculado através do cálculo de cubagem.";
            }
        }

        if ($post['forma_envio'] == 'PACMINI') { 
            
            if (!isset($post['peso'])) {
                $post['peso'] = 0.3;
            } else {
                if ($post['peso'] > 0.3) {
                    $this->error = "Para Envio Mini o peso da encomenda não pode passar de 300 gramas";
                    return false;
                }
            }
        }

        if ($post['forma_envio'] == 'SEDEX HOJE' && strlen($post['telefone']) < 10) {
            $this->error = "Para envios do tipo SEDEX HOJE preencha o telefone do Destinatário";
            return false;
        }

        // Somente números no telefone
        if (isset($post['telefone']) && strlen($post['telefone'])) {
            $post['telefone'] = preg_replace('/[^0-9]/', '', $post['telefone']);
        }

        // Verificando se envios já foram gerados pelo ID
        if (isset($post['ref_id']) && strlen(trim($post['ref_id'])) && isset($post['api']) && in_array($post['api'], ['NUVEM_SHOP', 'LINX', 'BLING', 'SHOPIFY', 'YAMPI', 'WEBSTORE', 'FASTCOMMERCE', 'TINY', 'LOJA_INTEGRADA', 'WORDPRESS']) && $post['user_id'] != 'xx5') {

            $query = DB::table('envios')
                ->where('user_id', $post['user_id'])
                ->where(function ($query) use ($post) {
                    $query->where('ref_id', $post['ref_id']);
                    if ($post['user_id'] == '8789') {
                        $query->orWhere('ref_id_api_source', $post['ref_id_api_source']);
                    }
                })
                ->whereIn('integration', [
                    'NuvemShop', 'Tiny', 'Yampi', 'Webstore', 'Shopify',
                    'Fastcommerce', 'LojaIntegrada', 'Bling', 'Linx', 'Wordpress'
                ])
                ->get();

            $_envs = $query->toArray();

            if ($_envs) {
                foreach ($_envs as $ev) {
                    $has_cancel = DB::table('envios_cancelamento')
                        ->where('envio_id', $ev->id)
                        ->first();

                    if (!$has_cancel) {
                        $this->error = "Envio " . $post['ref_id'] . " já gerado";
                        return false;
                    }
                }
            }
        }

        // Formatação do retorno do erro
        $type_error_return = 'NORMAL';
        if (isset($post['type_error_return'])) {
            $type_error_return = $post['type_error_return'];
        }
        $data_post = $this->form_builder->validade_data($this->fields, $post, $type_error_return);

        // Caso for NUVEM SHOP salvar envio com erro + alerta
        if (!$data_post) {
            if (isset($post['api']) && in_array($post['api'], ['NUVEM_SHOP', 'BLING', 'LINX', 'YAMPI', 'SHOPIFY', 'WEBSTORE', 'FASTCOMMERCE', 'LOJA_INTEGRADA', 'WORDPRESS', 'TINY'])) {
                $error_description = $this->form_builder->get_error_validation();
                $data_post = $this->form_builder->get_data_post();
            } else {
                $this->error = $this->form_builder->get_error_validation();
                return false;
            }
        }

        // Neste caso é uma situação apenas de validação do envio, se as infos estão totalmente preenchidas
        if (isset($post['only_validate']) && $post['only_validate']) {
            return true;
        }

        $user = $userModel->get($post['user_id']);

        if (!$user) {
            $this->error = "Falha ao obter usuário";
            return false;
        }

        // Validação da Nota Fiscal
        $data_post['nota_fiscal'] = trim($data_post['nota_fiscal']);
        if (strlen($data_post['nota_fiscal']) && (strlen($data_post['nota_fiscal']) < 3 || strlen($data_post['nota_fiscal']) > 15)) {
            if (isset($post['api']) && in_array($post['api'], ['NUVEM_SHOP', 'BLING', 'LINX', 'YAMPI', 'SHOPIFY', 'WEBSTORE', 'FASTCOMMERCE', 'LOJA_INTEGRADA', 'WORDPRESS', 'TINY'])) {
                $error_description[] = '<br>' . "O campo Nota fiscal, quando preenchido, deve ter entre 3 e 15 caracteres.";
            } else {
                $this->error = "O campo Nota fiscal, quando preenchido, deve ter entre 3 e 15 caracteres.";
                return false;
            }
        }

        // Assumindo default para o campo peso
        if (!isset($data_post['peso']) || !$data_post['peso']) {
            $data_post['peso'] = 0.300;
        }
        // Assumindo default para o campo AR
        if (!isset($data_post['AR']) || $data_post['AR'] != 'S') {
            $data_post['AR'] = null;
        }

        // Caso peso for maior que 0.3 vamos arredondar para o próximo inteiro maior
        if ($data_post['peso'] > 0.3) {
            $data_post['peso'] = ceil($data_post['peso']);
        }

        $data_post['altura'] = ceil($data_post['altura']);
        $data_post['comprimento'] = ceil($data_post['comprimento']);
        $data_post['largura'] = ceil($data_post['largura']);

        // Validacoes do PAC mini
        if ($data_post['forma_envio'] == 'PACMINI') {
            if (isset($data_post['AR']) && $data_post['AR'] == 'S') {
                $this->error = 'Nas formas de envio "Envio Mini" não é permitido informar AR.';
                return false;
            }
            if ($data_post['peso'] > 0.3) {
                $this->error = 'Nas formas de envio "Envio Mini" o peso não pode passar de 300 gramas.';
                return false;
            }
            if ($data_post['altura'] > 4) {
                $this->error = 'Nas formas de envio "Envio Mini" a Altura não pode passar de 4 Cm.';
                return false;
            }
            if ($data_post['largura'] > 16) {
                $this->error = 'Nas formas de envio "Envio Mini" a Largura não pode passar de 16 Cm.';
                return false;
            }
            if ($data_post['comprimento'] > 24) {
                $this->error = 'Nas formas de envio "Envio Mini" o Comprimento não pode passar de 24 Cm.';
                return false;
            }
        }

        // Soma das dimensões não pode passar de 200 CM
        $sum_size = (int) $data_post['altura'] + (int) $data_post['comprimento'] + (int) $data_post['largura'];
        if ($sum_size > 200) {
            if (isset($post['api']) && in_array($post['api'], ['NUVEM_SHOP', 'BLING', 'LINX', 'YAMPI', 'SHOPIFY', 'WEBSTORE', 'FASTCOMMERCE', 'LOJA_INTEGRADA', 'WORDPRESS', 'TINY'])) {
                $error_description[] = '<br>' . "Somatório (altura + largura + comprimento) acima de 200 cm.<br>Somatório deve ser menor do que 200 cm.";
            } else {
                $this->error = "Somatório (altura + largura + comprimento) acima de 200 cm.<br>Somatório deve ser menor do que 200 cm.";
                return false;
            }
        }
        if (strlen(preg_replace('/[^0-9]/', '', $data_post['CEP'])) != 8) {
            if (isset($post['api']) && in_array($post['api'], ['NUVEM_SHOP', 'BLING', 'LINX', 'YAMPI', 'SHOPIFY', 'WEBSTORE', 'FASTCOMMERCE', 'LOJA_INTEGRADA', 'WORDPRESS', 'TINY'])) {
                $error_description[] = '<br>' . "CEP (" . $data_post['CEP'] . ") destino inválido";
            } else {
                $this->error = "CEP (" . $data_post['CEP'] . ") destino inválido";
                return false;
            }
        }

        // Parâmetros para consulta do VALOR Correios
        $param_consulta = [
            'peso' => $data_post['peso'],
            'cep_destino' => $data_post['CEP'],
            'forma_envio' => $data_post['forma_envio'],
            'altura' => $data_post['altura'],
            'comprimento' => $data_post['comprimento'],
            'largura' => $data_post['largura'],
        ];

        // Adicionando Seguro aos parâmetros de consulta
        if ($data_post['seguro'] && $data_post['seguro'] > 0) {
            $data_post['seguro'] = preg_replace('/,/', '.', $data_post['seguro']);
            $param_consulta['seguro'] = $data_post['seguro'];
        } else {
            $data_post['seguro'] = null;
        }

        // Validacoes do Seguro PAC mini
        if ($data_post['forma_envio'] == 'PACMINI') {
            if ((float) $data_post['seguro'] > 100) {
                $this->error = 'Em formas de envio "Envio Mini" o Seguro informado não pode ser maior que R$ 100.';
                return false;
            }
            if ($data_post['seguro'] && (float) $data_post['seguro'] < 12.25) {
                $this->error = 'O Seguro, para "Envio Mini", quando informado, precisa ser maior que R$ 12,25.';
                return false;
            }
        }

        // Validacoes Seguro para Sedex e PAC
        if ($data_post['forma_envio'] == 'SEDEX' || $data_post['forma_envio'] == 'PAC') {
            if ($data_post['seguro'] && (float) $data_post['seguro'] < 24.50) {
                $this->error = 'O Seguro, para "PAC" e "SEDEX", quando informado, precisa ser maior que R$ 24,50.';
                return false;
            }

            if ($data_post['forma_envio'] == 'PAC') {
                if ((float) $data_post['seguro'] > 3000) {
                    $this->error = 'Em formas de envio "PAC" o Seguro informado não pode ser maior que R$ 3000.';
                    return false;
                }
            } else {
                if ((float) $data_post['seguro'] > 10000) {
                    $this->error = 'Em formas de envio "SEDEX" o Seguro informado não pode ser maior que R$ 10000.';
                    return false;
                }
            }
        }

        // CEP vem da API | Plugin | Webservice
        $info_remetente = false;
        if (isset($post['cep_origem'])) {
            $post['cep_origem'] = preg_replace('/[^0-9]/', '', $post['cep_origem']);
            $info_remetente = app('App\Http\Controllers\UserController')->getUserRemetenteByCep($post['cep_origem'], $user->id);
            if (!$info_remetente) {
                $this->error = "Falha ao obter Endereço do Remetente (CEP: " . $post['cep_origem'] . " não cadastrado no perfil Manda Bem), Contate suporte.";
                return false;
            }
            $data_post['user_remetente_id'] = $info_remetente->id;

            $param_consulta['cep_origem'] = $post['cep_origem'];
        } else {
            // CEP vem de Multi-remetente para ENVIO NORMAL e REVERSA
            if (isset($post['remetente_id']) && (int) $post['remetente_id']) {
                $info_remetente = app('App\Http\Controllers\UserController')->getUserRemetente($post['remetente_id'], $user->id);
                if (!$info_remetente) {
                    $this->error = "Falha ao obter Endereço do Remetente, Contate suporte.";
                    return false;
                }
                $data_post['user_remetente_id'] = $info_remetente->id;
                $param_consulta['cep_origem'] = $info_remetente->cep;
            }
            // CEP do cadastro
            else {
                $data_post['user_remetente_id'] = null;
                $param_consulta['cep_origem'] = $user->CEP;
            }
        }
        if (isset($data_post['AR']) && $data_post['AR'] == 'S') {
            $param_consulta['AR'] = true;
        }

        // Ajuste industrial
        if (isset($post['tipo_contrato']) && $post['tipo_contrato'] == 'industrial' && $data_post['type'] == 'NORMAL') {
            $param_consulta['is_industrial'] = true;
        }

        // Config
        $user_config = $userModel->getConfig($user);

        // Setando Industrial para o usuário
        if ((isset($user_config['config_enable_industrial']) && (int) $user_config['config_enable_industrial']) || ($user->id == 6727 || $user->id == 16947 || $user->id == 62885)) {
            $param_consulta['is_industrial'] = true;
        }

        if (request()->server('REMOTE_ADDR') == '177.25.215.226') {
            print_r($param_consulta);
            exit;
        }

        // Mudar consulta REVERSA
        if ($data_post['type'] == 'NORMAL') {
            $data_post['CEP_origem'] = $param_consulta['cep_origem'];
        } else {
            // Reversa Destino é a Origem
            $param_consulta['cep_destino'] = $param_consulta['cep_origem'];
            // A origem se torna Destino, para cadastro e consulta | $data_post['CEP'] vem do formulário
            $data_post['CEP_origem'] = $param_consulta['cep_origem'] = $data_post['CEP'];
            // Invertendo para salvar o CEP como CEP Destino | CEP em tb envios será sempre o Destino
            $data_post['CEP'] = $param_consulta['cep_destino'];
        }

        // VERIFICAR DISPONIBILIDADE DO SERVIÇO PARA ESTAS MODALIDADES
        if ($param_consulta['forma_envio'] == 'SEDEX HOJE' || $param_consulta['forma_envio'] == 'SEDEX 10' || $param_consulta['forma_envio'] == 'SEDEX 12') {
            $param_consulta['is_industrial'] = false;

            $correio = app(Correio::class);
            $is_servico_disponivel = $correio->verificaDispServicos([
                'cep_origem' => $param_consulta['cep_origem'],
                'cep_destino' => $param_consulta['cep_destino'],
                'forma_envio' => $param_consulta['forma_envio'],
            ]);

            if ($is_servico_disponivel != 'ok') {
                $this->error = "CEP de origem não pode postar para CEP destino utilizando a modalidade " . $param_consulta['forma_envio'];
                return false;
            }
        }

    
        // para PAC mini vamos simular o valor do PAC comum para métrica de Balcão
        if ($param_consulta['forma_envio'] == 'PACMINI') {
            $param_consulta['forma_envio'] = 'PAC';
            $return = $this->simule_envio($param_consulta);

            if (!$return) {
                return false;
            }
            $valor_balcao = preg_replace('/,/', '.', $return['valor']);

            $param_consulta['cod_empresa'] = '18086160';
            $param_consulta['senha_empresa'] = '27347642';
            $param_consulta['forma_envio'] = 'PACMINI';
            $return_contrato = $this->simule_envio($param_consulta);

            if (!$return_contrato) {
                return false;
            }
        } else {
            $return = $this->simule_envio($param_consulta);

            if (!$return) {
                return false;
            }
            $valor_balcao = preg_replace('/,/', '.', $return['valor']);

            $param_consulta['cod_empresa'] = '18086160';
            $param_consulta['senha_empresa'] = '27347642';
            $return_contrato = $this->simule_envio($param_consulta);

            if (!$return_contrato) {
                return false;
            }
        }

        $valor_contrato = preg_replace('/,/', '.', $return_contrato['valor']);
        if (isset($param_consulta['is_industrial']) && ($param_consulta['is_industrial']) && ($post['type'] != 'REVERSA')) {
            $taxa_mandabem = 0; 
            if (true) {
                $param_consulta['cal_industrial'] = true;
                $return_industrial = $this->simule_envio($param_consulta);
                $data_post['valor_industrial'] = preg_replace('/,/', '.', $return_industrial['valor']);
            }
        } else {
            $taxa_mandabem = app('App\Http\Controllers\YourController')->getTaxaEnvio([
                'valor_envio' => $valor_contrato,
                'forma_envio' => $data_post['forma_envio'],
                'grupo_taxa_pacmini' => $user->grupo_taxa_pacmini
            ]);
        }

        $data_post['taxa_mandabem'] = $taxa_mandabem;

        $data_post['type'] = strtoupper($post['type']);
        $data_post['date_insert'] = now();
        $data_post['date_update'] = now();

        $data_post['prazo'] = $return_contrato['prazo'];
        $data_post['valor_balcao'] = $valor_balcao;
        $data_post['valor_contrato'] = $valor_contrato;
        if ($valor_contrato == 9.73) {
            $valor_contrato = 9.60;
            $data_post['valor_total'] = number_format($valor_contrato, 2, '.', '');
        } else {
            $data_post['valor_total'] = number_format($valor_contrato + $taxa_mandabem, 2, '.', '');
        }
        $data_post['valor_desconto'] = $valor_balcao - $data_post['valor_total'];

        // Ajuste desconto quando frete for muito próximo do valor balcão
        if (false && $data_post['valor_total'] >= $data_post['valor_balcao']) {
            $old_valor_total = $data_post['valor_total'];

            $desc = ($data_post['valor_balcao'] * 0.10);

            // Limitando o desconto a 10 reais
            if ($desc > 10) {
                $desc = 10;
            }

            $data_post['valor_total'] = $data_post['valor_balcao'] - $desc;
            $data_post['valor_desconto_frete'] = $old_valor_total - $data_post['valor_total'];
            // Atualizando desconto
            $data_post['valor_desconto'] = $valor_balcao - $data_post['valor_total'];
        } else {
            $old_valor_total = $data_post['valor_total'];
            if ($data_post['valor_total'] > 100) {
                if (($data_post['valor_balcao'] - $data_post['valor_total']) <= 10) {
                    $data_post['valor_total'] = $data_post['valor_total'] - ($data_post['valor_total'] * 0.10);
                    $data_post['valor_desconto_frete'] = $old_valor_total - $data_post['valor_total'];
                    // Atualizando desconto
                    $data_post['valor_desconto'] = $valor_balcao - $data_post['valor_total'];
                }
            } else {
                if (($data_post['valor_balcao'] - $data_post['valor_total']) <= 2) {
                    $data_post['valor_total'] = $data_post['valor_total'] - ($data_post['valor_total'] * 0.10);
                    $data_post['valor_desconto_frete'] = $old_valor_total - $data_post['valor_total'];
                    // Atualizando desconto
                    $data_post['valor_desconto'] = $valor_balcao - $data_post['valor_total'];
                }
            }
        }
    
        if (isset($post['ref_id']) && strlen(trim($post['ref_id']))) {
            // || $post['user_id'] == '5'
            if ($post['user_id'] == '426' || $post['user_id'] == '873') {
                $has_pedido = DB::table('envios')
                    ->where('user_id', $post['user_id'])
                    ->where('ref_id', (int) $post['ref_id'])
                    ->first();
        
                if ($has_pedido) {
                    $this->error = "Pedido de numero " . (int) $post['ref_id'] . " já possui envio gerado.";
                    return false;
                }
            }
        
            $data_post['ref_id'] = $post['ref_id'];
        }
        
        if (isset($post['api']) && $post['api'] == 'NUVEM_SHOP') {
            $data_post['integration'] = 'NuvemShop';
            $data_post['ref_id_api_source'] = $post['ref_id_api_source'] ?? null;
        }
        if (isset($post['api']) && $post['api'] == 'BLING') {
            $data_post['integration'] = 'Bling';
        }
        if (isset($post['api']) && $post['api'] == 'LINX') {
            $data_post['integration'] = 'Linx';
            $data_post['ref_id_api_source'] = $post['ref_id_api_source'] ?? null;
        }
        if (isset($post['api']) && $post['api'] == 'LOJA_INTEGRADA') {
            $data_post['integration'] = 'LojaIntegrada';
            $data_post['ref_id_api_source'] = $post['ref_id_api_source'] ?? null;
        }
        if (isset($post['api']) && $post['api'] == 'WORDPRESS') {
            $data_post['integration'] = 'Wordpress';
        }
        if (isset($post['api']) && $post['api'] == 'TINY') {
            $data_post['integration'] = 'Tiny';
            $data_post['ref_id_api_source'] = $post['ref_id_api_source'] ?? null;
        }
        if (isset($post['api']) && $post['api'] == 'YAMPI') {
            $data_post['integration'] = 'Yampi';
            $data_post['ref_id_api_source'] = $post['ref_id_api_source'] ?? null;
        }
        if (isset($post['api']) && $post['api'] == 'WEBSTORE') {
            $data_post['integration'] = 'Webstore';
            $data_post['ref_id_api_source'] = $post['ref_id_api_source'] ?? null;
        }
        if (isset($post['api']) && $post['api'] == 'SHOPIFY') {
            $data_post['integration'] = 'Shopify';
            $data_post['ref_id_api_source'] = $post['ref_id_api_source'] ?? null;
        }
        if (isset($post['api']) && $post['api'] == 'FASTCOMMERCE') {
            $data_post['integration'] = 'Fastcommerce';
            $data_post['ref_id_api_source'] = $post['ref_id_api_source'] ?? null;
        }
        
        if($post['integration']){
            $data_post['integration'] = $post['integration'];
        }
        
        if ($post['integration']) {
            $data_post['integration'] = $post['integration'];
        }
        
        $data_post['user_id'] = $post['user_id'];
        
        if (isset($post['cpf_destinatario']) && strlen($post['cpf_destinatario']) == 11) {
            $data_post['cpf_destinatario'] = $post['cpf_destinatario'];
        }
        
        if ($error_description) {
            $data_post['error_description'] = implode("\n -", $error_description);
            $data_post['validado'] = 0;
        } else {
            $data_post['error_description'] = '';
            $data_post['validado'] = 1;
        }
        
        if ($error_description_cubagem[0]) {
            $data_post['error_description'] = $error_description_cubagem[0];
            $data_post['validado'] = 1;
        }
        
        // Ajuste para troca de nome var "numero", navegador bloqueando
        if (isset($data_post['numero_endereco'])) {
            $data_post['numero'] = $data_post['numero_endereco'];
            unset($data_post['numero_endereco']);
        }
        
        // Ajuste º
        $data_post['numero'] = preg_replace('/º|\'|"/', '', $data_post['numero']);
        
        if (isset($param_consulta['is_industrial']) && $param_consulta['is_industrial']) {
            $data_post['etiqueta_correios'] = 'industrial';
        }
        
        if ($post['correio_amigo']) {
            $data_post['remetente_amigo'] = $post['correio_amigo'];
        }
    
        if (isset($post['id']) && (int) $post['id']) {
            $alter_env = DB::table('envios')->where('id', $post['id'])->first();
        
            DB::table('envios')
                ->where('id', $post['id'])
                ->where('user_id', $post['user_id'])
                ->update($data_post);
        
            $alteracoes = [
                'id_user_alt' => auth()->user()->id,
                'type' => 'EDICAO_ENVIO',
                'id_table' => $post['id'],
                'data_before' => json_encode($alter_env),
                'data_after' => json_encode($data_post),
                'date_insert' => now(),
            ];
        
            DB::table('alt_registros_log')->insert($alteracoes);
        } else {
            $exec = DB::table('envios')->insert($data_post);
        
            if (!$exec) {
                abort(500, "Falha ao salvar Envio, tente novamente mais tarde");
            }
        }
        
        $envio_id = isset($post['id']) && (int) $post['id'] ? $post['id'] : DB::table('envios')->insertGetId($data_post);
        
        // Salvando endereco remetente 
        if (true) {
            $address_origin = DB::table('envio_origem')->where('envio_id', $envio_id)->first();
        
            if (true) {
                if ($info_remetente) {
                    $data_envio_origem = [
                        'nome' => $info_remetente->nome,
                        'cep' => $info_remetente->cep,
                        'logradouro' => $info_remetente->logradouro,
                        'numero' => $info_remetente->numero,
                        'complemento' => $info_remetente->complemento,
                        'bairro' => $info_remetente->bairro,
                        'cidade' => $info_remetente->cidade,
                        'uf' => $info_remetente->uf,
                        'date_update' => now(),
                    ];
                } else {
                    $data_envio_origem = [
                        'nome' => $user->name,
                        'cep' => $user->CEP,
                        'logradouro' => $user->logradouro,
                        'numero' => $user->numero,
                        'complemento' => $user->complemento,
                        'bairro' => $user->bairro,
                        'cidade' => $user->cidade,
                        'uf' => $user->uf,
                        'date_update' => now(),
                    ];
                }
            }
        
            if ($address_origin) {
                // Atualizar registro existente
                DB::table('envio_origem')
                    ->where('id', $address_origin->id)
                    ->where('envio_id', $envio_id)
                    ->update($data_envio_origem);
            } else {
                // Inserir novo registro
                $data_envio_origem['date_insert'] = now();
                $data_envio_origem['envio_id'] = $envio_id;
                
                DB::table('envio_origem')->insert($data_envio_origem);
            }
        }
        
        return $envio_id;

    }
    
    public function getTaxaEnvio($param = [])
    {
        $taxaMandabem = 0;

        $valorEnvio = $param['valor_envio'] ?? 0;

        if (isset($param['id_industrial'])) {
            $idGrupoIndustrial = DB::selectOne("SELECT grupo_taxa FROM user WHERE id = ?", [$param['id_industrial']]);
            
            if ($idGrupoIndustrial) {
                $tabela = DB::selectOne("SELECT * FROM grupo_taxa WHERE id = ? AND tabela = ?", [$idGrupoIndustrial->grupo_taxa, 'industrial']);
                
                if ($tabela) {
                    $taxaMandabem = app('grupo_taxa_model')->getTaxa($idGrupoIndustrial, $valorEnvio, true);
                    return $taxaMandabem;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        }

        // Tratamento PAC MINI 
        if (isset($param['forma_envio']) && $param['forma_envio'] == 'PACMINI') {
            if (isset($param['grupo_taxa_pacmini']) && (int) $param['grupo_taxa_pacmini']) {
                $taxaMandabem = app('grupo_taxa_model')->getTaxa($param['grupo_taxa_pacmini'], $valorEnvio);
            } else {
                $param['grupo_taxa_pacmini'] = null;

                // Se houver usuário
                if ((int) auth()->id()) {
                    $user = app('user_model')->get(auth()->id());

                    if ((int) $user->grupo_taxa_pacmini) {
                        $param['grupo_taxa_pacmini'] = $user->grupo_taxa_pacmini;
                    }
                }

                // Grupo PAC MINI NORMAL caso não tenha associado
                if (!$param['grupo_taxa_pacmini']) {
                    $param['grupo_taxa_pacmini'] = 14;
                }

                $taxaMandabem = app('grupo_taxa_model')->getTaxa($param['grupo_taxa_pacmini'], $valorEnvio);
            }

            if (!$taxaMandabem) {
                app('email_maker')->msg([
                    'to' => 'reginaldo@mandabem.com.br',
                    'subject' => 'GRUPO TAXA PAC MINI SEM TAXA',
                    'msg' => "Param:<br><pre>" . print_r($param, true) . "</pre>"
                ]);

                return false;
            } else {
                return $taxaMandabem;
            }
        }

        // Métodos PAC e SEDEX a partir daqui
        // Se não houver taxa, pegamos o grupo do usuário logado
        if (!isset($param['grupo_taxa']) || !(int) $param['grupo_taxa']) {
            if ((int) auth()->id()) {
                $user = app('user_model')->get(auth()->id());

                if ((int) $user->grupo_taxa) {
                    $param['grupo_taxa'] = $user->grupo_taxa;
                }
            } else {
                $param['grupo_taxa'] = 5;
            }
        }

        if (isset($param['grupo_taxa']) && (int) $param['grupo_taxa']) {
            $taxaMandabem = app('grupo_taxa_model')->getTaxa($param['grupo_taxa'], $valorEnvio);
        } else {
            if ($valorEnvio <= 14) {
                $taxaMandabem = 1.07;
            } elseif ($valorEnvio > 14 && $valorEnvio <= 20) {
                $taxaMandabem = 2.00;
            } elseif ($valorEnvio > 20 && $valorEnvio <= 30) {
                $taxaMandabem = 3.40;
            } elseif ($valorEnvio > 30 && $valorEnvio <= 40) {
                $taxaMandabem = 4.40;
            } elseif ($valorEnvio > 40 && $valorEnvio <= 50) {
                $taxaMandabem = 5.40;
            } elseif ($valorEnvio > 50 && $valorEnvio <= 60) {
                $taxaMandabem = 6.40;
            } elseif ($valorEnvio > 60 && $valorEnvio <= 70) {
                $taxaMandabem = 8.40;
            } elseif ($valorEnvio > 70 && $valorEnvio <= 80) {
                $taxaMandabem = 9.40;
            } elseif ($valorEnvio > 80 && $valorEnvio <= 90) {
                $taxaMandabem = 12.90;
            } elseif ($valorEnvio > 90 && $valorEnvio <= 100) {
                $taxaMandabem = 15.00;
            } elseif ($valorEnvio > 100 && $valorEnvio <= 110) {
                $taxaMandabem = 16.00;
            } elseif ($valorEnvio > 110 && $valorEnvio <= 120) {
                $taxaMandabem = 19.00;
            } elseif ($valorEnvio > 120 && $valorEnvio <= 130) {
                $taxaMandabem = 23.00;
            } elseif ($valorEnvio > 130 && $valorEnvio <= 140) {
                $taxaMandabem = 27.00;
            } elseif ($valorEnvio > 140 && $valorEnvio <= 150) {
                $taxaMandabem = 29.00;
            } elseif ($valorEnvio > 150) {
                $taxaMandabem = 32.00;
            }
        }

        return $taxaMandabem;
    }

    public function getByIds($param = [])
    {
        return DB::table('envios as a')
            ->whereIn('a.id', explode(',', $param['ids']))
            ->where('a.user_id', $param['user_id'])
            ->whereNull('a.coleta_id')
            ->get()
            ->all();
    }

    public function getByEtiqueta($etiqueta)
    {
        return DB::table('envios as a')
            ->select('a.*')
            ->where('a.etiqueta_correios', $etiqueta)
            ->first();
    }

    public function getWithNoCancel($param = [])
    {
        return DB::table('envios')
            ->select('envios.*')
            ->when(isset($param['ref_id_api']), function ($query) use ($param) {
                return $query->where('ref_id_api_source', $param['ref_id_api']);
            })
            ->when(isset($param['ref_id']), function ($query) use ($param) {
                return $query->where('ref_id', $param['ref_id']);
            })
            ->when(isset($param['integration']), function ($query) use ($param) {
                return $query->where('integration', $param['integration']);
            })
            ->where('envios.user_id', $param['user_id'])
            ->leftJoin('envios_cancelamento as ec', 'ec.envio_id', '=', 'envios.id')
            ->whereNull('ec.id')
            ->first();
    }

    public function get($param = [])
    {
        $envio = DB::table('envios as a')
            ->select('a.*')
            ->when(isset($param['ref_id_api']), function ($query) use ($param) {
                return $query->where('a.ref_id_api_source', $param['ref_id_api']);
            }, function ($query) use ($param) {
                return $query->when(isset($param['ref_id']), function ($query) use ($param) {
                    return $query->where('a.ref_id', $param['ref_id']);
                }, function ($query) use ($param) {
                    return $query->where('a.id', $param['id']);
                });
            })
            ->when($param['user_id'] != 'mandabem', function ($query) use ($param) {
                return $query->where('a.user_id', $param['user_id']);
            })
            ->when(isset($param['type']), function ($query) use ($param) {
                return $query->where('a.type', $param['type']);
            })
            ->first();

        if (!$envio) {
            return $envio;
        }

        $envio->cancelamento = DB::table('envios_cancelamento')
            ->where('envio_id', $envio->id)
            ->first();

        return $envio;
    }

    public function hasEnvio($param = [])
    {
        return DB::table('envios')
            ->where('user_id', $param['user_id'])
            ->when(isset($param['ref_id']), function ($query) use ($param) {
                return $query->where('ref_id', $param['ref_id']);
            })
            ->first();
    }

    public function getEnvios($param)
    {
        return DB::table('envios')
            ->select('envios.*', 'coletas.date_insert as date_geracao', 'ev.descricao as status_obj', 'ec.id as cancel_id', 'ec.status as cancel_status')
            ->when(isset($param['only_postados']) && $param['only_postados'], function ($query) {
                return $query->whereNotNull('envios.valor_correios')->whereNotNull('envios.coleta_id');
            })
            ->where(function ($query) {
                $query->whereRaw('coletas.status NOT LIKE "ERROR"')
                    ->orWhereNull('coletas.status');
            })
            ->leftJoin('coletas', 'coletas.id', '=', 'envios.coleta_id')
            ->leftJoin('etiqueta_status as es', 'es.envio_id', '=', 'envios.id')
            ->leftJoin('etiqueta_events as ev', 'ev.id', '=', 'es.etiqueta_event_id')
            ->leftJoin('envios_cancelamento as ec', 'ec.envio_id', '=', 'envios.id')
            ->when(isset($param['date_from']), function ($query) use ($param) {
                return $query->where('coletas.date_insert', '>=', $param['date_from'] . ' 00:00:00');
            })
            ->when(isset($param['date_to']), function ($query) use ($param) {
                return $query->where('coletas.date_insert', '<=', $param['date_to'] . ' 23:59:59');
            })
            ->where('envios.user_id', $param['user_id'])
            ->when(isset($param['only_postados']) && $param['only_postados'], function ($query) {
                return $query->whereNotNull('envios.date_postagem');
            })
            ->whereNotNull('envios.etiqueta_correios')
            ->get()
            ->all();
    }

    public function getList($param = [])
    {
        $query = DB::table('envios as a')
            ->select('a.*', 'b.nome as other_remetente_name')
            ->leftJoin('user_remetente as b', 'b.id', '=', 'a.user_remetente_id')
            ->where('a.user_id', $param['user_id']);

        if (isset($param['type'])) {
            $query->where('a.type', $param['type']);
        }

        if (isset($param['date_from'])) {
            $query->where('a.date_postagem', '>=', $param['date_from'] . ' 00:00:00');
        }

        if (isset($param['date_to'])) {
            $query->where('a.date_postagem', '<=', $param['date_to'] . ' 23:59:59');
        }

        if (isset($param['only_postados']) && $param['only_postados']) {
            $query->whereNotNull('a.valor_correios')->whereNotNull('a.coleta_id');
        } else {
            $query->whereNull('a.coleta_id');
        }

        if (isset($param['order'])) {
            switch ($param['order']) {
                case 'data_geracao':
                    $query->orderBy('a.date_insert');
                    break;
                case 'numero_pedido':
                    $query->orderBy('a.ref_id');
                    break;
                case 'forma_envio':
                    $query->orderBy('a.forma_envio');
                    break;
                case 'valor_total':
                    $query->orderBy('a.valor_total');
                    break;
            }
        } else {
            $query->orderBy('a.date_insert');
        }

        $list = $query->get();

        // Correção, apagando envio duplicado
        if ($param['user_id'] == '6528xx') {
            $tmp_verify = [];
            foreach ($list as $i) {
                if (trim(strlen($i->ref_id))) {
                    if (isset($tmp_verify[$i->ref_id])) {
                        DB::table('envios')
                            ->where('id', $i->id)
                            ->where('ref_id', $i->ref_id)
                            ->where('user_id', $param['user_id'])
                            ->whereNull('coleta_id')
                            ->where('integration', $tmp_verify[$i->ref_id]->integration)
                            ->delete();
                    } else {
                        $tmp_verify[$i->ref_id] = $i;
                    }
                }
            }
        }

        $user = app(User::class)->get($param['user_id']);
        $userConfig = app(User::class)->getConfig($user);

        if (isset($userConfig['config_enable_industrial']) && (int) $userConfig['config_enable_industrial']) {
            foreach ($list as $i) {
                if ($i->etiqueta_correios != 'industrial') {
                    DB::table('envios')
                        ->where('id', $i->id)
                        ->where('user_id', $param['user_id'])
                        ->whereNull('coleta_id')
                        ->update(['etiqueta_correios' => 'industrial']);
                }
            }
        }

        return $list;
    }

    public function getDivergencias($param = [])
    {
        $info = [];

        $list = DB::table('envios')
            ->select('envios.*')
            ->where('user_id', $param['user_id'])
            ->where('valor_divergente', '>', 0)
            ->whereNull('payment_divergente_id')
            ->get();

        // Retorna total acumulativo
        if (isset($param['return_total']) && $param['return_total']) {
            $valorTotal = 0;

            foreach ($list as $i) {
                $valorTotal += $i->valor_divergente;
            }

            return $valorTotal;
        }

        // Retorna vazio caso sem divergência
        if (count($list) === 0) {
            return [];
        }

        foreach ($list as $i) {
            $info[] = [
                'id' => $i->id,
                'coleta_id' => $i->coleta_id,
                'cep' => $i->CEP,
                'valor_divergente' => $i->valor_divergente,
            ];
        }

        return $info;
    }

    public function updateColetaId($coletaId, $envioId)
    {
        return DB::table('envios')
            ->whereNull('coleta_id')
            ->where('id', $envioId)
            ->update(['coleta_id' => $coletaId]);
    }

    public function updateInfoPagtoDivergencia($infoPayment, $infoEnvio = [])
    {
        foreach ($infoEnvio as $envio) {
            DB::table('envios')
                ->where('id', $envio['id'])
                ->whereNull('payment_divergente_id')
                ->update(['payment_divergente_id' => $infoPayment['id']]);
        }
    }

    public function delete($param = [])
    {
        $_envioId = DB::table('envios')
            ->where('user_id', $param['user_id'])
            ->where('id', $param['id'])
            ->whereNull('coleta_id')
            ->first();

        $del = DB::table('envios')
            ->whereNull('coleta_id')
            ->where('id', $param['id'])
            ->where('user_id', $param['user_id'])
            ->delete();

        if ($del && $_envioId) {
            DB::table('envio_origem')
                ->where('envio_id', $_envioId->id)
                ->delete();
        }

        return $del;
    }

    public function getPesos($key = null)
    {
        $user_id = session('user_id');

        if (isset($user_id) && $user_id == 5) {
            $pesos = [
                '0.300' => 'Até 300g',
                '0.500' => 'De 300g a 500g',
                '1' => 'De 500g a 1Kg',
                '2' => 'De 1Kg a 2Kg',
                '3' => 'De 2Kg a 3Kg',
                '4' => 'De 3Kg a 4Kg',
                '5' => 'De 4Kg a 5Kg',
                '6' => 'De 5Kg a 6Kg',
                '7' => 'De 6Kg a 7Kg',
                '8' => 'De 7Kg a 8Kg',
                '9' => 'De 8Kg a 9Kg',
                '10' => 'De 9Kg a 10Kg',
                '11' => 'De 10Kg a 11Kg',
                '12' => 'De 11Kg a 12Kg',
                '13' => 'De 12Kg a 13Kg',
                '14' => 'De 13Kg a 14Kg',
                '15' => 'De 14Kg a 15Kg',
                '16' => 'De 15Kg a 16Kg'
            ];

            // Substituído por função Loop
            if (true) {
                $pesos = [];
                $pesos['0.300'] = 'Até 300g';
                $pesos['0.500'] = 'De 300g a 500g';

                for ($x = 1; $x <= 30; $x++) {
                    $pesos[$x] = $x == 1 ? 'De 500g a 1Kg' : 'De ' . ($x - 1) . 'Kg a ' . $x . 'Kg';
                }
            }

            if ($key && isset($pesos[$key])) {
                return $pesos[$key];
            }

            return $pesos;
        } else {
            $pesos = [
                '0.300' => 'Até 300g',
                '1' => 'De 300g a 1Kg',
                '2' => 'De 1Kg a 2Kg',
                '3' => 'De 2Kg a 3Kg',
                '4' => 'De 3Kg a 4Kg',
                '5' => 'De 4Kg a 5Kg',
                '6' => 'De 5Kg a 6Kg',
                '7' => 'De 6Kg a 7Kg',
                '8' => 'De 7Kg a 8Kg',
                '9' => 'De 8Kg a 9Kg',
                '10' => 'De 9Kg a 10Kg',
                '11' => 'De 10Kg a 11Kg',
                '12' => 'De 11Kg a 12Kg',
                '13' => 'De 12Kg a 13Kg',
                '14' => 'De 13Kg a 14Kg',
                '15' => 'De 14Kg a 15Kg',
                '16' => 'De 15Kg a 16Kg'
            ];

            // Substituído por função Loop
            if (true) {
                $pesos = [];
                $pesos['0.300'] = 'Até 300g';

                for ($x = 1; $x <= 30; $x++) {
                    $pesos[$x] = $x == 1 ? 'De 300g a 1Kg' : 'De ' . ($x - 1) . 'Kg a ' . $x . 'Kg';
                }
            }

            if ($key && isset($pesos[$key])) {
                return $pesos[$key];
            }

            return $pesos;
        }
    }

    public function cancelEnvio($data = [])
    {
        $this->load->library('correio/correio');
        $coletaModel = app(Coleta::class);

        $exist = DB::table('envios_cancelamento')->where('envio_id', $data['envio']->id)->first();
        if ($exist) {
            $this->error = "Cancelamento já solicitado";
            return false;
        }
        $params_coleta = ['user_id' => $data['envio']->user_id, 'id' => $data['envio']->coleta_id];
        $coleta = $coletaModel->get($params_coleta);

        if (isset($data['only_check']) && $data['only_check']) {
            if ($coleta->date_insert < '2019-05-24' && session('group_code') != 'mandabem') {
                $this->error = "Data de geração do envio fora da data permitida para cancelamento";
                return false;
            }
            return true;
        }

        $user = $this->user_model->objectToArray($this->user_model->get($data['envio']->user_id));

        if ($data['envio']->type == 'REVERSA' && strlen($data['envio']->etiqueta_correios) && $data['envio']->etiqueta_correios != 'industrial') {
            $this->error = "Reversa já postada, cancelamento negado.";
            return false;
        }

        $cancelReversa = false;
        if ($data['envio']->type == 'REVERSA' && (!strlen($data['envio']->etiqueta_correios) || $data['envio']->etiqueta_correios == 'industrial')) {
            if (!preg_match('/Prazo de Utiliza(.*?)Expirado/', $coleta->status)) {
                $cancel = $this->correio->cancelReversa([
                    'user' => $user,
                    'numero_pedido' => $coleta->plp,
                    'environment' => $coleta->environment,
                ]);
                if (!$cancel) {
                    $this->error = "Falha ao cancelar reversa, tente novamente mais tarde";
                    return false;
                }
                $cancelReversa = true;
            }
        }

        $cancelPostado = false;
        if (!$cancelReversa && strlen($data['envio']->etiqueta_correios)) {
            if (true) {
                $info = $this->correio->statusEtiqueta([
                    'user' => $user,
                    'environment' => $coleta->environment,
                    'etiqueta' => $data['envio']->etiqueta_correios . 'BR',
                ]);

                if (preg_match('/Favor desconsiderar a informa(.*?)o anterior/i', $info['status']) && isset($info['data']->return->objeto->evento)) {
                    $evento = $info['data']->return->objeto->evento;
                
                    if (isset($evento[1]) && $evento[1]->tipo == 'PO' && ((int) $evento[1]->status === 0 || (int) $evento[1]->status === 1 || (int) $evento[1]->status === 9)) {
                        $info['status'] = 'NAO_ENCONTRADO';
                    }
                }
                
                if (!$info) {
                    $this->error = $this->correio->getError();
                    return false;
                }
                
                $logModel = app(Log::class);
                $logModel->log([
                    'text' => "Info status objeto:\n" . print_r($info, true),
                    'type' => 'INIT_CANCEL_OBJ',
                ]);
                
                if ($info['status'] == 'FINALIZADO') {
                    $this->error = "Objeto já foi finalizado";
                    return false;
                }
                
                if ($info && $info['status'] != '' && $info['status'] != 'NAO_ENCONTRADO') {
                    $cancel = $this->correio->bloquearObjeto([
                        'user' => $user,
                        'plp' => $coleta->plp,
                        'environment' => $coleta->environment,
                        'etiqueta' => $data['envio']->etiqueta_correios . 'BR',
                    ]);
                
                    $errorCorreios = $this->correio->getError();
                    if (!$cancel) {
                        $this->error = $errorCorreios ? $errorCorreios : "Falha ao cancelar objeto já postado, tente novamente mais tarde";
                        return false;
                    }
                
                    $cancelPostado = true;
                }
            }
            if (request()->server('REMOTE_ADDR') == '45.181.35.193') {
                $cancel = $this->correio->bloquearObjeto([
                    'user' => $user,
                    'plp' => $coleta->plp,
                    'environment' => $coleta->environment,
                    'etiqueta' => $data['envio']->etiqueta_correios . 'BR',
                ]);
            
                $errorCorreios = $this->correio->getError();
            
                if (!$cancel) {
                    $this->error = $errorCorreios ? $errorCorreios : "Falha ao cancelar objeto já postado, tente novamente mais tarde";
                    return false;
                }
            
                $cancelPostado = true;
            }
            
            if (true && $cancelPostado == false) {
                $dataCancelar = [
                    'plp' => $coleta->plp,
                    'numeroEtiqueta' => $data['envio']->etiqueta_correios . 'BR',
                    'user' => $user,
                ];
            
                $retCanc = $this->correio->cancelarObjeto($dataCancelar);
            
                if (!$retCanc) {
                    $errorCorreios = $this->correio->getError();
                    $this->error = $errorCorreios ? $errorCorreios : "Falha ao cancelar objeto já postado, tente novamente mais tarde";
                    return false;
                }
            
                $logModel = app(Log::class);
                $logModel->log([
                    'text' => "RETORNO para :\n retorno (" . print_r($retCanc, true) . ")\nData: " . print_r($data, true) . "\n",
                    'type' => 'CANCEL_OBJ',
                ]);
            }
        }
        $insertCancel = [
            'envio_id' => $data['envio']->id,
            'user_id' => optional(session('user_id'))->value ?? 1,
            'date_insert' => now(),
            'date_update' => now(),
        ];
        
        if ($cancelPostado) {
            $insertCancel['status'] = 'SEM_CREDITO';
        }
        
        $enviosCancelamento = DB::table('envios_cancelamento')->insert($insertCancel);
        
        if (!$enviosCancelamento) {
            $this->error = "Falha, tente novamente mais tarde";
            return false;
        }
        
        // Atualizando campo etiqueta_correios para "industrial" para que o cliente possa gerar novamente como industrial
        if ($data['envio']->valor_industrial > 0) {
            Envio::where('id', $data['envio']->id)
                ->where('user_id', $data['envio']->user_id)
                ->whereNull('coleta_id')
                ->update(['etiqueta_correios' => 'industrial']);
        }
        
        if (!$cancelPostado) {
            $this->checkEnvioCancelamento();
        }
        
        if ($cancelPostado) {
            return 'cancel_postado';
        }
        
        return true;
    }

    public function isCancelado($envioId)
    {
        $row =  DB::table('envios_cancelamento')->where('envio_id', $envioId)->first();

        return $row ? true : false;
    }

    public function checkEnvioCancelamento($userId = null)
    {
        $paymentModel = app(Payment::class);
        $enviosPendentes = DB::table('envios_cancelamento as ec')
        ->select('e.*', 'e.id as envio_id', 'ec.id as cancelamento_id', 'ec.date_insert as date_criacao', 'ec.user_id as user_solicitacao')
        ->leftJoin('envios as e', 'e.id', '=', 'ec.envio_id')
        ->whereNull('ec.status')
        ->whereNull('e.date_postagem')
        ->when($userId, function ($query) use ($userId) {
            $query->where('e.user_id', $userId);
        })
        ->get();


        foreach ($enviosPendentes as $envioPendente) {
            $userEnvio = User::find($envioPendente->user_id);
            $valorTotal = $envioPendente->valor_total;

            $dataCredito = [
                'user_id' => $userEnvio->id,
                'value' => $valorTotal,
                'description' => 'Crédito Cancelamento Etiqueta ' . ($envioPendente->type == 'REVERSA' ? ' REVERSA ' : $envioPendente->etiqueta_correios . 'BR') . ' - Envio ' . "CEP: " . $envioPendente->CEP,
                'description_tipo' => 'credito',
                'obs' => null,
                'is_cancelamento' => true,
            ];

            $addCredito = $paymentModel->saveCredito($dataCredito);

            if ($addCredito) {
                DB::table('envios_cancelamento')
                ->where('id', $envioPendente->cancelamento_id)
                ->whereNull('status')
                ->update([
                    'status' => 'CREDITADO',
                    'payment_id' => $addCredito,
                ]);
            } else {
                echo "Falha ao adicionar crédito ";
                print_r($envioPendente);
                exit;
            }
        }
    }

    public function removeAll($data)
    {
        foreach ($data['itens'] as $item) {
            $param = [$data['user_id'], $item];
            $_envioId = Envio::where('user_id', $data['user_id'])
                ->where('id', $item)
                ->whereNull('coleta_id')
                ->first();

            Envio::where('user_id', $data['user_id'])
                ->where('id', $item)
                ->whereNull('coleta_id')
                ->delete();

            if ($_envioId) {
                DB::table('envio_origem')->where('envio_id', $_envioId->id)->delete();
            }
        }

        return true;
    }

    public function searchEnvios($data = []) {
        // Usuarios com muitas consultas
        if ($data['user_id'] == 11090 || $data['user_id'] == 13649) {
            return;
        }
        
        if (!auth()->check()) {
            return;
        }
    
        $sql = 'SELECT envios.id, CEP_origem, CEP, forma_envio, destinatario, logradouro, numero, complemento, bairro, cidade, estado, type, ';
        $sql .= ' envios.peso, envios.email, envios.altura, envios.largura, envios.comprimento ';
        $sql .= 'FROM envios WHERE 1 ';
    
        if ($data['user_id'] == 26341) {
            $sql .= 'AND destinatario LIKE ? AND user_id = ? AND envios.coleta_id IS NOT NULL ';
        } else {
            $sql .= 'AND destinatario LIKE ? AND user_id = ? AND envios.coleta_id IS NOT NULL ';
        }
    
        $sql .= 'AND envios.CEP_origem IS NOT NULL ';
        $sql .= 'AND envios.CEP NOT IN ( SELECT CEP FROM user WHERE user.id = ? )  ';
    
        if (request()->get('type') == 'REVERSA') {
            $sql .= 'AND envios.type = "NORMAL" ';
        }
    
        // Teste de limite de 3 meses para autocomplete
        $hoje = now();
        $dateNoventa = $hoje->subDays(90)->startOfDay();
        $sql .= ' AND envios.date_insert >= ? ';
    
        // Fim teste
    
        // TOTTA Auto Complete
        if ($data['user_id'] == 26) {
            $sql .= ' AND envios.date_insert >= "2020-07-10 13:30:00" ';
        }
    
        if (
            ($data['user_id'] == 33819) || ($data['user_id'] == 30229) || ($data['user_id'] == 6124) || ($data['user_id'] == 5468)
            || ($data['user_id'] == 7199) || ($data['user_id'] == 33540) || ($data['user_id'] == 5722) || ($data['user_id'] == 7589) || ($data['user_id'] == 54844)
        ) {
            return false;
        }
    
        if (($data['user_id'] == 3087)) {
            $dateTri = $hoje->subDays(30)->startOfDay();
            $sql .= ' AND envios.date_insert >= ? ';
        }
    
        if ($data['user_id'] == 26341) {
            $dateId26341 = now()->subDays(60)->startOfDay();
            $sql .= ' AND envios.date_insert >= ? ';
        }
    
        if ($data['user_id'] == 6401) {
            $sql .= ' AND CEP != 88085201 ';
        }
    
        $sql .= 'GROUP BY envios.id, CEP_origem, CEP, forma_envio, destinatario, logradouro, numero, complemento, bairro, cidade, estado, type ';
        $sql .= 'LIMIT 10 ';
    
        $rows = DB::select($sql, [
            '%' . $data['filter_name'] . '%',
            $data['user_id'],
            $data['user_id'],
            $data['user_id'],
            $dateNoventa,
            $dateTri ?? null,
            $dateId26341 ?? null,
        ]);
    
        foreach ($rows as $r) {
            if ($r->type == 'REVERSA') {
                continue;
            }
        }
    
        return $rows;
    }

    public function hasManifestacao($envioId)
    {
        return DB::table('envios_manifestacao')
            ->where('envio_id', $envioId)
            ->where('codigo_motivo', 'NOT LIKE', 0)
            ->where('numero_pi', 'NOT LIKE', 'f')
            ->orderByDesc('id')
            ->first();
    }

    public function cleanEnvioTmpData($param = [])
    {
        $query = DB::table('envios_tmp_data')->where('user_id', $param['user_id']);

        if (isset($param['type'])) {
            $query->where('type', $param['type']);
        }

        $query->delete();
    }

    public function getEnvioTmpData($param = [])
    {
        return DB::table('envios_tmp_data')
            ->where('user_id', $param['user_id'])
            ->where('type', $param['type'])
            ->where('ref_id', $param['ref_id'])
            ->first();
    }

    public function addEnvioTmpData($param = [])
    {
        if ($this->getEnvioTmpData($param)) {
            return;
        }

        DB::table('envios_tmp_data')->insert([
            'user_id' => $param['user_id'],
            'type' => $param['type'],
            'ref_id' => $param['ref_id'],
            'data_serial' => $param['data_serial'],
            'date' => now(),
        ]);
    }

    public function devolveValor($data)
    {
        return DB::table('envios')
            ->where('user_id', $data['user_id'])
            ->where('id', $data['envio_id'])
            ->whereNull('payment_devolvido_id')
            ->update([
                'valor_devolvido' => $data['valor_devolvido'],
                'payment_devolvido_id' => $data['payment_devolvido_id'],
            ]);
    }

    public function getDevolucoes($param)
    {
        $dateStart = now()->startOfMonth();
        $dateEnd = now()->endOfMonth();

        $query = DB::table('envios')
            ->where('date_postagem', '>=', $dateStart)
            ->where('date_postagem', '<=', $dateEnd);

        if (isset($param['user_id'])) {
            $query->where('user_id', $param['user_id']);
        }

        return $query->sum('valor_devolvido');
    }

    public function getEtiquetaStatus($data = [])
    {
        $statusDesc = 'Aguardando Postagem';

        $sqlEtiq = 'SELECT ev.descricao as etiqueta_status, ev.tipos as status_tipo, ev.status as status_number ';
        $sqlEtiq .= 'FROM etiqueta_status es ';
        $sqlEtiq .= 'LEFT JOIN etiqueta_events ev ON ev.id = es.etiqueta_event_id ';
        $sqlEtiq .= 'WHERE es.envio_id = ?';

        $row = DB::select($sqlEtiq, [$data['envio_id']])[0] ?? null;

        if ($row && strlen($row->etiqueta_status)) {
            // Substitua o código de notificação de e-mail ou use o Log para registrar a mensagem.
            Log::info('Obtendo Etiqueta Status em Envio Model', [
                'Envio ID' => $data['envio_id'],
                'Status' => $row->etiqueta_status,
            ]);

            $statusDesc = $row->etiqueta_status;
        }

        return $statusDesc;
    }

    public function updateEventsEnvio($data)
    {
        $this->loadLibrary('email_maker');
        $this->loadModel('acompanhamento_model');
        $this->loadModel('payment_model');

        if ($data['evento'] == 'NOT_FOUND') {

            $etiquetaStatus = DB::table('etiqueta_status')
                ->where('envio_id', $data['envio_id'])
                ->first();

            if ($etiquetaStatus) {
                DB::table('etiqueta_status')
                    ->where('id', $etiquetaStatus->id) 
                    ->update([
                        'etiqueta_event_id' => 128,
                        'date_event' => now(),
                        'date_last_search' => now(),
                    ]);
            } else {
                DB::table('etiqueta_status')->insert([
                    'envio_id' => $data['envio_id'],
                    'etiqueta_event_id' => 128,
                    'date_event' => now(),
                    'date_insert' => now(),
                    'date_last_search' => now(),
                ]);
            }

            return;
        }

        $evento = $data['evento'];
        $dateEvent = preg_replace('/T/', ' ', $evento['dtHrCriado']);
        $status = (int) $evento['tipo'];

        $etiquetaEvent = DB::select('SELECT * FROM etiqueta_events WHERE tipos LIKE ? AND status = ?', ["%{$evento['codigo']}%", $status]);
        $etiquetaStatus = DB::table('etiqueta_status')->where('envio_id', $data['envio_id'])->first();

        if (isset($data['is_cron']) && $data['is_cron']) {
            $envio = $data['envio'];
        } else {
            $envio = $this->get(['id' => $data['envio_id'], 'user_id' => 'mandabem']);
        }

        if (!$etiquetaEvent) {
            $this->email_maker->msg([
                'to' => 'reginaldo@mandabem.com.br,wieder@mandabem.com.br',
                'subject' => 'Sem Evento da Etiqueta',
                'msg' => "INFO:<br><pre>" . print_r($data, true) . '</pre>',
            ]);

            return;
        }

        // RETIRAR DEPOIS

        $param_notification = [
            'etiqueta' => $envio->etiqueta_correios,
            'status_number' => $status,
            'status_desc' => $evento['descricao'],
        ];

        if ($etiquetaStatus) {
            DB::table('etiqueta_status')
                ->where('id', $etiquetaStatus->id)
                ->where('envio_id', $data['envio_id'])
                ->update([
                    'etiqueta_event_id' => $etiquetaEvent['id'],
                    'date_last_search' => now(),
                    'date_event' => $dateEvent,
                ]);
        } else {
            $statusEtiquetaId = DB::table('etiqueta_status')->insertGetId([
                'envio_id' => $data['envio_id'],
                'etiqueta_event_id' => $etiquetaEvent['id'],
                'date_event' => $dateEvent,
                'date_insert' => now(),
                'date_last_search' => now(),
            ]);
        }

        // SAIU para entrega 
        if ($evento['codigo'] == 'OEC' && ($status === 0 || $status === 1)) {
            // exit("ENVIAR EMAIL");
            $this->acompanhamento_model->send_email_notification($param_notification);
        }

        // ENTREGA efetivada
        if ($evento['codigo'] == 'BDE' || $evento['codigo'] == 'BDI' || $evento['codigo'] == 'BDR') {

            // Objeto Entregue
            if ($status === 0 || $status === 1 || $status == 23) {

                if (!$envio->date_entregue) {

                    // Notificando Por email
                    if ($status === 0 || $status === 1) {
                        $param_notification['email_replicate'] = 'reginaldo@mandabem.com.br';
                        $this->acompanhamento_model->send_email_notification($param_notification);
                    }
                    // Final Notificando Por email

                    DB::table('envios')
                        ->where('id', $envio->id)
                        ->whereNull('date_entregue')
                        ->update(['date_entregue' => $dateEvent, 'is_finalizado' => 1]);

                    // NOTIFY Etiqueta Entregua
                    $this->acompanhamento_model->notify_envio([
                        'envio_id' => $envio->id,
                        'etiqueta' => $envio->etiqueta_correios,
                        'type' => 'date_entregue'
                    ]);
                    // FINAL NOTIFY Etiqueta Entregua
                } else {
                    // Finalizando Objeto
                    DB::table('envios')
                        ->where('id', $envio->id)
                        ->update(['is_finalizado' => 1]);
                }
            }
        }

        if ($evento['codigo'] == 'LDI') {
            if ($status === 0 || $status === 1 || $status === 3 || $status === 14) {

                $param_notification['endereco_retirada'] = $evento['unidade']['endereco'];
                $param_notification['local_retirada'] = isset($evento['local']) ? $evento['local'] : $evento['local_retirar'];

                $detalhes = serialize([
                    'local' => $param_notification['local_retirada'],
                    'endereco' => $evento['unidade']['endereco']
                ]);

                DB::table('etiqueta_status')
                    ->where('id', $statusEtiquetaId)
                    ->where('envio_id', $envio->id)
                    ->update(['detalhes' => $detalhes]);

                // enviando email
                if ($dateEvent >= '2020-06-10') {
                    $this->acompanhamento_model->send_email_notification($param_notification);
                }
            }
        }
         // caso objeto tem passado sem data de postagem
         if ($evento['tipo'] != 'EST' && $evento['tipo'] != 'PO' && !$envio->date_postagem && isset($data['all_events']) && count($data['all_events'])) {
            foreach ($data['all_events'] as $_e) {
                if ($_e->tipo == 'PO') {
                    $evento = ($_e);
                    $dateEvent = $this->date_utils->to_en($evento['data'] . ' ' . $evento['hora'] . ':00', true, true);
                    $status = (int) $evento['status'];
                    // print_r($evento);
                    // exit("EVENDO de postado para obj nao postado");
                    $param_notification['email_replicate'] = 'reginaldo@mandabem.com.br';
                    $param_notification = array(
                        'etiqueta' => $envio->etiqueta_correios,
                        'status_number' => $evento['status'],
                        'status_desc' => $evento['descricao']
                    );

                    break;
                }
            }
        }

        // POSTAGEM
        if ($evento['tipo'] == 'PO') {

            // Objeto Postado
            if ($status === 0 || $status === 1 || $status === 9) {

                if (!$envio->date_postagem) {

                    // verificando Cancelamento
                    $has_cancel = DB::table('envios_cancelamento')
                        ->where('envio_id', $envio->id)
                        ->first();

                    if ($has_cancel) {

                        if ($has_cancel->status == 'CREDITADO') {

                            $cred_revogado = false;

                            // Se o crédito ainda não tiver sido usado, revogá-lo
                            if (!$this->payment_model->is_used($has_cancel->payment_id)) {
                                if ($this->payment_model->remove_credit($has_cancel->payment_id)) {
                                    DB::table('envios_cancelamento')
                                        ->where('id', $has_cancel->id)
                                        ->where('envio_id', $envio->id)
                                        ->update(['status' => 'REVOGADO_EC']);
                                    $cred_revogado = true;
                                    $this->email_maker->msg(array(
                                        'subject' => 'Credito revogado - OBJETO CANCELADO POSTADO',
                                        'msg' => 'Envio: ' . $envio->etiqueta_correios . 'BR' . ', Postagem: ' . $this->date_utils->to_br($dateEvent),
                                        'to' => 'reginaldo@mandabem.com.br'
                                    ));
                                }
                            }

                            if (!$cred_revogado) {

                                // $cred = $this->payment_model->get($cred->payment_id);

                                $user = $this->user_model->get($envio->user_id);
                                $this->email_maker->msg(array(
                                    'subject' => 'OBJETO CANCELADO POSTADO',
                                    'msg' => 'Envio: ' . $envio->etiqueta_correios . 'BR' . ', Postagem: ' . $this->date_utils->to_br($dateEvent) . '<br>Cliente: ' . $user->id . ' - ' . $user->name . ', Razao: ' . $user->razao_social,
                                    'to' => 'reginaldo@mandabem.com.br,marcos@mandabem.com.br'
                                ));
                            }
                        }
                    }

                    $param_upd_postagem = ['date_postagem' => $dateEvent];

                    if ($status == 9) {
                        $param_upd_postagem['postado_apos_h_limite'] = 1;
                    }

                    DB::table('envios')
                        ->where('id', $envio->id)
                        ->whereNull('date_postagem')
                        ->update($param_upd_postagem);

                    // Notificando Por email
                    if ($dateEvent >= '2020-06-15' && strlen($envio->email)) {
                        $this->acompanhamento_model->send_email_notification($param_notification);
                    } else {
                        if (strlen($envio->email)) {
                            $this->email_maker->msg(array(
                                'subject' => 'NÃO --- Notificando envio',
                                'msg' => '<pre>' . print_r($data, true) . '</pre>',
                                'to' => 'regygom@gmail.com'
                            ));
                        }
                    }
                    // Final Notificando Por email
                    // NOTIFY API Etiqueta Postagem
                    $this->acompanhamento_model->notify_envio([
                        'envio_id' => $envio->id,
                        'etiqueta' => $envio->etiqueta_correios,
                        'type' => 'date_postagem'
                    ]);
                    // FINAL NOTIFY API Etiqueta Postagem
                } else {
                    // marcando postado limite
                    if ($status == 9) {
                        DB::table('envios')
                            ->where('id', $envio->id)
                            ->update(['postado_apos_h_limite' => 1]);
                    }
                }
            }
        }
        return true;
    }
    public function updateEventsEnvioV2($data)
    {
        $this->loadLibrary('email_maker');
        $this->loadModel('acompanhamento_model');
        $this->loadModel('payment_model');

        if ($data['evento'] == 'NOT_FOUND') {
            $this->email_maker->msg([
                'to' => 'regygom@gmail.com',
                'subject' => 'Evento da Etiqueta - NOT_FOUND',
                'msg' => "INFO:<br><pre>" . print_r($data, true) . '</pre>',
            ]);

            exit("EVENTO NOT FOUND");

            $etiquetaStatus = DB::table('etiqueta_status')
                ->where('envio_id', $data['envio_id'])
                ->first();

            if ($etiquetaStatus) {
                DB::table('etiqueta_status')
                    ->where('id', $etiquetaStatus->id)
                    ->where('envio_id', $etiquetaStatus->envio_id)
                    ->update([
                        'etiqueta_event_id' => 128,
                        'date_event' => now(),
                        'date_last_search' => now(),
                    ]);
            } else {
                DB::table('etiqueta_status')->insert([
                    'envio_id' => $data['envio_id'],
                    'etiqueta_event_id' => 128,
                    'date_event' => now(),
                    'date_insert' => now(),
                    'date_last_search' => now(),
                ]);
            }

            return;
        }

        $evento = $data['evento'];
        $dateEvent = preg_replace('/T/', ' ', $evento['dtHrCriado']);
        $status = (int) $evento['tipo'];

        $etiquetaEvent = DB::select('SELECT * FROM etiqueta_events WHERE tipos LIKE ? AND status = ?', ["%{$evento['codigo']}%", $status]);
        $etiquetaStatus = DB::table('etiqueta_status')->where('envio_id', $data['envio_id'])->first();

        if (isset($data['is_cron']) && $data['is_cron']) {
            $envio = $data['envio'];
        } else {
            $envio = $this->get(['id' => $data['envio_id'], 'user_id' => 'mandabem']);
        }

        if (!$etiquetaEvent) {
            $this->email_maker->msg([
                'to' => 'reginaldo@mandabem.com.br,wieder@mandabem.com.br',
                'subject' => 'Sem Evento da Etiqueta',
                'msg' => "INFO:<br><pre>" . print_r($data, true) . '</pre>',
            ]);

            return;
        }

        $param_notification = [
            'etiqueta' => $envio->etiqueta_correios,
            'status_number' => $status,
            'status_desc' => $evento['descricao'],
        ];

        if ($etiquetaStatus) {
            DB::table('etiqueta_status')
                ->where('id', $etiquetaStatus->id)
                ->where('envio_id', $data['envio_id'])
                ->update([
                    'etiqueta_event_id' => $etiquetaEvent['id'],
                    'date_last_search' => now(),
                    'date_event' => $dateEvent,
                ]);
        } else {
            $statusEtiquetaId = DB::table('etiqueta_status')->insertGetId([
                'envio_id' => $data['envio_id'],
                'etiqueta_event_id' => $etiquetaEvent['id'],
                'date_event' => $dateEvent,
                'date_insert' => now(),
                'date_last_search' => now(),
            ]);
        }

        // SAIU para entrega 
        if ($evento['codigo'] == 'OEC' && ($status === 0 || $status === 1)) {
            // exit("ENVIAR EMAIL");
            $this->acompanhamento_model->send_email_notification($param_notification);
        }

        // ENTREGA efetivada
        if ($evento['codigo'] == 'BDE' || $evento['codigo'] == 'BDI' || $evento['codigo'] == 'BDR') {

            // Objeto Entregue
            if ($status === 0 || $status === 1 || $status == 23) {

                if (!$envio->date_entregue) {

                    // Notificando Por email
                    if ($status === 0 || $status === 1) {
                        $param_notification['email_replicate'] = 'reginaldo@mandabem.com.br';
                        $this->acompanhamento_model->send_email_notification($param_notification);
                    }
                    // Final Notificando Por email

                    DB::table('envios')
                        ->where('id', $envio->id)
                        ->whereNull('date_entregue')
                        ->update(['date_entregue' => $dateEvent, 'is_finalizado' => 1]);

                    // NOTIFY Etiqueta Entregua
                    $this->acompanhamento_model->notify_envio([
                        'envio_id' => $envio->id,
                        'etiqueta' => $envio->etiqueta_correios,
                        'type' => 'date_entregue'
                    ]);
                    // FINAL NOTIFY Etiqueta Entregua
                } else {
                    // Finalizando Objeto
                    DB::table('envios')
                        ->where('id', $envio->id)
                        ->update(['is_finalizado' => 1]);
                }
            }
        }

        if ($evento['codigo'] == 'LDI') {
            if ($status === 0 || $status === 1 || $status === 3 || $status === 14) {

                $param_notification['endereco_retirada'] = $evento['unidade']['endereco']['logradouro'] . ' - ' .
                    $evento['unidade']['endereco']['numero'] . ' - ' .
                    $evento['unidade']['endereco']['bairro'] . ' - ' .
                    $evento['unidade']['endereco']['cidade'] . '/' .
                    $evento['unidade']['endereco']['uf'] . ' - CEP ' .
                    $evento['unidade']['endereco']['cep'];
                $param_notification['local_retirada'] = $evento['unidade']['tipo'];

                $detalhes = serialize([
                    'local' => $param_notification['local_retirada'],
                    'endereco' => $evento['unidade']['endereco']
                ]);

                DB::table('etiqueta_status')
                    ->where('id', $statusEtiquetaId)
                    ->where('envio_id', $envio->id)
                    ->update(['detalhes' => $detalhes]);

                // enviando email
                if ($dateEvent >= '2020-06-10') {
                    $this->acompanhamento_model->send_email_notification($param_notification);
                }
            }
        }
         // caso objeto tem passado sem data de postagem
         if ($evento['tipo'] != 'EST' && $evento['tipo'] != 'PO' && !$envio->date_postagem && isset($data['all_events']) && count($data['all_events'])) {
            foreach ($data['all_events'] as $_e) {
                if ($_e->tipo == 'PO') {
                    $evento = ($_e);
                    $dateEvent = $this->date_utils->to_en($evento['data'] . ' ' . $evento['hora'] . ':00', true, true);
                    $status = (int) $evento['status'];
                    // print_r($evento);
                    // exit("EVENDO de postado para obj nao postado");
                    $param_notification['email_replicate'] = 'reginaldo@mandabem.com.br';
                    $param_notification = array(
                        'etiqueta' => $envio->etiqueta_correios,
                        'status_number' => $evento['status'],
                        'status_desc' => $evento['descricao']
                    );

                    break;
                }
            }
        }

        // POSTAGEM
        if ($evento['tipo'] == 'PO') {

            // Objeto Postado
            if ($status === 0 || $status === 1 || $status === 9) {

                if (!$envio->date_postagem) {

                    // verificando Cancelamento
                    $has_cancel = DB::table('envios_cancelamento')
                        ->where('envio_id', $envio->id)
                        ->first();

                    if ($has_cancel) {

                        if ($has_cancel->status == 'CREDITADO') {

                            $cred_revogado = false;

                            // Se o crédito ainda não tiver sido usado, revogá-lo
                            if (!$this->payment_model->is_used($has_cancel->payment_id)) {
                                if ($this->payment_model->remove_credit($has_cancel->payment_id)) {
                                    DB::table('envios_cancelamento')
                                        ->where('id', $has_cancel->id)
                                        ->where('envio_id', $envio->id)
                                        ->update(['status' => 'REVOGADO_EC']);
                                    $cred_revogado = true;
                                    $this->email_maker->msg(array(
                                        'subject' => 'Credito revogado - OBJETO CANCELADO POSTADO',
                                        'msg' => 'Envio: ' . $envio->etiqueta_correios . 'BR' . ', Postagem: ' . $this->date_utils->to_br($dateEvent),
                                        'to' => 'reginaldo@mandabem.com.br'
                                    ));
                                }
                            }

                            if (!$cred_revogado) {

                                // $cred = $this->payment_model->get($cred->payment_id);

                                $user = $this->user_model->get($envio->user_id);
                                $this->email_maker->msg(array(
                                    'subject' => 'OBJETO CANCELADO POSTADO',
                                    'msg' => 'Envio: ' . $envio->etiqueta_correios . 'BR' . ', Postagem: ' . $this->date_utils->to_br($dateEvent) . '<br>Cliente: ' . $user->id . ' - ' . $user->name . ', Razao: ' . $user->razao_social,
                                    'to' => 'reginaldo@mandabem.com.br,marcos@mandabem.com.br'
                                ));
                            }
                        }
                    }

                    $param_upd_postagem = ['date_postagem' => $dateEvent];

                    if ($status == 9) {
                        $param_upd_postagem['postado_apos_h_limite'] = 1;
                    }

                    DB::table('envios')
                        ->where('id', $envio->id)
                        ->whereNull('date_postagem')
                        ->update($param_upd_postagem);

                    // Notificando Por email
                    if ($dateEvent >= '2020-06-15' && strlen($envio->email)) {
                        $this->acompanhamento_model->send_email_notification($param_notification);
                    } else {
                        if (strlen($envio->email)) {
                            $this->email_maker->msg(array(
                                'subject' => 'NÃO --- Notificando envio',
                                'msg' => '<pre>' . print_r($data, true) . '</pre>',
                                'to' => 'regygom@gmail.com'
                            ));
                        }
                    }
                    // Final Notificando Por email
                    // NOTIFY API Etiqueta Postagem
                    $this->acompanhamento_model->notify_envio([
                        'envio_id' => $envio->id,
                        'etiqueta' => $envio->etiqueta_correios,
                        'type' => 'date_postagem'
                    ]);
                    // FINAL NOTIFY API Etiqueta Postagem
                } else {
                    // marcando postado limite
                    if ($status == 9) {
                        DB::table('envios')
                            ->where('id', $envio->id)
                            ->update(['postado_apos_h_limite' => 1]);
                    }
                }
            }
        }
        return true;
    }

    public function rollbackPostagem($data)
    {
        if (is_array($data['events']) && count($data['events']) > 1) {
            // desconsiderar info anterior
            if (is_array($data['events'][0]) && $data['events'][0]['codigo'] == 'EST' && preg_match('/1|2|3|4|5|6|9/', (int)$data['events'][0]['tipo'])) {
                // Desfazer
                if ($data['events'][1]->tipo == 'PO') {
                    $this->emailMaker->msg([
                        'to' => 'regygom@gmail.com',
                        'subject' => 'Rollback => ' . $data['envio_id']
                    ]);

                    DB::table('envios')
                        ->where('id', $data['envio_id'])
                        ->whereNull('date_entregue')
                        ->update(['date_postagem' => null]);

                    return true;
                }
            }

            // desconsiderar info anterior
            if (is_object($data['events'][0]) && $data['events'][0]->tipo == 'EST' && preg_match('/1|2|3|4|5|6|9/', (int)$data['events'][0]->status)) {
                // Desfazer
                if ($data['events'][1]->tipo == 'PO') {
                    $this->emailMaker->msg([
                        'to' => 'regygom@gmail.com',
                        'subject' => 'Rollback => ' . $data['envio_id']
                    ]);

                    DB::table('envios')
                        ->where('id', $data['envio_id'])
                        ->whereNull('date_entregue')
                        ->update(['date_postagem' => null]);

                    return true;
                }
            }
        }
        return false;
    }

    public function getCodeServico($forma, $contrato = true)
    {
        // Com Contrato
        if ($contrato) {
            switch (strtoupper($forma)) {
                case 'PAC':
                    return '03298';
                case 'SEDEX':
                    return '03220';
                case 'SEDEX 10':
                    return '40940';
                case 'PACMINI':
                    return '04227';
            }
        }

        // Sem Contrato
        else {
            switch (strtoupper($forma)) {
                case 'PAC':
                    return '04510';
                case 'SEDEX':
                    return '04014';
                case 'SEDEX 10':
                    return '40215';
                case 'PACMINI':
                    return '04227';
            }
        }
    }

    public function getInfoContratoMb()
    {
        return [
            'cod_empresa' => '18086160',
            'senha_empresa' => '27347642'
        ];
    }

    public function checkInCache($data)
    {
        $row = DB::table('envios_tmp_data')
            ->where('user_id', $data['user_id'])
            ->where('ref_id', $data['info']['ref_id'])
            ->where('type', $data['type'])
            ->first();
    
        if ($row) {
            return true;
        }
    
        $dateLimit = now()->subSeconds(84600); // 1 Dia
    
        DB::table('envios_tmp_data')
            ->where('user_id', $data['user_id'])
            ->where('type', $data['type'])
            ->where('date', '<=', $dateLimit)
            ->delete();
    
        $ins = DB::table('envios_tmp_data')->insert([
            'user_id' => $data['user_id'],
            'type' => $data['type'],
            'ref_id' => $data['info']['ref_id'],
            'data_serial' => serialize($data['info']),
            'date' => now(),
        ]);
    
        return !$ins;
    }
    
    public function cleanTmpCache($data)
    {
        return DB::table('envios_tmp_data')
            ->where('user_id', $data['user_id'])
            ->where('ref_id', $data['ref_id'])
            ->where('type', $data['type'])
            ->delete();
    }

    public function getEnviosByColeta($coleta_id)
    {
        return DB::table('envios')->where('coleta_id', $coleta_id)->get();
    }

    public function getEnviosUser()
    {
        $trintaDias = now()->subDays(30)->startOfDay();
        $sql = 'SELECT user.id as id, user.name as nome,user.cpf as cpf,user.CEP as CEP,user.latitude as latitude,user.longitude as longitude, COUNT(envios.id) as total FROM user JOIN envios ON envios.user_id = user.id ';
        $sql .= 'WHERE envios.date_postagem >= ? AND user.status = ? ';
        $sql .= 'AND user.latitude IS NOT NULL ';
        $sql .= 'GROUP BY  user.id ';

        return DB::select($sql, [$trintaDias, 'ACTIVE']);
    }

    public function isTrechoLocal($param = array())
    {
        $faixaOrigem = DB::select("SELECT * FROM ceps_locais WHERE ? BETWEEN cep_inicial_origem AND cep_final_origem AND ? BETWEEN cep_inicial_dest AND cep_final_dest LIMIT 1", [$param['cep_origem'], $param['cep_destino']]);

        return $faixaOrigem ? true : false;
    }

    public function supports(): HasMany
    {
        return $this->hasMany(Support::class);
    }
}
