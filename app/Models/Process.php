<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\DateUtils;

class Process extends Model
{
    protected $table = 'process_runtime';
    protected $primaryKey = 'id';
    public $timestamps = false;

    private $do_log = false;

    public function isRunning($name)
    {
        return $this->where('name', $name)->where('status', 'running')->exists();
    }

    public function initProcess($name)
    {
        $data = [
            'name' => $name,
            'date_init' => now(),
            'status' => 'running'
        ];

        $process = $this->where('name', $name)->first();

        if ($process) {
            $process->update($data);
        } else {
            $this->create($data);
        }

        return true;
    }

    public function initProcessForce($name, $time = 540)
    {
        $shouldStart = false;

        $process = $this->where('name', $name)->first();

        if ($process) {
            if ($process->status == 'running') {
                $nowTime = time();
                $processTime = strtotime($process->date_init);

                if (($nowTime - $processTime) >= $time) {
                    $msg = now() . " - Tempo de execução do processo excedido [$nowTime" . '-' . "$processTime]\n";

                    if ($name != 'status_manifestacao') {
                    }

                    echo $msg;
                    $shouldStart = true;
                } else {
                    if ($this->do_log) {
                        echo now() . " - [BLOQUEIO] Processo rodando há " . (($nowTime - $processTime) / 60) . " minutos...\n";
                    }
                }
            } else {
                $shouldStart = true;
            }
        } else {
            $shouldStart = true;
        }

        if ($shouldStart) {
            $this->initProcess($name);
            echo now() . " - Processo $name iniciado\n";
            return true;
        }

        return false;
    }

    public function finishProcess($name)
    {
        $dateUtils = new DateUtils();
        $process = $this->where('name', $name)->first();

        if ($process) {
            $timeStart = strtotime($process->date_init);
        } else {
            $timeStart = time();
        }

        $timeEnd = time();

        $this->where('name', $name)->update([
            'status' => null,
            'date_end' => now()
        ]);

        echo now() . " - Processo $name finalizado | Tempo de execução: " . $dateUtils->timeToHour(($timeEnd - $timeStart), true) . " \n";

        return true;
    }

}
