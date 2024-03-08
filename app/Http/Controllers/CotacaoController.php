<?php

namespace App\Http\Controllers;

use App\Enums\AlturaEnum;
use App\Enums\ComprimentoEnum;
use App\Enums\LarguraEnum;
use App\Enums\PesoEnum;

class CotacaoController extends Controller
{
  public function index()
  {
    $pesosEnum       = PesoEnum::cases();
    $comprimentoEnum = ComprimentoEnum::cases();
    $largurasEnum    = LarguraEnum::cases();
    $alturaEnum      = AlturaEnum::cases();

    return view('cotacoes.index', [
      'pesosEnum'       => $pesosEnum,
      'comprimentoEnum' => $comprimentoEnum,
      'largurasEnum'    => $largurasEnum,
      'alturaEnum'      => $alturaEnum,
    ]);
  }

}
