<?php
declare( strict_types = 1 );
namespace App\Services;

use App\Services\Mails\AppGenericRegisterTokenMail;
use App\Services\Mails\CancelSubscriptionMail;

class DeveloperTests
{
    protected $app_generic_register_token_mail;
    protected $cancel_subscription_mail;

    public function __construct(
        AppGenericRegisterTokenMail $app_generic_register_token_mail,
        CancelSubscriptionMail $cancel_subscription_mail
    ) {
        $this->app_generic_register_token_mail = $app_generic_register_token_mail;
        $this->cancel_subscription_mail = $cancel_subscription_mail;
    }

    public function make()
    {
        $this->app_generic_register_token_mail->make(
            "JUAN CARRIZALEZ",             #name,
            "jcarrizalez+test1@gmail.com", #email
            "XCXZCVZXCXZCXZCXZCXZCXZCXZC"  #token
        );

        $this->cancel_subscription_mail->make(
            "JUAN CARRIZALEZ",              #name,
            "jcarrizalez+test1gmail.com" ,  #email
            '2021-04-14 16:27:34'           #end_date
        );
    }    
}