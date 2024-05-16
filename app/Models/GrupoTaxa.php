<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Libraries\FormBuilder;
use Carbon\Carbon;

class GrupoTaxa extends Model
{
    private $error;
    protected $fields;
    public $table = "grupo_taxa";
    public $guarded = [];

    public function __construct()
    {
        // Status
        $tiposStatus = ['1' => 'Habilitado', '0' => 'Desabilitado'];
        $listTiposStatus = [];
        foreach ($tiposStatus as $value => $label) {
            $std = new \stdClass();
            $std->id = $value;
            $std->name = $label;
            $listTiposStatus[] = $std;
        }

        $types = [['id' => 'FIX', 'name' => 'Valores Fixos'], ['id' => 'PERCENT', 'name' => 'Percentual']];
        $tabelas = [['id' => 'varejo', 'name' => 'Varejo'], ['id' => 'industrial', 'name' => 'Industrial']];
        $application = [['id' => 'DEFAULT', 'name' => 'Default'], ['id' => 'PACMINI', 'name' => 'Pac Mini']];

        $this->fields = [
            "name" => ['label' => 'Nome', 'required' => true, 'placeholder' => 'Nome do Grupo'],
            "percent" => ['label' => 'Percentual (%)', 'placeholder' => 'Percentual a ser aplicado', 'class' => 'input-money'],
            "application" => ['label' => 'Aplicação', 'required' => true, 'type' => 'select', 'opts' => $this->getSelectOpts($application), 'help' => 'Defina se esta regra vale para Taxação normal ou PAC Mini'],
            "type" => ['label' => 'Tipo de Desconto', 'required' => true, 'type' => 'select', 'opts' => $this->getSelectOpts($types)],
            "tabela" => ['label' => 'Tabela', 'required' => true, 'type' => 'select', 'opts' => $this->getSelectOpts($tabelas)],
            "status" => ['label' => 'Situação', 'type' => 'select', 'opts' => $listTiposStatus],
        ];
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getError()
    {
        return $this->error;
    }

    private function getSelectOpts($array)
    {
        $opts = [];
        foreach ($array as $item) {
            $std = new \stdClass();
            $std->id = $item['id'];
            $std->name = $item['name'];
            $opts[] = $std;
        }
        return $opts;
    }

    public function saveGrupoTaxa($post)
    {
        // Formatacao do retorno do Error
        $typeErrorReturn = 'NORMAL';
        if (isset($post['type_error_return'])) {
            $typeErrorReturn = $post['type_error_return'];
        }
        $formBuilder = new FormBuilder();
        $validator = $formBuilder->validadeData($post, $this->fields, [], []);

        if (!$validator) {
            $this->error = $formBuilder->getErrorValidation();
            return false;
        } else {
            try {
                DB::beginTransaction();

                $dataInsert = [
                    'date_update' => now(),
                    'name' => $post['name'],
                    'status' => (int) $post['status'],
                    'type' => $post['type'],
                    'tabela' => $post['tabela'],
                    'application' => $post['application'],
                ];

                if ($post['type'] == 'PERCENT') {
                    $percent = (float) str_replace(',', '.', $post['percent']);
                    if (!$percent) {
                        throw new \Exception("Informe o valor Percentual");
                    }
                    $dataInsert['percent'] = $percent;
                }

                if (isset($post['id']) && (int) $post['id']) {
                    $grupoTaxaId = $post['id'];
                    $dataInsert['date_insert'] = now();
                    $this->where('id', $grupoTaxaId)->update($dataInsert);
                } else {
                    $dataInsert['date_insert'] = now();
                    $grupoTaxaId = $this->insertGetId($dataInsert);
                }

                if (!$grupoTaxaId) {
                    throw new \Exception("Falha ao inserir, tente novamente mais tarde");
                }

                if ($grupoTaxaId && $post['type'] == 'FIX') {
                    foreach ($post['faixa_init'] as $k => $v) {
                        $taxa = (float) str_replace(',', '.', $post['taxa'][$k]);
                        $faixaInit = (float) str_replace(',', '.', $post['faixa_init'][$k]);
                        $faixaEnd = (float) str_replace(',', '.', $post['faixa_end'][$k]);

                        $exist = DB::table('grupo_taxa_itens')
                            ->where('grupo_taxa_id', $grupoTaxaId)
                            ->where('min', $faixaInit)
                            ->where('max', $faixaEnd)
                            ->first();

                        $itensInsert = [
                            'taxa' => $taxa,
                            'date_update' => now(),
                        ];

                        if ($exist) {
                            DB::table('grupo_taxa_itens')
                                ->where('id', $exist->id)
                                ->update($itensInsert);
                        } else {
                            $itensInsert['grupo_taxa_id'] = $grupoTaxaId;
                            $itensInsert['date_insert'] = now();
                            $itensInsert['min'] = $faixaInit;
                            $itensInsert['max'] = $faixaEnd;

                            DB::table('grupo_taxa_itens')->insert($itensInsert);
                        }
                    }
                }

                DB::commit();
                return true;
            } catch (\Exception $e) {
                DB::rollback();
                $this->error = $e->getMessage();
                return false;
            }
        }
    }

    public function deleteGrupoTaxa($data)
    {
        $id = $data['id'];

        try {
            DB::beginTransaction();

            DB::table('grupo_taxa_itens')->where('grupo_taxa_id', $id)->delete();
            DB::table('grupo_taxa')->where('id', $id)->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function get($data)
    {
        $id = $data['id'];

        $grupo = DB::table('grupo_taxa')->where('id', $id)->first();

        if ($grupo) {
            $grupo->itens = DB::table('grupo_taxa_itens')->where('grupo_taxa_id', $grupo->id)->get();
        }

        return $grupo;
    }

    public function getList(array $data = [])
    {
        $query = DB::table('grupo_taxa');

        if (isset($data['active']) && $data['active']) {
            $query->where('status', 1);
        }

        if (isset($data['application']) && $data['application']) {
            $query->where('application', $data['application']);
        }

        $grupos = $query->get();

        foreach ($grupos as $g) {
            $g->itens = DB::table('grupo_taxa_itens')->where('grupo_taxa_id', $g->id)->get();
        }
        return $grupos;
    }

    public function getTaxa($grupo_taxa_id, $valor)
    {
        $grupo = $this->get(['id' => $grupo_taxa_id]);

        if ($grupo->type == 'FIX') {
            $row = DB::table('grupo_taxa_itens')
                ->where('grupo_taxa_id', $grupo_taxa_id)
                ->whereRaw('? BETWEEN min AND max', [$valor])
                ->first();

            // Se nao houver valores, pegará o Máximo
            if (!$row) {
                $row = DB::table('grupo_taxa_itens')
                    ->where('grupo_taxa_id', $grupo_taxa_id)
                    ->whereRaw('? > min AND max = 0', [$valor])
                    ->first();
            }

            if ($row) {
                return $row->taxa;
            }
        } elseif ($grupo->type == 'PERCENT') {
            return number_format($valor * ($grupo->percent / 100), 2, '.', '');
        }

        return false;
    }

    public function getGrupo($data)
    {
        $id = $data['id'];

        return DB::table('grupo_taxa')->where('id', $id)->first();
    }
}