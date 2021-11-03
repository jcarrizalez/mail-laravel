<?php 
declare( strict_types = 1 );
namespace App\Services\Mails;

use App\Persistence\Job;

class AppGenericRegisterTokenMail
{
    protected $job;

    public function __construct(
        Job $job
    )
    {
        $this->job  = $job;
    }
    public function make(string $name, string $email, string $token) :void
    {   
        $name       = ($name==='')?'estimado usuario':$name;

        $link       = env('WEB_URL')."/api/v2/token={token}";

        #$template_raw = @file_get_contents('http://localhost:4040/raw/template.txt/');
        #$template_html = @file_get_contents('http://localhost:4040/html/template.html/');
        $template_raw  = file_get_contents(__DIR__.'/raw/template.txt');
        $template_html = file_get_contents(__DIR__.'/html/template.html');

        #$raw = @file_get_contents('http://localhost:4040/raw/app_generic_register_token.txt/');
        #$html = @file_get_contents('http://localhost:4040/html/app_generic_register_token.html/');
        $raw = file_get_contents(__DIR__."/raw/app_generic_register_token.txt");
        $html = file_get_contents(__DIR__."/html/app_generic_register_token.html");

        if(in_array(false, [$template_raw, $raw, $template_html, $html])){

            dd('404, not found');
        }

        $body_raw   = str_replace('{{CONTENT}}', $raw, $template_raw);
        $body_html  = str_replace('{{CONTENT}}', $html, $template_html);
        
        $body_raw   = str_replace('{{ANIO}}', date('Y'), $body_raw);
        $body_html  = str_replace('{{ANIO}}', date('Y'), $body_html);

        #REPLACE DATA
        $body_raw   = str_replace('{{LINK}}', $link, $body_raw);
        $body_html  = str_replace('{{LINK}}', $link, $body_html);

        $body_raw   = str_replace('{{NAME_ALIAS}}', $name, $body_raw);
        $body_html  = str_replace('{{NAME_ALIAS}}', $name, $body_html);

        #EVENT
        $this->job->mail((object) [
            'type'      => 'mail',
            'subject'   => '¡Bienvenido a Publica.la!',
            'email'     => $email,
            'name'      => $name,
            'view_html' => $body_html,
            'view_raw'  => $body_raw
        ]);
    }
}