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

    public function saveGrupoTaxa(array $data)
    {
        try {
            DB::beginTransaction();

            $dataInsert = [
                'date_update' => now(),
                'name' => $data['name'],
                'status' => $data['situacao'] === 'habilitado' ? 1 : 0,
                'type' => $data['type'] === 'percentual' ? "PERCENT" : "FIX",
                'tabela' => $data['tabela'],
                'application' => $data['application'] === "pac_mini" ? "PACMINI" : "DEFAULT",
            ];
            
            if ($data['type'] == 'percentual') {
                $percent = (float) str_replace(',', '.', $data['percentual']);
                if (!$percent) {
                    throw new \Exception("Informe o valor Percentual");
                }
                $dataInsert['percentual'] = $percent;
            }
            
            if (isset($data['id']) && (int) $data['id']) {
                $grupoTaxaId = $data['id'];
                $dataInsert['date_insert'] = now();
                $this->where('id', $grupoTaxaId)->update($dataInsert);
            } else {
                $dataInsert['date_insert'] = now();
            }

            if ($data['type'] == 'fixos') {
                $grupoTaxaId = $this->insertGetId($dataInsert);

                if (!$grupoTaxaId) {
                    throw new \Exception("Falha ao inserir, tente novamente mais tarde");
                }

                foreach ($data['faixa_init'] as $k => $v) {
                    $taxa = (float) str_replace(',', '.', $data['taxas'][$k]);
                    $faixaInit = (float) str_replace(',', '.', $data['faixa_init'][$k]);
                    $faixaEnd = (float) str_replace(',', '.', $data['faixa_end'][$k]);
       
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
            } else {
                DB::table('grupo_taxa')->insert([
                    'name' => $dataInsert['name'],
                    'status' => $dataInsert['status'],
                    'type' => $dataInsert['type'],
                    'tabela' => $dataInsert['tabela'],
                    'application' => $dataInsert['application'],
                    'date_update' => $dataInsert['date_update']->format('Y-m-d H:i:s'),
                    'date_insert' => $dataInsert['date_insert']->format('Y-m-d H:i:s'),
                ]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function deleteGrupoTaxa(int $idGrupoTaxa)
    {
        try {
            DB::beginTransaction();

            DB::table('grupo_taxa_itens')->where('grupo_taxa_id', $idGrupoTaxa)->delete();
            DB::table('grupo_taxa')->where('id', $idGrupoTaxa)->delete();

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

       /*  foreach ($grupos as $g) {
            $g->itens = DB::table('grupo_taxa_itens')->where('grupo_taxa_id', $g->id)->get();
        } */
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