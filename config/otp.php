<?php 

return [

    /**
     * The number of minutes the OTP will expire after being generated.
     */
    'expires_at' => 15,

    /**
     * The number of attempts a user can make to verify an OTP.
     */
    'attempts' => 5,


    /**
     * The default provider to use for sending OTPs. 
     */
    'default_provider' => env('OTP_PROVIDER', 'mail'),

    /**
     * Providers 
     */
    'providers' => [
        'vonage' => [
            // 'class' => \App\Domain\Auth\Providers\VonageProvider::class,
        ],
        'twilio' => [
            // 'class' => \App\Domain\Auth\Providers\TwilioProvider::class,
        ],
        'nexmo' => [
            // 'class' => \App\Domain\Auth\Providers\NexmoProvider::class,
        ],
        'yopmail' => [
            // 'class' => \App\Domain\Auth\Providers\YopmailProvider::class,
        ],
        'log' => [
            // 'class' => \App\Domain\Auth\Providers\LogProvider::class,
        ]
    ],


    // sms or email 
    'service' => [
        'sms' => [
            // 'class' => \App\Domain\Auth\Services\SmsService::class,
        ],
        'email' => [
            // 'class' => \App\Domain\Auth\Services\EmailService::class,
        ],
    ] 
];