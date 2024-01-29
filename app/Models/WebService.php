<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Webservice extends Model
{
    protected $table = 'api';
    protected $primaryKey = 'id';
    public $timestamps = false;

    private $error;

    public function __construct()
    {
        parent::__construct();
        $this->error = null;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getApi($data = [])
    {
        if (isset($data['user_id'])) {
            return DB::table('api')->where(['user_id' => $data['user_id'], 'status' => 1])->orderByDesc('id')->first();
        } else {
            return DB::table('api')->where(['id' => $data['id'], 'api_token' => $data['api_token'], 'status' => 1])->first();
        }
    }

    public function active($data = [])
    {
        $exist = DB::table('api')->where(['user_id' => $data['user_id'], 'status' => 1])->first();
        
        if ($exist) {
            return true;
        }

        $api_token = password_hash(hash('ripemd160', random_bytes(10) . time() . random_bytes(10)), PASSWORD_BCRYPT);

        return DB::table('api')->insert([
            'user_id' => $data['user_id'],
            'plataforma' => $data['plataforma'],
            'api_token' => $api_token,
            'status' => 1,
            'date_insert' => now(),
            'date_update' => now(),
        ]);
    }

    public function inactive($data = [])
    {
        return DB::table('api')->where('user_id', (int)$data['user_id'])->update(['status' => 0]);
    }

    public function validateApi($data = [])
    {
        if (!isset($data['plataforma_id']) || !isset($data['plataforma_chave'])) {
            $this->error = 'Credenciais [plataforma_id] ou [plataforma_chave] inválidas ou não fornecidas';
            return false;
        }

        $plataforma_id = $data['plataforma_id'];
        $plataforma_chave = $data['plataforma_chave'];

        $api = $this->getApi([
            'id' => $plataforma_id,
            'api_token' => $plataforma_chave,
        ]);

        if (!$api) {
            $this->error = 'Credenciais [plataforma_id] ou [plataforma_chave] inválidas ou não fornecidas';
            return false;
        }

        return $api;
    }

    public function updateApi($data = [])
    {
        $user = DB::table('users')->find($data['api']->user_id);

        if ($data['integracao'] == 'nuvem_shop') {
            return DB::table('api_nuvem_shop')->where('code', $data['code'])->update([
                'user_id' => $user->id,
                'status_generate_post' => $data['status_generate_post'],
            ]);
        }

        return false;
    }

    public function sendTrackNumber($data = [])
    {
        $integration = 'wordpress';

        if (isset($data['integracao'])) {
            $integration = $data['integracao'];
        }

        if ($integration == 'wordpress') {
            $api = $this->getApi(['user_id' => $data['user_id']]);

            if (!$api) {
                return false;
            }

            if (!$api->domain) {
                $this->logError("API sem domínio configurado, necessário atualizar Plugin", 'WORDPRESS', $api->user_id);
                return false;
            }

            $response = $this->sendRequest($api->domain . '/wp-json/mandabem/update_rastreio', 'POST', [
                'id' => $data['ref_id'],
                'rastreio' => $data['etiqueta'] . ' - Postagem: ' . now()->format('d/m/Y'),
            ]);

            if ($response && $response['http_code'] == '200') {
                return true;
            }

            return false;
        }
    }

    public function sendDateEntrega($data = [])
    {
        $integration = 'wordpress';

        if (isset($data['integracao'])) {
            $integration = $data['integracao'];
        }

        if ($integration == 'wordpress') {
            $api = $this->getApi(['user_id' => $data['user_id']]);

            if (!$api) {
                return false;
            }

            if (!$api->domain) {
                $this->logError("API sem domínio configurado, necessário atualizar Plugin", 'WORDPRESS', $api->user_id);
                return false;
            }

            $response = $this->sendRequest($api->domain . '/wp-json/mandabem/update_entrega', 'POST', [
                'id' => $data['ref_id'],
                'entrega' => ' - Entrega: ' . now()->format('d/m/Y'),
            ]);

            if ($response && $response['http_code'] == '200') {
                return true;
            }

            return false;
        }
    }

    private function logError($text, $type, $user_id)
    {
        DB::table('log')->insert([
            'text' => $text,
            'type' => $type,
            'user_id' => $user_id,
        ]);
    }

    private function sendRequest($url, $method, $data)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        ]);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return [
            'response' => $response,
            'http_code' => $info['http_code'],
        ];
    }
}
