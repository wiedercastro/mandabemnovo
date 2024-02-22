<?php
namespace App\Libraries;
use Illuminate\Support\Facades\Mail;

class EmailMaker 
{

    private $error;
    protected $obj_email = null;

    public function getError() {
        return $this->error;
    }

    public function sendAmazon($data = [])
    {
        // Configurações de e-mail
        $config = [
            'driver' => 'smtp',
            'host' => 'email-smtp.sa-east-1.amazonaws.com',
            'port' => 465,
            'username' => 'AKIAVA4QDQYXLDY7BJOI',
            'password' => 'BDB/SJR3ouzpMGCOHvEBKfYmvrE3DKmmXenQDMNnvUVx',
            'encryption' => 'ssl',
            'from' => ['address' => 'info@updateenvio.com.br', 'name' => ''], // Seu endereço de e-mail e nome
            'reply_to' => ['address' => 'info@updateenvio.com.br', 'name' => ''],
            'charset' => 'utf-8',
            'newline' => "\r\n",
        ];

        // Configuração do e-mail
        Mail::send([], [], function ($message) use ($data, $config) {
            if (isset($data['unique']) && $data['unique']) {
                // Configuração para e-mail único
                $this->configureSingleEmail($message, $data, $config);
            } else {
                // Configuração para e-mails múltiplos
                $this->configureMultipleEmails($message, $data, $config);
            }
        });
    }

    private function configureSingleEmail($message, $data, $config)
    {
        if (isset($data['in_reply_to']) && $data['in_reply_to']) {
            $message->getHeaders()->addTextHeader('References', $data['in_reply_to']);
            $message->getHeaders()->addTextHeader('In-Reply-To', $data['in_reply_to']);
        }

        $from = isset($data['from']) ? $data['from'] : $config['from']['address'];
        $name_from = isset($data['name_from']) ? $data['name_from'] : $config['from']['name'];
        $message->from($from, $name_from);

        $email_from = isset($data['email_from']) ? $data['email_from'] : $config['reply_to']['address'];
        $message->replyTo($email_from, $name_from);

        $to = isset($data['to']) ? $data['to'] : 'regygom@gmail.com';
        $message->to($to);

        if (isset($data['email_replicate'])) {
            $message->bcc($data['email_replicate']);
        }

        $subject = isset($data['subject']) ? $data['subject'] : '--';
        $message->subject($subject);

        $message->setBody($data['msg'], 'text/html');
    }

    private function configureMultipleEmails($message, $data, $config)
    {
        $main_sent = [];

        foreach ($data['emails'] as $params) {
            $this->configureSingleEmail($message, $params, $config);

            if (Mail::send()->failures()) {
                $this->error = 'Erro no envio de e-mail para: ' . implode(', ', Mail::failures());
            } else {
                $main_sent[$params['ref_id']] = 1;
            }
        }

        return $main_sent;
    }

    public function sendInMassa($data)
    {
        if (empty($data['emails'])) {
            echo "Sem lista de e-mails fornecida\n";
            return;
        }

        $config = [
            'driver' => 'smtp',
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'encryption' => 'tls',
            'username' => 'informativo@mandabem.com.br',
            'password' => '5*eV%S2Zws',
            'from' => ['address' => 'informativo@mandabem.com.br', 'name' => ''],
            'reply_to' => ['address' => 'informativo@mandabem.com.br', 'name' => ''],
            'mailtype' => 'html',
            'charset' => 'UTF-8',
            'wordwrap' => TRUE,
        ];

        if (isset($data['credenciais'])) {
            $config['username'] = $data['credenciais']['user'];
            $config['password'] = $data['credenciais']['pass'];
        }

        if (isset($data['account_email'])) {
            $config['username'] = $data['account_email'];
            $config['password'] = $data['account_pass'];
        }

        $main_sent = [];

        foreach ($data['emails'] as $params) {
            Mail::send([], [], function ($message) use ($params, $config) {
                $this->configureEmailMessage($message, $params, $config);
            });

            if (Mail::failures()) {
                $this->error = 'Erro no envio de e-mail para: ' . implode(', ', Mail::failures());
            } else {
                $main_sent[$params['ref_id']] = 1;
            }
        }

        return $main_sent;
    }

    private function configureEmailMessage($message, $params, $config)
    {
        $message->setNewline("\r\n");

        $from = isset($params['from']) ? $params['from'] : $config['from']['address'];
        $message->from($from, isset($params['name_from']) ? $params['name_from'] : '');

        if (isset($params['email_from'])) {
            $message->replyTo($params['email_from'], isset($params['name_from']) ? $params['name_from'] : '');
        } else {
            $message->replyTo($config['reply_to']['address'], $config['reply_to']['name']);
        }

        if (isset($params['bcc']) && $params['bcc'] != null) {
            $message->bcc($params['bcc']);
        } else {
            $message->to(isset($params['to']) ? $params['to'] : 'regygom@gmail.com');
        }

        $message->subject(isset($params['subject']) ? $params['subject'] : '--');
        $message->html(isset($params['msg']) ? $params['msg'] : '');
    }

    public function sendMail($params = [])
    {
        $from = isset($params['from']) ? $params['from'] : 'marcos@mandabem.com.br';

        // Configuração para gmail
        $config = [
            'driver' => 'smtp',
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'encryption' => 'tls',
            'username' => 'marcos@mandabem.com.br', // change it to yours
            'password' => '5*eV%S2Zws', // change it to yours
            'from' => ['address' => $from, 'name' => ''], // Seu endereço de e-mail e nome
            'reply_to' => ['address' => $from, 'name' => ''],
            'mailtype' => 'html',
            'charset' => 'UTF-8',
            'wordwrap' => TRUE,
        ];

        if (isset($params['credenciais'])) {
            $config['username'] = $params['credenciais']['user'];
            $config['password'] = $params['credenciais']['pass'];
        }

        if (isset($params['account_email'])) {
            $config['username'] = $params['account_email'];
            $config['password'] = $params['account_pass'];
        }

        $message = isset($params['msg']) ? $params['msg'] : '';

        // Configuração do e-mail
        Mail::send([], [], function ($message) use ($params, $config) {
            if (isset($params['in_reply_to']) && $params['in_reply_to']) {
                $message->getHeaders()->addTextHeader('References', $params['in_reply_to']);
                $message->getHeaders()->addTextHeader('In-Reply-To', $params['in_reply_to']);
            }
            $to = isset($params['to']) ? $params['to'] : 'regygom@gmail.com';
            $subject = isset($params['subject']) ? $params['subject'] : '--';

            // Anexos 
            if (isset($params['attach']) && count($params['attach'])) {
                foreach ($params['attach'] as $attach) {
                    if (isset($attach['path'])) {
                        $message->attach($attach['path'], ['as' => $attach['name']]);
                    } elseif (isset($attach['content'])) {
                        $message->attachData($attach['content'], $attach['name'], ['mime' => $attach['mime']]);
                    }
                }
            }

            if (isset($params['email_from'])) {
                $message->from($params['email_from'], (isset($params['name_from']) ? $params['name_from'] : ''));
                $message->replyTo($params['email_from'], (isset($params['name_from']) ? $params['name_from'] : ''));
            } else {
                $message->from($config['from']['address'], $config['from']['name']);
                $message->replyTo($config['reply_to']['address'], $config['reply_to']['name']);
            }

            if (isset($params['bcc']) && $params['bcc'] != null) {
                $message->bcc($params['bcc']);
            } else {
                $message->to($to);
            }

            $message->subject($subject);
            $message->setBody($message, 'text/html');
        });

        return true;  
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
