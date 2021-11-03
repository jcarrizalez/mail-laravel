<?php
declare( strict_types = 1 );
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Services\Events\Process AS Service;

/**
* IMPORTANTE, ESTA COLA ESTA ATADA EN file artisan del proyecto esto para que
* que cada vez que se levanta el php artisan queue: <-se inyecta un registro
* en los jobs asi, se jecuta cada cierto tiempo 
*/
class Process implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // override the queue tries
    // configuration for this job
    public $tries = 4;

    protected $event;

    public function __construct(object $event)
    {
        $this->event = json_decode(json_encode($event));
    }

    public function handle(Service $service)
    {
        $days    = env('JOB_ATEMPTS_DAYS', 1);
        $minutes = env('JOB_ATEMPTS_AFTER_MINUTES', 5);
        $days    = $days*86400;
        $minutes = $minutes*60;

        try {
            #todos los eventos son enviados al servicio Process, alli segun su tipo se ejecuta
            $service->make($this->event);
        }
        catch (\Throwable $exception) {
            
            if ($this->attempts() > $days/$minutes) { #(8dias) intentando pasar la data
                // hard fail after 10 attempts
                throw $exception;
            }
            // requeue this job to be executes
            // in 5 minutes (300 seconds) from now
            $this->release($minutes);
            return;
        }
        return true;
    }
}