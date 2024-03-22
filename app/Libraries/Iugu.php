<?php

namespace App\Libraries;
use App\Models\Log;


class Iugu {

    private $chave_api_test = 'caccd029215f1a2b5e11cbd1bd58e056';
    private $chave_api_production = '21d9352bea89b4a5cb1c03b0ea8e61cc';
    private $environment = 'production'; // production | test
    private $token = '';

    private $error;
    
    protected $description_boleto = 'Adição crédito Manda Bem';
    protected $description_pix = 'Adição crédito Manda Bem';
    
    // chave prod: 4ad90e75559520c7825fd995c369eeb0
    // chave teste: df9354d796674d0027078ad82481ea21
    protected $chave_pix = '4ad90e75559520c7825fd995c369eeb0';  // Produção
//    protected $chave_pix = 'df9354d796674d0027078ad82481ea21'; // Homologação

    public function __construct() {
        if ($this->environment == 'test') {
            $this->token = $this->chave_api_test;
        } else {
            $this->token = $this->chave_api_production;
        }
    }

    // PIX PIX
    public function gerar_cobranca($param) {

        $emailMaker = new EmailMaker();
        $logModel = new Log();
        $validation = new Validation();
        $utils = new DateUtils();
        // $ci->load->library('email_maker');
        // $ci->load->model('log_model');
        // $ci->load->library('validation');
        // $ci->load->database();

        if (request()->ip() == 'xxx189.3.177.162') {

            $var = json_decode('{"id":"B22881765ADB4CF7AC40978CB9A0ED33","due_date":"2020-11-27","currency":"BRL","discount_cents":null,"email":"maduartedecastro@gmail.com","items_total_cents":2500,"notification_url":null,"return_url":null,"status":"pending","tax_cents":null,"total_cents":2500,"total_paid_cents":0,"taxes_paid_cents":null,"paid_at":null,"paid_cents":null,"cc_emails":null,"financial_return_date":null,"payable_with":"pix","overpaid_cents":null,"ignore_due_email":null,"ignore_canceled_email":null,"advance_fee_cents":null,"commission_cents":null,"early_payment_discount":false,"order_id":null,"updated_at":"2020-11-25T09:54:43-03:00","credit_card_brand":null,"credit_card_bin":null,"credit_card_last_4":null,"credit_card_captured_at":null,"credit_card_tid":null,"external_reference":null,"max_installments_value":null,"payer_name":"Marcos Castro","payer_email":"maduartedecastro@gmail.com","payer_cpf_cnpj":"27347642000118","payer_phone":null,"payer_phone_prefix":null,"payer_address_zip_code":"22430090","payer_address_street":"Rua Padre Achotegui","payer_address_district":"Leblon","payer_address_city":"Rio de Janeiro","payer_address_state":"RJ","payer_address_number":"60","payer_address_complement":null,"payer_address_country":null,"secure_id":"b2288176-5adb-4cf7-ac40-978cb9a0ed33-b380","secure_url":"https://faturas.iugu.com/b2288176-5adb-4cf7-ac40-978cb9a0ed33-b380","customer_id":null,"customer_ref":null,"customer_name":null,"user_id":null,"total":"R$ 25,00","taxes_paid":"R$ 0,00","total_paid":"R$ 0,00","total_overpaid":"R$ 0,00","total_refunded":"R$ 0,00","commission":"R$ 0,00","fines_on_occurrence_day":"R$ 0,00","total_on_occurrence_day":"R$ 0,00","fines_on_occurrence_day_cents":0,"total_on_occurrence_day_cents":0,"refunded_cents":0,"remaining_captured_cents":0,"advance_fee":null,"paid":"R$ 0,00","original_payment_id":null,"double_payment_id":null,"per_day_interest":false,"per_day_interest_value":null,"interest":null,"discount":null,"created_at":"25/11, 09:54","created_at_iso":"2020-11-25T09:54:43-03:00","authorized_at":null,"authorized_at_iso":null,"expired_at":null,"expired_at_iso":null,"refunded_at":null,"refunded_at_iso":null,"canceled_at":null,"canceled_at_iso":null,"protested_at":null,"protested_at_iso":null,"chargeback_at":null,"chargeback_at_iso":null,"occurrence_date":null,"refundable":null,"installments":null,"transaction_number":null,"payment_method":null,"financial_return_dates":null,"bank_slip":null,"pix":{"qrcode":"https://qr.iugu.com/public/qr_codes/image/B22881765ADB4CF7AC40978CB9A0ED33","qrcode_base64":null,"qrcode_text":"00020101021226670014br.gov.bcb.pix2545qr.iugu.com/public/payload/v2/A2oUNmMnmotBul15204000053039865802BR5924MANDA BEM INTERMEDIACOES6014RIO DE JANEIRO62070503***63040427"},"items":[{"id":"1AD0A8634486474E8EA50A023CB00CBB","description":"Adi\u00e7\u00e3o cr\u00e9dito Manda Bem","price_cents":2500,"quantity":1,"created_at":"2020-11-25T09:54:44-03:00","updated_at":"2020-11-25T09:54:44-03:00","price":"R$ 25,00"}],"early_payment_discounts":[],"variables":[{"variable":"payer.address.city","value":"Rio de Janeiro"},{"variable":"payer.address.city","value":"Rio de Janeiro"},{"variable":"payer.address.district","value":"Leblon"},{"variable":"payer.address.district","value":"Leblon"},{"variable":"payer.address.number","value":"60"},{"variable":"payer.address.number","value":"60"},{"variable":"payer.address.state","value":"RJ"},{"variable":"payer.address.state","value":"RJ"},{"variable":"payer.address.street","value":"Rua Padre Achotegui"},{"variable":"payer.address.street","value":"Rua Padre Achotegui"},{"variable":"payer.address.zip_code","value":"22430090"},{"variable":"payer.address.zip_code","value":"22430090"},{"variable":"payer.cpf_cnpj","value":"27347642000118"},{"variable":"payer.cpf_cnpj","value":"27347642000118"},{"variable":"payer.email","value":"maduartedecastro@gmail.com"},{"variable":"payer.email","value":"maduartedecastro@gmail.com"},{"variable":"payer.name","value":"Marcos Castro"},{"variable":"payer.name","value":"Marcos Castro"}],"custom_variables":[],"logs":[{"id":"095BAC81AF2E4A2A88C2916594C89445","description":"Fatura criada com sucesso!","notes":"Fatura criada!","created_at":"25/11, 09:54"},{"id":"98DEF9F5507F432DA846AE2C8BA24E9A","description":"Email de Lembrete enviado!","notes":"Lembrete enviado com sucesso para: maduartedecastro@gmail.com","created_at":"25/11, 09:54"}]}', true);

            print_r($var);
            exit;
        }

        $user = $param['user'];

        $data = [];
        $data['email'] = $user->email;
//        $data['due_date'] = date('Y-m-d', $ci->date_utils->get_time() + (84600 * 1)); // 1 DIA
        $data['due_date'] = date('Y-m-d', $utils->getTime() + (3600 * 8)); // 1 Hora

        $data['ensure_workday_due_date'] = 'false'; // para que o vencimento não caia em dias não uteis

        $data['items'][0]['quantity'] = '1';
        $data['items'][0]['description'] = $this->description_pix;
        $data['items'][0]['price_cents'] = $param['value'];
//        $data['total'] = $param['value'];

        $data['payable_with'] = 'pix';
        $data['order_id'] = 'PIX_' . $param['order_id'];
//        $data['bank_slip_extra_days'] = '3';




        $data['payer']['cpf_cnpj'] = $param['doc'];
        $data['payer']['name'] = $user->name;
        
//        $enable = $ci->db->get_where('enable_log',['user_id' => -42482])->row();
        if(true){
            $data['payer']['email'] = 'info@updateenvio.com.br';
            $data['email'] = 'info@updateenvio.com.br';
        } else {
            $data['payer']['email'] = $user->email;
        }
        
        $data['payer']['address']['zip_code'] = $user->CEP;
        $data['payer']['address']['number'] = $user->numero;
        $data['payer']['address']['street'] = $user->logradouro;
        $data['payer']['address']['district'] = $user->bairro;
        $data['payer']['address']['city'] = $user->cidade;
        $data['payer']['address']['state'] = $user->uf;

        $data['token'] = $this->chave_pix;

//        print_r($data);
//        echo json_encode($data);

        $info = $this->request([
            'url' => 'https://api.iugu.com/v1/invoices',
            'post_json' => $data,
            'show_header' => false
        ]);

        $logModel->log([
            'text' => 'Enviado<br>' . print_r($data, true) . '<br>Retorno<br>' . print_r($info, true),
            'type' => 'PIX'
        ]);

        if (!$info) {
            $emailMaker->msg([
                'to' => 'wieder@mandabem.com.br',
                'subject' => 'FAIL Gerar PIX (1)',
                'msg' => 'Enviado<br>' . print_r($param, true) . '<br>Retorno<br>' . print_r($info, true)
            ]);
            $this->error = "Falha, tente novamente mais tarde";
            return false;
        }

//        print_r($info); return;

        /*
          Retorno:
         *     [success] => 1
          [url] => https://faturas.iugu.com/a4ccf4f7-0780-443d-8966-fe48cc587e58-ef86?bs=true
          [pdf] => https://faturas.iugu.com/a4ccf4f7-0780-443d-8966-fe48cc587e58-ef86.pdf
          [identification] => 00000000000000000000000000000000000000000000000
          [invoice_id] => A4CCF4F70780443D8966FE48CC587E58

         *          */
        if (isset($info['id']) && strlen($info['id'])) {
            return $info;
        } else {

            if (isset($info['errors'])) {
                if (isset($info['errors']['payer.address.zip_code'])) {

                    $data['payer']['address']['zip_code'] = '22030040';
                    $data['payer']['address']['number'] = '191';
                    $data['payer']['address']['street'] = 'Rua Marechal Mascarenhas de Moraes';
                    $data['payer']['address']['district'] = 'Copacabana';
                    $data['payer']['address']['city'] = 'Rio de Janeiro';
                    $data['payer']['address']['state'] = 'RJ';

                    $info = $this->request([
                        'url' => 'https://api.iugu.com/v1/invoices',
                        'post_json' => $data,
                        'show_header' => false
                    ]);

                    $logModel->log([
                        'text' => 'Enviado<br>' . print_r($data, true) . '<br>Retorno<br>' . print_r($info, true),
                        'type' => 'BOLETO'
                    ]);

                    if (isset($info['id']) && strlen($info['id'])) {
                        return $info;
                    }
                }
            }

            $emailMaker->msg([
                'to' => 'wieder@mandabem.com.br',
                'subject' => 'FAIL Gerar PIX',
                'msg' => 'Enviado<br>' . print_r($param, true) . '<br>Retorno<br>' . print_r($info, true)
            ]);



            return false;
        }
    }

    public function gerar_boleto($param) {

        $ci = &get_instance();
        $ci->load->library('email_maker');
        $ci->load->model('log_model');

        $data = [];
        $data['method'] = 'bank_slip'; // boleto
//        $data['api_token'] = $this->token;
        $data['restrict_payment_method'] = 'true';
        $data['order_id'] = $param['boleto_id'];
        $data['bank_slip_extra_days'] = '3';

        $data['items']['quantity'] = '1';
        $data['items']['description'] = $this->description_boleto;
        $data['items']['price_cents'] = $param['value'];

        $data['payer']['cpf_cnpj'] = $param['doc'];
        $data['payer']['name'] = $param['user_name'];
        $data['payer']['email'] = $param['email'];
        $data['payer']['address']['zip_code'] = $param['cep'];
        $data['payer']['address']['number'] = $param['user_endereco_numero'];
        $data['payer']['address']['street'] = $param['user_endereco_logradouro'];
        $data['payer']['address']['district'] = $param['user_endereco_bairro'];
        $data['payer']['address']['city'] = $param['user_endereco_cidade'];
        $data['payer']['address']['state'] = $param['user_endereco_estado'];

        $info = $this->request([
            'url' => 'https://api.iugu.com/v1/charge',
            'post' => $data,
            'show_header' => false
        ]);

        $ci->log_model->log([
            'text' => 'Enviado<br>' . print_r($data, true) . '<br>Retorno<br>' . print_r($info, true),
            'type' => 'BOLETO'
        ]);

        /*
          Retorno:
         *     [success] => 1
          [url] => https://faturas.iugu.com/a4ccf4f7-0780-443d-8966-fe48cc587e58-ef86?bs=true
          [pdf] => https://faturas.iugu.com/a4ccf4f7-0780-443d-8966-fe48cc587e58-ef86.pdf
          [identification] => 00000000000000000000000000000000000000000000000
          [invoice_id] => A4CCF4F70780443D8966FE48CC587E58

         *          */
        if (isset($info['success']) && $info['success'] == 1) {
            return $info;
        } else {

            if (isset($info['errors'])) {
                if (isset($info['errors']['payer.address.zip_code'])) {

                    $data['payer']['address']['zip_code'] = '22030040';
                    $data['payer']['address']['number'] = '191';
                    $data['payer']['address']['street'] = 'Rua Marechal Mascarenhas de Moraes';
                    $data['payer']['address']['district'] = 'Copacabana';
                    $data['payer']['address']['city'] = 'Rio de Janeiro';
                    $data['payer']['address']['state'] = 'RJ';

                    $info = $this->request([
                        'url' => 'https://api.iugu.com/v1/charge',
                        'post' => $data,
                        'show_header' => false
                    ]);

                    $ci->log_model->log([
                        'text' => 'Enviado<br>' . print_r($data, true) . '<br>Retorno<br>' . print_r($info, true),
                        'type' => 'BOLETO'
                    ]);

                    if (isset($info['success']) && $info['success'] == 1) {
                        return $info;
                    }
                }
            }

            $ci->email_maker->msg([
                'to' => 'reginaldo@mandabem.com.br',
                'subject' => 'FAIL Gerar Boleto',
                'msg' => 'Enviado<br>' . print_r($param, true) . '<br>Retorno<br>' . print_r($info, true)
            ]);



            return false;
        }
    }

    public function consultar_fatura($invoice_id, $args = []) {
        // https://api.iugu.com/v1/invoices/

        $data = [
            'url' => 'https://api.iugu.com/v1/invoices/' . $invoice_id, //.'?api_token='.$this->token,
//            'post' => $data,
//            'show_header' => true
        ];

        if (isset($args['token'])) {
            $data['token'] = $args['token'];
        }

        $info = $this->request($data);

        return $info;
    }

    public function request($data = array()) {

        $opt_array = array();

        $ch = curl_init();

        if (isset($data['token'])) {
            $opt_array[] = 'Authorization: Basic ' . base64_encode($data['token']);
            unset($data['token']);
        } else {
            $opt_array[] = 'Authorization: Basic ' . base64_encode($this->token);
        }
//        if (isset($data['token'])) {
//            array_push($opt_array, 'X-Shopify-Access-Token: ' . $data['token']);
//        }
//
//        if (isset($data['post']) || isset($data['put'])) {
//            array_push($opt_array, 'Content-Type: application/json');
//        }


        if (isset($data['post_json']) && $data['post_json']) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data['post_json']));
            array_push($opt_array, 'Content-Type: application/json');
        }


        if ($opt_array) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $opt_array);
        }

        curl_setopt_array($ch, array(
            CURLOPT_URL => $data['url'],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
        ));

        if (isset($data['show_header']) && $data['show_header']) {
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        }

        if (isset($data['post']) && $data['post']) {
//            $data['post'] = json_encode($data['post']);
//            echo "*** POST: " . $data['post'];
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data['post']));
        }





//        if (isset($data['put']) && $data['put']) {
//            $data['put'] = json_encode($data['put']);
//            curl_setopt_array($ch, array(
//                CURLOPT_CUSTOMREQUEST => "PUT",
//                CURLOPT_POSTFIELDS => $data['put']
//            ));
//        }
//        if (isset($data['delete']) && $data['delete']) {
//            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
//        }

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($info['http_code'] != '200' && $info['http_code'] != '201') {
            
            
//            echo "Falha request: Sent:\n" . print_r($data, true) . "\nReceived; " . $response . "\nInfo:\n" . print_r($info, true);
            
////            $this->log_model->log(array(
////                'type' => $this->log_type,
////                'text' => "Falha request: Sent:\n" . print_r($data, true) . "\nReceived; " . $response . "\nInfo:\n" . print_r($info, true)
////            ));
        }
        if (isset($data['show_header']) && $data['show_header']) {
            print_r($info);
        }
        $json = json_decode($response, true);
        if ($json) {
            return $json;
        }

        return $response;
    }

}