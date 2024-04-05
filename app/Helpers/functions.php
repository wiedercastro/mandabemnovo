<?php

use App\Enums\SupportStatus;

if (!function_exists('getInitials')) {
    function getInitials($name)
    {
        $words = explode(' ', $name); // Split the name into an array of words
        $initials = '';

        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1)); // Append the first character of each word
            if (strlen($initials) >= 2) {
                break; // Stop appending initials once we reach the limit
            }
        }

        return $initials;
    }
}


if (!function_exists('getMeses')) {
    function getMeses(string $mesAtual): string
    {
        $meses = [
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        ];
      
        return $meses[$mesAtual];
    }
}
