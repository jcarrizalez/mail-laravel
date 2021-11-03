<?php
declare( strict_types = 1 );
namespace App\Persistence;

use App\Jobs\Process;

class Job
{
    public static function process($event, $recurrent=false) :void
    {
    }

    public static function register(object $event) :void
    {
    }

    public static function mail(object $event) :void
    {
        if(env('APP_ENV')!='production' && strpos($event->email?:'', '@publica.la') === false){
            $event->email = env('MAIL_TEST_NOT_PRODUCTION', 'error');
        }

        Process::dispatch( $event )->onQueue('process')
        ->delay(now()->addSeconds(3) );
    }
}