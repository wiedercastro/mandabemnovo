<?php

namespace App\Models;

use App\Libraries\DateUtils;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use App\Libraries\EmailMaker;
use App\Libraries\FormBuilder;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $table = "user";
    

    public function migrateUser()
    {
        $usersImport = ['maquinadecamisa', 'hilobaby', 'piltover', 'sentidogeek', 'deboratotta', 'tottaacessorios', 'meujardim', 'fueloculos', 'belezapura', 'simporte', 'lojakoki'];

        $userWp = DB::select("SELECT * FROM wp_users WHERE user_login IN ('" . implode("','", $usersImport) . "') ORDER BY ID");

        $passwords = [
            'belezacontrato' => 'paulocontrato2019',
            'maquinadecamisa' => 'marcosmaquina2018',
            'hilobaby' => 'kamihilo2018',
            'piltover' => 'raphapiltover2018',
            'sentidogeek' => 'juniosentido2018',
            'deboratotta' => 'debtotta2018',
            'tottaacessorios' => 'deborabalbe2018',
            'meujardim' => 'brendameujardim2018',
            'fueloculos' => 'tienzofuel2018',
            'belezapura' => 'paulobelezapura2018',
            'simporte' => 'andersonsimporte2018',
            'lojakoki' => 'raquelkoki2018',
            'luxury' => 'juluxury2019',
        ];

        foreach ($userWp as $user) {
            $data = [];
            $userData = DB::table('wp_usermeta')->where('user_id', $user->ID)->get();

            foreach ($userData as $_data) {
                $data[$_data->meta_key] = $_data->meta_value;
            }

            $userExist = DB::table('user')->where('id', $user->ID)->first();

            $dataUpdate = [
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'name_ecommerce' => $user->display_name,
                'razao_social' => $data['razao_social_cliente'],
                'telefone' => $data['telefone_responsavel'],
                'CEP' => $data['cep_cliente'],
                'logradouro' => $data['endereco_cliente'],
                'numero' => $data['numero_cliente'],
                'complemento' => $data['complemento_cliente'],
                'bairro' => $data['bairro_cliente'],
                'cidade' => $data['cidade_cliente'],
                'uf' => getListaEstados()[$data['estado_cliente']],
                'login' => $user->user_login,
                'email' => $user->user_email,
                'password' => bcrypt($passwords[$user->user_login]),
            ];

            if (strlen(trim($data['cpf_responsavel'])) > 11) {
                $dataUpdate['cnpj'] = $data['cpf_responsavel'];
            } else {
                $dataUpdate['cpf'] = $data['cpf_responsavel'];
            }

            $dataUpdate['date_update'] = now();

            if ($userExist) {
                DB::table('user')->where('id', $userExist->id)->update($dataUpdate);
            } else {
                $dataUpdate['id'] = $user->ID;
                $dataUpdate['date_insert'] = now();
                DB::table('user')->insert($dataUpdate);
            }
        }
    }

    public function getFields()
    {
        $grupoTaxaModel = new GrupoTaxa();
        $listaEstados_ = getListaEstados(true);
        $listaEstados = [];
        foreach ($listaEstados_ as $id => $name) {
            $std = new \stdClass();
            $std->id = $id;
            $std->name = $name;
            $listaEstados[] = $std;
        }

        $listaFranquias = [];

        $listaStatus_ = ['ACTIVE' => 'ATIVO', 'BLOCK' => 'BLOQUEADO'];
        $listaStatus = [];
        foreach ($listaStatus_ as $k => $v) {
            $std = new \stdClass();
            $std->id = $k;
            $std->name = $v;
            $listaStatus[] = $std;
        }

        $listYesNo_AR = ['0' => 'Não', '1' => 'Sim'];
        $listYesNo = [];
        foreach ($listYesNo_AR as $k => $v) {
            $std = new \stdClass();
            $std->id = $k;
            $std->name = $v;
            $listYesNo[] = $std;
        }

        $this->fields = [
            "user_group_id" => ['opts' => $this->getUserGroups(), 'type' => 'select', 'label' => 'Tipo de Usuário', 'required' => false, 'cols' => [4, 8]],
            "tipo_cliente" => ['opts' => $this->getTipoCliente(), 'type' => 'select', 'label' => 'Tipo Emissão', 'required' => true, 'cols' => [4, 8]],
            "login" => ['type' => 'text', 'label' => 'Usuário', 'required' => true, "autocomplete_off" => true],
            "password" => ['type' => 'password', 'label' => 'Senha', 'required' => false, "autocomplete_off" => true],
            "name_ecommerce" => ['label' => 'E-commerce', 'required' => false, 'placeholder' => 'Será o nome do Remetente nos Emails enviados', 'maxlength' => '50'],
            "CEP" => ['label' => 'CEP', 'required' => false, 'cols' => [4, 6]],
            "logradouro" => ['label' => 'Logradouro', 'required' => false, 'cols' => [4, 8], 'maxlength' => '50', 'placeholder' => 'Rua, Avenida, Travessa... '],
            "numero" => ['label' => 'Número', 'required' => false, 'cols' => [4, 6], 'maxlength' => '8'],
            "complemento" => ['label' => 'Complemento', 'cols' => [4, 8], 'maxlength' => 20],
            "bairro" => ['label' => 'Bairro', 'required' => false, 'cols' => [4, 8], 'maxlength' => '30'],
            "cidade" => ['label' => 'Cidade', 'required' => false, 'cols' => [4, 8], 'maxlength' => '40'],
            "uf" => ['type' => 'select', 'opts' => $listaEstados, 'label' => 'Estado', 'required' => false, 'cols' => [4, 8]],
            "name" => ['type' => 'text', 'label' => 'Nome', 'required' => false, 'maxlength' => '50'],
            "email" => ['type' => 'email', 'label' => 'E-mail', 'required' => false, "autocomplete_off" => true],
            "telefone" => ['label' => 'Telefone', 'required' => false],
            "cpf" => ['label' => 'CPF', 'required' => true],
            "cnpj" => ['label' => 'CNPJ', 'required' => false],
            "contrato_correios" => ['label' => 'Contrato Correios'],
            "codigo_adm_correios" => ['label' => 'Codigo Administrativo'],
            "user_correios" => ['label' => 'Usuário Correios (SIGEP)'],
            "senha_correios" => ['label' => 'Senha Correios'],
            "cartao_correios" => ['label' => 'Cartão Correios'],
            "codigo_servico_pac" => ['label' => 'Cód. Serv. PAC Correios'],
            "codigo_servico_sedex" => ['label' => 'Cód. Serv. SEDEX Correios'],
            "volume_medio" => ['label' => 'Volume médio Envios', 'disabled' => true],
            "status" => ['type' => 'select', 'opts' => $listaStatus, 'label' => 'Status', 'required' => false],
            "config_enable_autocomplete" => ['type' => 'select', 'opts' => $listYesNo, 'label' => 'Habilitar Autocompletar', 'required' => false, 'no_show_empty' => true],
            "config_enable_plp" => ['type' => 'select', 'opts' => $listYesNo, 'label' => 'Habilitar PLP', 'required' => false, 'no_show_empty' => true],
            "config_enable_seguro" => ['type' => 'select', 'opts' => $listYesNo, 'label' => 'Habilitar Seguro', 'required' => false, 'no_show_empty' => true],
            "config_etiquetas_por_pag" => ['type' => 'select', 'opts' => array(['1' => '1 por folha', '2' => '2 por folha', '4' => '4 por folha']), 'label' => 'Etiquetas por Folha', 'required' => false, 'no_show_empty' => true],
            "config_enable_manifestacao" => ['type' => 'select', 'opts' => array(['0' => 'Não', '1' => 'Sim']), 'label' => 'Habilitar Gerenciamento Manifestações', 'required' => false, 'no_show_empty' => true],
            "config_num_boletos_permitidos" => ['type' => 'select', 'opts' => array(['1' => '1', '2' => '2', '3' => '3', '4' => '4']), 'label' => 'Qtde Boletos pendentes permitidos', 'required' => false, 'no_show_empty' => true],
            "config_enable_view_nfse" => ['type' => 'select', 'opts' => array(['0' => 'Não', '1' => 'Sim']), 'label' => 'Hablitar visualização NFSe', 'required' => false, 'no_show_empty' => true],
            "config_enable_cupom" => ['type' => 'select', 'opts' => array(['0' => 'Não', '1' => 'Sim']), 'label' => 'Hablitar Aba Cupom', 'required' => false, 'no_show_empty' => true],
            "config_enable_afiliado" => ['type' => 'select', 'opts' => array(['0' => 'Não', '1' => 'Sim']), 'label' => 'Hablitar Aba Afiliado', 'required' => false, 'no_show_empty' => true],
            "tipo_pix" => ['type' => 'select', 'opts' => array(['CPF' => 'CPF', 'E-Mail' => 'E-Mail', 'Telefone' => 'Telefone']), 'label' => 'Chave PIX', 'required' => false, 'no_show_empty' => true],
            "codigo_pix" => ['label' => 'Número'],
            "config_enable_sedex" => ['type' => 'select', 'opts' => $listYesNo, 'label' => 'Habilitar SEDEX', 'required' => false, 'no_show_empty' => true],
            "config_enable_pac" => ['type' => 'select', 'opts' => $listYesNo, 'label' => 'Habilitar PAC', 'required' => false, 'no_show_empty' => true],
            "config_enable_pacmini" => ['type' => 'select', 'opts' => $listYesNo, 'label' => 'Habilitar PACMINI', 'required' => false, 'no_show_empty' => true],
            "config_enable_sedex_hoje" => ['type' => 'select', 'opts' => $listYesNo, 'label' => 'Habilitar SEDEX HOJE', 'required' => false, 'no_show_empty' => true],
            "config_enable_sedex_12" => ['type' => 'select', 'opts' => $listYesNo, 'label' => 'Habilitar SEDEX 12', 'required' => false, 'no_show_empty' => true],
            "config_enable_industrial" => ['type' => 'select', 'opts' => $listYesNo, 'label' => 'Habilitar Industrial', 'required' => false, 'no_show_empty' => true],
            "razao_social" => ['type' => 'text', 'label' => 'Razão Social', 'required' => false, 'placeholder' => 'Será o nome do Remetente nas etiquetas', 'maxlength' => '50'],
            "franquia_responsavel" => ['type' => 'select', 'opts' => $listaFranquias, 'label' => 'Franquia Responsavel', 'required' => false, 'cols' => [4, 8]],
            "grupo_taxa" => ['type' => 'select', 'opts' => array($grupoTaxaModel->getList(['active' => true, 'application' => 'DEFAULT'])), 'label' => 'Grupo Taxa (PAC & SEDEX)', 'required' => true, 'cols' => [4, 8]],
            "grupo_taxa_pacmini" => ['type' => 'select', 'opts' => array($grupoTaxaModel->getList(['active' => true, 'application' => 'PACMINI'])), 'label' => 'Grupo Taxa PAC Mini', 'required' => false, 'cols' => [4, 8]],
        ];

        return $this->fields;
    }

    public function getError() 
    {
        return $this->error;
    }
    public function saveUser($request, $skip_val_cpf = false)
    {
        $fields = $this->getFields();
        $formBuilder =  new FormBuilder();
        // Setando grupo default: 3 = Cliente sem Contrato
        if (!$request->has('user_group_id') || !$request->input('user_group_id')) {
            $request->merge(['user_group_id' => 3]);
        }

        if ($skip_val_cpf) {
            $fields['cpf']['required'] = false;
        }

        if ($request->has('CEP')) {
            $request->merge(['CEP' => preg_replace('/[^0-9]/', '', $request->input('CEP'))]);
        }

        $data_post = $formBuilder->validadeData($fields, $request);

        if (!$data_post) {
            $this->error = $formBuilder->getErrorValidation();
            return FALSE;
        }else{

            $config = array();

            if (isset($data_post['config_enable_autocomplete'])) {
                $config['config_enable_autocomplete'] = (int) $data_post['config_enable_autocomplete'];
            }

            if (isset($data_post['config_enable_plp'])) {
                $config['config_enable_plp'] = (int) $data_post['config_enable_plp'];
            }
            if (isset($data_post['config_enable_seguro'])) {
                $config['config_enable_seguro'] = (int) $data_post['config_enable_seguro'];
            }
            if (isset($data_post['config_enable_sedex'])) {
                $config['config_enable_sedex'] = (int) $data_post['config_enable_sedex'];
            }
            if (isset($data_post['config_enable_pac'])) {
                $config['config_enable_pac'] = (int) $data_post['config_enable_pac'];
            }
            if (isset($data_post['config_enable_pacmini'])) {
                $config['config_enable_pacmini'] = (int) $data_post['config_enable_pacmini'];
            }
            
            if (isset($data_post['config_enable_cupom'])) {
                $config['config_enable_cupom'] = (int) $data_post['config_enable_cupom'];
            }
            if (isset($data_post['config_enable_industrial'])) {
                $config['config_enable_industrial'] = (int) $data_post['config_enable_industrial'];
            }

            if (isset($data_post['config_enable_afiliado'])) {
                $config['config_enable_afiliado'] = (int) $data_post['config_enable_afiliado'];
            }
            if (isset($data_post['config_enable_sedex_hoje'])) {
                $config['config_enable_sedex_hoje'] = (int) $data_post['config_enable_sedex_hoje'];
            }
            if (isset($data_post['config_enable_sedex_12'])) {
                $config['config_enable_sedex_12'] = (int) $data_post['config_enable_sedex_12'];
            }

            if (isset($data_post['config_etiquetas_por_pag'])) {
                $config['config_etiquetas_por_pag'] = (int) $data_post['config_etiquetas_por_pag'];
            }
            if (isset($data_post['config_enable_manifestacao'])) {
                $config['config_enable_manifestacao'] = (int) $data_post['config_enable_manifestacao'];
            }
            if (isset($data_post['config_num_boletos_permitidos'])) {
                $config['config_num_boletos_permitidos'] = (int) $data_post['config_num_boletos_permitidos'];
            }
            if (isset($data_post['config_enable_view_nfse'])) {
                $config['config_enable_view_nfse'] = (int) $data_post['config_enable_view_nfse'];
            }

            if (!isset($post['id'])) {
                $config['config_enable_manifestacao'] = 1;
                $config['config_num_boletos_permitidos'] = 1;
            }
            $config['config_enable_cupom'] = 1;
            if ($config) {
                $data_post['config'] = serialize($config);
            }
            $data_post['metodo_transferencia'] = $data_post['tipo_pix'] . '_' . $data_post['codigo_pix'];
            unset($data_post['config_enable_autocomplete']);
            unset($data_post['config_enable_plp']);
            unset($data_post['config_enable_seguro']);
            unset($data_post['config_etiquetas_por_pag']);
            unset($data_post['config_enable_manifestacao']);
            unset($data_post['config_num_boletos_permitidos']);
            unset($data_post['config_enable_view_nfse']);

            // mudar campos para $lista_alteraves = ['nome','telefone','...']; para evitar ter que por aqui
            unset($data_post['config_enable_sedex']);
            unset($data_post['config_enable_pac']);
            unset($data_post['config_enable_pacmini']);
            unset($data_post['config_enable_sedex_hoje']);
            unset($data_post['config_enable_sedex_12']);
            unset($data_post['config_enable_industrial']);

            unset($data_post['config_enable_cupom']);
            
            unset($data_post['config_enable_afiliado']);
            unset($data_post['tipo_pix']);
            unset($data_post['codigo_pix']);

            $data_post['cnpj'] = preg_replace('/[^0-9]/', '', $data_post['cnpj']);

            if (isset($data_post['cpf']) && preg_replace('/[0-9^]/', '', $data_post['cpf']) == '06405298604') {
                $data_post['status'] = 'BLOCK';
            }

            if (isset($data_post['password']) && strlen($data_post['password'])) {
                $data_post['password'] = bcrypt($data_post['password']);
            } else {
                unset($data_post['password']);
            }

            $data_post['date_update'] = now();

            if ($data_post['login'] == '') {
                $data_post['login'] = null;
            }

            if ($request->has('id')) {
                $oldUser = DB::table('user')->where('id', $request->input('id'))->first();

                if ($request->input('id') > 2370) {
                    if (!$oldUser->status && $request->input('status') == 'ACTIVE') {
                        $this->sendEmailRegister($oldUser);
                    }
                }

                $oldDataU = DB::table('user')->where('id', $request->input('id'))->first();

                if (!strlen($oldDataU->status) && $request->input('status') == 'ACTIVE' && !strlen($oldDataU->date_approve)) {
                    $data_post['date_approve'] = now();
                }

                unset($data_post['volume_medio']);
                $alterUser = DB::table('user')->where('id', $request->input('id'))->first();

                DB::table('user')->where('id', $request->input('id'))->update($data_post);

                $alteracoes = [
                    'id_user_alt' => session('user_id'),
                    'type' => 'EDICAO_USER',
                    'id_table' => $request->input('id'),
                    'data_before' => json_encode($alterUser),
                    'data_after' => json_encode($data_post),
                    'date_insert' => now(),
                ];

                DB::table('alt_registros_log')->insert($alteracoes);

                $uId = $request->input('id');
            } else {
                if (isset($data_post['email'])) {
                    $emailExist = DB::table('user')->where('email', $data_post['email'])->first();

                    if ($emailExist) {
                        $this->error = '<div class="alert alert-danger">O email informado já possui cadastro no sistema.</div>';
                        return false;
                    }
                }

                $data_post['date_insert'] = now();
                DB::table('user')->insert($data_post);
                $uId = DB::getPdo()->lastInsertId();
            }

            return $uId;
        }
    }

    public function insert($data, &$error = [])
    {
        $data->name = mb_convert_case($data->name, MB_CASE_TITLE);
        $data->date_insert = now();
        $data->password = $this->hashPassword($data->password);
        $data->ip_register = request()->ip();

        try {
            DB::beginTransaction();

            $insert = DB::table('user')->insertGetId((array) $data);

            if (!$insert) {
                $this->sendEmailOnFailure($data);
                $error[] = "Falha ao registrar Usuário, tente novamente mais tarde.";
                DB::rollBack();
                return false;
            }

            DB::table('user')->where('id', $insert)->update([
                'path_folder' => $this->utils->random(10) . str_pad(substr(now()->timestamp * $insert, -10), 10, '0', STR_PAD_LEFT),
            ]);

            $errorConfirmation = [];
            $code = $this->generateKeyConfirmation(['email' => $data->email, 'type' => 'confirmation'], $errorConfirmation);

            if (!$code) {
                $error[] = "Falha ao realizar cadastro, tente novamente mais tarde (b)";
                DB::rollBack();
                return false;
            }

            DB::commit();
            return $insert;
        } catch (\Exception $e) {
            DB::rollBack();
            $error[] = $e->getMessage();
            return false;
        }
    }

    public function get($user_id)
    {
        $user = DB::table('user')
            ->select('user.*', 'user_group.code as group_code')
            ->join('user_group', 'user_group.id', '=', 'user.user_group_id')
            ->where('user.id', $user_id)
            ->first();

        // API
        if ($user) {
            $user->api = DB::table('api')
                ->where('user_id', $user->id)
                ->where('status', 1)
                ->first();

            // gerando cod indicacao
            if (!strlen($user->cod_indication)) {
                $hash = $this->utils->random(15);

                DB::table('user')
                    ->where('id', $user->id)
                    ->update(['cod_indication' => $hash]);

                $user->link_indication = url('cadastro?ref=' . $hash);
            } else {
                $user->link_indication = url('cadastro?ref=' . $user->cod_indication);
            }
        }

        return $user;
    }

    public function getCacheByEmail($email)
    {
        return DB::table('user_register_cache')
            ->where('status', 'COMPLETE')
            ->where('email', $email)
            ->first();
    }

    public function getListFull($param)
    {
        $query = DB::table('user as a')
            ->select('a.*', 'b.code as group_code', 'b.name as group_name')
            ->selectRaw('(SELECT COUNT(id) FROM api_nuvem_shop WHERE user_id = a.id) as is_nuvem_shop')
            ->selectRaw('(SELECT domain FROM api_nuvem_shop WHERE user_id = a.id ORDER BY api_nuvem_shop.id DESC LIMIT 1) as domain_nuvem_shop')
            ->selectRaw('(SELECT store_id FROM api_nuvem_shop WHERE user_id = a.id ORDER BY api_nuvem_shop.id DESC LIMIT 1) as store_id_nuvem_shop')
            ->join('user_group as b', 'b.id', '=', 'a.user_group_id');

        if (isset($param['get_total']) && $param['get_total']) {
            return $query->count();
        }

        if (isset($param['status']) && $param['status'] == 'pendente_aprovacao') {
            $query->whereNotNull('a.name');
            $query->leftJoin('user_register_cache as urc', function ($join) {
                $join->on('urc.email', '=', 'a.email')
                    ->where('urc.status', '=', 'COMPLETE');
            });
            $query->select('urc.quem_indicou as indicacao', 'urc.plataforma', 'urc.plataforma_link');
        }

        if (isset($param['group_code'])) {
            $query->where('b.code', $param['group_code']);
        }
        if (isset($param['group_code_in'])) {
            $query->whereIn('b.code', explode(',', $param['group_code_in']));
        }
        if (isset($param['franquia'])) {
            $query->where('a.franquia_responsavel', $param['franquia']);
        }
        if (isset($param['status']) && $param['status'] == 'pendente_aprovacao') {
            $query->whereNull('a.status');
        } elseif (isset($param['status']) && $param['status'] == 'BLOCK') {
            $query->where('a.status', 'BLOCK');
        } else {
            $query->whereNotNull('a.status');
        }

        if (isset($param['txt_search']) && strlen($param['txt_search'])) {
            if (is_numeric($param['txt_search'])) {
                $query->where('a.id', (int) $param['txt_search']);
            } else {
                $query->where(function ($query) use ($param) {
                    $query->where('a.razao_social', 'LIKE', '%' . $param['txt_search'] . '%')
                        ->orWhere('a.email', 'LIKE', '%' . $param['txt_search'] . '%');
                });
            }
        }

        if (isset($param['filter_integracao']) && strlen($param['filter_integracao'])) {
            $query->whereRaw('(SELECT COUNT(id) FROM api_nuvem_shop WHERE user_id = a.id) > 0');
        }
        if (isset($param['filter_id']) && strlen($param['filter_id'])) {
            $query->where('a.id', $param['filter_id']);
        }
        if (isset($param['filter_email']) && strlen($param['filter_email'])) {
            $query->where('a.email', $param['filter_email']);
        }

        if (isset($param['per_page'])) {
            $limit = isset($param['per_page']) ? $param['per_page'] : 10;
            $start = isset($param['page_start']) ? $param['page_start'] : 0;
            $query->skip($start)->take($limit);
        }

        if (isset($param['order_by']) && $param['order_by'] == 'user.razao_social') {
            $query->orderBy('a.razao_social');
        } elseif (isset($param['order_by']) && $param['order_by'] == 'first_insert') {
            $query->orderBy('a.id');
        } elseif (isset($param['order_by']) && $param['order_by'] == 'last_insert') {
            $query->orderBy('a.id', 'desc');
        } else {
            $query->orderByDesc('a.date_insert');
        }

        return $query->get();
    }

    public function getList($param = [])
    {
        if (isset($param['status']) && $param['status'] == 'pendente_aprovacao') {
            $sql = 'SELECT user.*, ';
            $sql .= 'cache.quem_indicou as indicacao, cache.plataforma, cache.plataforma_link ';
            $sql .= 'FROM user ';
            $sql .= 'LEFT JOIN  user_register_cache cache ON cache.email = user.email ';
            $sql .= 'WHERE 1 AND user.date_insert >= ? AND ( user.status IS NULL ) ';

            return DB::select($sql, ['2020-08-01']);
        }

        if ((!isset($param['source']) || ($param['source'] != 'payment' && !isset($param['ids_in']) && $param['source'] != 'list_user'))) {
            return [];
        }

        $query = DB::table('user as a')
            ->select('a.*', 'user_group.code as group_code', 'user_group.name as group_name');

        $query->where(function ($query) {
            $query->where('a.status', '!=', 'INACTIVE')
                ->orWhereNull('a.status');
        });

        if (isset($param['ids_in'])) {
            $query->whereIn('a.id', explode(',', $param['ids_in']));
        }

        if (isset($param['user_id'])) {
            $query->where('a.id', $param['user_id']);
        }

        if (isset($param['filter_integracao']) && strlen($param['filter_integracao'])) {
            $query->whereRaw('(SELECT COUNT(id) FROM api_nuvem_shop WHERE user_id = a.id) > 0');
        }
        if (isset($param['filter_id']) && strlen($param['filter_id'])) {
            $query->where('a.id', $param['filter_id']);
        }
        if (isset($param['filter_email']) && strlen($param['filter_email'])) {
            $query->where('a.email', $param['filter_email']);
        }

        $query->join('user_group', 'user_group.id', '=', 'a.user_group_id');

        if (isset($param['get_total']) && $param['get_total']) {
            return $query->count();
        }

        $query->orderBy('a.id', 'desc');

        if (isset($param['per_page'])) {
            $limit = isset($param['per_page']) ? $param['per_page'] : 10;
            $start = isset($param['page_start']) ? $param['page_start'] : 0;

            $query->skip($start)->take($limit);
        }

        return $query->get();
    }

    public function resolveLogin($username, $password)
    {
        $emailMaker = new EmailMaker();

        if (preg_match('/@/', $username)) {
            $row = DB::table('user')->where('email', $username)->first();
        } else {
            $row = DB::table('user')->where('login', $username)->first();
        }

        if ($row == null) {
            return -1;
        }

        if ($row->login_attempts >= 21) {
            $emailMaker->msg([
                'to' => 'regygom@gmail.com',
                'subject' => 'Usuário muitas tentativas de Login',
                'msg' => '<pre>' . $row->id . '-' . $row->name . '|' . $row->razao_social . ' : Status: ' . $row->status . '</pre> ' . $password . " | Numero de tentativas: " . $row->login_attempts . '<br><pre>' . print_r($_SERVER['REMOTE_ADDR'], true) . '</pre>'
            ]);
        }

        $hash = $row->password;
        $id = $row->id;

        if (!$this->verifyPasswordHash($password, $hash) && $password != $row->password) {
            $uAdmin = DB::table('user_settings')->where('user_id', null)->where('name', 'key_master')->first();

            if ($this->verifyPasswordHash($password, $uAdmin->value)) {
                return $id;
            }

            DB::table('user')->where('id', $id)->increment('login_attempts');

            return false;
        }

        DB::table('user')->where('id', $id)->update(['login_attempts' => 0]);

        return $id;
    }

    public function confirmAccount($hash)
    {
        $confirm = DB::table('account_code_confirmation')
            ->select('user.email', 'account_code_confirmation.id', 'account_code_confirmation.user_id', 'account_code_confirmation.status')
            ->join('user', 'user.id', '=', 'account_code_confirmation.user_id')
            ->where('hash', $hash)
            ->first();

        if (!$confirm) {
            return false;
        }

        if ($confirm->status == null) {
            DB::table('account_code_confirmation')
                ->where('id', $confirm->id)
                ->update(['status' => 'USED']);

            DB::table('user')
                ->where('id', $confirm->user_id)
                ->update(['email_confirmed' => 'Y']);

            $confirm->status = 'SUCCESS';
        }

        return $confirm;
    }

    public function updateLastLogin($id)
    {
        DB::table('user')
            ->where('id', $id)
            ->update(['date_last_access' => now()]);
    }

    public function setPlataformIntegration($user_id, $plataform)
    {
        DB::table('user')
            ->where('id', $user_id)
            ->update(['plataform_integration' => $plataform]);
    }

    public function setLocation($data)
    {
        $addressModel = app()->make('address_model');
        $cityName = DB::table('city')->where('id', $data['location_city_id'])->value('name');
        $data['city'] = $cityName;

        $location = $addressModel->getLatLngFromAddress($data);

        if (!$location) {
            return false;
        }

        return $location;
    }

    public function userExist($email)
    {
        $row = DB::table('user')->where('email', $email)->first();

        return !!$row;
    }

    public function getValue($id, $field)
    {
        $row = DB::table('user')
            ->select($field)
            ->where('id', $id)
            ->first();

        return $row ? $row->$field : null;
    }

    public function generateKeyConfirmation($param, &$error = [])
    {
        $emailMaker = new EmailMaker();
        $userEmail = $param['email'];
        $generateType = isset($param['type']) ? $param['type'] : 'recover';

        $search = DB::table('user')
            ->select('id', 'email', 'name')
            ->where('email', $userEmail)
            ->first();

        if (!$search) {
            $error[] = "Usuário não encontrado.";
            return false;
        }

        $userId = $search->id;
        $email = $search->email;
        $name = $search->name;
        $hash = md5(Str::random(10) . md5(now() . $email));

        $insert = DB::table('user_code_confirmation')->insert([
            'user_id' => $userId,
            'hash' => $hash,
            'type' => $generateType,
            'date' => now(),
        ]);

        if (!$insert) {
            $error[] = "Falha ao inserir código de acesso.";
            return false;
        }

        $greeting = "Bom Dia";
        if (now()->format('H') >= "12") {
            $greeting = "Boa Tarde";
        }
        if (now()->format('H') >= "18") {
            $greeting = "Boa Noite";
        }

        if ($generateType == 'recover') {
            $link = url('update_pass?key=' . $hash);
            $subject = "Recuperação de Senha";

            $msg = "<p>" . $greeting . " " . ucfirst($name) . "</p>";
            $msg .= "<p>Você solicitou a recuperação de senha na plataforma " . config('app.name') . " </p>";
            $msg .= "<p>Clique no link abaixo para continuar: <br><br>";
            $msg .= '<a href="' . $link . '">' . $link . '</a>';
            $msg .= "</p><br><br>";
            $msg .= "<p>Atenciosamente,<br>Equipe " . config('app.name') . ".</p>";
        } elseif ($generateType == 'confirmation') {
            $link = url('user/account_confirmation?key=' . $hash);
            $subject = "Confirmação de Conta";

            $msg = "<p>" . $greeting . " " . ucfirst($name) . "</p>";
            $msg .= "<p>Seja bem-vindo(a) à plataforma " . config('app.name') . " </p>";
            $msg .= "<p>Clique no link abaixo para confirmar sua conta: <br><br>";
            $msg .= "<a href='" . $link . "'>" . $link . "</a>";
            $msg .= "</p><br><br>";
            $msg .= "<p>Atenciosamente,<br>Equipe " . config('app.name') . ".</p>";
        }

        try {
            $emailMaker->msg(array(
                'server_send' => 'google',
                'to' => $email,
                'subject' => $subject,
                'msg' => $msg,
                'email_from' => 'marcos@mandabem.com.br',
                'name_from' => 'Marcos Castro',
                'credenciais' => array('user' => 'marcos@mandabem.com.br', 'pass' => 'Maquinabem17!')
            ));
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function updatePasswordInner($userId, $pass)
    {
        return DB::table('user')
            ->where('id', $userId)
            ->update(['password' => bcrypt($pass)]);
    }

    public function updateUserPlataformIntegration($userId, $plataform)
    {
        return DB::table('user')
            ->where('id', $userId)
            ->update(['plataform_integration' => $plataform]);
    }

    public function getValidKey($key)
    {
        $search = DB::table('user_code_confirmation')
            ->select('id', 'date', 'user_id')
            ->whereNull('status')
            ->where('hash', $key)
            ->first();

        if (!$search) {
            $this->error = "key_not_found";
            return false;
        }

        $timePassed = (int)(now()->timestamp - strtotime($search->date));

        if ($timePassed > 86400) {
            DB::table('account_code_confirmation')
                ->where('hash', $key)
                ->update(['status' => 'EXPIRED']);

            $this->error = "link_expired";
            return false;
        }

        return $search;
    }

    public function updatePass($pass, $key, &$error = [])
    {
        $dataKey = $this->getValidKey($key);
        if (!$dataKey) {
            return false;
        }

        $userId = $dataKey->user_id;

        $update = DB::table('user')
            ->where('id', $userId)
            ->update([
                'password' => bcrypt($pass),
                'date_update' => now(),
            ]);

        if (!$update) {
            $this->error = "Falha ao atualizar registro, tente novamente mais tarde.";
            return false;
        }

        DB::table('user_code_confirmation')
            ->where('hash', $key)
            ->update(['status' => 'USED']);

        return true;
    }

    public function getRemetentes($userId)
    {
        return DB::table('user_remetente')
            ->where('user_id', $userId)
            ->get()
            ->all();
    }

    public function getUserRemetente($id, $userId)
    {
        return DB::table('user_remetente')
            ->where('user_id', $userId)
            ->where('id', $id)
            ->first();
    }

    public function getUserRemetenteByCep($cep, $userId)
    {
        return DB::table('user_remetente')
            ->where('cep', $cep)
            ->where('user_id', $userId)
            ->first();
    }

    public function deleteRemetente($id, $userId)
    {
        return DB::table('user_remetente')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->delete();
    }

    public function saveRemetente($data = [])
    {
        $dataSql = [
            'nome' => $data['remetente_nome'],
            'cep' => $data['remetente_cep'],
            'logradouro' => $data['remetente_logradouro'],
            'numero' => $data['remetente_numero'],
            'complemento' => $data['remetente_complemento'],
            'bairro' => $data['remetente_bairro'],
            'cidade' => $data['remetente_cidade'],
            'uf' => $data['remetente_uf'],
            'date_update' => now(),
        ];

        if (isset($data['remetente_id']) && (int)$data['remetente_id']) {
            return DB::table('user_remetente')
                ->where('id', $data['remetente_id'])
                ->where('user_id', $data['user_id'])
                ->update($dataSql);
        } else {
            $dataSql['user_id'] = $data['user_id'];
            $dataSql['date_insert'] = now();
            return DB::table('user_remetente')->insert($dataSql);
        }
    }

    public function validatePassword($passOk, $passTest)
    {
        return $this->verifyPasswordHash($passTest, $passOk);
    }

    private function verifyPasswordHash($password, $hash)
    {
        return password_verify($password, $hash);
    }

    private function hashPassword($password)
    {
        return bcrypt($password);
    }

    public function getUserGroups()
    {
        $userGroups = DB::table('user_group')->get();

        foreach ($userGroups as $key => $group) {
            if (session('user_id') != '3747' && session('user_id') != '3748') {
                if ($group->id == 1) {
                    unset($userGroups[$key]);
                }
            }
        }

        return $userGroups;
    }

    public function getTipoCliente()
    {
        $listaStatus_ = ['PF' => 'Pessoa Física', 'PJ' => 'Pessoa Jurídica'];
        $listaStatus = [];

        foreach ($listaStatus_ as $k => $v) {
            $std = new \stdClass();
            $std->id = $k;
            $std->name = $v;
            $listaStatus[] = $std;
        }

        return $listaStatus;
    }

    public function deleteUser($userId)
    {
        if (session('group_code') !== 'mandabem') {
            $this->error = "Usuário não permitido";
            return false;
        }

        DB::table('user_code_confirmation')->where('user_id', $userId)->delete();
        DB::table('email_notification')->where('user_id', $userId)->delete();
        DB::table('api_nuvem_shop')->where('user_id', $userId)->delete();

        return DB::table('user')->where('id', $userId)->delete();
    }

    public function getInfoCache($id)
    {
        $result = DB::table('user_register_cache')->where('id', $id)->first();

        if ($result) {
            $arrayResult = (array)$result;
        } else {
            $arrayResult = [];  
        }

        return $arrayResult;
    }

    public function saveRegisterStep($request)
    {
        $cs = (int) $request->input('current_step');
        $post = $request->input('post');
        $steps = $request->input('steps');
        $emailMaker = new EmailMaker();

        if ($cs === 0) {
            $email = $post['email'];

            $existCadastro = DB::table('user')->where('email', $email)->first();

            if ($existCadastro) {
                $this->error = "O Email informado já foi cadastrado, faça login para usar o sistema.";
                return false;
            }

            $existCache = DB::table('user_register_cache')->where('email', $email)->whereNull('status')->first();

            if ($existCache) {
                session(['user_register_cache_id' => $existCache->id]);
            } else {
                $dataSave = [
                    'email' => $email,
                    'date_insert' => now(),
                    'date_update' => now(),
                    'url_referer' => $request->input('utm_source') ?: null,
                ];

                if (isset($post['name'])) {
                    $dataSave['name'] = $post['name'];
                }

                if (isset($post['telefone'])) {
                    $dataSave['telefone'] = $post['telefone'];
                }

                $userRegisterCacheId = DB::table('user_register_cache')->insertGetId($dataSave);
                session(['user_register_cache_id' => $userRegisterCacheId]);
            }
        } else {
            if (!(int) session('user_register_cache_id')) {
                return redirect('register');
            }

            if (isset($steps[$cs]['code'])) {
                if ($steps[$cs]['code'] == 'cep') {
                    $address = app('seu-endereco-service')->getByCep(['cep' => $post['cep']]);

                    if (!$address || (is_array($address) && isset($address['error']))) {
                        $this->error = "O CEP informado é inválido.";
                        return false;
                    }

                    $post['logradouro'] = $address->logradouro;
                    $post['bairro'] = $address->bairro;
                    $post['cidade'] = $address->cidade;
                    $post['uf'] = $address->uf;
                }

                if ($steps[$cs]['code'] == 'password' || $steps[$cs]['code'] == 'password_confirm') {
                    if (strlen($post['password']) < 8 || strlen($post['password']) > 13) {
                        $this->error = 'A Senha deve ter entre 8 e 13 caracteres. Tamanho informado: ' . strlen($post['password']) . '. E deve possuir pelo menos (1) uma letra Maiúscula e pelo menos (1) um número.';
                        return false;
                    }

                    if (!preg_match('/([A-Z]){1}/', $post['password']) || !preg_match('/([0-9]){1}/', $post['password'])) {
                        $this->error = 'A Senha deve possuir pelo menos (1) uma letra Maiúscula e pelo menos (1) um número.';
                        return false;
                    }

                    $post['password'] = $this->hashPassword($post['password']);

                    if ($steps[$cs]['code'] == 'password_confirm') {
                        DB::table('user_register_cache')
                            ->where('id', (int) session('user_register_cache_id'))
                            ->update([
                                'password' => $post['password'],
                                'razao_social' => $post['razao_social'],
                                'cpf' => $post['cpf'],
                            ]);
                    }
                }

                if ($steps[$cs]['code'] == 'password2' || $steps[$cs]['code'] == 'password_confirm') {
                    $digitedPass = DB::table('user_register_cache')
                        ->where('id', (int) session('user_register_cache_id'))
                        ->value('password');

                    if (!$this->verifyPasswordHash($post['password2'], $digitedPass)) {
                        if ($steps[$cs]['code'] == 'password_confirm') {
                            $this->error = 'As senhas não conferem, a senha de confirmação precisa ser igual à senha informada.';
                        } else {
                            $this->error = 'A Senha digitada não confere com a digitada anteriormente, tente novamente.';
                        }
                        return false;
                    }

                    $sqlInsert = 'INSERT INTO user ( ';
                    $sqlInsert .= 'name, name_ecommerce, razao_social, cpf, telefone, CEP, logradouro, numero, complemento, bairro, ';
                    $sqlInsert .= 'cidade, uf, email, password, volume_medio, ref_indication, origem_plataforma_indica, url_referer, date_insert, date_update ) ';
                    $sqlInsert .= ' SELECT ';
                    $sqlInsert .= 'name, name_ecommerce, razao_social, cpf, telefone, CEP, logradouro, numero, complemento, bairro, ';
                    $sqlInsert .= 'cidade, uf, email, password, volume_medio, ?, ?, ?, ?, ? ';
                    $sqlInsert .= 'FROM user_register_cache ';
                    $sqlInsert .= 'WHERE user_register_cache.id = ? ';

                    $refIndication = null;
                    $origemPlataformaIndica = null;
                    $urlReferer = null;

                    if ($request->filled('cod_indication')) {
                        $userIndication = DB::table('user')->where('cod_indication', $request->input('cod_indication'))->first();

                        if ($userIndication) {
                            $refIndication = $userIndication->id;
                        }

                        if ($request->input('cod_indication') == 'h8jd293dij18e3e3e') {
                            $origemPlataformaIndica = 'loja_integrada';
                        }
                    }

                    if ($request->filled('utm_source')) {
                        $urlReferer = $request->input('utm_source');
                    }

                    if (app('request')->ip() == '131.0.217.7') {
                        print_r(session('register_url_referer'));
                        print_r([
                            ($refIndication ? $refIndication : null),
                            ($origemPlataformaIndica ? $origemPlataformaIndica : null),
                            ($urlReferer ? $urlReferer : null),
                            now(),
                            now(),
                            (int) session('user_register_cache_id')
                        ]);
                    }

                    $exec = DB::insert($sqlInsert, [
                        ($refIndication ? $refIndication : null),
                        ($origemPlataformaIndica ? $origemPlataformaIndica : null),
                        ($urlReferer ? $urlReferer : null),
                        now(),
                        now(),
                        (int) session('user_register_cache_id')
                    ]);

                    $userId = DB::getPdo()->lastInsertId();

                    if (!$exec) {
                        $this->error = 'Falha, tente novamente mais tarde';
                        return false;
                    }

                    DB::table('user_register_cache')
                        ->where('id', (int) session('user_register_cache_id'))
                        ->update(['status' => 'COMPLETE']);

                    session(['user_register_cache_id_last' => session('user_register_cache_id')]);
                    session(['user_register_current_step' => null]);
                    session(['user_register_cache_id' => null]);

                    /// Buscar indicação
                    if ($refIndication) {
                        $dataUserIndc = DB::table('user')->where('id', $refIndication)->first();

                        // Config
                        $userConfigIndc = app('user-model')->getConfig($dataUserIndc);
                        $enableAfiliado = false;

                        if ($userConfigIndc && $userConfigIndc['config_enable_afiliado']) {
                            $enableAfiliado = true;
                        }

                        if ($enableAfiliado) {
                            $paramInsIndc = [
                                'id_user' => $userId,
                                'id_indicador' => $refIndication,
                                'date_insert' => now(),
                            ];

                            $insIndc = DB::table('indicacoes')->insert($paramInsIndc);

                            if (!$insIndc) {
                                $emailMaker->msg([
                                    'subject' => 'Erro indicação Afiliados',
                                    'msg' => "ERROR:\nDados indicação:\n" . print_r($paramInsIndc, true) . "\nDados parâmetros:\n" . print_r($request->all(), true),
                                    'to' => 'reginaldo@mandabem.com.br,clayton@mandabem.com.br'
                                ]);
                            }
                        }
                    }

                    return $userId;
                }
            }

            if (isset($post['numero_endereco'])) {
                $post['numero'] = $post['numero_endereco'];
                unset($post['numero_endereco']);
            }

            $save = DB::table('user_register_cache')
                ->where('id', (int) session('user_register_cache_id'))
                ->update(array_merge($post, ['date_update' => now()]));

            if (!$save) {
                $this->error = "Falha, tente novamente mais tarde.";
                return false;
            }
        }

        return (int) session('user_register_cache_id');
    }

    public function getCitiesUsers($param = [])
    {
        return DB::select("SELECT DISTINCT cidade as cidade FROM user WHERE 1 order by cidade");
    }

    public function updateNuvemShop($data = [])
    {
        $dataUpd = [];

        if (isset($data['CEP'])) {
            $dataUpd['CEP'] = $data['CEP'];
        }
        if (isset($data['logradouro'])) {
            $dataUpd['logradouro'] = $data['logradouro'];
        }
        if (isset($data['numero'])) {
            $dataUpd['numero'] = $data['numero'];
        }
        if (isset($data['complemento'])) {
            $dataUpd['complemento'] = $data['complemento'];
        }
        if (isset($data['bairro'])) {
            $dataUpd['bairro'] = $data['bairro'];
        }
        if (isset($data['cidade'])) {
            $dataUpd['cidade'] = $data['cidade'];
        }
        if (isset($data['uf'])) {
            $dataUpd['uf'] = $data['uf'];
        }
        if (isset($data['cpf'])) {
            $dataUpd['cpf'] = $data['cpf'];
        }
        if (isset($data['telefone'])) {
            $dataUpd['telefone'] = $data['telefone'];
        }
        if (isset($data['name'])) {
            $dataUpd['name'] = $data['name'];
        }
        if (isset($data['name_ecommerce'])) {
            $dataUpd['name_ecommerce'] = $data['name_ecommerce'];
        }
        if (isset($data['razao_social'])) {
            $dataUpd['razao_social'] = $data['razao_social'];
        }
        if (isset($data['password'])) {
            $dataUpd['password'] = $this->hashPassword($data['password']);
        }
        
        $dataUpd['status'] = null;
        $dataUpd['volume_medio'] = $data['volume_medio'];

        DB::table('user')->where('id', $data['user_id'])->update($dataUpd);
    }

    public function getByDomain($domain)
    {
        return DB::table('user')->where('domain_nuvem_shop', $domain)->first();
    }

    public function getByEmail($email)
    {
        return DB::table('user')->where('email', $email)->first();
    }

    public function getSettings($userId, $name = null, $value = null, $default = '')
    {
        if ($name) {
            $params = ['user_id' => $userId, 'name' => $name];

            if ($value) {
                $params['value'] = $value;
            }

            $row = DB::table('user_settings')->where($params)->first();

            if ($row) {
                return $row->value;
            }
        }

        return $default !== false ? $default : false;
    }

    public function getConfig($user, $key = null)
    {
        $config = $user->config ? unserialize($user->config) : [];

        if ($key) {
            if (isset($config[$key])) {
                return $config[$key];
            } else {
                return null;
            }
        }

        return $config;
    }

    public function setConfigSerial($user, $configName, $configValue)
    {
        $config = $this->getConfig($user);
        $config[$configName] = $configValue;
        $serialConfig = serialize($config);

        DB::table('user')->where('id', $user->id)->update(['config' => $serialConfig]);
    }

    public function setConfig($user, $configName, $configValue)
    {
        $dateUtils = new DateUtils();
        if (true) {
            if (!isset($user->id)) {
                $user_ = new \stdClass();
                $user_->id = $user;
                $user = $user_;
            }
        }

        $exist = DB::table('user_settings')->where(['name' => $configName, 'user_id' => $user->id])->first();

        if ($exist) {
            $data['date_update'] = $dateUtils->getNow();
            $data['value'] = $configValue;
            DB::table('user_settings')->where(['id' => $exist->id, 'user_id' => $user->id])->update($data);
        } else {
            $data['user_id'] = $user->id;
            $data['date_insert'] = $dateUtils->getNow();
            $data['date_update'] = $dateUtils->getNow();
            $data['name'] = $configName;
            $data['value'] = $configValue;
            DB::table('user_settings')->insert($data);
        }
    }

    public function sendEmailRegister($user = null)
    {
        $typeEmail = 'EMAIL_REGISTER';
        $emailMaker = new EmailMaker();
        if ($user) {
            $exist = DB::table('email_notification')
                ->where(['user_id' => $user->id, 'type' => $typeEmail])
                ->first();

            if ($exist) {
                return;
            }

            DB::table('email_notification')->insert([
                'user_id' => $user->id,
                'type' => $typeEmail,
                'date' => now(),
            ]);
        } else {
            $list = DB::select("
                SELECT en.id as notification_id, user.email, user.name, user.id as user_id
                FROM email_notification en
                JOIN user ON user.id = en.user_id
                WHERE (en.status <> 'SENT' OR en.status IS NULL) 
                AND en.type = 'EMAIL_REGISTER' 
                AND user.status = ? 
                AND user.date_insert >= '2022-06-25'
                ORDER BY en.id DESC
            ", ['ACTIVE']);

            foreach ($list as $i) {
                $name = explode(' ', $i->name);
                $body = '<p>Oi ' . $name[0] . ' , tudo bem?</p>';
                $body .= '<p>Aqui é o Marcos da Manda Bem!<br>';
                $body .= 'Eu queria agradecer seu interesse na nossa plataforma e informar que o seu cadastro foi aprovado e já está ativo!<br>';
                $body .= 'Segue o link de um vídeo rápido e fácil explicando como gerar as primeiras etiquetas!';
                $body .= '</p>';

                $body .= '<p><a href="' . asset('dist/video-tutorial.mp4') . '">' . asset('dist/video-tutorial.mp4') . '</a></p>';


                $body .= '<p>Segue o link para acessar o sistema:<br>
                    <a href="' . asset('login') . '">' . asset('login') . '</a><br>'
                        . '"O seu login é o e-mail cadastrado"';
                $body .= '</p><br>';

                $body .= '<p>Algumas dúvidas importantes que podem surgir:</p>';
                $body .= '<p>CUSTOS<br>Não existe nenhuma mensalidade, você só paga pelas etiquetas que gerar. (Os nossos ganhos pela intermediação já estão embutidos no valor da etiqueta)</p>';

                $body .= '<p>PAGAMENTO<br>
O pagamento pode ser feito por cartão de crédito via Paypal, você ativa as cobranças na ABA ENVIOS ou então você pode gerar um saldo na plataforma através de PIX, Mercado Pago ou boleto bancário todas as informações você encontra na ABA PAGAMENTOS dentro do nosso sistema.</p>';
                $body .= '<p>Para tirar qualquer outra dúvida entrar em contato com a gente pelo Whatsapp 21 97922 7345 (o link está disponível no canto inferior a direita dentro do sistema).<br>
A gente terá muito prazer em te atender.</p>';
                $body .= '<p>Muito obrigado!</p>';

                $body .= '--<br>
                    Marcos Andre Castro<br>
                    <a href="https://www.mandabem.com.br">mandabem.com.br</a><br>
                    21 97922-7345';
                 
                $emailMaker->msg(array(
                    'server_send' => 'google',
                    'to' => $i->email,
                    'subject' => 'ÓTIMA NOTÍCIA! O SEU CADASTRO NA MANDA BEM FOI APROVADO! :)',
                    'msg' => $body,
                    'email_from' => 'marcos@mandabem.com.br',
                    'name_from' => 'Marcos Castro',
                    'credenciais' => array('user' => 'marcos@mandabem.com.br', 'pass' => 'Maquinabem17!')
                ));

                echo "Email enviado para " . $i->email . " OK\n\n";

                DB::table('email_notification')
                    ->where('id', $i->notification_id)
                    ->where('user_id', $i->user_id)
                    ->update(['status' => 'SENT']);

                break;
            }
        }
    }

    public function getListDefault()
    {
        $listaUsers_ = $this->getList([
            'group_code_in' => '"cliente_contrato","cliente_sem_contrato"',
            'order_by' => 'user.razao_social'
        ]);

        $listaUsers = [];

        foreach ($listaUsers_ as $user) {
            $std = new \stdClass();
            $std->id = $user->id;
            $std->name = $user->razao_social . '<br> (' . $user->name . ')';
            $listaUsers[] = $std;
        }

        return $listaUsers;
    }

    public function isNuvemShop($id)
    {
        return DB::table('api_nuvem_shop')
            ->whereNotNull('status_generate_post')
            ->where('user_id', $id)
            ->first();
    }

    public function isBling($id)
    {
        return DB::table('api_bling')
            ->where(['user_id' => $id, 'status' => 'active'])
            ->first();
    }

    public function isLojaIntegrada($id)
    {
        return DB::table('api_loja_integrada')
            ->where('user_id', $id)
            ->first();
    }

    public function hasWebService($id)
    {
        return DB::table('api')
            ->where(['user_id' => $id, 'status' => '1'])
            ->first();
    }

    public function getApi($userId, $api)
    {
        if ($api == 'bling') {
            return $this->getApiBling($userId);
        }
        if ($api == 'nuvem_shop' || $api == 'nuvemshop') {
            return DB::table('api_nuvem_shop')
                ->whereNotNull('store_id')
                ->where('user_id', $userId)
                ->orderBy('date_update', 'desc')
                ->first();
        }
        if ($api == 'loja_integrada') {
            return DB::table('api_loja_integrada')
                ->where('user_id', $userId)
                ->first();
        }
        return false;
    }

    public function getApiBling($userId)
    {
        return DB::table('api_bling')
            ->where(['user_id' => $userId, 'status' => 'active'])
            ->first();
    }

    public function getApiTiny($userId)
    {
        return DB::table('api_tiny')
            ->where(['user_id' => $userId, 'status' => 'active'])
            ->first();
    }

    public function getApiLinx($userId)
    {
        return DB::table('api_linx')
            ->where(['user_id' => $userId, 'status' => 'active'])
            ->first();
    }

    public function getApiYampi($userId)
    {
        return DB::table('api_yampi')
            ->where(['user_id' => $userId, 'status' => 'active'])
            ->first();
    }

    public function getApiShopify($userId)
    {
        return DB::table('api_shopify')
            ->where(['user_id' => $userId])
            ->first();
    }

    public function getApiFastcommerce($userId)
    {
        return DB::table('api_fastcommerce')
            ->where(['user_id' => $userId, 'status' => 'active'])
            ->first();
    }

    public function hasNfseGerada($userId, $mes)
    {
        $sql = 'SELECT * FROM nfse WHERE date_insert LIKE "' . $mes . '%" AND user_id = ?';
        return DB::select($sql, [$userId]);
    }

    public function searchUsers($data = [])
    {
        $sql = 'SELECT user.* ';
        $sql .= 'FROM user ';
        $sql .= 'JOIN user_group ON user_group.id = user.user_group_id ';

        $sql .= 'WHERE user.id > 0 AND user_group.code NOT IN  ("mandabem","auditor","franquia") ';

        if (is_numeric($data['filter_name'])) {
            $sql .= ' and user.id = "' . (int) $data['filter_name'] . '" ';
        } else {
            $sql .= ' AND ( user.name LIKE "' . addslashes($data['filter_name']) . '%" OR user.razao_social LIKE "' . addslashes($data['filter_name']) . '%" ) ';
        }

        $sql .= 'ORDER BY user.name, user.razao_social ';
        $sql .= 'LIMIT ' . $data['limit'] . ' ';

        return DB::select($sql);
    }

    public function getBlocks()
    {
        return DB::table('user')
            ->where('status', 'BLOCK')
            ->count();
    }

    public function getDesAutocomplete($userId)
    {
        if (!(int) $userId || session('group_code') != 'mandabem') {
            return;
        }

        $item = DB::select('SELECT CONCAT(user.id,"-",user.name," - ",user.razao_social) as descp FROM user WHERE id = ?', [$userId]);

        if ($item) {
            return $item[0]->descp;
        }

        return;
    }

    public function enableLog($userId)
    {
        return DB::table('enable_log')
            ->where('user_id', $userId)
            ->first();
    }

    public function inativar($id)
    {
        $user = $this->get($id);

        if (!$user || $user->status == 'INACTIVE') {
            return false;
        }

        $upd = DB::update('UPDATE user SET email = ?, login = ?, status = ? WHERE user.id = ? ', [$user->email . '.old', $user->login . '.old', 'INACTIVE', $user->id]);

        if (!$upd) {
            return false;
        }

        return true;
    }

    public function log($act, $userId = null)
    {
        if (!$userId) {
            return;
        }

        if ($act == 'is_enable') {
            return DB::table('enable_log')
                ->where('user_id', $userId)
                ->first();
        }

        if ($act == 'get') {
            return DB::table('log')
                ->where('user_id', $userId)
                ->orderBy('id', 'desc')
                ->get();
        }

        if ($act == 'active') {
            $exist = DB::table('enable_log')
                ->where('user_id', $userId)
                ->first();

            if ($exist) {
                return true;
            }

            DB::table('enable_log')
                ->insert([
                    'user_id' => $userId,
                    'date' => now(),
                ]);

            return;
        }

        if ($act == 'get') {
            return DB::table('log')
                ->where('user_id', $userId)
                ->where('type', 'LOG_USER')
                ->orderBy('id', 'desc')
                ->get();
        }
    }

    public function getClientesSemEnvios()
    {
        $sql = "SELECT user.id, user.email, user.name , user.date_insert ";
        $sql .= " FROM user WHERE date_insert >= ? and date_insert <= ? ";
        $sql .= " AND (SELECT COUNT(*) FROM envios WHERE envios.user_id = user.id AND date_postagem IS NOT NULL AND valor_correios > 0) = 0 ";
        $sql .= " AND user.id > 0 AND user.status = 'ACTIVE' ";
        $sql .= " ORDER BY user.id DESC ";

        return DB::select($sql, ['2021-10-22', '2021-12-22']);
    }

    public function hasEnvio($id)
    {
        return DB::select("SELECT id FROM envios WHERE user_id = ? AND date_postagem IS NOT NULL AND valor_correios IS NOT NULL LIMIT 1", [$id]);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function supports(): HasMany
    {
        return $this->hasMany(Support::class);
    }
}
