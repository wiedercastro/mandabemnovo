<?php

defined('BASEPATH') or exit('No direct access');

class Email_maker {

    private $ci;
    private $error;
    protected $obj_email = null;

    public function __construct() {
        $this->ci = &get_instance();
    }

    public function get_error() {
        return $this->error;
    }

    public function sendAmazon($data = []) 
    {
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://email-smtp.sa-east-1.amazonaws.com',
            'smtp_port' => 465,
            'smtp_user' => 'AKIAVA4QDQYXLDY7BJOI',
            'smtp_pass' => 'BDB/SJR3ouzpMGCOHvEBKfYmvrE3DKmmXenQDMNnvUVx',
            'mailtype' => 'html',
            'charset' => 'UTF-8',
            'wordwrap' => TRUE
        );
        
        $email_copy = 'reginaldo@mandabem.com.br';
        $this->ci->load->library('email', $config);
        $this->obj_email = new CI_Email($config);
        
        if (isset($data['in_reply_to']) && $data['in_reply_to']) {
            $this->obj_email->set_header('References', $data['in_reply_to']);
            $this->obj_email->set_header('In-Reply-To', $data['in_reply_to']);
        }
        

        if (isset($data['unique']) && $data['unique']) {
            $this->obj_email->set_newline("\r\n");
            $from = isset($data['from']) ? $data['from'] : 'info@updateenvio.com.br';
            $message = isset($data['msg']) ? $data['msg'] : '';

            $to = isset($data['to']) ? $data['to'] : 'regygom@gmail.com';
            $subject = isset($data['subject']) ? $data['subject'] : '--';
            
            if(isset($data['name_from'])) {
                $data['name_from'] = preg_replace('/:|\!/', '', $data['name_from']);
                $data['name_from'] = preg_replace('/Ôxe/', 'Oxe', $data['name_from']);
            }
            $this->obj_email->from($from, (isset($data['name_from']) ? $data['name_from'] : ''));
            
            if(isset($data['email_from'])) {
                $this->obj_email->reply_to($data['email_from'], (isset($data['name_from']) ? $data['name_from'] : ''));
            } else {
                $this->obj_email->reply_to('info@updateenvio.com.br', (isset($data['name_from']) ? $data['name_from'] : ''));
            }
            $this->obj_email->to($to);
            if(isset($data['email_replicate'])) {
                $this->obj_email->bcc($data['email_replicate']);
            }  
             
            $this->obj_email->subject($subject);
            $this->obj_email->message($message);

            $send = ($this->obj_email->send(true));

            if (!$send) {
                $this->error = $this->obj_email->print_debugger();
                return false;
            }

            return true;

        }

        $main_sent = [];
        foreach ($data['emails'] as $params) {
            $this->obj_email->set_newline("\r\n");
            $from = 'info@updateenvio.com.br';
            $message = isset($params['msg']) ? $params['msg'] : '';

            $to = isset($params['to']) ? $params['to'] : 'regygom@gmail.com';
            $subject = isset($params['subject']) ? $params['subject'] : '--';
            
            if(isset($params['name_from'])) {
                $params['name_from'] = preg_replace('/:|\!/', '', $params['name_from']);
                $params['name_from'] = preg_replace('/Ôxe/', 'Oxe', $params['name_from']);
            }
            
                $this->obj_email->reply_to($params['email_from'], (isset($params['name_from']) ? $params['name_from'] : ''));
                $this->obj_email->from($from, (isset($params['name_from']) ? $params['name_from'] : ''));
            $this->obj_email->to($to);
            
            if(isset($data['email_replicate'])) {
                $this->obj_email->bcc($data['email_replicate']);
            }            
            $this->obj_email->subject($subject);
            $this->obj_email->message($message);
            if ($this->obj_email->send(true)) {
                $main_sent[$params['ref_id']] = 1;
            } else {
                $this->error = $this->obj_email->print_debugger();
            }
        }
        return $main_sent;
    }
    # Email sends

    public function sendInMassa($data) 
    {

        if (!count($data['emails'])) {
            echo "Sem lista de email fornecida\n";
            return;
        }

        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
        //            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'informativo@mandabem.com.br', // change it to yours
            'smtp_pass' => '5*eV%S2Zws', // change it to yours - 
            'mailtype' => 'html',
            'charset' => 'UTF-8',
            'wordwrap' => TRUE
        );

        if (isset($data['credenciais'])) {
            $config['smtp_user'] = $data['credenciais']['user'];
            $config['smtp_pass'] = $data['credenciais']['pass'];
        }

        if (isset($data['account_email'])) {
            $config['smtp_user'] = $data['account_email'];
            $config['smtp_pass'] = $data['account_pass'];
        }

        $this->ci->load->library('email', $config);
        $this->obj_email = new CI_Email($config);
        $main_sent = [];
        foreach ($data['emails'] as $params) {
            $this->obj_email->set_newline("\r\n");
            $from = isset($params['from']) ? $params['from'] : 'informativo@mandabem.com.br';
            $message = isset($params['msg']) ? $params['msg'] : '';

            $to = isset($params['to']) ? $params['to'] : 'regygom@gmail.com';
            $subject = isset($params['subject']) ? $params['subject'] : '--';

            if (isset($params['email_from'])) {
                $this->obj_email->from($params['email_from'], (isset($params['name_from']) ? $params['name_from'] : ''));
                $this->obj_email->reply_to($params['email_from'], (isset($params['name_from']) ? $params['name_from'] : ''));
            } else {
                $this->obj_email->from($from, $this->ci->config->item('site_name'));
                $this->obj_email->reply_to($from, $this->ci->config->item('site_name'));
            }
            if (isset($params['bcc']) && $params['bcc'] != null) {
                $this->obj_email->bcc($params['bcc']);
            } else {
                $this->obj_email->to($to);
            }
            $this->obj_email->subject($subject);
            $this->obj_email->message($message);
            if ($this->obj_email->send(true)) {
        //                return true;
                $main_sent[$params['ref_id']] = 1;
            } else {
                $this->error = $this->obj_email->print_debugger();
            }
        }
        return $main_sent;
    }

    public function sendMail($params = []) 
    {

        $from = isset($params['from']) ? $params['from'] : 'marcos@mandabem.com.br';

        // Configuração para gmail
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            //            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'marcos@mandabem.com.br', // change it to yours
            'smtp_pass' => '5*eV%S2Zws', // change it to yours - 
            'mailtype' => 'html',
            'charset' => 'UTF-8',
            'wordwrap' => TRUE
        );

        if (isset($params['credenciais'])) {
            $config['smtp_user'] = $params['credenciais']['user'];
            $config['smtp_pass'] = $params['credenciais']['pass'];
        }

        if (isset($params['account_email'])) {
            $config['smtp_user'] = $params['account_email'];
            $config['smtp_pass'] = $params['account_pass'];
        }

        $message = isset($params['msg']) ? $params['msg'] : '';

        $to = isset($params['to']) ? $params['to'] : 'regygom@gmail.com';
        $subject = isset($params['subject']) ? $params['subject'] : '--';

        $this->ci->load->library('email', $config);
        $this->obj_email = new CI_Email($config);
        $this->obj_email->set_newline("\r\n");
        
        if (isset($params['in_reply_to']) && $params['in_reply_to']) {
            $this->obj_email->set_header('References', $params['in_reply_to']);
            $this->obj_email->set_header('In-Reply-To', $params['in_reply_to']);
        }

        // Anexos 
        if (isset($params['attach']) && count($params['attach'])) {
            foreach ($params['attach'] as $attach) {
                if (isset($attach['path'])) {
                    $this->obj_email->attach($attach['path'], 'attachment', $attach['name']);
                } else if (isset($attach['content'])) {
                    $this->obj_email->attach($attach['content'], 'attachment', $attach['name'], $attach['mime']);
                }
            }
        }

        if (isset($params['email_from'])) {
            $this->obj_email->from($params['email_from'], (isset($params['name_from']) ? $params['name_from'] : ''));
            $this->obj_email->reply_to($params['email_from'], (isset($params['name_from']) ? $params['name_from'] : ''));
        } else {
            $this->obj_email->from($from, $this->ci->config->item('site_name'));
            $this->obj_email->reply_to($from, $this->ci->config->item('site_name'));
        }
        if (isset($params['bcc']) && $params['bcc'] != null) {
            $this->obj_email->bcc($params['bcc']);
        } else {
            $this->obj_email->to($to);
        }
        $this->obj_email->subject($subject);
        $this->obj_email->message($message);
        if ($this->obj_email->send()) {
            return true;
        }

        $this->error = $this->obj_email->print_debugger();

        return false;
    }

    public function msg($param = array()) 
    {
        if(!isset($param['server_send'])) {
            $param['unique'] = true;
            return $this->sendAmazon($param);
        }

        $msg = isset($param['msg']) ? $param['msg'] : '...';
        $subject = isset($param['subject']) ? $param['subject'] : 'Aviso';
        $to = isset($param['to']) ? $param['to'] : 'regygom@gmail.com';

        $p_email = array(
            'to' => $to,
            'subject' => $subject,
            'msg' => $msg
        );

        if (isset($param['account_email'])) {
            $p_email['account_email'] = $param['account_email'];
            $p_email['account_pass'] = $param['account_pass'];
        }

        if (isset($param['email_from'])) {
            $p_email['email_from'] = $param['email_from'];
        }
        if (isset($param['bcc'])) {
            $p_email['bcc'] = $param['bcc'];
        }
        if (isset($param['name_from'])) {
            $p_email['name_from'] = $param['name_from'];
        }
        if (isset($param['credenciais'])) {
            $p_email['credenciais'] = $param['credenciais'];
        }
        if (isset($param['attach'])) {
            $p_email['attach'] = $param['attach'];
        }
        if (isset($param['in_reply_to'])) {
            $p_email['in_reply_to'] = $param['in_reply_to'];
        }

        return $this->sendMail($p_email);
    }

}
