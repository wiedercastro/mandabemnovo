<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FaqCategorie extends Model
{
    use HasFactory;

    protected $table = 'faq_categories';

    public function faq(): HasMany
    {
        return $this->hasMany(Faq::class);
    }

    public function getListCategories()
    {
        return $this->select('id', 'name')->get();
    }
}
