<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class Faq extends Model
{
    protected $table = 'faq';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id_creator',
        'category_id',
        'question',
        'answer',
        'visible_mandabem',
        'visible_customer',
        'date_update',
        'date_insert',
    ];
      
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(FaqCategorie::class, 'category_id');
    }
    
    public function getListFaqs(string|null $filter)
    {
        return $this
            ->with('categorie:id,name')
            ->when(filled($filter), function ($query) use ($filter) {
                return $query->where('question', 'LIKE', "%{$filter}%")
                        ->orWhere('answer', 'LIKE', "%{$filter}%");
            })
            ->orderBy('date_insert', 'DESC')
            ->paginate(15);
    }
    

    public function getDateInsertAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }    

    public function saveFaq($data)
    {
        $data_upd = [
            'user_id_creator' => $data['user_id_creator'],
            'category_id' => $data['category_id'],
            'question' => $data['question'],
            'answer' => $data['answer'],
            'visible_mandabem' => isset($data['visible_mandabem']) && (int)$data['visible_mandabem'] ? 1 : 0,
            'visible_customer' => isset($data['visible_customer']) && (int)$data['visible_customer'] ? 1 : 0,
            'date_update' => now(),
        ];

        if (isset($data['id'])) {
            $faq = $this->find($data['id']);
            $faq->update($data_upd);
        } else {
            $data_upd['date_insert'] = now();
            $this->create($data_upd);
        }

        return true;
    }


    public function saveCategory($data)
    {
        $data['faq_category_name'] = Str::ucfirst($data['faq_category_name']);

        $data_upd = [
            'name' => $data['faq_category_name'],
            'id_user_creator' => $data['id_user_creator'],
            'date_update' => now(),
        ];

        if (isset($data['id']) && (int) $data['id']) {
            DB::table('faq_categories')
                ->where('id', $data['id'])
                ->update($data_upd);
        } else {
            $data_upd['date_insert'] = now();
            DB::table('faq_categories')->insert($data_upd);
        }

        return true;
    }
}

 
