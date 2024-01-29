<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    public function mensagens()
    {
        return DB::table('mensagens')->orderByDesc('id')->limit(10)->get();
    }

    public function getMensagensUser($user_id)
    {
        return DB::table('mensagens')->where('user_id', $user_id)->orderByDesc('id')->get();
    }

    public function getMensagemUser($user_id)
    {
        $row = DB::table('mensagens')
            ->where('user_id', $user_id)
            ->where('status', 'ATIVO')
            ->where('conf_leitura', 0)
            ->orderByDesc('id')
            ->limit(1)
            ->first();

        return $row ? $row : false;
    }

    public function salvarMensagem($params)
    {
        return DB::table('mensagens')->insert([
            'user_id' => $params['user_id'],
            'texto' => $params['texto'],
            'by_insert' => $params['by_insert'],
            'date_insert' => now(),
            'by_update' => $params['by_update'],
            'date_update' => now(),
            'status' => 'ATIVO',
        ]);
    }

    public function nameUser($user_id)
    {
        return DB::table('user')->where('id', $user_id)->select('name')->first();
    }

    public function deleteMensagem($id)
    {
        DB::table('mensagens')->where('id', $id)->update([
            'status' => 'APAGADO',
        ]);

        return true;
    }

    public function lerMensagem($id)
    {
        DB::table('mensagens')->where('id', $id)->update([
            'conf_leitura' => 1,
        ]);

        return true;
    }
}
