<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorreioCache extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $table = 'correios_cache';
}
