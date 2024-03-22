<?php
namespace App\Libraries;

class Validation 
{

    public function validCnpj($value) 
    {
        return $this->validDoc('cnpj', $value);
    }

    public function validCpf($value) 
    {
        return $this->validDoc('cpf', $value);
    }

    public function validDomain($domain_name) 
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
                && preg_match("/^.{1,253}$/", $domain_name) //overall length check
                && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name) ); //length of each label
    }

    public function validDoc($type, $value_) 
    {
        $value = preg_replace('/[^0-9]/', '', $value_);

        if (!$value) {
            return false;
        }

        if ($type == 'cpf') {

            $cpf = $value;

            if ($cpf == '')
                return false;

            if (strlen($cpf) != 11)
                return false;

            $cpf_invalido = array('00000000000', '11111111111', '22222222222', '33333333333', '44444444444', '55555555555', '66666666666', '77777777777', '88888888888', '99999999999');

            for ($x = 0; $x < count($cpf_invalido); $x++) {
                if ($cpf == $cpf_invalido[$x]) {
                    return false;
                }
            }

            $soma1 = (substr($cpf, 0, 1) * 10) +
                    (substr($cpf, 1, 1) * 9) +
                    (substr($cpf, 2, 1) * 8) +
                    (substr($cpf, 3, 1) * 7) +
                    (substr($cpf, 4, 1) * 6) +
                    (substr($cpf, 5, 1) * 5) +
                    (substr($cpf, 6, 1) * 4) +
                    (substr($cpf, 7, 1) * 3) +
                    (substr($cpf, 8, 1) * 2);

            $restos1 = $soma1 % 11;

            if (( $restos1 == 0 ) || ( $restos1 == 1 ))
                $digito1 = 0;
            else
                $digito1 = 11 - $restos1;

            $soma2 = (substr($cpf, 0, 1) * 11) +
                    (substr($cpf, 1, 1) * 10) +
                    (substr($cpf, 2, 1) * 9) +
                    (substr($cpf, 3, 1) * 8) +
                    (substr($cpf, 4, 1) * 7) +
                    (substr($cpf, 5, 1) * 6) +
                    (substr($cpf, 6, 1) * 5) +
                    (substr($cpf, 7, 1) * 4) +
                    (substr($cpf, 8, 1) * 3) +
                    (substr($cpf, 9, 1) * 2);

            $restos2 = $soma2 % 11;

            if (( $restos2 == 0 ) || ( $restos2 == 1 ))
                $digito2 = 0;
            else
                $digito2 = 11 - $restos2;

            if (( substr($cpf, 9, 1) == $digito1 ) && ( substr($cpf, 10, 1) == $digito2 )) {
                return true;
            }
            return false;
        }


        if ($type == 'cnpj') {
            $cnpj = trim($value);

            if ($cnpj == '')
                return false;
            if (strlen($cnpj) != 14)
                return false;

            $cnpj_invalido = array('00000000000000', '11111111111111', '22222222222222', '33333333333333', '44444444444444', '55555555555555', '66666666666666', '77777777777777', '88888888888888', '99999999999999');

            for ($x = 0; $x < count($cnpj_invalido); $x++) {
                if ($cnpj == $cnpj_invalido[$x]) {
                    return false;
                }
            }

            if (strlen($cnpj) != 14) {
                return false;
            }

            $soma1 = (substr($cnpj, 0, 1) * 5) +
                    (substr($cnpj, 1, 1) * 4) +
                    (substr($cnpj, 2, 1) * 3) +
                    (substr($cnpj, 3, 1) * 2) +
                    (substr($cnpj, 4, 1) * 9) +
                    (substr($cnpj, 5, 1) * 8) +
                    (substr($cnpj, 6, 1) * 7) +
                    (substr($cnpj, 7, 1) * 6) +
                    (substr($cnpj, 8, 1) * 5) +
                    (substr($cnpj, 9, 1) * 4) +
                    (substr($cnpj, 10, 1) * 3) +
                    (substr($cnpj, 11, 1) * 2);
            $restos1 = $soma1 % 11;


            if (( $restos1 == 0 ) || ( $restos1 == 1 ))
                $digito1 = 0;
            else
                $digito1 = 11 - $restos1;

            $soma2 = (substr($cnpj, 0, 1) * 6) +
                    (substr($cnpj, 1, 1) * 5) +
                    (substr($cnpj, 2, 1) * 4) +
                    (substr($cnpj, 3, 1) * 3) +
                    (substr($cnpj, 4, 1) * 2) +
                    (substr($cnpj, 5, 1) * 9) +
                    (substr($cnpj, 6, 1) * 8) +
                    (substr($cnpj, 7, 1) * 7) +
                    (substr($cnpj, 8, 1) * 6) +
                    (substr($cnpj, 9, 1) * 5) +
                    (substr($cnpj, 10, 1) * 4) +
                    (substr($cnpj, 11, 1) * 3) +
                    (substr($cnpj, 12, 1) * 2);

            $restos2 = $soma2 % 11;

            if (( $restos2 == 0 ) || ( $restos2 == 1 ))
                $digito2 = 0;
            else
                $digito2 = 11 - $restos2;

            if (( substr($cnpj, 12, 1) == $digito1 ) && ( substr($cnpj, 13, 1) == $digito2 )) {
                return true;
            }

            return false;
        }


        return false;
    }

    public function validateHour($time) {
        return preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $time);
    }

    // Validade minimo e max de apenas numeros
    public function lengthNumber($str, $min, $max = null) {
        return $this->length(preg_replace('/[^0-9]/', '', $str), $min, $max);
    }

    // Validade minimo e max de alphanumerics
    public function length($str, $min, $max = null) {
        $val_1 = strlen(trim($str)) >= $min;
        if (!$max) {
            return $val_1;
        }
        $val_2 = strlen(trim($str)) <= $max;
        return $val_1 && $val_2;
    }

    public function valDate($date, $format = 'br') {

        if ($format == 'br') {
            return preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/', trim($date));
        } else if ($format == 'en') {
            return preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', trim($date));
        }
        return false;
    }

}