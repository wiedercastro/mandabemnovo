<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Declaracao extends MX_Controller {

    private $is_logged = false;

    public function __construct() {

        parent::__construct();
        if ((bool) $this->session->user_is_logged) {
            $this->is_logged = true;
        }

        if (!(bool) $this->session->user_is_logged) {
            if ($this->input->server('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest') {
                echo json_encode(['redirect' => base_url('login')]);
            } else {
                redirect('login');
            }
            exit;
        }

        $this->load->model('declaracao_model');
        $this->load->model('envio_model');
        $this->load->model('coleta_model');
        $this->load->model('user_model');

        $this->load->library('pagination');
    }

    public function index() {

        $data = new stdClass();

        $params = array('user_id' => $this->session->user_id);

        // Pagination
        $params['get_total'] = true;
        $data->total_rows = $this->declaracao_model->get_list($params);
        unset($params['get_total']);
        $config = $this->utils->get_config_pagination([
            'url' => 'declaracoes',
            'total' => $data->total_rows,
                //'uri_segment' => 3,
        ]);
        $page_start = $this->input->get('pstart') ? $this->input->get('pstart') : 0;
        $params['per_page'] = $config["per_page"];
        $params['page_start'] = $page_start;
        $this->pagination->initialize($config);
        // Pagination Ends


        $declaracoes = $this->declaracao_model->get_list($params);

        if (!$declaracoes) {
            $data->error = $this->declaracao_model->get_error();
        }

//        foreach ($coletas as $coleta) {
//            $coleta->envios = $this->coleta_model->get_envios($coleta->id);
//        }
        $data->declaracoes = $declaracoes;

        echo Modules::run('mandabem/common/header/index', $data);
        echo $this->load->view('declaracao/list' . $this->config->item('theme_version'), $data, true);
        echo Modules::run('mandabem/common/footer/index', []);
    }

    public function help() {
        $this->load->view('declaracao/help');
    }

    public function gerar() {

        if (true) {

            $data = array(
//                'quantidade' => $this->input->post('quantidade'),
//                'valores' => $this->input->post('valores'),
                'cpf' => $this->input->post('cpf'),
                'name_item' => $this->input->post('name_item'),
                'quantidade_item' => $this->input->post('quantidade_item'),
                'valor_item' => $this->input->post('valor_item'),
                'itens' => $this->input->post('itens'),
                'id' => $this->input->post('coleta_id'),
                'user_id' => $this->session->user_id
            );
            $save = $this->declaracao_model->save($data);

            if (!$save) {
                echo json_encode(array('status' => 0, 'error' => 'Falha ao gerar: ' . $this->declaracao_model->get_error()));
            } else {
                echo json_encode(array('status' => 1, 'msg' => 'Sucesso', 'id' => $this->input->post('coleta_id')));
            }

            return;
        }

        if ($this->input->post('itens_rsw_send')) {

            $id = explode("_", $this->input->post('id'))[0];

            #echo "ID: $id\n";
            // $id_identificador = explode("_", $_POST['id'])[1];
            // $id_identificador0 = $id_identificador-1;  // -1 para começar a partir do 0 no id_identificador
            // $query = 'DELETE FROM `declaracoes` WHERE id_declaracoes = ' . $id_declaracoes . ' AND id_identificador = ' . $id_identificador0;

            $data = array(
                'quantidade' => $this->input->post('quantidade'),
                'valores' => $this->input->post('valores'),
                'cpfs' => $this->input->post('cpfs'),
                'itens' => $this->input->post('itens'),
                'id' => $id,
                'user_id' => $this->session->user_id
            );
            $save = $this->declaracao_model->save($data);

            if (!$save) {
                echo json_encode(array('status' => 0, 'error' => 'Falha ao gerar: ' . $this->declaracao_model->get_error()));
            } else {
                echo json_encode(array('status' => 1, 'msg' => 'Sucesso', 'id' => $id));
            }
        }
    }

    public function print_action($id__) {

        // new 
        $declaracao = null;
        if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == '45.181.35.184') {
            $declaracao = $this->declaracao_model->get_declaracao($id__);
        }

        if (!$declaracao) {
            $idDeclaracao = $id__;
            require DIR_LIBRARY . 'declaracao/gerarCompleto_v2.php';
        } else {
            $envio = $this->envio_model->get_envios_by_coleta($id__);
            $coleta = $this->coleta_model->get(['id' => $id__]);
            $usuario = $this->user_model->get($coleta->user_id);
            
            $other_remetente = array();

            foreach ($declaracao['envios'] as $envio_id => $ev) {
//                print_r($ev); exit;
                $_envio = $this->envio_model->get(['id' => $envio_id, 'user_id' => $coleta->user_id]);
                    
                if ((int) $_envio->user_remetente_id && !$other_remetente) {
//                if (true && !$other_remetente) {
                    $other_remetente = object_to_array($this->user_model->get_user_remetente( $_envio['user_remetente_id'], $_envio['user_id']));
//                    $other_remetente = object_to_array($this->user_model->get_user_remetente( 1170, 27767));
                }
                
                $declaracao['envios'][$envio_id]['dados'] = $_envio;
                
            }

            $data = new stdClass();

            $data->declaracao = $declaracao;
            $data->coleta = $coleta;
            $data->usuario = $usuario;
            $data->quant_envios = count($envio);
            
            $data->other_remetente = $other_remetente;
            
            echo $this->load->view('declaracao/gerar_declaracao', $data, true);
        }
    }

}
