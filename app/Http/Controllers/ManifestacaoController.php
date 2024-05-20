<?php

namespace App\Http\Controllers;

use App\Models\Manifestacao;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use stdClass;

class ManifestacaoController extends Controller
{
    public function __construct(protected Manifestacao $manifestacao_model) 
    {}

    public function index(Request $request): View
    {
        $data = new stdClass();

        $filter_periodo      = trim($request->filter_periodo);
        $filter_numero_pi    = trim($request->filter_numero_pi);
        $filter_etiqueta     = trim($request->filter_etiqueta);
        $filter_cliente      = trim($request->filter_cliente);
        $filter_status       = trim($request->filter_status);
        $filter_date_start   = trim($request->date_start);
        $filter_date_end     = trim($request->date_end);

        $filter_only_auditor = (int) $request->filter_only_auditor;

        $url = '';

        if ($filter_etiqueta) {
            $url .= '&filter_etiqueta=' . $filter_etiqueta;
            $param['etiqueta'] = $filter_etiqueta;
        }

        $user = auth()->user();
        $userTipo = $user->group->code;
        //$userTipo = 'mandabem';

        if ($userTipo == 'mandabem' || $userTipo == 'auditor') {
            $param['user_id'] = 'mandabem';

            if ($filter_numero_pi) {
                $url .= '&filter_numero_pi=' . $filter_numero_pi;
                $param['filter_numero_pi'] = $filter_numero_pi;
            }
            if ($filter_periodo) {
                $url .= '&filter_periodo=' . $filter_periodo;
                $param['filter_periodo'] = $filter_periodo;
            }

            if ($filter_cliente) {
                $url .= '&filter_cliente=' . $filter_cliente;
                $param['user_id'] = $filter_cliente;
            }
            if ($filter_status) {
                $url .= '&filter_status=' . $filter_status;
                $param['status'] = $filter_status;
            }
            if ($filter_date_start) {
                $url .= '&filter_date_start=' . $filter_date_start;
                $param['date_start'] = $filter_date_start;
            }
            if ($filter_date_end) {
                $url .= '&filter_date_end=' . $filter_date_end;
                $param['date_end'] = $filter_date_end;
            }
            if ($filter_only_auditor) {
                $url .= '&filter_only_auditor=' . $filter_only_auditor;
                $param['filter_only_auditor'] = $filter_only_auditor;
                $data->filter_only_auditor = $filter_only_auditor;
            }
        } else {
            $param['user_id'] = $userTipo;
        }

        // auditor so verá oq for enviado para ele
        if ($userTipo == 'auditor') {
            $param['filter_only_auditor'] = true;
        }

        $data->filter_periodo = 'current_year';
        if ($filter_periodo) {
            $data->filter_periodo = $filter_periodo;
        }

        if ($data->filter_periodo) {
            $param['filter_periodo'] = $data->filter_periodo;
        }

        if ($filter_status == 'pending') {
            $param['status'] = 'pending';
        }
        if ($request->type == 'ajax') {
            $param['status'] = 'pending_sem_obj_finalizado';
        }


        $list = $this->manifestacao_model->getList($param);

        foreach ($list as $i) {
            $i->reabertura = $this->manifestacao_model->getReaberturas($i->id, 'all');
            
            if($i->date_insert < '2021-01-01'){
                $i->num_prazo_rec = 81;
            } else {
                $i->num_prazo_rec = 30;
            }
            
            
            if ($i->num_comments) {
                $i->comments = $this->manifestacao_model->getComments($i->id);
                foreach ($i->comments as $c) {
                    $lines = explode("\n", $c->comment);
                    $str = '';
                    foreach ($lines as $n) {
                        if (preg_match('/--ATTAC_FILES_/', $n)) {

                            $tmp = [];
                            preg_match('/(\(.*?\))/i', $n, $tmp);

                            if (isset($tmp[1])) {

                                $tmp[1] = preg_replace('/\(|\)/', '', $tmp[1]);

                                $n = preg_replace('/[^0-9]/', '', $n);

                               // $str .= '<img class="thumbnail" style="width: 97%" src="' . base_url('manifestacoes/ver_anexo?path=file_' . $c->id . '_' . $n . '.' . $tmp[1]) . '" /><br>';
                            }
                        } else {
                            $str .= $n . "\n";
                        }
                    }

                    $c->comment = $str;
                }
            }
        }

        $data->list = $list;

        if ($userTipo == 'mandabem') {
            $param['apuracao'] = true;
            $data->apuracao = $this->manifestacao_model->getList($param);

            $data->manifestacoes_negadas = $this->manifestacao_model->getNegadasTransito();
        }

        $data->abertura_automatica = $this->manifestacao_model->getStatsAberturaAutomatica();

        //dd($data);

        return view('layouts.manifestacoes.index', [
            'data' => $data,
            'userTipo' => $userTipo
        ]);
    }
}

