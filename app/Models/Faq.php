<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

    public function get($id)
    {
        return $this->find($id);
    }

    public function getList($param = [])
    {
        $query = DB::table('faq')
            ->join('faq_categories', 'faq.category_id', '=', 'faq_categories.id')
            ->select('faq.*', 'faq_categories.name as category')
            ->where('faq_categories.name', 'ASC');

        if (isset($param['group_code']) && $param['group_code']) {
            if ($param['group_code'] == 'mandabem') {
                $query->where('visible_mandabem', 1);
            } else {
                $query->where('visible_customer', 1);
            }
        }

        if (isset($param['text']) && strlen($param['text'])) {
            $query->where(function ($query) use ($param) {
                $query->where('faq.question', 'like', '%' . addslashes(preg_replace('/\s/', '%', $param['text'])) . '%')
                    ->orWhere('faq.answer', 'like', '%' . addslashes(preg_replace('/\s/', '%', $param['text'])) . '%');
            });
        }

        $list = $query->get();

        if (!isset($param['data_struct'])) {
            return $list;
        }

        $result = [];

        foreach ($list as $i) {
            $result[$i->category][] = $i;
        }

        return $result;
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

    public function deleteFaq($data)
    {
        if (!isset($data['id']) || !(int)$data['id']) {
            return false;
        }

        $faq = $this->find($data['id']);
        $faq->delete();

        return true;
    }

    public function getCategories()
    {
        $categories = DB::table('faq_categories')
            ->select('faq_categories.*', DB::raw('(SELECT COUNT(id) FROM faq WHERE faq.category_id = faq_categories.id) > 0 as can_delete'))
            ->get();

        return $categories;
    }
    public function getError() {
        return $this->error;
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

    public function deleteCategory($data)
    {
        if (!isset($data['id']) || !(int) $data['id']) {
            return false;
        }

        DB::table('faq_categories')
            ->where('id', $data['id'])
            ->delete();

        return true;
    }
}

 
