# mail-laravel: Uso dentro de un proyecto Laravel.
Enviar mail desde laravel

Esto es solo para ver las plantillas de ejemplo. 
```bash
php -S localhost:4040 -t app/Services/Mails/
```

## templates
[template_raw](http://localhost:4040/raw/template.txt) 

[template_html](http://localhost:4040/html/template.html) 

[cancel_subscription_raw](http://localhost:4040/raw/cancel_subscription.txt) 

[cancel_subscription_html](http://localhost:4040/html/cancel_subscription.html) 

[app_generic_register_token_raw](http://localhost:4040/raw/app_generic_register_token.txt) 

[app_generic_register_token_html](http://localhost:4040/html/app_generic_register_token.html) 


## Nota

- app/Services/DeveloperTest.php, tiene el ejemplo usando Job Laravel, en caso de un querer usar el Job, ver la funcion sendMail() dentro de app/Services/Events/Process.php

- Cada Class dentro app\Services\Mails tiene la logica de replace y template para ese mail;
