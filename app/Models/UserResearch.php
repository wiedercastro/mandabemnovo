<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserResearch extends Model
{
    protected $table = 'user_research';
    use HasFactory;

    public function getRespostasPesquisas()
    {
        return UserResearch::whereNotNull('value')->where('value', '>=', 0)->get();
    }

    public function getRespostasPesquisasNulas()
    {
        return UserResearch::whereNull('value')->orWhere('value', 0)->get();
    }
}
