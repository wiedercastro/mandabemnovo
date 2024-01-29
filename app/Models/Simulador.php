<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Simulador extends Model
{
    private $fields = [];
    private $error;

    public function __construct()
    {
        parent::__construct();
        $this->initializeFields();
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getError()
    {
        return $this->error;
    }

    private function initializeFields()
    {
        $pesos = DB::table('envios')->pluck('name', 'id')->toArray();
        $listPeso = [];

        foreach ($pesos as $id => $name) {
            $std = new \stdClass();
            $std->id = $id;
            $std->name = $name;
            $listPeso[] = $std;
        }

        $this->fields = [
            "cep_origem" => ['maxlength' => 8, 'label' => 'CEP Origem', 'required' => true, 'cols' => [4, 6]],
            "cep_destino" => ['maxlength' => 8, 'label' => 'CEP Destino', 'required' => true, 'cols' => [4, 6]],
            "peso" => ['type' => 'select', 'opts' => $listPeso, 'label' => 'Peso', 'required' => true, 'cols' => [4, 8]],
            "valor_declaracao" => ['label' => 'Valor Assegurado', 'placeholder' => 'Opcional', 'class' => 'input-money'],
        ];
    }
}
