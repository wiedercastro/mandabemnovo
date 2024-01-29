<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Log extends Authenticatable
{
    protected $table = 'log';
    protected $fillable = ['text', 'user_id', 'type', 'date', 'ip'];

    public function log($data = [])
    {
        $param = [
            'text' => $data['text'] ?? null,
            'user_id' => $data['user_id'] ?? null,
            'type' => $data['type'] ?? 'INFO',
            'date' => now(),
            'ip' => request()->ip(),
        ];

        DB::table('log')->insert($param);
    }
}
