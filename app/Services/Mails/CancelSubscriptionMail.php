<?php 
declare( strict_types = 1 );
namespace App\Services\Mails;

use App\Persistence\Job;

class CancelSubscriptionMail
{
    protected $job;

    public function __construct(
        Job $job 
    )
    {
        $this->job = $job;
    }

    public function make(string $name, string $email, string $end_date) :void
    {   
        $name       = ($name==='')?'estimado usuario':$name;
        $end_date    = date_format(date_create($end_date),"d/m/Y");

        #$template_raw = @file_get_contents('http://localhost:4040/raw/template.txt/');
        #$template_html = @file_get_contents('http://localhost:4040/html/template.html/');
        $template_raw  = file_get_contents(__DIR__.'/raw/template.txt');
        $template_html = file_get_contents(__DIR__.'/html/template.html');

        #$raw = @file_get_contents('http://localhost:4040/raw/cancel_subscription.txt/');
        #$html = @file_get_contents('http://localhost:4040/html/cancel_subscription.html/');
        $raw = file_get_contents(__DIR__."/raw/cancel_subscription.txt");
        $html = file_get_contents(__DIR__."/html/cancel_subscription.html");

        if(in_array(false, [$template_raw, $raw, $template_html, $html])){

            dd('404, not found');
        }

        $body_raw   = str_replace('{{CONTENT}}', $raw, $template_raw);
        $body_html  = str_replace('{{CONTENT}}', $html, $template_html);
        
        $body_raw   = str_replace('{{ANIO}}', date('Y'), $body_raw);
        $body_html  = str_replace('{{ANIO}}', date('Y'), $body_html);

        #REPLACE DATA
        $body_raw   = str_replace('{{NAME_ALIAS}}', $name, $body_raw);
        $body_raw   = str_replace('{{END_OF_SERVICE}}', $end_date, $body_raw);
        $body_html  = str_replace('{{NAME_ALIAS}}', $name, $body_html);
        $body_html  = str_replace('{{END_OF_SERVICE}}', $end_date, $body_html);

        #EVENT
        $this->job->mail((object) [
            'type'      => 'mail',
            'subject'   => 'Cancelamos tu suscripción',
            'email'     => $email,
            'name'      => $name,
            'view_html' => $body_html,
            'view_raw'  => $body_raw
        ]);
    }
}