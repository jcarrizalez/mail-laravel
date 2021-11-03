<?php
declare( strict_types = 1 );
namespace App\Persistence;

use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Mail as FacadeMail;

class Mail
{
    public function send(string $subject, string $email, string $name, string $view_html, string $view_raw) :void
    {
       $this->make([
            'subject' => $subject, 
            'alias' => env('MAIL_FROM_NAME'),
            'from' => env('MAIL_FROM_ADDRESS'),
            'to' => $email,
            'name' => $name,
            'view' => [
                'html' => $view_html,
                'raw'  => $view_raw
            ]
        ]);
    }

    private function make(array $params) :void
    {
        FacadeMail::send(new class((object) $params) extends Mailable
        {
            protected $params;

            public function __construct(object $params)
            {
                $this->params = $params;
            }

            public function build()
            {
                $html = $this->params->view['html'];
                $raw  = $this->params->view['raw'];

                # Ahora podemos configurar las secciones de contenido de texto y HTML sin
                # que involucran plantillas de hoja. Un problema menor es el Mailable ::view($view)
                # documenta $ view como una cadena, lo cual es incorrecto si sigue
                # la intrincada lógica descendente.
                /** @noinspection PhpParamsInspection */
                return $this
                    ->subject($this->params->subject)
                    ->to($this->params->to)
                    ->from($this->params->from, $this->params->alias)
                    ->view([
                        # Internamente, Mailer::renderView($view) interpreta $view como el nombre de una plantilla blade
                        # a menos que, en lugar de una cadena, se establezca en un objeto que implemente Htmlable,
                        # en cuyo caso devuelve el resultado $view->toHtml()
                        'html' => new class($html) implements Htmlable
                        {
                            protected $html;
                            public function __construct($html)
                            {
                                $this->html = $html;
                            }
                            public function toHtml(): string
                            {
                                return $this->html;
                            }
                        },
                        'raw' => $raw
                ]);
            }
        });
    }
}