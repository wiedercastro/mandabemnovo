<?php
namespace App\Libraries;

use Illuminate\Support\Facades\URL;


class Utils {

    public function getConfigPagination($param = array()) 
    {

        $url = isset($param['url']) ? $param['url'] : '#';

        $config['base_url'] = $url != '#' ?  URL::to($url) : '#';
        $config['total_rows'] = $param['total'];
        $config['per_page'] = isset($param['per_page']) ? $param['per_page'] : 20;
        //$config["uri_segment"] = $param['uri_segment'];
        if ($_SERVER['REMOTE_ADDR'] == 'x177.185.215.232') {
            $config['per_page'] = 100;
        }

        $config['query_string_segment'] = 'pstart';

        $ci = new \stdClass();

        //config for bootstrap pagination class integration
        $config['full_tag_open'] = '<ul class="pagination" id="paginator">';
        $config['full_tag_close'] = '</ul>';
        if (isset($param['lang']) && $param['lang'] == 'en') {
            $config['last_link'] = 'Last';
            $config['first_link'] = 'First';
        } else {
            $config['last_link'] = 'Última';
            $config['first_link'] = 'Primeira';
        }
        if ($ci->config->item('theme_version') == '_v3') {
            $config['first_tag_open'] = '<li class="page-link">';
        } else {
            $config['first_tag_open'] = '<li>';
        }
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        if ($ci->config->item('theme_version') == '_v3') {
            $config['prev_tag_open'] = '<li class="prev page-link">';
        } else {
            $config['prev_tag_open'] = '<li class="prev">';
        }
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        if ($ci->config->item('theme_version') == '_v3') {
            $config['next_tag_open'] = '<li class="page-link">';
        } else {
            $config['next_tag_open'] = '<li>';
        }

        $config['next_tag_close'] = '</li>';

        if ($ci->config->item('theme_version') == '_v3') {
            $config['last_tag_open'] = '<li class="page-link">';
        } else {
            $config['last_tag_open'] = '<li>';
        }

        $config['last_tag_close'] = '</li>';

        if ($ci->config->item('theme_version') == '_v3') {
            $config['cur_tag_open'] = '<li class="active page-link"><a href="#">';
        } else {
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
        }
        $config['cur_tag_close'] = '</a></li>';
        if ($ci->config->item('theme_version') == '_v3') {
            $config['num_tag_open'] = '<li class="page-link">';
            $config['num_tag_close'] = '</li>';
        } else {
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
        }
        $config['page_query_string'] = true;

        return $config;
    }

    public function removeAcent($string) 
    {

        return iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    }

    public function random($length = 8, $type = 'mixed') 
    {
        $key = '';

        if ($type == 'mixed')
            $keys = array_merge(range(0, 9), range('a', 'z'));
        if ($type == 'number')
            $keys = array_merge(range(0, 9));
        if ($type == 'string')
            $keys = array_merge(range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

    public function maskMoney($value = '') 
    {
        if ($value == '')
            $value = 0;
        if (trim($value) == '--')
            return '--';
        return 'R$ ' . number_format($value, 2, ',', '.');
    }

    public function maskPeso($value = '') 
    {
        if ($value == '')
            $value = 0;

        return number_format($value, 3, ',', '.');
    }

    public function maskCep($value = '') 
    {
        if ($value == '')
            return '';
        if (strlen($value) != 8)
            return $value;
        # 13468120
        return substr($value, 0, 5) . '-' . substr($value, 5, 3);
    }

    public function maskWeight($value = '') 
    {
        if ($value == '')
            return '';

        return number_format($value, 3, ',', '');
    }

    public function correctMoneyFromBr($value = '') 
    {
        if ($value == '')
            return '';

        if (!preg_match('/,/', $value))
            return $value;

        $value = preg_replace('/\./', '', $value);
        $value = preg_replace('/,/', '.', $value);

        return $value;
    }

    public function correctWeightFromBr($value = '') 
    {
        if ($value == '')
            return '';

        if (!preg_match('/,/', $value))
            return $value;

        $value = preg_replace('/,/', '.', $value);

        return $value;
    }

    public function maskDateBr($date = '', $hour = false, $seconds = false) 
    {
        if (strlen($date) != 19 && strlen($date) != 10)
            return '';
        #2016-08-27 21:09:53

        $date_formated = substr($date, 8, 2) . '/' . substr($date, 5, 2) . '/' . substr($date, 0, 4);

        $hour_formated = '';
        $seconds_formated = '';
        if (strlen($date) == 19) {
            $hour_formated = substr($date, 11, 5);
            $seconds_formated = substr($date, 17, 2);
        }

        if (!$hour)
            return $date_formated;

        if (!$seconds)
            return $date_formated . ' ' . $hour_formated;

        return $hour_formated . ' ' . $hour_formated . $seconds_formated;
    }

    public function makeNameForImg($name) 
    {
        $t_ext = explode('.', $name);
        $ext = end($t_ext);
        $name_random = $this->random(10);
        $name_file = substr($name_random, 0, 8) . time() . substr($name_random, 8, 8);
        return $name_file . '.' . $ext;
    }

    public function pingSite($url) 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:19.0) Gecko/20100101 Firefox/19.0');
        curl_setopt($ch, CURLOPT_HEADER, true);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        print_r($info);
        return $info['http_code'];
    }

    public function correctPhone($phone_original) 
    {
        // Apenas números
        $phone = preg_replace('/[^0-9]/', '', $phone_original);

        if (substr($phone, 0, 2) == '55') {
            $phone = substr($phone, 2);
        }
        if (substr($phone, 0, 1) == '0') {
            $phone = substr($phone, 1);
        }
        if (strlen($phone) == 8) {
            //88884444
            if (substr($phone, 0, 1) <= 5) { //De 2 a 5 é fixo
                return substr($phone, 0, 4) . '-' . substr($phone, 4, 4);
            } else {
                return '9 ' . substr($phone, 0, 4) . '-' . substr($phone, 4, 4);
            }
            return substr($phone, 0, 4) . '-' . substr($phone, 4, 4);
        }
        if (strlen($phone) == 9) {
            //988884444
            return substr($phone, 0, 1) . ' ' . substr($phone, 1, 4) . '-' . substr($phone, 5, 4);
        }
        if (strlen($phone) == 10) {
            //1988884444
            if (substr($phone, 2, 1) <= 5) { //De 2 a 5 é fixo
                return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 4) . '-' . substr($phone, 6, 4);
            } else {
                return '(' . substr($phone, 0, 2) . ') 9 ' . substr($phone, 2, 4) . '-' . substr($phone, 6, 4);
            }
        }
        if (strlen($phone) == 11) {
            //11988884444
            if ((substr($phone, 2, 1) == 9) AND ( substr($phone, 3, 1) > 5)) {
                return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 1) . ' ' . substr($phone, 3, 4) . '-' . substr($phone, 7, 4);
            }
        } else {
            return $phone_original;
        }

        return $phone_original;
    }

    
    public function getCenterFromDegrees($data) 
    {
        if (!is_array($data))
            return FALSE;

        $num_coords = count($data);

        $X = 0.0;
        $Y = 0.0;
        $Z = 0.0;

        foreach ($data as $coord) {
            $lat = $coord[0] * pi() / 180;
            $lon = $coord[1] * pi() / 180;

            $a = cos($lat) * cos($lon);
            $b = cos($lat) * sin($lon);
            $c = sin($lat);

            $X += $a;
            $Y += $b;
            $Z += $c;
        }

        $X /= $num_coords;
        $Y /= $num_coords;
        $Z /= $num_coords;

        $lon = atan2($Y, $X);
        $hyp = sqrt($X * $X + $Y * $Y);
        $lat = atan2($Z, $hyp);

        return array($lat * 180 / pi(), $lon * 180 / pi());
    }

    public function getStateUfFromPortugal($state)
    {
        if (empty($state)) {
            exit("Em utils->get_state_uf_from_portugal state is null\n");
        }
        
        $state_name = trim(preg_replace('/District/i', '', $state));
        $tmp_state = explode(" ", $state_name);
        
        if (count($tmp_state) == 1) {
            $state_uf = strtoupper(substr($state_name, 0, 3));
        } elseif (count($tmp_state) == 2) {
            $state_uf = strtoupper(substr($tmp_state[0], 0, 2) . substr($tmp_state[1], 0, 1));
        } elseif (count($tmp_state) == 3) {
            $state_uf = strtoupper(substr($tmp_state[0], 0, 1) . substr($tmp_state[1], 0, 1) . substr($tmp_state[2], 0, 1));
        } else {
            echo "Param $state\n";
            exit("Nome de estado 4 partes\n");
        }
        
        return $state_uf;
    }

    public function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verifyPasswordHash($password, $hash) 
    {
        return password_verify($password, $hash);
    }

    public function maskCodbarras($str_) 
    {
        return $str_;
        $str = preg_replace('/\./', '', trim($str_));
        $n = substr($str, 0, 5) . '.' . substr($str, 5, 6) . ' ' . substr($str, 11, 5) . '.' . substr($str, 16, 6) . ' ';
        $n .= substr($str, 22, 5) . '.' . substr($str, 27, 6) . ' ' . substr($str, 33, 1) . ' ' . substr($str, 34);

        return $n;
    }

    public function getFileSizeUnit($unit = 'MB') 
    {
        $size = [
            'KB' => 1024,
            'MB' => 1048576,
            'GB' => 1073741824,
            'TB' => 1099511627776
        ];
        
        if(isset($size[$unit])) {
            return $size[$unit];
        }
        
        return $size['MB'];
    }

}

