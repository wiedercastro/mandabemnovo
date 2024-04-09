<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

function getDevice() 
{
    $http_user_agent = request()->header('User-Agent');
    $iphone = strpos($http_user_agent, "iPhone");
    $android = strpos($http_user_agent, "Android");
    $palmpre = strpos($http_user_agent, "webOS");
    $berry = strpos($http_user_agent, "BlackBerry");
    $ipod = strpos($http_user_agent, "iPod");
    $ipad = strpos($http_user_agent, "iPad");

    if ($iphone || $android || $palmpre || $ipod || $ipad || $berry) {
        return "mobile";
    } else {
        return "desktop";
    }
}

function sortByKey1($a, $b) 
{
    $a = $a['key'];
    $b = $b['key'];

    if ($a == $b)
        return 0;
    return ($a > $b) ? 1 : -1;
}

function verifyUser()
{
    if (true) {
        Session::start();
        $userId = Session::get('user_id');

        if (isset($userId)) {
            $user = \App\Models\User::find((int) $userId);

            if ($user && $user->status != 'ACTIVE') {
                Session::forget('user_id');
                Session::forget('user_is_logged');
                Redirect::to('login')->send();
                return;
            }

            if ($user && $user->group_code == 'auditor' && request()->segment(1) != 'manifestacoes') {
                Redirect::to('manifestacoes')->send();
                return;
            }
        }
    }
}
 

function showDOMNode(DOMNode $domNode, $space = 0) 
{
    foreach ($domNode->childNodes as $node) {

        if ($node->hasChildNodes()) {
            echo '<h3>' . $node->nodeName . "</h3><br>\n";
            showDOMNode($node, ++$space);
        } else {
            echo $node->nodeName . ':' . $node->nodeValue . "\n";
        }
    }
}

function tirarAcentos($string) 
{
    return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç)/", "(Ç)"), explode(" ", "a A e E i I o O u U n N c C"), $string);
}

function removeAcentos($string, $slug = false) 
{
    return utf8_decode($string);
}

function mbUcfirst($string) 
{
    return mb_strtoupper(mb_substr($string, 0, 1)) . mb_strtolower(mb_substr($string, 1));
}

function utf8Strlen($string) 
{
    return mb_strlen($string);
}

function utf8Substr($string, $offset, $length = null) 
{
    if ($length === null) {
        return Str::substr($string, $offset);
    } else {
        return Str::substr($string, $offset, $length);
    }
}

function utf8Strtolower($string) 
{
    return mb_strtolower($string);
}

function utf8Strtoupper($string) 
{
    return mb_strtoupper($string);
}

function objectToArray($obj) 
{
    if (is_object($obj))
        $obj = (array) $obj;
    if (is_array($obj)) {
        $new = array();
        foreach ($obj as $key => $val) {
            $new[$key] = objectToArray($val);
        }
    } else
        $new = $obj;
    return $new;
}

function arrayToObject($d) 
{
    if (is_array($d)) {
        /*
         * Return array converted to object
         * Using __FUNCTION__ (Magic constant)
         * for recursive call
         */
        return (object) array_map(__FUNCTION__, $d);
    } else {
        // Return object
        return $d;
    }
}

function parseUtf8ToIso88591($string) 
{
    if (!is_null($string)) {
        $iso88591_1 = utf8_decode($string);
        $iso88591_2 = iconv('UTF-8', 'ISO-8859-1', $string);
        $string = mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
    }
    return $string;
}

function calcDistanceLatLng($point1, $point2) 
{
    $radius = 3958;      // Earth's radius (miles)
    $deg_per_rad = 57.29578;  // Number of degrees/radian (for conversion)

    $distance = ($radius * pi() * sqrt(
                    ($point1['lat'] - $point2['lat']) * ($point1['lat'] - $point2['lat']) + cos($point1['lat'] / $deg_per_rad)  // Convert these to
                    * cos($point2['lat'] / $deg_per_rad)  // radians for cos()
                    * ($point1['lng'] - $point2['lng']) * ($point1['lng'] - $point2['lng'])
            ) / 180);

    return number_format($distance * 1.60934, 2, '.', '');  // Returned using the units used for $radius.
}

function getListaEstados($key_sigla = false) 
{
    $estados = array(
        'Acre' => 'AC',
        'Alagoas' => 'AL',
        'Amapá' => 'AP',
        'Amazonas' => 'AM',
        'Bahia' => 'BA',
        'Ceará' => 'CE',
        'Distrito Federal' => 'DF',
        'Espírito Santo' => 'ES',
        'Goiás' => 'GO',
        'Maranhão' => 'MA',
        'Mato Grosso' => 'MT',
        'Mato Grosso do Sul' => 'MS',
        'Minas Gerais' => 'MG',
        'Pará' => 'PA',
        'Paraíba' => 'PB',
        'Paraná' => 'PR',
        'Pernambuco' => 'PE',
        'Piauí' => 'PI',
        'Rio de Janeiro' => 'RJ',
        'Rio Grande do Norte' => 'RN',
        'Rio Grande do Sul' => 'RS',
        'Rondônia' => 'RO',
        'Roraima' => 'RR',
        'Santa Catarina' => 'SC',
        'São Paulo' => 'SP',
        'Sergipe' => 'SE',
        'Tocantins' => 'TO',
    );

    if ($key_sigla) {
        $new_list = array();
        foreach ($estados as $estado => $sigla) {
            $new_list[$sigla] = $estado;
        }
        return $new_list;
    }

    return $estados;
}

function getCredentialsReversa($user = array()) 
{
    $conexao_reversa = array(
        'production' => array(
            'link' => 'https://cws.correios.com.br/logisticaReversaWS/logisticaReversaService/logisticaReversaWS?wsdl',
            'cnpj' => '27347642000118',
            'usuario' => 'maquinamqn',
            'senha' => 'urbju5', // nova senha: Envio22!
            'cod_adm' => '18086160',
            'contrato' => '9912437691',
            'cartao' => '0073996360',
            'num_diretoria' => '50',
            'ws_login' => 'maquinamqn',
            'ws_password' => 'manda2020',
        ),
        'test' => array(
            'link' => 'https://apphom.correios.com.br/logisticaReversaWS/logisticaReversaService/logisticaReversaWS?wsdl',
            'cnpj' => '34028316000103',
            'usuario' => 'sigep',
            'senha' => 'n5f9t8',
            'cod_adm' => '17000190',
            'contrato' => '9992157880',
            'cartao' => '0067599079',
            'num_diretoria' => '10',
            'ws_login' => 'empresacws',
            'ws_password' => '123456'
        )
    );

    if ($user) {

        // Caso GRUPO de usuario seja contrato usar informações de contrato do próprio
        if ($user['group_code'] == 'cliente_contrato') {
            if (strlen($user['cartao_correios'])) {
                $conexao['production']['cartao'] = $user['cartao_correios'];
            } else {
                $conexao['production']['cartao'] = '';
            }
            $conexao['production']['contrato'] = $user['contrato_correios'];
            $conexao['production']['num_diretoria'] = getNumDiretoria($user['uf'], $user['cidade']);
            $conexao['production']['cod_adm'] = $user['codigo_adm_correios'];

        }
    }

    return $conexao_reversa;
}

function getCredentialsEtiquetaECT($user = array()) 
{
    $conexao = array(
        'production' => array(
            #'link' => 'https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl',
            'link' => 'http://webservice.correios.com.br/service/rastro/Rastro.wsdl',
            'cnpj' => '27347642000118',
            'usuario' => 'MAQUINAMQN', #'ECT',
            'senha' => '8PHTAAXRIB', #'SRO',
            'cod_adm' => '18086160',
            'contrato' => '9912437691',
            'cod_serv' => '000',
            'cartao' => '0073996360',
            'num_diretoria' => '50',
        ),
        'test' => array(
            'link' => 'https://apphom.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl',
            'cnpj' => '34028316000103',
            'usuario' => 'ECT',
            'senha' => 'SRO',
            'cod_adm' => '17000190',
            'contrato' => '9992157880',
            'cod_serv' => '000',
            'cartao' => '0067599079',
            'num_diretoria' => '10',
        ),
    );

    if ($user) {

        // Caso GRUPO de usuario seja contrato usar informações de contrato do próprio
        if ($user['group_code'] == 'cliente_contrato') {
            if (strlen($user['cartao_correios'])) {
                $conexao['production']['cartao'] = $user['cartao_correios'];
            } else {
                $conexao['production']['cartao'] = '';
            }
            $conexao['production']['contrato'] = $user['contrato_correios'];
            $conexao['production']['num_diretoria'] = getNumDiretoria($user['uf'], $user['cidade']);
            $conexao['production']['cod_adm'] = $user['codigo_adm_correios'];

        }
    }

    return $conexao;
}

function getCredentialsEtiqueta($user = array()) 
{
    $conexao = array(
        'production' => array(
            'link' => 'https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl',
            'cnpj' => '27347642000118',
            'usuario' => 'MAQUINAMQN',
            'senha' => 'urbju5',
            'cod_adm' => '18086160',
            'contrato' => '9912437691',
            'cod_serv' => '000',
            'cartao' => '0073996360',
            'num_diretoria' => '50',
            'ws_login' => 'maquinamqn', // para abertura de Manifestacao
            'ws_password' => 'manda2020' // para abertura de Manifestacao
        ),
        'test' => array(
            'link' => 'https://apphom.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl',
            'cnpj' => '34028316000103',
            'usuario' => 'sigep',
            'senha' => 'n5f9t8',
            'cod_adm' => '17000190',
            'contrato' => '9992157880',
            'cod_serv' => '000',
            'cartao' => '0067599079',
            'num_diretoria' => '10',
        ),
    );

    if ($user) {

        // Caso GRUPO de usuario seja contrato usar informações de contrato do próprio
        if ($user['group_code'] == 'cliente_contrato') {
            if (strlen($user['cartao_correios'])) {
                $conexao['production']['cartao'] = $user['cartao_correios'];
            } else {
                $conexao['production']['cartao'] = '';
            }
            $conexao['production']['contrato'] = $user['contrato_correios'];
            $conexao['production']['num_diretoria'] = getNumDiretoria($user['uf'], $user['cidade']);
            $conexao['production']['cod_adm'] = $user['codigo_adm_correios'];

            $conexao['production']['usuario'] = $user['user_correios'];
            $conexao['production']['senha'] = $user['senha_correios'];
            $conexao['production']['cnpj'] = $user['cnpj'];
        }
    }

    return $conexao;
}

function getNumDiretoria($UF, $cidade = null) 
{
    $diretoria = '';

    // São Paulo Capital - Codigo exclusivo
    if ($UF == 'SP' && $cidade == 'São Paulo') {
        return '72';
    }

    switch ($UF) {
        case 'AC': $diretoria = '03';
            break;
        case 'AL': $diretoria = '04';
            break;
        case 'AP': $diretoria = '05';
            break;
        case 'AM': $diretoria = '06';
            break;
        case 'BA': $diretoria = '08';
            break;
        case 'DF': $diretoria = '10';
            break;
        case 'CE': $diretoria = '12';
            break;
        case 'ES': $diretoria = '14';
            break;
        case 'GO': $diretoria = '16';
            break;
        case 'MA': $diretoria = '18';
            break;
        case 'MG': $diretoria = '20';
            break;
        case 'MS': $diretoria = '22';
            break;
        case 'MT': $diretoria = '24';
            break;
        case 'PA': $diretoria = '28';
            break;
        case 'PB': $diretoria = '30';
            break;
        case 'PE': $diretoria = '32';
            break;
        case 'PI': $diretoria = '34';
            break;
        case 'PR': $diretoria = '36';
            break;
        case 'RJ': $diretoria = '50';
            break;
        case 'RN': $diretoria = '60';
            break;
        case 'RO': $diretoria = '26';
            break;
        case 'RR';
            $diretoria = '65';
            break;
        case 'RS';
            $diretoria = '64';
            break;
        case 'SC';
            $diretoria = '68';
            break;
        case 'SE';
            $diretoria = '70';
            break;
        case 'SP';
            $diretoria = '74';
            break;
        case 'TO';
            $diretoria = '75';
            break;
    }
    return $diretoria;
}

function getInfoPrestadorNfse() 
{
    $prestador = array(
        'cnpj' => '27347642000118',
        'inscricao_municipal' => '10468930',
        'razao_social' => 'MANDA BEM INTERMEDIACOES LTDA',
        'fantasia' => 'MANDA BEM',
        'logradouro' => 'RUA MAL MASCARENHAS DE MORAIS',
        'numero' => '191',
        'complemento' => 'AP T303',
        'bairro' => 'COPACABANA',
        'uf' => 'RJ',
        'cep' => '22030040',
        'cod_municipio' => '3304557',
        'telefone' => '21983100077',
        'email' => 'maduartedecastro@gmail.com',
    );

    return $prestador;
}

function getSelectOpts($list) 
{
    $listaX = array();
    foreach ($list as $i) {
        $std = new stdClass();
        $std->id = $i->id;
        $std->name = $i->name;
        $listaX[] = $std;
    }
    return $listaX;
}

function getSelectOptsArray($list) 
{
    $listaX = array();
    foreach ($list as $k => $v) {
        $std = new stdClass();
        $std->id = $k;
        $std->name = $v;
        $listaX[] = $std;
    }
    return $listaX;
}

// normalizar valores floats
function normalizePriceValue($value) 
{
    return (float) number_format($value, 2, '.', '');
}

function getFisrtStr($str) 
{
    $tmp = explode(' ', $str);
    if (count($tmp) && isset($tmp[0])) {
        return $tmp[0];
    }
    return $str;
}

function encodeXML($xml) 
{
    // Tratando caracteres especiais
    $xml = preg_replace('/&/', "&amp;", $xml);
    $xml = preg_replace('/"/', " ;", $xml);
    $xml = preg_replace("/'/", " ", $xml);
    $xml = preg_replace("/</", "&lt;", $xml);
    $xml = preg_replace("/>/", "&gt;", $xml);
    return $xml;
}
