<?php

namespace App\Libraries;

class DateUtils 
{
    
    private $num_feriados = 13;

    public function __construct($default_date = 'America/Sao_Paulo') 
    {
        date_default_timezone_set($default_date);
    }

    public function getHour($sec = true) 
    {
        return $sec ? date('H:i:s') : date('H:i');
    }

    public function getH() 
    {
        return date('H');
    }

    public function getNow($return_time = true, $type = 'en') 
    {
        $date_ = date('Y-m-d');

        if (!$return_time) {
            if ($type == 'en')
                return $date_;
            else if ($type == 'br')
                return $this->toBr($date_);
        }

        $date = date('Y-m-d H:i:s');

        if ($type == 'en')
            return $date;
        else if ($type == 'br')
            return $this->toBr($date, true, $return_time);

        return false;
    }

    public function getDayStr($time = null) 
    {
        if (!$time) {
            return date('D');
        }
        return date('D', $time);
    }

    public function geTtime() 
    {
        return time();
    }

    public function getTodayAbrev() {
        return strtolower(date('D'));
    }

    public function getTodayStart() {
        return date('Y-m-d') . ' 00:00:00';
    }

    public function getTodayEnd() 
    {
        return date('Y-m-d') . ' 23:59:59';
    }

    public function getNameDayByCode($code) 
    {
        $str = '';
        switch (strtoupper($code)) {
            case 'SEG': $str = 'Segunda - Feira';
                break;
            case 'TER': $str = 'Terça - Feira';
                break;
            case 'QUA': $str = 'Quarta - Feira';
                break;
            case 'QUI': $str = 'Quinta - Feira';
                break;
            case 'SEX': $str = 'Sexta - Feira';
                break;
            case 'SAB': $str = 'Sábado';
                break;
            case 'DOM': $str = 'Domingo';
                break;
            default: $str = 'Nome dia';
        }

        return $str;
    }
    
    public function toBrMini($date = '') 
    {
        return substr($this->toBr($date),0,8);
    }
    
    public function toBr($date = '', $hour = true, $seconds = false, $min = false) 
    {
        if (strlen($date) != 19 && strlen($date) != 10)
            return '';
        #2016-08-27 21:09:53

        if ($min)
            $date_formated = substr($date, 8, 2) . '/' . substr($date, 5, 2) . '/' . substr($date, 2, 2);
        else
            $date_formated = substr($date, 8, 2) . '/' . substr($date, 5, 2) . '/' . substr($date, 0, 4);

        $hour_formated = '';
        $seconds_formated = '';
        if (strlen($date) == 19) {
            $hour_formated = substr($date, 11, 5);
            $seconds_formated = substr($date, 17, 2);
        }

        if (!$hour || strlen($date) == 10)
            return $date_formated;

        if (!$seconds)
            return $date_formated . ' ' . $hour_formated;

        return $date_formated . ' ' . $hour_formated . ':' . $seconds_formated;
    }

    // 10/10/2016 21:09:53
    public function toEn($date = '', $hour = false, $seconds = false) 
    {
        if (strlen($date) != 19 && strlen($date) != 10)
            return '';

        $date_formated = substr($date, 6, 4) . '-' . substr($date, 3, 2) . '-' . substr($date, 0, 2);

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

        return $date_formated . ' ' . $hour_formated . ':'.$seconds_formated;
    }

    public function timeToHour($time, $showSeg = false) 
    {

        //if( $time < 0     ) return null;
        if ($time == null)
            return;
        if ($time == 0)
            return "00:00" . ( $showSeg ? ":00" : "" );

        $negativo = false;
        if ($time < 0) {
            $time = str_replace("-", "", $time);
            $negativo = true;
        }

        if ($time > 3599) {
            $tmpHora = $time / 3600;
            $tmpHora = explode(".", $tmpHora);
            $hora = $tmpHora[0];
            $tmpAuxHora = (int) $time - ( $hora * 3600 );
        } else {
            $hora = 0;
            $tmpAuxHora = $time;
        }
        if ($tmpAuxHora > 59) {
            $tmpMinuto = $tmpAuxHora / 60;
            $tmpMinuto = explode(".", $tmpMinuto);
            $minuto = $tmpMinuto[0];

            $tmpAuxMinuto = $minuto * 60;

            $segundo = $tmpAuxHora - $tmpAuxMinuto;
        } else {
            $minuto = 0;
            $segundo = $tmpAuxHora;
        }


        if ($hora < 10)
            $hora = "0" . $hora;
        if ($minuto < 10)
            $minuto = "0" . $minuto;
        if ($segundo < 10)
            $segundo = "0" . $segundo;

        return ( $negativo ? "-" : "" ) . $hora . ":" . $minuto . ( $showSeg ? ":" . substr($segundo, 0, 2) : "" );
    }

    public function toStr($date_en, $template = 1) 
    {
        $str_months = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
        ];
        if ($template == 1) { // Ex: Janeiro 2020
            $month = (int) substr($date_en, 5, 2);
            return $str_months[$month] . ' ' . substr($date_en, 0, 4);
        }
        if ($template == 2) { // Janeiro, com base no inteiro
            $month = (int) $date_en;
            if($month == 13) {
                $month = 1;
            }
            return $str_months[$month];
        }
        if ($template == 3) { // EX; janeiro com base da data
            $month = (int) substr($date_en, 5, 2);
            return $str_months[$month];
        }
    }

    public function getDateBeforeNumDays($num_days, $return_time = false) 
    {
        return date('Y-m-d', time() - (86400 * $num_days)) . ( $return_time ? ' ' . date('H:i:s') : '' );
    }

    public function getTimeFromNowToPast($seconds, $return_time = true) 
    {
        if ($return_time) {
            return date('Y-m-d H:i:s', time() - $seconds);
        } else {
            return date('Y-m-d', time() - $seconds);
        }
    }

    public function getWorkingDays($startDate, $endDate) 
    {
        $begin = strtotime($startDate);
        $end = strtotime($endDate);
        if ($begin > $end) {
            echo "startdate is in the future! <br />";
            return 0;
        } else {
            $holidays = array('01/01', '25/12');
            $weekends = 0;
            $no_days = 0;
            $holidayCount = 0;
            while ($begin <= $end) {
                $no_days++; // no of days in the given interval
                if (in_array(date("d/m", $begin), $holidays)) {
                    $holidayCount++;
                }
                $what_day = date("N", $begin);
                if ($what_day > 5) { // 6 and 7 are weekend days
                    $weekends++;
                };
                $begin += 86400; // +1 day
            };
            $working_days = $no_days - $weekends - $holidayCount;

            return $working_days;
        }
    }

    public function getLastDayOnMonth($param = [])
    {
        if (isset($param['month']) && $param['month'] == 'current') {
            $start = date('Y') . '-' . (date('m')) . '-01';

            $last_day = date('t', strtotime($start));
            $end = date('Y') . '-' . (date('m')) . '-' . $last_day; //get end date of month
        } else {
            $month = str_pad($param['month'], 2, '0', STR_PAD_LEFT);
            $start = date('Y') . '-' . ($month) . '-01';

            $last_day = date('t', strtotime($start));
            $end = date('Y') . '-' . ($month) . '-' . $last_day; //get end date of month
        }
        return $end;
    }

    // funcoes para calculo de dias Uteis::  Ricardo Herrero
    //CALCULANDO DIAS NORMAIS
    /* Abaixo vamos calcular a diferença entre duas datas. Fazemos uma reversão da maior sobre a menor
      para não termos um resultado negativo. */
    public function CalculaDias($xDataInicial, $xDataFinal) 
    {
        $time1 = $this->dataToTimestamp($xDataInicial);
        $time2 = $this->dataToTimestamp($xDataFinal);

        $tMaior = $time1 > $time2 ? $time1 : $time2;
        $tMenor = $time1 < $time2 ? $time1 : $time2;

        $diff = $tMaior - $tMenor;
        $numDias = $diff / 86400; //86400 é o número de segundos que 1 dia possui  
        return $numDias;
    }

    //LISTA DE FERIADOS NO ANO
    /* Abaixo criamos um array para registrar todos os feriados existentes durante o ano. */
    public function Feriados($ano, $posicao) 
    {
        $dia = 86400;
        $datas = array();
        $datas['pascoa'] = easter_date($ano);
        $datas['sexta_santa'] = $datas['pascoa'] - (2 * $dia);
        $datas['carnaval'] = $datas['pascoa'] - (47 * $dia);
        $datas['corpus_cristi'] = $datas['pascoa'] + (60 * $dia);
        $feriados = array(
            '01/01',
            '02/02', // Navegantes
            date('d/m', $datas['carnaval']),
            date('d/m', $datas['sexta_santa']),
            date('d/m', $datas['pascoa']),
            '21/04',
            '01/05',
            date('d/m', $datas['corpus_cristi']),
            '20/09', // Revolução Farroupilha \m/
            '12/10',
            '02/11',
            '15/11',
            '25/12',
        );
        
        return $feriados[$posicao] . "/" . $ano;
    }

    //FORMATA COMO TIMESTAMP
    /* Esta função é bem simples, e foi criada somente para nos ajudar a formatar a data já em formato  TimeStamp facilitando nossa soma de dias para uma data qualquer. */
    public function dataToTimestamp($data) 
    {
        $ano = substr($data, 6, 4);
        $mes = substr($data, 3, 2);
        $dia = substr($data, 0, 2);
        return mktime(0, 0, 0, $mes, $dia, $ano);
    }

    //SOMA 01 DIA  
    public function Soma1dia($data) 
    {
        $ano = substr($data, 6, 4);
        $mes = substr($data, 3, 2);
        $dia = substr($data, 0, 2);
        return date("d/m/Y", mktime(0, 0, 0, $mes, $dia + 1, $ano));
    }

    //CALCULA DIAS UTEIS
    /* É nesta função que faremos o calculo. Abaixo podemos ver que faremos o cálculo normal de dias ($calculoDias), após este cálculo, faremos a comparação de dia a dia, verificando se este dia é um sábado, domingo ou feriado e em qualquer destas condições iremos incrementar 1 */
    public function DiasUteis($yDataInicial, $yDataFinal) 
    {
        $diaFDS = 0; //dias não úteis(Sábado=6 Domingo=0)
        $calculoDias = $this->CalculaDias($yDataInicial, $yDataFinal); //número de dias entre a data inicial e a final
        $diasUteis = 0;

        while ($yDataInicial != $yDataFinal) {
            $diaSemana = date("w", $this->dataToTimestamp($yDataInicial));
            if ($diaSemana == 0 || $diaSemana == 6) {
                //se SABADO OU DOMINGO, SOMA 01
                $diaFDS++;
            } else {
                //senão vemos se este dia é FERIADO
                for ($i = 0; $i < $this->num_feriados; $i++) {
                    if ($yDataInicial == $this->Feriados(date("Y"), $i)) {
                        $diaFDS++;
                    }
                }
            }
            $yDataInicial = $this->Soma1dia($yDataInicial); //dia + 1
        }
        return $calculoDias - $diaFDS;
    }

    public function DiasUteisFromInit($yDataInicial, $num_dias) 
    {
        $time_start = strtotime($yDataInicial);
        $dias_uteis_cont = 0;
        $x = 1;
        $d_str = null;
        while($dias_uteis_cont < $num_dias) {
            $d = $time_start + ( $x * (86400) );
            $d_str = date('d/m/Y', $d);
            $diaSemana = date("w", $d);
            $x++;
            
            if ($diaSemana == 0 || $diaSemana == 6) {
                //se SABADO OU DOMINGO, SOMA 01
                continue;
            } else {
                $is_feriado = false;
                for ($i = 0; $i < $this->num_feriados; $i++) {
                    if ($d_str == $this->Feriados(date("Y"), $i)) {
                        $is_feriado = true;
                        break;
                    }
                }
                if($is_feriado) {
                    continue;
                }
            }
            
            $dias_uteis_cont++;
        }
        
        return $d_str;
    }
    
    public function getNumDay() 
    {
        return date('d');
    }
    
    public function normalizeDate($date, $format = 'A') 
    {
        
        $date_ret = '';
        
        if($format == 'A') {
            $date_ret.= substr($date, 8, 2) . '/';
            $date_ret.= substr($date, 5, 2) . '/';
            $date_ret.= substr($date, 0, 4) . ' ';
            $date_ret.= substr($date, 11, 5);
        }
        
        return $date_ret;
        
    }
}
