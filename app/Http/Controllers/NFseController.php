<?php

namespace App\Http\Controllers;

use App\Models\Nfse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use stdClass;

class NFseController extends Controller
{
    public function __construct(protected Nfse $nfse_model)
    { }

    public function index(Request $request): View
    {
       /*  $data = new stdClass();

        $params = [];

        if ($request->filter_month) {
            $params['date_autorizacao_start'] = date('Y') . '-' . str_pad($request->filter_month, 2, '0', STR_PAD_LEFT) . '-01 00:00:00';
            $params['date_autorizacao_end'] = $request->filter_month;
        }
        
        if ($request->filter_cliente) {
            $params['filter_cliente'] = $request->filter_cliente;
        }
        if ($request->filter_date_start) {
            $params['filter_date_start'] = $request->filter_date_start;
        }
        if ($request->filter_date_end) {
            $params['filter_date_end'] = $request->filter_date_en;
        }
        
       // $params['get_total'] = true;
        
        if (auth()->user()->id != 3) {
            $params['user_id'] = auth()->user()->id;
        }
        $data = $this->nfse_model->getList($params); */
        //dd($data);

        return view('layouts.nfse.index');
    }
}
