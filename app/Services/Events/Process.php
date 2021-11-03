<?php 
declare( strict_types = 1 );
namespace App\Services\Events;

#use App\Persistence\Log;
use App\Persistence\Mail;

class Process
{
    #protected $log;
    protected $mail;

    public function __construct(
        #Log $log,
        Mail $mail
    ) {
        #$this->log      = $log;
        $this->mail     = $mail;
    }

    public function make(object $event)
    {
        $bool = false;
        $timer_start = microtime(true);
        $type = $event->type??null;
        $info = $type.' -id:'.round($timer_start, 0);
        $log = (array) $event;

        switch ($type) {
            case 'mail':
                unset($log['view_html']);
                unset($log['view_raw']);
                echo ' > '.$type.': '.$event->email."\n";
                $bool = $this->sendMail($event);
                break;
            default:
                $bool = false;
                break;
        } 
        $timer_end = microtime(true);
        $timer = round(($timer_end - $timer_start), 2);

        $this->log->stack(['queue-info'])->info($info, $log);
        $this->log->stack(['queue-time'])->info($info.' - time: '.$timer);
        return $bool;
    }

    protected function sendMail(object $event) :bool
    {
        #SENDMAIL
        $this->mail->send(
            $event->subject,    #subject
            $event->email,      #to_email
            $event->name,       #to_name
            $event->view_html,  #view_html
            $event->view_raw    #view_raw
        );
        return true;
    }
}