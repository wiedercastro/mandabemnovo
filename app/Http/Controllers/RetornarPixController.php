<?php

namespace App\Http\Controllers;

use App\Models\Transferencia;
use Illuminate\Http\Request;

class RetornarPixController extends Controller
{
    public function __construct(
        protected Transferencia $transferencia_model
    ){ }
    
    public function buscar(Request $request) {
        $user_id = $request->cliente_retornar_pix;
        
        $params['filter_cliente'] = $user_id; 
        
        $listPix = $this->transferencia_model->getPixDeleteAuto($params);

        if (empty($listPix)) {
            return response()->json(['status' => 0]);
        }
        return response()->json(['listPix' => $listPix]);
        
    }
}
