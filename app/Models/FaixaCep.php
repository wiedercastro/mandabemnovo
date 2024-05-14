<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaixaCep extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $table = 'faixa_cep';
}
