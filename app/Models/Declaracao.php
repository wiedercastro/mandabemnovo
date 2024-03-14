<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Declaracao extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $table = 'declaracao'; 

    protected $fillable = [
        'coleta_id',
        'date_update',
        'date_insert',
    ];

    public function getDateInsertAttribute($value)
    {
      return Carbon::parse($value)->format('d/m/Y H:i');
    }    

    public function getList()
    {
        if (!isset($param['user_id'])) {
            $this->error = "ID de usuário não fornecido";
            return false;
        }

        $query = $this->where('user_id', $param['user_id']);

        if (isset($param['get_total'])) {
            return $query->count();
        } else {
            $query->select('id', 'date_insert as data', DB::raw('(SELECT SUM(envios.valor_total) FROM envios WHERE envios.coleta_id = coletas.id) as valor_total'), 'status');
            $query->select('user.name as user_name');
        }

        $query->join('users as user', 'user.id', '=', 'coletas.user_id');

        $query->whereNotNull('plp');
        $query->where('type', 'NORMAL');
        $query->where(DB::raw('(SELECT COUNT(*) FROM envios WHERE envios.coleta_id = coletas.id)'), '>', 0);

        if (!isset($param['get_total']) || !$param['get_total']) {
            $limit = isset($param['per_page']) ? $param['per_page'] : 10;
            $start = isset($param['page_start']) ? $param['page_start'] : 0;

            $query->limit($limit)->offset($start)->orderByDesc('id');

            $coletas = $query->get();

            foreach ($coletas as $coleta) {
                $coleta->envios = Envio::where('coleta_id', $coleta->id)->get();

                // Novas Declarações
                foreach ($coleta->envios as $e) {
                    $e->declaracoes = Declaracao::where('envio_id', $e->id)->get();
                }

                $declaracoes = Declaracao::where('id_declaracoes', $coleta->id)->get();
                foreach ($declaracoes as $dec) {
                    $coleta->declaracoes[$dec->id_identificador]['cpf'] = $dec->cpfDestinatario;
                    $coleta->declaracoes[$dec->id_identificador]['itens'][$dec->id_declaracao] = $dec;
                }
            }

            return $coletas;
        }
    }   

    public function generateByProducts($param = [])
    {
        // $emailMaker = new EmailMaker(); 

        if (empty($param['products'])) {
            return false;
        }

        $x = 0;

        foreach ($param['products'] as $prod) {
            $insert = [
                'envio_id' => $param['envio_id'],
                'id_declaracoes' => null,
                'id_identificador' => $x,
                'id_item' => $x,
                'item' => $prod['nome'],
                'quantidade' => $prod['quantidade'],
                'valor' => isset($prod['preco']) ? $prod['preco'] : 0,
                'cpfDestinatario' => $param['cpf'],
            ];

            $x++;

            try {
                $this->insert($insert);
            } catch (\Exception $e) {
                // $emailMaker->msg([
                //     'subject' => 'Notificando Nuvemshop',
                //     'msg' => "Params:\n" . print_r($param, true) . "\nError:\n" . print_r($e, true),
                //     'to' => 'reginaldo@mandabem.com.br,clayton@mandabem.com.br',
                // ]);

                $this->error = print_r($e, true);
                return false;
            }
        }

        return true;
    }

    public function save($param = [])
    {
        $coletaModel = new Coleta(); 

        $coleta = $coletaModel->get($param);

        if (!$coleta) {
            $this->error = "Coleta não encontrada.";
            return false;
        }

        if (!isset($param['cpf'])) {
            $this->error = "Informe o CPF.";
            return false;
        }

        if (!isset($param['name_item']) || !isset($param['quantidade_item']) || !isset($param['valor_item'])) {
            $this->error = "Nenhum Item informado";
            return false;
        }

        foreach ($param['name_item'] as $envio_id => $row1) {
            foreach ($row1 as $indice => $info) {
                $name = trim($info);
                $quantidade = trim($param['quantidade_item'][$envio_id][$indice]);
                $valor = trim($param['valor_item'][$envio_id][$indice]);

                if (!strlen($name) || !strlen($quantidade) || !strlen($valor)) {
                    $campo = !strlen($name) ? 'Nome Item' : (!strlen($quantidade) ? 'Quantidade Item' : 'Valor Item');
                    $this->error = "Informe todos os itens ($campo) linha " . ($indice + 1);
                    return false;
                }
            }
        }

        $this->where('id_declaracoes', $coleta->id)->delete();

        // Delete envios
        $_envios = DB::table('envios')->where('coleta_id', $coleta->id)->get();
        foreach ($_envios as $e) {
            DB::table('declaracoes')->where('id_declaracoes', null)->where('envio_id', $e->id)->delete();
        }

        $testNew = true;
        if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == '201.182.68.53') {
            $testNew = true;
        }

        if ($testNew) {
            $declaracao = DB::table('declaracao')->where('coleta_id', $coleta->id)->first();

            if (!$declaracao) {
                $paramDeclaracao = [
                    'coleta_id' => $coleta->id,
                    'date_update' => now(),
                    'date_insert' => now(),
                ];

                $declaracaoId = DB::table('declaracao')->insertGetId($paramDeclaracao);

                if (!$declaracaoId) {
                    $this->error = "Falha ao inserir declaração, por favor tente novamente mais tarde";
                    return false;
                }
            } else {
                $declaracaoId = $declaracao->id;
                DB::table('declaracao')->where('id', $declaracaoId)->update(['date_update' => now()]);
            }

            // Removendo Linha dos Envios
            DB::table('declaracao_envio')->where('declaracao_id', $declaracaoId)->delete();

            // Removendo Linha dos Itens dos Envios
            DB::table('declaracao_envio_itens')->where('declaracao_id', $declaracaoId)->delete();
        }

        $x = 0;
        foreach ($param['name_item'] as $envioId => $row1) {
            if ($testNew) {
                $paramDeclaEnvio = [
                    'declaracao_id' => $declaracaoId,
                    'envio_id' => $envioId,
                    'documento' => $param['cpf'][$envioId],
                ];

                $declaracaoEnvioId = DB::table('declaracao_envio')->insertGetId($paramDeclaEnvio);

                if (!$declaracaoEnvioId) {
                    $this->error = "Falha ao inserir declaração, por favor tente novamente mais tarde";
                    return false;
                }
            }

            foreach ($row1 as $indice => $info) {
                $name = trim($info);
                $quantidade = trim($param['quantidade_item'][$envioId][$indice]);
                $cpf = trim($param['cpf'][$envioId]);
                $valor = preg_replace('/,/', '.', trim($param['valor_item'][$envioId][$indice]));

                if ($testNew) {
                    $paramDeclaEnvioItem = [
                        'declaracao_id' => $declaracaoId,
                        'declaracao_envio_id' => $declaracaoEnvioId,
                        'descricao' => $name,
                        'quantidade' => $quantidade,
                        'valor' => $valor,
                    ];

                    $insDeclaEnvioItem = DB::table('declaracao_envio_itens')->insert($paramDeclaEnvioItem);

                    if (!$insDeclaEnvioItem) {
                        $this->error = "Falha ao inserir declaração, por favor tente novamente mais tarde";
                        return false;
                    }
                }

                $insert = [
                    'envio_id' => $envioId,
                    'id_declaracoes' => $coleta->id,
                    'id_identificador' => $x,
                    'id_item' => $x + 1,
                    'item' => $name,
                    'quantidade' => $quantidade,
                    'valor' => $valor,
                    'cpfDestinatario' => $cpf,
                ];

                try {
                    $this->insert($insert);
                } catch (\Exception $e) {
                    $this->error = print_r($e, true);
                    return false;
                }
            }
            $x++;
        }

        return true;
    }

    public function migrate($param = [])
    {
        if (!isset($param['user']) || (empty($param['user']->cpf) && empty($param['user']->cnpj))) {
            // Sem cpf ou cnpj
            return;
        }

        $envios = DB::table('envios')->where('coleta_id', $param['coleta_id'])->get();

        // Se tiver algum sem CPF ou ref_id, não gera
        foreach ($envios as $e) {
            $isEmpty = DB::table('declaracoes')
                ->whereRaw('(cpfDestinatario IS NULL OR cpfDestinatario = "")')
                ->where('envio_id', $e->id)
                ->first();

            if ($isEmpty || empty($e->ref_id)) {
                return;
            }
        }

        // Busca Envios
        $declaracoes = DB::table('declaracoes')
            ->select('envios.id as envio_id', 'envios.coleta_id', 'declaracoes.id_declaracao')
            ->whereNull('declaracoes.id_declaracoes')
            ->where('envios.user_id', $param['user']->id)
            ->where('envios.coleta_id', $param['coleta_id'])
            ->join('envios', 'envios.id', '=', 'declaracoes.envio_id')
            ->get();

        $coletas = [];

        foreach ($declaracoes as $dec) {
            array_push($coletas, $dec->coleta_id);

            DB::table('declaracoes')
                ->where('id_declaracao', $dec->id_declaracao)
                ->where('envio_id', $dec->envio_id)
                ->update(['id_declaracoes' => $dec->coleta_id]);
        }

        foreach ($coletas as $coletaId) {
            $rows = DB::table('declaracoes')
                ->where('id_declaracoes', $coletaId)
                ->orderBy('envio_id')
                ->get();

            $x = 0;
            $oldEnvioId = null;

            foreach ($rows as $i) {
                if ($oldEnvioId != null && $oldEnvioId != $i->envio_id) {
                    $x++;
                }

                DB::table('declaracoes')
                    ->where('envio_id', $i->envio_id)
                    ->where('id_declaracao', $i->id_declaracao)
                    ->update(['id_identificador' => $x]);

                $oldEnvioId = $i->envio_id;
            }
        }

        return true;
    }

    public function getDeclaracao($id)
    {
        $declaracoes = DB::table('declaracao')
            ->select(
                'declaracao.id',
                'declaracao.coleta_id',
                'declaracao.date_update',
                'declaracao.date_insert',
                'de.documento',
                'de.envio_id',
                'de.id as declaracao_envio_id',
                'dei.id as item_id',
                'dei.descricao',
                'dei.quantidade',
                'dei.valor'
            )
            ->leftJoin('declaracao_envio de', 'de.declaracao_id', '=', 'declaracao.id')
            ->leftJoin('declaracao_envio_itens dei', 'dei.declaracao_envio_id', '=', 'de.id')
            ->where('declaracao.coleta_id', $id)
            ->orderBy('declaracao.id', 'desc')
            ->get();

        $declaracao = [];

        foreach ($declaracoes as $d) {
            $declaracao['id'] = $d->id;
            $declaracao['coleta_id'] = $d->coleta_id;
            $declaracao['envio'] = $d->envio_id;
            $declaracao['date_update'] = $d->date_update;
            $declaracao['date_insert'] = $d->date_insert;

            $declaracao['envios'][$d->envio_id]['documento'] = $d->documento;
            $declaracao['envios'][$d->envio_id]['itens'][$d->item_id]['descricao'] = $d->descricao;
            $declaracao['envios'][$d->envio_id]['itens'][$d->item_id]['quantidade'] = $d->quantidade;
            $declaracao['envios'][$d->envio_id]['itens'][$d->item_id]['valor'] = $d->valor;
        }

        return $declaracao;
    }

    public function supports(): HasMany
    {
        return $this->hasMany(Support::class);
    }
}
