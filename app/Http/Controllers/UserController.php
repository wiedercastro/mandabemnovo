<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Api;
use App\Models\Envio;
use App\Models\Cupom;
use App\Models\Afiliado;
use App\Models\Payment;
use App\Libraries\EmailMaker;
use App\Libraries\FormBuilder;
use App\Libraries\DateUtils;
use App\Libraries\Utils;
use App\Libraries\HtmlUtils;
use App\Libraries\Validation;

class UserController extends Controller
{

    private $user_model;
    private $cupom_model;
    private $form_builder;
    private $afiliado_model;
    private $date_utils;
    private $utils;
    private $email_maker;
    private $api_model;
    private $html_utils;
    private $validation;
    private $payment_model;

    public function __construct() {
        $this->user_model = new User();
        $this->cupom_model = new Cupom();
        $this->form_builder = new FormBuilder();
        $this->afiliado_model = new Afiliado();
        $this->date_utils = new DateUtils();
        $this->utils = new Utils();
        $this->email_maker = new EmailMaker();
        $this->api_model = new Api();
        $this->html_utils = new HtmlUtils();
        $this->validation = new Validation();
        $this->payment_model = new Payment();
    }


    public function show(int $id)
    {
        $user = User::select(
                'id',
                'login as usuario',
                'email',
                'name_ecommerce',
                'status',
                'CEP as cep',
                'logradouro',
                'numero',
                'complemento',
                'bairro',
                'cidade',
                'uf',
                'name',
                'email',
                'telefone',
                'tipo_cliente as tipo_emissao',
                'cpf',
                'cnpj',
                'razao_social',
                'grupo_taxa',
                'grupo_taxa_pacmini',
                'ref_indication as link_indicacao',
                'plataform_integration',
            )
            ->where('id', '=', $id)
            ->first();

        if (! $user) {
            abort(204);
        }

        return response()->json(['user' => $user]);
    }

    public function update(Request $req)
    {
        $user = User::findOrFail($req->id);

        if (! $user) {
            abort(404);
        }

        $user->update([
            "login" => $req->usuario,
            //"login" => $req->tipo_usuario,
            "name_ecommerce" => $req->ecommerce,
            "status" => ($req->status_usuario == "ativo" ? "ACTIVE" : ($req->status_usuario == "bloqueado" ? "INACTIVE" : "BLOCK")),
            "cep" => $req->cep,
            "logradouro" => $req->logradouro,
            "numero" => $req->numero,
            "complemento" => $req->complemento,
            "uf" => $req->estado,
            "bairro" => $req->bairro,
            "cidade" => $req->cidade,
            "name" => $req->nome_usuario,
            "email" => $req->email_usuario,
            "telefone" => $req->telefone,
            "tipo_cliente" => $req->tipo_emissao,
            "cpf" => $req->cpf,
            "cnpj" => $req->cnpj,
            "razao_social" => $req->razao_social,
            "grupo_taxa" => $req->grupo_taxa,
            "grupo_taxa_pacmini" => $req->grupo_taxa_mini,
            "ref_indication" => $req->link_indicacao,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dados do usuário atualizado com sucesso :)'
        ]);
    }
    

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::to('/');
    }

    public function profile()
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = new User();
        $fields = $user->getFields();
        
        $user = auth()->user(); 

        $fields['name']['default_value'] = $user->name;
        $fields['razao_social']['default_value'] = $user->razao_social;
        $fields['razao_social']['label'] = "Razão Social (Nome nas Etiquetas)";
        $fields['name_ecommerce']['default_value'] = $user->name_ecommerce;
        $fields['name_ecommerce']['label'] = "E-commerce (Nome nos emails enviados)";
        $fields['CEP']['default_value'] = $user->CEP;
        $fields['logradouro']['default_value'] = $user->logradouro;
        $fields['numero']['default_value'] = $user->numero;
        $fields['complemento']['default_value'] = $user->complemento;
        $fields['bairro']['default_value'] = $user->bairro;
        $fields['cidade']['default_value'] = $user->cidade;
        $fields['uf']['default_value'] = $user->uf;

        foreach ($fields as $k => $v) {
            $fields[$k]['disabled'] = true;
        }

        // dd($fields);exit;

        $data = [
            'fields_form' => $fields,
            'user_id' => $user->id
        ];
        

        return view('layouts.user.profile', $data);
    }

    public function getInfoComplementar($user_id) 
    {
        if (session(['user_is_logged' => false])) {
            echo json_encode(['redirect' => URL::to('login')]);

            return;
        }
        $user = $this->user_model->get($user_id);
        $user_cache = $this->user_model->getCacheByEmail($user->email);
        $html = '';
        if ($user_cache) {
            $html .= "<hr><h5 style='margin-bottom: 3px;'>Informações Complementares:</h5>";
            if ($user_cache->quem_indicou) {
                $html .= '<strong><span style="color: blue;">Indicação:</span> <span style="color: green;">' . $user_cache->quem_indicou . '</span></strong><br>';
            }
            if ($user_cache->plataforma_link || $user_cache->plataforma) {
                $html .= '<strong><span style="color: blue;">Plataforma: </span>' . ($user_cache->plataforma ? $user_cache->plataforma : 'Não informado');
                if ($user_cache->plataforma_link) {
                    $html .= '<br>';
                    if (preg_match('/http(.*?):\/\//', $user_cache->plataforma_link)) {
                        $html .= '(<a href="' . $user_cache->plataforma_link . '" target="_blank">' . $user_cache->plataforma_link . '</a>)</strong>';
                    } else {
                        $html .= '(<span style="color: blue;">' . $user_cache->plataforma_link . '</span>)</strong>';
                    }
                }
            }
        }

        echo json_encode(['html' => $html]);
    }

    public function aprovarIndicacao(Request $request) 
    {
        $post = $request->input();
        $aprovar = $this->afiliado_model->getById($post['id']);

        $result = array();
        if ($aprovar) {
            $update = $this->afiliado_model->update($post['id']);
        } else {
            $error[] = "Usuário não possui indicação";
        }


        $result = array();
        $error = array();
        if ($error) {
            $result['title'] = '  Sem Indicação!';
            $result['html'] = '<p>Usuário não possui indicação!';
            $result['footer'] = '<button class="btn btn-danger" type="button" data-dismiss="modal">Fechar</button>';

            echo json_encode($result);
        } else {

            $result['title'] = '<i class="fa fa-check"></i> Sucesso!';
            $result['html'] = '<p>Usuário reenvindicado com sucesso!';
            $result['footer'] = '<button class="btn btn-danger" type="button" data-dismiss="modal">Fechar</button>';

            echo json_encode($result);
        }
    }

    public function index(Request $request) 
    {
        if (session(['user_is_logged' => false])) {
            redirect('login');
            return;
        }
    

        $data = new \stdClass();
        
        $data->fields_form = $this->form_builder->mountFormV1($this->user_model->getFields());
        $data->user_groups = $this->user_model->getUserGroups();
       
        $data->info_groups = array();
        foreach ($data->user_groups as $g) {
            $data->info_groups[$g->id] = $g->code;
        }
        
        // Search
        $params = array();
        $url = '';
        
        $data->txt_search = '';
        if ($request->input('txt_search') && strlen($request->input('txt_search'))) {
            $params['txt_search'] = $data->txt_search = trim($request->input('txt_search'));
            $url .= 'txt_search=' . $request->input('txt_search') . '&';
        }
        $data->user_id = '';
        if ($request->input('user_id') && strlen($request->input('user_id'))) {
            $params['user_id'] = $data->user_id = trim($request->input('user_id'));
            $url .= 'user_id=' . $request->input('user_id') . '&';
        }
        $data->filter_integracao = '';
        $data->filter_id = '';
        if ($request->input('filter_integracao') && strlen($request->input('filter_integracao'))) {
            $params['filter_integracao'] = $data->filter_integracao = trim($request->input('filter_integracao'));
            $url .= 'filter_integracao=' . $request->input('filter_integracao') . '&';
        }
        if ($request->input('filter_id') && strlen($request->input('filter_id'))) {
            $params['filter_id'] = $data->filter_id = trim($request->input('filter_id'));
            $url .= 'filter_id=' . $request->input('filter_id') . '&';
        }
        if ($request->input('filter_email') && strlen($request->input('filter_email'))) {
            $params['filter_email'] = $data->filter_email = trim($request->input('filter_email'));
            $url .= 'filter_email=' . $request->input('filter_email') . '&';
        }

        if (Session::get('group_code') == 'franquia') {
            $params['franquia'] = auth()->id();
        }


        // permitindo execultar o metodo
        $params['source'] = 'list_user';

        // Pagination
        $params['get_total'] = true;
        $data->total_rows = 0; //$this->user_model->getList($params);

        $data->total_rows = $this->user_model->getList($params);

        $data->total_block = $this->user_model->getBlocks();
        
        unset($params['get_total']);
        $config = $this->utils->getConfigPagination([
            'url' => 'usuarios?' . $url, 
            'total' => $data->total_rows,
        ]);
        $page_start = $request->input('pstart') ? $request->input('pstart') : 0;
        $params['per_page'] = $config["per_page"];
        $params['page_start'] = $page_start;


        $list = $this->user_model->getList($params);
        
        foreach ($list as $i) {
            
            $i->has_postagem = $this->user_model->hasEnvio($i->id);
            
            $afiliado = $this->user_model->get($i->ref_indication);
            $i->afiliado = '';
            if($afiliado) {
                $i->afiliado = $afiliado->name;
            }
            $i->is_nuvem_shop = $this->user_model->isNuvemShop($i->id);
            $i->cupom = $this->cupom_model->getCuponsUser($i->id);
            $i->is_bling = false;
            $i->is_loja_integrada = false;
            if (!$i->is_nuvem_shop) {
                $i->is_bling = $this->user_model->isBling($i->id);

                if (!$i->is_loja_integrada) {
                    $i->is_loja_integrada = $this->user_model->isLojaIntegrada($i->id);
                }
            }

            $u_cache = $this->user_model->getCacheByEmail($i->email);
            $i->indicacao = '';
            $i->plataforma = '';
            $i->plataforma_link = '';

            if ($u_cache) {
                $i->indicacao = $u_cache->quem_indicou;
                $i->plataforma = $u_cache->plataforma;
                $i->plataforma_link = $u_cache->plataforma_link;
            }
            $i->enable_log = $this->user_model->log('is_enable', $i->id); 
        }

        $data->list = $list;

        $integracaos = array();
        $int_cadastro = new \stdClass();
        $int_nuvem = new \stdClass();
        $int_cadastro->id = 'cadastro';
        $int_cadastro->name = 'Cadastro';
        $int_nuvem->id = 'nuvem_shop';
        $int_nuvem->name = 'Nuvem Shop';
        $integracaos[] = $int_cadastro;
        $integracaos[] = $int_nuvem;
        $fields_filter = array(
            'filter_id' => ['label' => 'ID', 'default_value' => $request->input('filter_id')],
            "filter_integracao" => array('default_value' => $request->input('filter_integracao'), 'label' => 'Integração', 'type' => 'select', 'opts' => getSelectOpts($integracaos)),
            "filter_email" => array('default_value' => $request->input('filter_email'), 'label' => 'Email'),
        );
        $fields_filter['txt_search'] = ['label' => 'Cliente', 'type' => 'autocomplete', 'element_name' => 'user_id', 'element_id' => 'filter_user_cadastro', 'element_keyup_id' => 'filter_user_cadastro', 'default_value' => $request->input('user_id'), 'description' => $this->user_model->getDesAutocomplete($request->input('user_id'))];
        $data->user_filter = $this->form_builder->mountFormV1($fields_filter);
        

        return view('layouts/user/index', [
            'data' => $data
        ]);
        
    }

    public function accountConfirmation(Request $request) 
    {

        $key = $request->input('key');

        $confirm = $this->user_model->confirmAccount($key);

        $data = new \stdClass();
        if (!$confirm) {
            $data->status = 'EXPIRED';
        } else {
            $data->status = $confirm->status;
        }

        $data_header = ['type_header' => 'mini'];
        View::make('user/account_confirmation', $data, true);
    }

    public function regenerateEmailConfirmation(Request $request) 
    {
        $email = $request->input('email');
        $this->user_model->generateKeyConfirmation(['email' => $email, 'type' => 'confirmation']);

        echo json_encode(['status' => '1', 'email' => $email]);
    }

    // DEPRECIADO
    public function edit($id) 
    {
        $info = $this->get($id, true);

        if (!$info) {
            echo json_encode(['error' => 'Usuário não encontrado']);
            return;
        }

        $data = new \stdClass();

        $user = $info['user'];

        $_fields = $this->user_model->getFields();

        foreach ($user as $code => $i) {
            if (isset($_fields[$code])) {
                $_fields[$code]['default_value'] = $i;
            }
        }

        $data->user = $info['user'];
        $data->html_webservice = $info['html_webservice'];

        $data->fields_form = $this->form_builder->mountFormV1($_fields);
        $data->user_groups = $this->user_model->getUserGroups();

        $data->info_groups = array();
        foreach ($data->user_groups as $g) {
            $data->info_groups[$g->id] = $g->code;
        }

        $result = array();
        $result['title'] = '<i class="fa fa-edit"></i> Editar usuário <strong>' . $user->id . '-' . $user->name . ' | ' . $user->razao_social . '</strong> ';
        $result['html'] = View::make('user/box-edit', $data, true);
        $result['footer'] = '<button class="btn btn-danger" type="button" data-dismiss="modal">Fechar</button>';

        echo json_encode($result);
    }

    public function get($id, $return_obj = false) 
    {
        $json = array();
        if (session(['user_is_logged' => false])) {
            $json['redirect'] = URL::to('login');
        }
        if (!$json) {
            $user = $this->user_model->get($id);


            if (!$user->login) {
                $user->login = $user->email;
            }

            if (!$user->grupo_taxa) {
                $user->grupo_taxa = 5;
            }

            $user->password = '';

            $plataform = DB::table('user_register_cache')
                ->where('email', $user->email)
                ->first();
            $user->plataform = $plataform ? $plataform->plataforma : '';

            // Config
            $config = $user->config ? unserialize($user->config) : array();
            if (isset($config['config_enable_autocomplete']) && (int) $config['config_enable_autocomplete']) {
                $user->config_enable_autocomplete = 1;
            }
            
            $user->config_enable_plp = 0;
            if (isset($config['config_enable_plp']) && (int) $config['config_enable_plp']) {
                $user->config_enable_plp = 1;
            }
            if (isset($config['config_enable_seguro']) && (int) $config['config_enable_seguro']) {
                $user->config_enable_seguro = 1;
            }
            if (isset($config['config_enable_sedex']) && (int) $config['config_enable_sedex']) {
                $user->config_enable_sedex = 1;
            }
            if (isset($config['config_enable_pac']) && (int) $config['config_enable_pac']) {
                $user->config_enable_pac = 1;
            }
            if (isset($config['config_enable_pacmini']) && (int) $config['config_enable_pacmini']) {
                $user->config_enable_pacmini = 1;
            }
            if (isset($config['config_enable_sedex_hoje']) && (int) $config['config_enable_sedex_hoje']) {
                $user->config_enable_sedex_hoje = 1;
            }
            if (isset($config['config_enable_sedex_12']) && (int) $config['config_enable_sedex_12']) {
                $user->config_enable_sedex_12 = 1;
            }
            if (isset($config['config_enable_industrial']) && (int) $config['config_enable_industrial']) {
                $user->config_enable_industrial = 1;
            }
            if (isset($config['config_etiquetas_por_pag'])) {
                $user->config_etiquetas_por_pag = $config['config_etiquetas_por_pag'];
            } else {
                $user->config_etiquetas_por_pag = 4;
            }
            if (isset($config['config_enable_manifestacao'])) {
                $user->config_enable_manifestacao = $config['config_enable_manifestacao'];
            } else {
                $user->config_enable_manifestacao = 0;
            }
            if (isset($config['config_num_boletos_permitidos'])) {
                $user->config_num_boletos_permitidos = $config['config_num_boletos_permitidos'];
            } else {
                $user->config_num_boletos_permitidos = 1;
            }
            if (isset($config['config_enable_view_nfse'])) {
                $user->config_enable_view_nfse = (int) $config['config_enable_view_nfse'];
            } else {
                $user->config_enable_view_nfse = 0;
            }

            if (isset($config['config_enable_cupom']) && (int) $config['config_enable_cupom']) {
                $user->config_enable_cupom = 1;
            }
            if (isset($config['config_enable_afiliado']) && (int) $config['config_enable_afiliado']) {
                $user->config_enable_afiliado = 1;
            }
            if (isset($user->metodo_transferencia)) {
                $pix = explode('_', $user->metodo_transferencia, 2);
                $user->tipo_pix = $pix[0];
                $user->codigo_pix = $pix[1];
            }

            if (!isset($config['config_enable_sedex'])) {
                $user->config_enable_sedex = 1;
            }
            if (!isset($config['config_enable_pac'])) {
                $user->config_enable_pac = 1;
            }
            if (!isset($config['config_enable_pacmini'])) {
                $user->config_enable_pacmini = 1;
            }
            if (!isset($config['config_enable_sedex_hoje'])) {
                $user->config_enable_sedex_hoje = 0;
            }
            if (!isset($config['config_enable_sedex_12'])) {
                $user->config_enable_sedex_12 = 0;
            }
            if (!isset($config['config_enable_industrial'])) {
                $user->config_enable_industrial = 0;
            }

            if ($return_obj) {
                return [
                    'user' => $user,
                    'html_webservice' => $this->webservice($user, true)
                ];
            }


            $user->html_webservice = $this->webservice($user);

            echo json_encode($user);
        }
    }

    public function webservice($user, $no_form = false) 
    {
        $request = new Request ;
        if ($request->input('no_form') == '1') {
            $no_form = true;
        }

        $data = new \stdClass();

        $data->user = $user;
        $data->no_form = $no_form;

        $data->fields = $this->form_builder->mountFormV1([
            'plataforma' => [
                'label' => 'Plataforma<br><small><strong>Informe em qual plataforma as credenciais serão usadas</strong></small>',
                'type' => 'select',
                'opts' => getSelectOptsArray([
                    'LojaIntegrada' => 'Loja Integrada',
                    'Wordpress' => 'Woocommerce',
                    'wbuy' => 'Wbuy',
                    'opencart' => 'Opencart',
                    'dooca' => 'Bagy / Dooca Commerce',
                    'ecomplus' => 'E-com-plus',
                    'outros' => 'Outros',
                ]),
            ]
        ]);

        return View::make('user/webservice', $data, true);
    }

    public function save(Request $request) 
    {

        if (Session::get('group_code') != 'mandabem') {
            $json['status'] = 0;
            $json['error'] = "Usuario nao permitido";
            echo json_encode($json);
            return;
        }

        $json = array();

        $old_data = $this->user_model->get($request->input('id'));


        if (!$this->user_model->save($request->input())) {
            $json['status'] = 0;
            $json['error'] = $this->user_model->getError();
        } else {
            $json['status'] = 1;

            if (!$old_data->status && $request->input('status') == 'ACTIVE') {
                $json['was_pending'] = 1;
                $json['id'] = $old_data->id;
            }

            $json['msg'] = 'Usuário salvo com sucesso';
        }

        echo json_encode($json);
    }

    public function registerStep(Request $request) 
    {

        $info_cache = array();
        if (Session::has('user_register_cache_id') && (int) Session::get('user_register_cache_id')) {
            $info_cache = $this->user_model->getInfoCache(Session::get('user_register_cache_id'));
        }

        $fields_user = $this->user_model->getFields();
        foreach ($fields_user as $code => $fu) {
            $code_min = strtolower($code);

            if ($code != 'password') {
                $fields_user[$code]['default_value'] = isset($info_cache[$code_min]) ? $info_cache[$code_min] : '';
            }
            $fields_user[$code]['no_label'] = true;
            $fields_user[$code]['no_cols'] = true;
            $fields_user[$code]['required'] = true;
            $fields_user[$code]['autocomplete_off'] = true;
            if ($code == 'email') {
                $fields_user[$code]['placeholder'] = "Informe seu email";
            }
            if ($code == 'name') {
                $fields_user[$code]['placeholder'] = "Nome e Sobrenome";
            }
            if ($code == 'name_ecommerce') {
                $fields_user[$code]['label'] = "Nome da Loja";
                $fields_user[$code]['placeholder'] = "Nome da Loja com até 50 Caracters";
            }
            if ($code == 'razao_social') {
                $fields_user[$code]['label'] = "Remetente Etiquetas";
            }
            if ($code == 'cpf') {
                $fields_user[$code]['valide'] = true;
            }
            if ($code == 'complemento') {
                $fields_user[$code]['required'] = false;
            }
        }


        $_lista_plataforms_ = array(
            'WooCommerce',
            'Shopify',
            'Bling',
            'Wix',
            'Magento',
            'Opencart',
            'Tray',
            'Vtex',
            'Nuvem Shop',
            'Facebook',
            'AndCommerce',
            'Instagram',
            'WhatsApp',
            'XTECH',
            'Loja Integrada',
            'E-Com Plus',
            'WBUY',
            'Yampi',
            'Fastcommerce',
            'BW Commerce',
            'Outras',
            'Não sei informar',
        );
        $lista_plataforms = array();
        foreach ($_lista_plataforms_ as $v) {
            $std = new \stdClass();
            $std->id = $v;
            $std->name = $v;
            $lista_plataforms[] = $std;
        }

        $lista_volume = array();
        foreach (['até 10 Envios', 'de 10 a 30 Envios', 'de 30 a 50 Envios', 'mais de 50 Envios'] as $v) {
            $std = new \stdClass();
            $std->id = $v;
            $std->name = $v;
            $lista_volume[] = $std;
        }

        if ($request->input('v') == '2') {

            $steps = array();
            $steps[] = array(
                'title' => 'Olá,<br>Para começar a ter um frete muito mais barato, vamos realizar o seu cadastro!',
                'fields' => array('email' => $fields_user['email'], "name" => $fields_user['name'], "telefone" => $fields_user['telefone'])
            );

            $steps[] = array(
                'code' => 'cep',
                'title' => 'Estamos quase lá!<br>Para continuarmos com o seu cadastro, precisamos de mais algumas informações.',
                'fields' => array(
                    "plataforma" => array('default_value' => isset($info_cache['plataforma']) ? $info_cache['plataforma'] : '', 'type' => 'select', 'opts' => $lista_plataforms, 'label' => 'Qual destas plataformas você usa para efetuar vendas online?', 'required' => true),
                    "name_ecommerce" => $fields_user['name_ecommerce'],
                    'plataforma_link' => ['required' => true, 'placeholder' => 'Qual é o link da sua loja?', 'label' => 'Qual é o link da sua loja?<br><small>(Se não tiver loja ainda coloque o link da sua página na rede social)</small>', 'default_value' => isset($info_cache['plataforma_link']) ? $info_cache['plataforma_link'] : ''],
                    "volume_medio" => array('default_value' => isset($info_cache['volume_medio']) ? $info_cache['volume_medio'] : '', 'type' => 'select', 'opts' => $lista_volume, 'label' => 'Conta pra gente, na média, quantos envios o seu e-commerce faz por mês?', 'required' => true),
                    "cep" => $fields_user['CEP'],
                )
            );

            $fields_user['numero']['label'] = 'Informe o número do endereço';
            $fields_user['numero']['required'] = true;

            $steps[] = array(
                'title' => 'O Endereço está correto?',
                'code' => 'address',
                'fields' => array(
                    "logradouro" => $fields_user['logradouro'],
                    "bairro" => $fields_user['bairro'],
                    "cidade" => $fields_user['cidade'],
                    "uf" => $fields_user['uf'],
                    "numero_endereco" => $fields_user['numero'],
                    "complemento" => $fields_user['complemento']
                )
            );


            $fields_user['razao_social']['label'] = "Qual é o nome que ficará como remetente nas etiquetas de envio?";
            $fields_user['cpf']['label'] = "E o CPF do responsável?";
            $steps[] = array(
                'code' => 'password_confirm',
                'title' => 'Agora, só mais alguns dados pessoais e já finalizamos o seu cadastro! (Fique tranquilo, nós da Manda Bem seguimos todas as regras da LGPD. Caso queira saber mais acesse o <a href="https://www.mandabem.com.br/politica-privacidade/">link</a>)',
                'fields' => array(
                    "razao_social" => $fields_user['razao_social'],
                    "cpf" => $fields_user['cpf'],
                    "password" => $fields_user['password'],
                    "password2" => $fields_user['password']
                )
            );

        } else {

            $steps = array();
            $steps[] = array(
                'title' => 'Olá,<br><br>Qual é o melhor e-mail para contato?',
                'fields' => array('email' => $fields_user['email'])
            );

            if (true) {
                $steps[] = array(
                    'code' => 'quem_indicou',
                    'title' => 'Quem indicou a Manda Bem?<br><small>(Nome da pessoa e Loja, Nome do Grupo/página das Redes Sociais, Nome da Agência dos Correios)</small>',
                    'fields' => array(
                        'quem_indicou' => ['placeholder' => 'Quem indicou a Manda Bem?', 'no_label' => true, 'default_value' => isset($info_cache['quem_indicou']) ? $info_cache['quem_indicou'] : ''],
                    )
                );
            }


            if (true) {
                $steps[] = array(
                    'code' => 'plataforma',
                    'title' => 'Qual destas plataformas você usa para efetuar vendas online?',
                    'fields' => array(
                        "plataforma" => array('default_value' => isset($info_cache['plataforma']) ? $info_cache['plataforma'] : '', 'type' => 'select', 'opts' => $lista_plataforms, 'label' => 'Plataforma', 'required' => false),
                        'plataforma_link' => ['required' => true, 'placeholder' => 'Qual é o link da sua loja?', 'label' => 'Qual é o link da sua loja?<br><small>(Se não tiver loja ainda coloque o link da sua página na rede social)</small>', 'default_value' => isset($info_cache['plataforma_link']) ? $info_cache['plataforma_link'] : ''],
                    )
                );
            }
            $steps[] = array(
                'title' => 'Qual é o Nome completo do responsável pela loja?',
                'fields' => array("name" => $fields_user['name'])
            );
            $steps[] = array(
                'title' => 'Qual é o nome da sua loja virtual?',
                'fields' => array("name_ecommerce" => $fields_user['name_ecommerce'])
            );
            $steps[] = array(
                'title' => 'Qual é o nome que ficará como remetente nas etiquetas de envio?',
                'fields' => array("razao_social" => $fields_user['razao_social'])
            );
            $steps[] = array(
                'title' => 'E o CPF do responsável?',
                'fields' => array("cpf" => $fields_user['cpf'])
            );
            $steps[] = array(
                'title' => 'Agora informe qual é o número do whatsapp do responsável? (com DDD)',
                'fields' => array("telefone" => $fields_user['telefone'])
            );
            $steps[] = array(
                'title' => 'Qual é o Cep da Loja?',
                'code' => 'cep',
                'fields' => array("cep" => $fields_user['CEP'])
            );
            $steps[] = array(
                'title' => 'O Endereço está correto?',
                'code' => 'address',
                'fields' => array(
                    "logradouro" => $fields_user['logradouro'],
                    "bairro" => $fields_user['bairro'],
                    "cidade" => $fields_user['cidade'],
                    "uf" => $fields_user['uf'],
                )
            );
            $steps[] = array(
                'title' => 'Qual é o número?',
                'fields' => array("numero_endereco" => $fields_user['numero'])
            );
            $steps[] = array(
                'code' => 'complemento',
                'title' => 'O endereço tem Complemento?',
                'fields' => array("complemento" => $fields_user['complemento'])
            );
            if (true) {

                $steps[] = array(
                    'code' => 'volume_medio',
                    'title' => 'Última Pergunta:<br><br>Quantos envios vocês costumam fazer por mês?:)',
                    'fields' => array("volume_medio" => array('default_value' => isset($info_cache['volume_medio']) ? $info_cache['volume_medio'] : '', 'type' => 'select', 'opts' => $lista_volume, 'label' => 'Volume', 'required' => true))
                );
            }
            $steps[] = array(
                'code' => 'password',
                'title' => 'Crie uma senha de 8 a 13 caracteres com letras e número',
                'fields' => array("password" => $fields_user['password'])
            );
            $steps[] = array(
                'code' => 'password2',
                'title' => 'Repita a senha, por gentileza',
                'fields' => array("password2" => $fields_user['password'])
            );
        }

        if ($request->method() === 'POST') {

            $error = array();
            $post = $request->input();

            $current_step = ( ( isset($post['current_step']) && strlen($post['current_step']) ) ? $post['current_step'] : false);

            if ($current_step === false) {
                redirect('register_step?current_step=0');
                return;
            }

            if ($request->input('go_back')) {
                if ($current_step < 1) {
                    echo json_encode(array('redirect' => Config::get('site_wp_url')));
                } else {
                    redirect('register_step?current_step=' . --$current_step . ( $request->input('ref') ? '&ref=' . $request->input('ref') : '' ) . ( $request->input('utm_source') ? '&utm_source=' . $request->input('utm_source') : '' ) . ( $request->input('v') ? '&v=' . $request->input('v') : ''));
                }
                return;
            }

            $step = $steps[$current_step];

            $data_post = $this->form_builder->validadeData($step['fields'], $post);

            if (!$data_post) {
                $error[] = $this->form_builder->getErrorValidation();
            }

            if ($error) {
                $return['error'] = implode('<br>', $error);
                echo json_encode($return);
            } else {

                // Salvar
                $save = $this->user_model->saveRegisterStep(array(
                    'current_step' => $current_step,
                    'steps' => $steps,
                    'post' => $data_post,
                    'cod_indication' => $request->input('ref'),
                    'utm_source' => $request->input('utm_source'),
                ));

                if (!$save) {

                    // Se for cadastrar via Nuvemshop fazer associacao da Conta ao App
                    if ((int) $current_step === 0 && strlen($request->input('codenuvemshop'))) {
                        $api = $this->api_model->get(['type' => 'nuvemshop', 'code' => $request->input('codenuvemshop')]);
                        if ($api) {
                            $data_choice = new \stdClass();
                            $data_choice->api = $api;

                            $return['option_nuvemshop'] = 1;
                            $return['title'] = '<h3><i class="fa fa-exclamation-triangle"></i> Aviso - Email Cadastrado</h3>';
                            $tmp = explode('.', $api->domain);
                            $data_choice->fisrt_name_domain = isset($tmp[0]) ? $tmp[0] : '';
                            if (!strlen($data_choice->fisrt_name_domain)) {
                                $this->email_maker->msg([
                                    'to' => 'regygom@gmail.com',
                                    'subjetc' => 'Cadastro Nuvem',
                                    'msg' => 'Não obtido domainio para: <br><pre>' . print_r($api, true) . '</pre>'
                                ]);
                            } else {
                                $return['html'] = View::make('user/box_choice_install_nuvemshop', $data_choice, true);
                                echo json_encode($return);
                                return;
                            }
                        }
                    }

                    $return['error'] = $this->html_utils->wrapErrorAlert($this->user_model->getError());
                    echo json_encode($return);
                } else {
                    $next_step = ++$current_step;

                    // Finalizando
                    if (!isset($steps[$next_step])) {

                        $new_user = $this->user_model->get($save);

                        $to_email = 'reginaldo@mandabem.com.br,marcos@mandabem.com.br,carol@mandabem.com.br';

                        if ($request->input('v') == '2') {
                            $to_email = 'reginaldo@mandabem.com.br';
                        }

                        if ($request->input('v') == '2') {



                            $html = '<h4><i class="fa fa-check" style="color: green;"></i> ';
                            $html .= 'Pronto!<br>Seu cadastro foi concluído com sucesso e já pode aproveitar todos os benefícios do seu frete muito mais acessível.<br> ';
                            $html .= 'Você receberá uma mensagem por email com as orientações de como entrar e gerar as primeiras etiquetas!<br><br>';
                            $html .= 'Como nos conheceu?</h4>';


                            $html .= '<div id="content-ask-foi-indic">Foi indicação? <input style="min-height: 0px;" type="radio" name="foi_indicacao" id="foi_indicacao_s" value="S" /> <label for="foi_indicacao_s">Sim</label> <input style="min-height: 0px;"  type="radio" name="foi_indicacao" id="foi_indicacao_n" value="N" /> <label for="foi_indicacao_n">Não</label><br></div>';

                            $f = $this->form_builder->mountFormV1([
                                'quem_indicou' => ['label' => 'Quem indicou?']
                            ]);

                            $html .= '<div id="content-for-quem-ind" style="display: none;">';

                            $html .= $f['quem_indicou'];

                            $html .= '<button class="btn btn-xs btn-success" id="btn-answer-who-ind">Responder <i class="fa fa-paper-plane"></i></button>';

                            $html .= '</div>';

                            $html .= '<br><br>';
                            $html .= '<a class="btn btn-primary" href="' . Config::get('site_wp_url') . '">Voltar para o site</a>';
                            $html .= "<script>dataLayer.push({'event': 'cadastro-finalizado'});</script>";

                            $html .= '<script>';
                            $html .= ' $(function() {';

                            $html .= '   $("input[name=foi_indicacao]").change(function () { if($(this).val() == "S") { $("#content-for-quem-ind").show(); } else { $("#content-for-quem-ind").hide(); } }); ';
                            $html .= '   $("#btn-answer-who-ind").click( function() { ';

                            $html .= '   if($("#quem_indicou").val().length < 2) { alert("Por favor, informe quem indicou."); $("#quem_indicou").focus(); return;}';

                            $html .= '  $.ajax({ type: "POST", url: "' . URL::to("save_user_quem_indic") . '", data: "quem_indicou=" + $("#quem_indicou").val(),';
                            $html .= ' success: function() { $("#content-ask-foi-indic").hide(); $("#content-for-quem-ind").html("Muito Obrigado por sua resposta!");  }, ';
                            $html .= ' });';

                            $html .= '   } ); ';
                            $html .= ' ';
                            $html .= ' });';
                            $html .= '</script>';


                            echo json_encode(array('html' => $html));
                            return;
                        } else {

                            $html = '<h4><i class="fa fa-check" style="color: green;"></i> O seu cadastro foi realizado com sucesso!<br>Você receberá uma mensagem por email com as orientações de como entrar e gerar as primeiras etiquetas!</h4>';
                            $html .= '<h3>;) Muito obrigado pelo cadastro!</h3><br>';
                            $html .= 'Seus dados de login são:<br>';
                            $html .= '<strong>login:</strong> ' . $new_user->email . '<br>';
                            $html .= '<strong>senha:</strong> ********* (senha cadastrada)<hr><br><br>';
                            $html .= '<a class="btn btn-primary" href="' . Config::get('site_wp_url') . '">Voltar para o site</a>';
                            $html .= "<script>dataLayer.push({'event': 'cadastro-finalizado'});</script>";
                        }
                        echo json_encode(array('html' => $html));
                        return;
                    }

                    Session::put('user_register_current_step',$next_step) ;
                    redirect('register_step?current_step=' . $next_step . ( $request->input('ref') ? '&ref=' . $request->input('ref') : '' ) . ( $request->input('utm_source') ? '&utm_source=' . $request->input('utm_source') : '' ) . ( $request->input('v') ? '&v=' . $request->input('v') : ''));
                    return;
                }
            }
            return;
        }
        $current_step = (int) ( (int) $request->input('current_step') !== false ? $request->input('current_step') : 0);
        if ($request->input('current_step') === NULL) {
            if (null !== Session::get('user_register_current_step') && (int) Session::get('user_register_current_step')) {
                $current_step = (int) Session::get('user_register_current_step');
                // Caso confirmação da senha, volta pra senha
                if (isset($steps[$current_step]['code']) && $steps[$current_step]['code'] == 'password2') {
                    $current_step--;
                }
            }
        }

        $fields = $steps[$current_step]['fields'];
        $data = new \stdClass();
        $data->current_step = $current_step;
        $data->title = $steps[$current_step]['title'];

        $fields['current_step'] = array('type' => 'hidden', 'default_value' => $current_step, 'label' => '');
        $data->fields = $this->form_builder->mountFormV1($fields);

        $data->show_btn_back = $current_step > 0 ? true : false;
        $data->show_btn_edit = ( isset($steps[$current_step]['code']) && $steps[$current_step]['code'] == 'address' ) ? true : false;
        $data->show_btn_not = ( isset($steps[$current_step]['code']) && $steps[$current_step]['code'] == 'complemento' ) ? true : false;
        $data->label_btn_proccess = ( isset($steps[$current_step]['code']) && $steps[$current_step]['code'] == 'address' ) ? 'Sim' : 'Prosseguir';

        $html = View::make('user/register_steps', $data, true);

        echo json_encode(array('html' => $html));
    }

    public function register($param = []) 
    {
        $request = new Request ;
        Auth::logout(false);

        if (preg_match('/cadastro_v2/', $request->input('REQUEST_URI'))) {

            if (null !== Session::get('register_url_referer') && trim(strlen($request->input('utm_source')))) {
                Session::put('register_url_referer',$request->input('utm_source')) ;
            }


            $http_user_agent = $request->input('HTTP_USER_AGENT');
            $iphone = strpos($http_user_agent, "iPhone");
            $android = strpos($http_user_agent, "Android");
            $palmpre = strpos($http_user_agent, "webOS");
            $berry = strpos($http_user_agent, "BlackBerry");
            $ipod = strpos($http_user_agent, "iPod");
            $ipad = strpos($http_user_agent, "iPad");

            $data = array();

            if ($iphone || $android || $palmpre || $ipod || $ipad || $berry) {
                $data['device'] = "mobile";
            } else {
                $data['device'] = "desktop";
            }

            $data['url_base'] = URL::to('register_step?' . ($request->input('ref') ? 'ref=' . $request->input('ref') : '' ) . ( $request->input('utm_source') ? '&utm_source=' . $request->input('utm_source') : '' ));

            if (isset($param['is_nuvemshop'])) {
                $data['api'] = $param['api'];
                $data['url_base'] = URL::to('register_step?codenuvemshop=' . $param['api']->code);
            }

            $data['url_base'] .= '&v=2';

            View::make('user/register', $data);

            return;
        }


        if (!Session::has('register_url_referer') && trim(strlen($request->input('utm_source')))) {
            Session::put('register_url_referer', $request->input('utm_source'));
        }

        $http_user_agent = $request->input('HTTP_USER_AGENT');
        $iphone = strpos($http_user_agent, "iPhone");
        $android = strpos($http_user_agent, "Android");
        $palmpre = strpos($http_user_agent, "webOS");
        $berry = strpos($http_user_agent, "BlackBerry");
        $ipod = strpos($http_user_agent, "iPod");
        $ipad = strpos($http_user_agent, "iPad");

        $data = array();

        if ($iphone || $android || $palmpre || $ipod || $ipad || $berry) {
            $data['device'] = "mobile";
        } else {
            $data['device'] = "desktop";
        }

        $data['url_base'] = URL::to('register_step?' . ($request->input('ref') ? 'ref=' . $request->input('ref') : '' ) . ( $request->input('utm_source') ? '&utm_source=' . $request->input('utm_source') : '' ));

        if (isset($param['is_nuvemshop'])) {
            $data['api'] = $param['api'];
            $data['url_base'] = URL::to('register_step?codenuvemshop=' . $param['api']->code);
        }

        View::make('user/register', $data);
    }

    public function saveUserQuemIndic(Request $request) 
    {
        DB::table('user_register_cache')
            ->where('id', Session::get('user_register_cache_id_last'))
            ->update(['quem_indicou' => $request->input('quem_indicou')]);
    }

    public function registerOk() 
    {
        $data_header = ['type_header' => 'mini'];
        View::make('user/register_ok', [], true);
    }

    public function registerAjax(Request $request) 
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $error = [];
        if (!$email) {
            $error[] = '<i class="fa fa-exclamation-triangle"></i> Informe um email.';
        }
        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error[] = '<i class="fa fa-exclamation-triangle"></i> O Email informado é inválido.';
        }
        if (!$password) {
            $error[] = '<i class="fa fa-exclamation-triangle"></i> Informe uma senha.';
        }

        $user_exist = $this->user_model->userExist($email);

        if ($user_exist) {
            $user_logged = $this->user_model->resolveLogin($email, $password);
            if (!$user_logged) {
                $error[] = '<i class="fa fa-exclamation-triangle"></i> Email informado já está cadastrado.<br> Digite a senha da conta ou ' .
                        '<a href="' . URL::to('forgot_pass') . '">clique aqui</a> para recuperar';
            } else {
                $user = $this->user_model->get($user_logged);
                $this->initSession($user);
                echo json_encode(['status' => 1, 'user_id' => $user->id]);
                return;
            }
        }

        if (count($error)) {
            echo json_encode(['status' => 0, 'errors' => implode('<br>', $error)]);
            return;
        }
        $data_user = new \stdClass();

        $tmp_name = explode('@', $email);
        $data_user->name = $tmp_name[0];
        $data_user->email = $email;
        $data_user->password = $password;
        $data_user->email_confirmed = 'Y';

        $error_insert = [];
        $user_id = $this->user_model->insert($data_user, $error_insert);

        if (!$user_id) {
            echo json_encode(['status' => 0, 'errors' => implode('<br>', $error_insert)]);
            return;
        }

        $user = $this->user_model->get($user_id);
        $this->initSession($user);

        echo json_encode(['status' => 1, 'user_id' => $user_id]);
    }

    public function logout($redirect = true) 
    {
        $request = new Request ;
        $this->destroySession(false);
        if (null !== Session::get('user_is_logged')) {
            $this->destroySession(false);
        }
        Session::flush();
        session_start();
        Session::flush();

        if ($request->input('ajax') == '1') {
            echo "success";
            return;
        }

        if ($redirect) {
            redirect('/');
        }
    }

    ######
    # PRIVATE METHODS
    ######

    private function destroySession($redirect = false) 
    {
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        $_SESSION['user_is_logged'] = null;
        unset($_SESSION['user_is_logged']);
        if ($redirect) {
            redirect('/');
        }
    }

    private function initSession($user) 
    {

        $this->destroySession();

        $user_name = explode(" ", (string) $user->name);


        // set session user datas
        Session::put('id',(int) $user->id) ;
        Session::put('user_name',(string) $user_name[0]) ;
        Session::put('group_code',(string) $user->group_code) ;
        Session::put('user_last_access',$this->utils->maskDateBr($user->date_last_access, true)) ;

        Session::put('user_is_logged',(bool) true) ;
        return true;
    }

    public function forgotPass(Request $request) 
    {

        if ($request->input('REQUEST_METHOD') == 'POST' && !$request->input('email')) {
            echo json_encode(array('error' => $this->html_utils->wrapErrorAlert('Informe o email')));

            return;
        }
        if ($request->input('email') != '') {
            
            //Busca o endereço de quem quer alterar a senha através do IP 
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.hackertarget.com/ipgeo/?q=".$request->input('REMOTE_ADDR'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch); 
            $d['email'] = $request->input('email');
            $d['dados'] = $response;
            $alteracoes = array();
            $alteracoes['user_id'] = 0; 
            $alteracoes['text'] = json_encode($d);
            $alteracoes['type'] = "PASSWOR_EMAIL";
            $alteracoes['ip'] = $request->input('REMOTE_ADDR');
            $alteracoes['date'] = $this->date_utils->getNow();
            DB::table('log')->insert($alteracoes);

            $error = array();
            $email = $request->input('email');
            $update = $this->user_model->generateKeyConfirmation(['email' => $email], $error);

            // Nao encontrado email, retorno Sucesso para evitar o usuario saber qual email existe
            if (!$update) {
                echo json_encode(array('error' => '<div class="alert alert-danger">Email não econtrado, Usuário não possui cadastro.</div>'));
                $redirect = "login?status_recover=fail";
                return;
            }

            $redirect = "login?status_recover=success";

            echo json_encode(array('redirect' => URL::to($redirect)));
            return;
        }

        $data = new \stdClass;
        $data->status = $request->input('status');

        $html = View::make('user/forgot_pass', $data, true);
        echo json_encode(array('html' => $html));

    }

    public function updatePass(Request $request) 
    {
        $key = $request->input('key') ? $request->input('key') : $request->input('key');
        $valid = $this->user_model->getValidKey($key);
        if (!$valid) {
            if ($request->input('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest') {
                echo json_encode(array('redirect' => 'login?status_recover=expired'));
            } else {
                redirect('login?status_recover=expired');
            }
            return;
        }
        if ($request->input('key') != '') {

            $senha = $request->input("password");
            $senha2 = $request->input("password_2");

            if ($senha == '') {
                echo json_encode(array('error' => $this->html_utils->wrapErrorAlert('Preencha o campo Senha')));
                return;
            }

            if ($senha != $senha2) {
                echo json_encode(array('error' => $this->html_utils->wrapErrorAlert('Senha e Senha de confirmação não conferem')));
                return;
            }
            $error = array();
            if (strlen(trim($senha)) < 8 || strlen(trim($senha)) > 13) {
                $error[] = 'A Senha deve ter entre 8 e 13 caracters. Tamanho informado: ' . strlen(trim($senha)) . '.';
            }
            if (!preg_match('/([A-Z]){1}/', trim($senha)) || !preg_match('/([0-9]){1}/', trim($senha))) {
                $error[] = 'A Senha deve possuir pelo menos (1) uma letra Maiúscula e pelo menos (1) um número.';
            }

            if ($error) {
                echo json_encode(array('error' => $this->html_utils->wrapErrorAlert(implode('<br>', $error))));
                return;
            }

            if (!$error) {
                if (!$this->user_model->updatePass($senha, $key)) {
                    $error = $this->user_model->getError();
                    $str_status = 'failpass';
                    if (isset($error) && ( $error == 'key_not_found' || $error == 'link_expired' )) {
                        $str_status = 'faillink';
                    }
                    echo json_encode(array('redirect' => 'login?status_recover=expired'));
                    return;
                }
            }
            echo json_encode(array('redirect' => 'login?status_recover=success_update'));

            return;
        }

        if ($key == null) {
            redirect('login');
            return;
        }
        $data = new \stdClass();
        $data->page_id = 'update_pass';
        $data->key = $key;


        View::make('user/login', $data, true);
    }

    public function delete($user_id) 
    {
        $rem = $this->user_model->delete($user_id);
        if (!$rem) {
            echo json_encode(array('error' => 'Falha ' . $this->user_model->getError()));
        } else {
            echo json_encode(array('redirect' => URL::to('usuarios?delete=sucess')));
        }
    }

    public function deleteRemetente($user_id, $id) 
    {
        if (!(bool) Session::get('user_is_logged') || Session::get('group_code') != 'mandabem') {
            echo json_encode(array('redirect' => URL::to('login')));
            return;
        }
        $del = $this->user_model->deleteRemetente($id, $user_id);
        $return['user_id'] = $user_id;
        if (!$del) {
            $return['error'] = $this->html_utils->wrapErrorAlert('Falha, tente novamente mais tarde');
        } else {
            $return['msg'] = "Sucesso.";
        }

        echo json_encode($return);
    }

    public function saveRemetente($user_id, $id_row = null) 
    {
        $request = new Request ;
        if (auth()->id() != $user_id) {
            if (!(bool) Session::get('user_is_logged') || Session::get('group_code') != 'mandabem') {
                echo json_encode(array('redirect' => URL::to('login')));
                return;
            }
        }
        $lista_estados_ = getListaEstados(true);
        $lista_estados = array();
        foreach ($lista_estados_ as $id => $name) {
            $std = new \stdClass();
            $std->id = $id;
            $std->name = $name;
            $lista_estados[] = $std;
        }

        $fields = array(
            "remetente_nome" => array('maxlength' => 60, 'label' => 'Remetente', 'required' => true),
            "remetente_cep" => array('maxlength' => 8, 'label' => 'CEP', 'required' => true),
            "remetente_logradouro" => array('label' => 'Logradouro', 'required' => true, 'maxlength' => 60),
            "remetente_numero" => array('maxlength' => 8, 'label' => 'Número', 'required' => true, 'maxlength' => 8),
            "remetente_complemento" => array('maxlength' => 30, 'label' => 'Complemento', 'maxlength' => 20),
            "remetente_bairro" => array('label' => 'Bairro', 'required' => true, 'maxlength' => 30),
            "remetente_cidade" => array('label' => 'Cidade', 'required' => true, 'maxlength' => 40),
            "remetente_uf" => array('type' => 'select', 'opts' => $lista_estados, 'label' => 'Estado', 'required' => true),
        );


        if ($request->input('REQUEST_METHOD') == 'POST') {
            $error = array();
            $post = $request->input();

            if (isset($post['remetente_cep'])) {
                $post['remetente_cep'] = preg_replace('/[^0-9]/', '', $post['remetente_cep']);
            }

            $data_valided = $this->form_builder->validadeData($fields, $post);
            if (!$data_valided) {
                $error[] = $this->form_builder->getErrorValidation();
            }

            if (!$error) {
                $data_valided['user_id'] = $user_id;
                if (isset($post['remetente_id']) && (int) $post['remetente_id']) {
                    $data_valided['remetente_id'] = $post['remetente_id'];
                }
                $save = $this->user_model->saveRemetente($data_valided);
                if (!$save) {
                    $error[] = $this->html_utils->wrapErrorAlert("Falha ao inserir, tente novamente mais tarde.");
                }
            }

            if ($error) {
                $return['error'] = implode("<br>", $error);
            } else {
                if (isset($post['remetente_id']) && (int) $post['remetente_id']) {
                    $return['msg'] = "Remetente Salvo com sucesso.";
                } else {
                    $return['msg'] = "Remetente Salvo com sucesso.";
                }
            }
            echo json_encode($return);
            return;
        }


        $data = new \stdClass();

        if ($id_row) {
            $info_remetente = $this->user_model->getUserRemetente($id_row, $user_id);
            if ($info_remetente) {
                $fields['remetente_nome']['default_value'] = $info_remetente->nome;
                $fields['remetente_cep']['default_value'] = $info_remetente->cep;
                $fields['remetente_logradouro']['default_value'] = $info_remetente->logradouro;
                $fields['remetente_numero']['default_value'] = $info_remetente->numero;
                $fields['remetente_complemento']['default_value'] = $info_remetente->complemento;
                $fields['remetente_bairro']['default_value'] = $info_remetente->bairro;
                $fields['remetente_cidade']['default_value'] = $info_remetente->cidade;
                $fields['remetente_uf']['default_value'] = $info_remetente->uf;
                $data->row_id = $info_remetente->id;
            }
        }

        $data->user_id = $user_id;
        $data->fields_form = $this->form_builder->mountFormV1($fields);

        echo json_encode(array('html' => View::make('user/remetente_form', $data, true)));
    }

    public function remetentes($user_id) 
    {

        if (auth()->id() != $user_id) {
            if (!(bool) Session::get('user_is_logged') || Session::get('group_code') != 'mandabem') {
                echo json_encode(array('redirect' => URL::to('login')));
                return;
            }
        }
        $data = new \stdClass();
        $data->list = $this->user_model->getRemetentes($user_id);
        $data->user_id = $user_id;
        View::make('user/remetente_list', $data);
    }

    public function changePassword(Request $request) 
    {

        $fields = array(
            "current_pass" => array('type' => 'password', 'label' => 'Senha Atual', 'required' => true),
            "new_pass" => array('type' => 'password', 'label' => 'Nova Senha', 'required' => true),
            "new_pass_confirm" => array('type' => 'password', 'label' => 'Confirmar Senha', 'required' => true),
        );

        if ($request->method() === 'POST') {

            if (session(['user_is_logged' => false])) {
                echo json_encode(array('redirect' => URL::to('login')));
                return;
            }

            $error = array();

            $user = $this->user_model->get(auth()->id());

            $post = $request->input();
            $data_valided = $this->form_builder->validadeData($fields, $post);
            if (!$data_valided) {
                $error[] = $this->form_builder->getErrorValidation();
            }

            if (!$error) {
                $pass_ok = $this->user_model->validatePassword($user->password, $data_valided['current_pass']);

                if (!$pass_ok) {
                    $error[] = '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Senha Atual inválida.</div>';
                }
                if (!$error) {
                    if ($data_valided['new_pass'] !== $data_valided['new_pass_confirm']) {
                        $error[] = '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Nova Senha e Senha de Confirmação não conferem.</div>';
                    }
                }

                if (!$error) {
                    if (strlen($data_valided['new_pass']) < 8 || strlen($data_valided['new_pass']) > 13) {
                        $error[] = '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> A nova Senha deve ter entre 8 e 13 caracters. Tamanho informado: ' . strlen($data_valided['new_pass']) . '</div>';
                    }
                    if (!$error) {
                        if (!preg_match('/([A-Z]){1}/', $data_valided['new_pass']) || !preg_match('/([0-9]){1}/', $data_valided['new_pass'])) {
                            $error[] = '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> A nova Senha deve possuir ao menos (1) uma letra Maiúscula e ao menos (1) um número.</div>';
                        }
                    }
                }

                if (!$error) {
                    $upd = $this->user_model->updatePasswordInner($user->id, $data_valided['new_pass']);
                    if (!$upd) {
                        $error[] = '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Falha ao atualizar senha, tente novamente mais tarde.</div>';
                    }
                }
            }

            if ($error) {
                $return['error'] = implode("<br>", $error);
            } else {
                $return['msg'] = "Senha alterada com sucesso.";
            }
            echo json_encode($return);
            return;
        }

        if (session(['user_is_logged' => false])) {
            echo '<script> $(function(){ location.href = "' . URL::to('login') . '" }); </script>';
            return;
        }

        $data = new \stdClass();



        $data->fields_form = $this->form_builder->mountFormV1($fields, array(3, 9));

        View::make('user/change_password', $data);
    }

    public function dimissMsgPaypal() 
    {
        if (!Auth::check()) {
            echo json_encode(['redirect' => URL::to('login')]);
            return;
        }
        
        $user = Auth::user();
    
        $exist = DB::table('user_settings')
                    ->where('user_id', $user->id)
                    ->where('name', 'dissmiss_paypal')
                    ->exists();
                    
        if ($exist) {
            echo '{}';
        } else {
            DB::table('user_settings')->insert([
                'user_id' => $user->id,
                'name' => 'dissmiss_paypal',
                'value' => '1',
                'date_insert' => now(),
                'date_update' => now(),
            ]);
            echo '{}';
        }
    }
    
    public function dimissMsgAvisoSeguro() 
    {
        if (!Auth::check()) {
            echo json_encode(['redirect' => URL::to('login')]);
            return;
        }
        
        $user = Auth::user();
    
        $exist = DB::table('user_settings')
                    ->where('user_id', $user->id)
                    ->where('name', 'dissmiss_aviso_seguro')
                    ->exists();
                    
        if ($exist) {
            echo '{}';
        } else {
            DB::table('user_settings')->insert([
                'user_id' => $user->id,
                'name' => 'dissmiss_aviso_seguro',
                'value' => '1',
                'date_insert' => now(),
                'date_update' => now(),
            ]);
            echo '{}';
        }
    }

    public function boxChangeAbaNomenclatura($confirm = null) 
    {

        if ($confirm) {
            $user = $this->user_model->get(auth()->id());
            $this->user_model->setConfig($user, 'show_box_aba_nomenclatura', '1');

            echo '{}';
            return;
        }

        $result = array();
        $result['title'] = 'Mudança de Nomenclatura';
        $result['html'] = View::make('user/box_change_aba_nomenclatura', [], true);
        $result['footer'] = '<button class="btn btn-success" data-path="' . URL::to('user/box_change_aba_nomenclatura/1') . '" type="button" id="btn-set-ok-box-aba-nomenclatura"><i class="fa fa-check"></i> Entendi</button>';

        echo json_encode($result);
    }

    public function boxInfoCpf(Request $request) 
    {

        $user = $this->user_model->get(auth()->id());

        if ($request->input('REQUEST_METHOD') == 'POST' && $request->input('name')) {

            $error = [];
            $nome_resp = $request->input('name');
            $cpf = preg_replace('/[^0-9]/', '', $request->input('cpf'));

            if (strlen($nome_resp) < 2) {
                $error[] = "Informe o nome Responsavel";
            }
            if (strlen($cpf) != 11) {
                $error[] = "Informe o CPF corretamente";
            } else {
                $valid = $this->validation->validDoc('cpf', $cpf);

                if (!$valid) {
                    $error[] = "O CPF informado é inválido";
                }
            }

            if (!$error) {

                $param_update = [
                    'name' => $nome_resp,
                    'cpf' => $cpf,
                ];

                DB::table('user')
                    ->where('id', $user->id)
                    ->update($param_update);

                echo json_encode(['msg' => "Informações salvas com sucesso.<br>Obrigado!"]);
            } else {
                echo json_encode(['error' => implode("\n", $error)]);
            }

            return;
        }

        $_fields = $this->user_model->getFields();
        $fields['name'] = $_fields['name'];
        $fields['cpf'] = $_fields['cpf'];
        $fields['name']['label'] = "Nome Responsável";
        $fields['name']['default_value'] = $user->name;

        $data = new \stdClass();
        $data->fields = $this->form_builder->mountFormV1($fields);
        $data->user = $user;

        $result = array();
        $result['title'] = 'CADASTRO INCOMPLETO';
        $result['informe_doc'] = 1;
        $result['html'] = View::make('user/box_info_cpf', $data, true);
        $result['footer'] = '<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> <button class="btn btn-primary" data-path="' . URL::to('user/box_info_cpf') . '" type="button" id="btn-post-info-cpf"><i class="fa fa-save"></i> Enviar</button>';

        echo json_encode($result);
    }

    public function autocomplete($param = []) 
    {
        $request = new Request ;
        if (Session::get('group_code') != 'mandabem') {
            return;
        }
        
        $return = [];
        
        if ($request->input('text')) {

            $filter_name = trim($request->input('text'));

            $limit = 10;
            if ($request->input('limit')) {
                $limit = $request->input('limit');
            }

            $filter_data = array(
                'filter_name' => $filter_name,
                'start' => 0,
                'limit' => $limit,
            );

            $results = $this->user_model->searchUsers($filter_data);

            foreach ($results as $i) {

                if ($request->input('v') == '2') {
                    $return[$i->id] = array(
                        'description' => $i->id . '-' . $i->name . ' - ' . $i->razao_social,
                        'id' => $i->id,
                    );
                } else {

                    $return[$i->id] = array(
                        'description' => $i->id . '-' . $i->name . ' - ' . $i->razao_social,
                        'id' => $i->id,
                    );
                }
            }
            header('Content-Type: application/json');
            echo json_encode($return);
            return;
        }

        $data = new \stdClass();

        $field_input = 'cliente_autocomplete_id';
        $field_hidden = 'cliente';
        if (isset($param['field_input'])) {
            $field_input = $param['field_input'];
        }
        if (isset($param['field_hidden'])) {
            $field_hidden = $param['field_hidden'];
        }

        // recebendo o nome do input hidden
        if (isset($param['field_hidden_name'])) {
            $field_hidden = $param['field_hidden_name'];
        }

        $param_inp_hidden = [
            'type' => 'hidden',
            'label' => 'Cliente Valor',
            'default_value' => strlen(trim($request->input($field_hidden))) ? trim($request->input($field_hidden)) : '',
        ];

        if (isset($param['field_hidden_name'])) {
            $param_inp_hidden['element_id'] = $param['field_hidden'];
            $data->element_id = $param_inp_hidden['element_id'];
        }

        $fields = [
            "{$field_input}" => [
                'label' => 'Cliente',
                'required' => false,
                'autocomplete_off' => true,
                'default_value' => strlen(trim($request->input($field_input))) ? trim($request->input($field_input)) : '',
            ],
            "{$field_hidden}" => $param_inp_hidden,
        ];

        $data->fields = $this->form_builder->mountFormV1($fields);

        $data->field_input = $field_input;
        $data->field_hidden = $field_hidden;

        View::make('user/autocomplete', $data);
    }

    public function inativar($id) 
    {

        if (Session::get('group_code') != 'mandabem') {
            exit("Usuario nao permitido");
        }

        $ina = $this->user_model->inativar($id);

        if (!$ina) {
            echo json_encode(['error' => "Falha, tente novamente mais tarde"]);
        } else {
            echo json_encode(['status' => "1"]);
        }
    }

    public function extrato($user_id = null) {

        $data = new \stdClass();

        $this->payment_model->getExtratoUser($user_id);

        View::make('user/extrato', $data, true);
    }

    public function log($action, $user_id) {

        if (Session::get('group_code') != 'mandabem') {
            abort(404);
            return;
        }

        if ($action == 'active') {
            $this->user_model->log('active', $user_id);
            echo json_encode(['status' => 1, 'user_id' => $user_id]);
            return;
        }

        if ($action == 'see') {

            $data = new \stdClass();

            $data->logs = $this->user_model->log('get', $user_id);

            echo json_encode([
                'html' => View::make('user/log_view', $data, true)
            ]);

            return;
        }
    }

}
